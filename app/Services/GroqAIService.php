<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqAIService
{
    public function generateQuestions(string $chapterText, string $type = 'mcq', ?string $topicTitle = null): ?array
    {
        // Clean malformed UTF-8 characters
        $chapterText = mb_convert_encoding($chapterText, 'UTF-8', 'UTF-8');
        $chapterText = preg_replace('/[^\x{0000}-\x{10FFFF}]/u', '', $chapterText);

        Log::info("Groq OCR: Single-request {$type} generation. Original length: ".mb_strlen($chapterText, 'UTF-8'));

        // Truncate to safe limit (15,000 chars) for better context
        // Llama-3-8b has an 8k context window (~25k-30k chars), so 15k is very safe.
        if (mb_strlen($chapterText, 'UTF-8') > 3000) {
            Log::warning('Groq OCR: Source text is very large ('.mb_strlen($chapterText, 'UTF-8').' chars). Truncated to first 3000 chars.');
            $chapterText = mb_substr($chapterText, 0, 3000, 'UTF-8');
        }

        if ($type === 'qa') {
            $format = '[{"question": "...", "correct": "...", "explanation": "...", "difficulty": "..."}]';
            $instruction = "Generate 5 high-quality Short Q&A pairs. Focus on conceptual 'Why/How' questions.";
        } elseif ($type === 'topic') {
            $format = "{\"title\": \"{$topicTitle}\", \"content\": \"Structured explanation in Markdown...\"}";
            $instruction = "You are a Professional Teacher. Create a DETAILED, structured lesson focusing EXCLUSIVELY on: \"{$topicTitle}\".
            - Task: Explain ONLY \"{$topicTitle}\" using the provided source.
            - Required Structure (Use these EXACT Gujarati headers):
              1. **{$topicTitle}**: Start with a clear definition.
              2. **ઉદાહરણો:** (Bullet points of core examples).
              3. **ગુણધર્મો:** (If applicable, list key properties).
              4. **સમજૂતી:** (Detailed step-by-step conceptual explanation).
              5. **સમાધાનો:** (At least 2 unique worked-out solutions or applications).
              6. **પ્રશ્નો:** (3 short test questions for the student).
              7. **ઉત્તરો:** (Answers to the above 3 questions).
            - Format: Use high-quality Markdown for all sections.
            - Language: Must match the source text (e.g., Gujarati).";
        } else {
            $format = '[{"question": "...", "options": {"A": "...", "B": "...", "C": "...", "D": "..."}, "correct": "A/B/C/D", "explanation": "...", "difficulty": "..."}]';
            $instruction = "Generate 5 high-quality MCQs. For MATHS: Use step-by-step Gujarati solutions in 'explanation'.";
        }

        $prompt = "Role: Professional Teacher\nTask: {$instruction}\nFormat: Return ONLY valid JSON ".($type === 'topic' ? 'object' : 'array').".\nSchema: {$format}\n\nSource: {$chapterText}";

        $apiKey = Setting::get('groq_api_key', env('GROQ_API_KEY'));
        if (! $apiKey) {
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => Setting::get('groq_model', env('GROQ_MODEL', 'llama-3.1-8b-instant')),
                'messages' => [['role' => 'user', 'content' => $prompt]],
                'temperature' => 0.1,
                'max_tokens' => ($type === 'topic' ? 3500 : 2500),
            ]);

            if (! $response->successful()) {
                Log::error('Groq OCR API Failed: '.$response->status().' - '.$response->body());

                return null;
            }

            $content = $response->json()['choices'][0]['message']['content'] ?? null;
            if (! $content) {
                Log::warning('Groq OCR: Empty response content.');

                return null;
            }

            Log::debug('Groq OCR Raw Response (Partial): '.mb_substr($content, 0, 500, 'UTF-8').'...');

            // Strip markdown code blocks if present
            if (strpos($content, '```json') !== false) {
                $content = preg_replace('/^```json\s*/', '', $content);
                $content = preg_replace('/\s*```$/', '', $content);
            }

            // Extract JSON structure
            $startChar = ($type === 'topic') ? '{' : '[';
            $endChar = ($type === 'topic') ? '}' : ']';

            $firstPos = strpos($content, $startChar);
            $lastPos = strrpos($content, $endChar);

            if ($firstPos !== false) {
                // If we found both and they are in order, take the chunk
                if ($lastPos !== false && $lastPos > $firstPos) {
                    $content = substr($content, $firstPos, $lastPos - $firstPos + 1);
                } else {
                    // Truncated JSON - attempt to close it
                    $content = substr($content, $firstPos);
                    Log::warning('Groq OCR: Detected truncated JSON. Attempting repair.');

                    // If it ends in the middle of a string, close the quote
                    if (substr_count($content, '"') % 2 !== 0) {
                        $content .= '"';
                    }

                    // Close the object/array based on type, checking if it already has it
                    if (substr(trim($content), -1) !== $endChar) {
                        $content = trim($content).$endChar;
                    }
                }
            }

            // Clean dangerous literal control characters (preserving tab, newline, carriage return)
            $content = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $content);

            // First attempt: try decoding as-is (structural newlines are allowed)
            $decoded = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $error = json_last_error_msg();
                Log::warning("Groq OCR: Initial JSON Decode failed (Error: {$error}). Attempting to fix literal newlines in strings.");

                // If the error is about control characters, it's likely literal newlines inside a string value.
                // We'll surgically replace them with \n ONLY if they are not part of the structural whitespace.
                // For simplicity/speed, we'll try a common repair: escape all newlines and then fix structural ones.

                $repaired = str_replace(["\r\n", "\r", "\n"], '\\n', $content);

                // Fix major structural elements that shouldn't have been escaped
                $repaired = str_replace('{\\n', '{', $repaired);
                $repaired = str_replace('\\n}', '}', $repaired);
                $repaired = str_replace('",\\n', '",', $repaired);
                $repaired = str_replace(':\\n', ':', $repaired);

                $decoded = json_decode($repaired, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('Groq OCR: Final JSON Decode failed (Error: '.json_last_error_msg().').');
                    Log::debug('Final content attempted: '.$content);
                }
            }

            $usage = $response->json()['usage']['total_tokens'] ?? 0;

            return [
                'data' => $decoded,
                'total_tokens' => $usage
            ];
        } catch (\Exception $e) {
            Log::error('Groq OCR Exception: '.$e->getMessage());

            return null;
        }
    }
}
