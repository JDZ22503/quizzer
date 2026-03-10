<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Question;
use App\Models\Subject;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class QuizWebController extends Controller
{
    public function subjects()
    {
        $studentClass = auth()->guard('student')->user()->class;
        $studentMedium = auth()->guard('student')->user()->medium;

        $subjectsQuery = Subject::with(['books' => function($query) use ($studentMedium) {
            $query->where('medium', $studentMedium);
        }]);
        
        if ($studentClass) {
            $subjectsQuery->where('class', $studentClass);
        }

        $subjects = $subjectsQuery->get();

        return view('student.subjects', compact('subjects'));
    }

    public function subjectShow(int $id)
    {
        $studentMedium = auth()->guard('student')->user()->medium;
        $subject = Subject::with(['books' => function($query) use ($studentMedium) {
            $query->where('medium', $studentMedium)
                  ->with(['chapters' => function($q) {
                      $q->with(['topics' => function($t) {
                          $t->orderBy('created_at');
                      }])->withCount([
                          'questions as mcq_count' => function ($sq) { $sq->where('type', 'mcq'); },
                          'questions as qa_count' => function ($sq) { $sq->where('type', 'qa'); }
                      ])->orderBy('chapter_number');
                  }]);
        }])->findOrFail($id);

        return view('student.subject-show', compact('subject'));
    }

    public function dashboard(Request $request)
    {
        $student = auth()->guard('student')->user();
        
        // 1. Momentum Chart Data (Default 14 days)
        $days = $request->get('days', 14);
        $activityData = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            // Using whereDate and student_id. Ensure we only count unique CHAPTER attempts if that's the intent, 
            // but usually "total quizzes" means any attempt.
            $count = QuizAttempt::where('student_id', $student->id)
                ->whereDate('created_at', $date)
                ->count();
            
            $activityData[] = [
                'date' => $date,
                'count' => $count,
                'label' => now()->subDays($i)->format('j')
            ];
        }

        // Streak data for the current week (UI purposes)
        $startOfWeek = now()->startOfWeek();
        $weekDays = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $wasActive = QuizAttempt::where('student_id', $student->id)
                ->where('type', 'daily')
                ->whereDate('created_at', $date)
                ->exists();
            
            $weekDays[] = [
                'label' => $date->format('M j'),
                'short' => $date->format('D')[0],
                'active' => $wasActive,
                'isToday' => $date->isToday()
            ];
        }

        // 2. Full Monthly Calendar Data with Navigation
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        $date = \Carbon\Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $date->daysInMonth;
        $startOfMonth = $date->copy()->startOfMonth();
        $startDayOfWeek = $startOfMonth->dayOfWeek;

        $calendarData = [];
        for ($i = 0; $i < $startDayOfWeek; $i++) {
            $calendarData[] = ['padding' => true];
        }

        $attemptsOfMonth = QuizAttempt::where('student_id', $student->id)
            ->where('type', 'daily')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $wasActive = $attemptsOfMonth->contains(function($attempt) use ($dateStr) {
                return $attempt->created_at->format('Y-m-d') === $dateStr;
            });

            $currentDateObj = $date->copy()->day($day);
            $isPast = $currentDateObj->isBefore(now()->startOfDay()) && $currentDateObj->gte($student->created_at->startOfDay());

            $calendarData[] = [
                'day' => $day,
                'active' => $wasActive,
                'isToday' => now()->isSameDay($currentDateObj),
                'isPast' => $isPast,
                'padding' => false
            ];
        }

        $currentMonthName = $date->format('F');
        $currentMonth = $month;
        $currentYear = $year;

        // Navigation links
        $prevDate = $date->copy()->subMonth();
        $nextDate = $date->copy()->addMonth();
        $prevUrl = route('student.dashboard', ['month' => $prevDate->month, 'year' => $prevDate->year]);
        $nextUrl = route('student.dashboard', ['month' => $nextDate->month, 'year' => $nextDate->year]);

        // 3. Recent Activity (Last 2 Quizzes)
        $recentQuizzes = QuizAttempt::where('student_id', $student->id)
            ->with('chapter')
            ->orderBy('created_at', 'desc')
            ->limit(2)
            ->get();

        return view('student.dashboard', compact(
            'student', 'activityData', 'weekDays', 'calendarData', 
            'currentMonthName', 'currentMonth', 'currentYear', 'prevUrl', 'nextUrl', 'days', 'recentQuizzes'
        ));
    }

    public function chapters(int $book_id)
    {
        $chapters = Chapter::where('book_id', $book_id)
            ->withCount([
                'questions as mcq_count' => function ($query) {
                    $query->where('type', 'mcq');
                },
                'questions as qa_count' => function ($query) {
                    $query->where('type', 'qa');
                }
            ])
            ->orderBy('chapter_number')
            ->get();

        return view('student.chapters', compact('chapters'));
    }

    public function quiz(int $chapter_id)
    {
        $student = auth()->guard('student')->user();
        $chapter = Chapter::findOrFail($chapter_id);

        // Check 7-day cooldown
        $lastAttempt = QuizAttempt::where('student_id', $student->id)
            ->where('chapter_id', $chapter_id)
            ->where('created_at', '>=', now()->subDays(7))
            ->first();

        if ($lastAttempt) {
            return redirect()->route('student.chapters', $chapter->book_id)
                ->with('error', "You already completed this quiz. You can retake it after 7 days.");
        }

        $questions = Question::where('chapter_id', $chapter_id)
            ->where('type', 'mcq')
            ->inRandomOrder()
            ->limit(10) // Limit to 10 questions
            ->get(['id', 'question', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_answer', 'difficulty']);

        return view('student.quiz', compact('chapter', 'questions'));
    }

    public function readMcqs(int $chapter_id)
    {
        $chapter = Chapter::findOrFail($chapter_id);
        $questions = Question::where('chapter_id', $chapter_id)
            ->where('type', 'mcq')
            ->orderBy('id')
            ->get();

        return view('student.read-mcqs', compact('chapter', 'questions'));
    }

    public function dailyChallenge()
    {
        $student = auth()->guard('student')->user();
        
        // Check if already done today
        if ($student->last_streak_at && $student->last_streak_at->isToday()) {
            return redirect()->route('student.subjects')->with('info', "You've already completed today's challenge! Come back tomorrow.");
        }

        // Fetch 5 random MCQs from all books available to student's class and medium
        $subjectIds = Subject::where('class', $student->class)->pluck('id');
        $studentMedium = $student->medium;
        
        $questions = Question::whereHas('chapter.book', function($q) use ($subjectIds, $studentMedium) {
                $q->whereIn('subject_id', $subjectIds)
                  ->where('medium', $studentMedium);
            })
            ->where('type', 'mcq')
            ->inRandomOrder()
            ->limit(5)
            ->get();

        if ($questions->count() < 5) {
            return redirect()->route('student.subjects')->with('error', "Not enough questions available for a Daily Challenge yet.");
        }

        return view('student.daily-challenge', compact('questions'));
    }

    public function study(int $chapter_id)
    {
        $chapter   = Chapter::findOrFail($chapter_id);
        $questions = Question::where('chapter_id', $chapter_id)
            ->where('type', 'qa')
            ->orderBy('id')
            ->get();

        return view('student.study', compact('chapter', 'questions'));
    }

    public function topic(int $id)
    {
        $topic = \App\Models\Topic::with('chapter.book')->findOrFail($id);
        return view('student.topic', compact('topic'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'answers'               => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.answer'      => 'nullable|in:A,B,C,D',
        ]);

        $student = auth()->guard('student')->user();
        $score   = 0;
        $total   = count($request->answers);

        $details = [];
        foreach ($request->answers as $a) {
            $q       = Question::find($a['question_id']);
            $answerVal = $a['answer'] ?? '';
            $correct = $answerVal ? (strtoupper($q->correct_answer) === strtoupper($answerVal)) : false;
            
            if ($correct) $score++;
            
            $details[] = [
                'question_id' => $q->id,
                'user_answer' => $answerVal,
                'is_correct'  => $correct,
            ];
        }

        // Record the attempt
        $accuracy = ($total > 0) ? ($score / $total) * 100 : 0;
        $attempt = QuizAttempt::create([
            'student_id' => $student->id,
            'chapter_id' => $request->chapter_id,
            'score'      => $score,
            'total'      => $total,
            'accuracy'   => $accuracy,
            'type'       => 'chapter',
            'details'    => $details,
        ]);

        // Gamification Logic (XP ONLY)
        $xpEarned = $score * 2;
        $student->xp += $xpEarned;

        // Level Up Logic (100 XP per level)
        $newLevel = floor($student->xp / 100) + 1;
        $student->level = max($student->level, $newLevel);

        $student->last_active_at = now();
        $student->save();

        return redirect()->route('student.quiz.results', $attempt->id)->with('success', "Quiz completed! You earned {$xpEarned} XP.");
    }

    public function submitDailyChallenge(Request $request)
    {
        $request->validate([
            'answers'               => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.answer'      => 'nullable|in:A,B,C,D',
        ]);

        $student = auth()->guard('student')->user();
        
        if ($student->last_streak_at && $student->last_streak_at->isToday()) {
            return redirect()->route('student.subjects')->with('info', "Already completed today's challenge.");
        }

        $score = 0;
        $total = count($request->answers);

        $details = [];
        foreach ($request->answers as $a) {
            $q = Question::find($a['question_id']);
            $answerVal = $a['answer'] ?? '';
            $correct = $answerVal ? (strtoupper($q->correct_answer) === strtoupper($answerVal)) : false;
            
            if ($correct) $score++;
            
            $details[] = [
                'question_id' => $q->id,
                'user_answer' => $answerVal,
                'is_correct'  => $correct,
            ];
        }

        // Record the attempt
        $accuracy = ($total > 0) ? ($score / $total) * 100 : 0;
        $attempt = QuizAttempt::create([
            'student_id' => $student->id,
            'chapter_id' => $q->chapter_id, // Link to one chapter just for DB constraints
            'score'      => $score,
            'total'      => $total,
            'accuracy'   => $accuracy,
            'type'       => 'daily',
            'details'    => $details,
        ]);

        // Streak & XP Logic
        $xpEarned = $score * 2; 
        $student->xp += $xpEarned;

        // Level Up 
        $student->level = max($student->level, floor($student->xp / 100) + 1);

        // Streak Maintenance
        $yesterday = now()->subDay()->startOfDay();
        if (!$student->last_streak_at || $student->last_streak_at->startOfDay()->lessThan($yesterday)) {
            $student->streak = 1;
        } else {
            $student->streak += 1;
        }

        $student->last_streak_at = now();
        $student->last_active_at = now();
        $student->save();

        return redirect()->route('student.quiz.results', $attempt->id)->with('success', "🔥 Daily Challenge Complete! Streak: {$student->streak} Days.");
    }

    public function results(int $attempt_id)
    {
        $student = auth()->guard('student')->user();
        $attempt = QuizAttempt::where('student_id', $student->id)
            ->with('chapter')
            ->findOrFail($attempt_id);

        $results = [];
        if ($attempt->details) {
            foreach ($attempt->details as $detail) {
                $q = Question::find($detail['question_id']);
                if (!$q) continue;

                $results[] = [
                    'question'      => $q->question,
                    'options'       => [
                        'A' => $q->option_a,
                        'B' => $q->option_b,
                        'C' => $q->option_c,
                        'D' => $q->option_d,
                    ],
                    'correct_answer' => $q->correct_answer,
                    'user_answer'    => $detail['user_answer'],
                    'is_correct'     => $detail['is_correct'],
                    'explanation'    => $q->explanation,
                ];
            }
        }

        return view('student.quiz-results', compact('attempt', 'results'));
    }

    public function history()
    {
        $student = auth()->guard('student')->user();
        $attempts = QuizAttempt::where('student_id', $student->id)
            ->with(['chapter.book'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('student.history', compact('attempts'));
    }
}
