<?php

namespace App\Jobs;

use App\Models\Chapter;
use App\Models\Topic;
use App\Models\Question;
use App\Services\GroqAIService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\AiJob;

class GenerateQuestionsJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public int $tries = 3;
    public int $timeout = 60; // Reverted to single-request timeout

    public function __construct(
        protected Chapter $chapter,
        protected ?string $textToProcess = null,
        protected string $type = 'mcq',
        protected ?string $topicTitle = null,
        protected ?int $aiJobId = null
    ) {}

    public function handle(GroqAIService $ai): void
    {
        Log::info("Questions Job: Starting {$this->type} generation for chapter '{$this->chapter->title}' using Groq AI");
        
        $aiJob = null;
        if ($this->aiJobId) {
            $aiJob = AiJob::find($this->aiJobId);
            if ($aiJob) {
                $aiJob->update([
                    'status' => 'processing',
                    'started_at' => now()
                ]);
            }
        }

        $content = $this->textToProcess ?? $this->chapter->content;

        // If content is empty (title-only mode for topics), use the topic title as the prompt
        if ($this->type === 'topic' && empty($content)) {
            $content = "Explain the topic: " . ($this->topicTitle ?? $this->chapter->title);
        }

        $aiResponse = $ai->generateQuestions($content, $this->type, $this->topicTitle);
        
        if (!$aiResponse) {
            Log::error("Questions Job: Failed to generate content/questions for chapter ID: {$this->chapter->id}");
            if ($aiJob) {
                $aiJob->update([
                    'status' => 'failed',
                    'errors' => 'Groq API returned no result.'
                ]);
            }
            return;
        }

        $aiResult = $aiResponse['data'];
        $tokens = $aiResponse['total_tokens'];

        if ($aiJob) {
            $aiJob->update(['tokens_used' => $tokens]);
        }

        if (!$aiResult) {
            Log::error("Questions Job: Failed to decode JSON for chapter ID: {$this->chapter->id}");
            if ($aiJob) $aiJob->update(['status' => 'failed', 'errors' => 'JSON decoding failed.']);
            return;
        }

        try {
            if ($this->type === 'topic') {
                // Handle single topic object
                if (empty($aiResult['content'])) {
                    Log::error("Questions Job: Topic content is empty.");
                    if ($aiJob) $aiJob->update(['status' => 'failed', 'errors' => 'Topic content empty.']);
                    return;
                }

                Topic::create([
                    'chapter_id' => $this->chapter->id,
                    'ai_job_id'  => $this->aiJobId,
                    'title'      => $this->topicTitle ?? ($aiResult['title'] ?? $this->chapter->title),
                    'content'    => $aiResult['content'],
                ]);

                Log::info("Topic created for chapter: {$this->chapter->title}");
            } else {
                // Handle questions array (MCQ/QA)
                if (!is_array($aiResult)) {
                    Log::error("Questions Job: Expected array for questions but got something else.");
                    if ($aiJob) $aiJob->update(['status' => 'failed', 'errors' => 'Invalid data format.']);
                    return;
                }

                foreach ($aiResult as $q) {
                    if (empty($q['question'])) continue;

                    if ($this->type === 'qa') {
                        Question::create([
                            'chapter_id'     => $this->chapter->id,
                            'ai_job_id'      => $this->aiJobId,
                            'type'           => 'qa',
                            'question'       => $q['question'],
                            'option_a'       => '',
                            'option_b'       => '',
                            'option_c'       => '',
                            'option_d'       => '',
                            'correct_answer' => $q['correct'] ?? '',
                            'explanation'    => $q['explanation'] ?? '',
                            'difficulty'     => $q['difficulty'] ?? null,
                        ]);
                    } else {
                        if (empty($q['options'])) continue;

                        Question::create([
                            'chapter_id'     => $this->chapter->id,
                            'ai_job_id'      => $this->aiJobId,
                            'type'           => 'mcq',
                            'question'       => $q['question'],
                            'option_a'       => $q['options']['A'] ?? '',
                            'option_b'       => $q['options']['B'] ?? '',
                            'option_c'       => $q['options']['C'] ?? '',
                            'option_d'       => $q['options']['D'] ?? '',
                            'correct_answer' => $q['correct'] ?? '',
                            'explanation'    => $q['explanation'] ?? '',
                            'difficulty'     => $q['difficulty'] ?? null,
                        ]);
                    }
                }
            }

            if ($aiJob) {
                $aiJob->update([
                    'status' => 'completed',
                    'completed_at' => now()
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Questions Job: Exception - " . $e->getMessage());
            if ($aiJob) {
                $aiJob->update([
                    'status' => 'failed',
                    'errors' => $e->getMessage()
                ]);
            }
        }
    }
}
