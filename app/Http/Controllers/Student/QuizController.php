<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * List all subjects with their chapters (for the student to browse).
     */
    public function subjects(): JsonResponse
    {
        $subjects = Subject::with('books.chapters')->get();

        return response()->json($subjects);
    }

    /**
     * Return up to 20 random questions for a given chapter.
     */
    public function getQuiz(int $chapter_id): JsonResponse
    {
        $chapter = Chapter::findOrFail($chapter_id);

        $questions = Question::where('chapter_id', $chapter_id)
            ->inRandomOrder()
            ->limit(20)
            ->get(['id', 'question', 'option_a', 'option_b', 'option_c', 'option_d', 'difficulty']);

        return response()->json([
            'chapter'   => $chapter->only(['id', 'title', 'chapter_number']),
            'questions' => $questions,
            'total'     => $questions->count(),
        ]);
    }

    /**
     * Score a submitted quiz and return results with explanations.
     */
    public function submit(Request $request): JsonResponse
    {
        $request->validate([
            'answers'              => 'required|array|min:1',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.answer'     => 'required|string|in:A,B,C,D',
        ]);

        $score   = 0;
        $results = [];

        foreach ($request->answers as $a) {
            $q       = Question::find($a['question_id']);
            $correct = strtoupper($q->correct_answer) === strtoupper($a['answer']);

            if ($correct) {
                $score++;
            }

            $results[] = [
                'question_id'    => $q->id,
                'question'       => $q->question,
                'your_answer'    => $a['answer'],
                'correct_answer' => $q->correct_answer,
                'is_correct'     => $correct,
                'explanation'    => $q->explanation,
            ];
        }

        $total = count($request->answers);

        return response()->json([
            'score'      => $score,
            'total'      => $total,
            'percentage' => round(($score / $total) * 100, 2),
            'results'    => $results,
        ]);
    }
}
