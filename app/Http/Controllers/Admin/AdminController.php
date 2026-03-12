<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiJob;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        \Illuminate\Support\Facades\Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_teachers' => Teacher::count(),
            'total_students' => Student::count(),
            'active_ai_jobs' => AiJob::whereIn('status', ['pending', 'processing'])->count(),
        ];

        $recentJobs = AiJob::with('teacher', 'chapter')->latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentJobs'));
    }

    public function users()
    {
        $teachers = Teacher::latest()->paginate(10);
        $students = Student::latest()->paginate(10);

        return view('admin.users', compact('teachers', 'students'));
    }

    public function editTeacher(Teacher $teacher)
    {
        return view('admin.teacher-edit', compact('teacher'));
    }

    public function updateTeacher(Request $request, Teacher $teacher)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,'.$teacher->id,
        ]);

        $teacher->update($data);

        return redirect()->route('admin.users')->with('success', 'Teacher updated successfully.');
    }

    public function editStudent(Student $student)
    {
        return view('admin.student-edit', compact('student'));
    }

    public function updateStudent(Request $request, Student $student)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,'.$student->id,
            'class' => 'required|string',
            'medium' => 'required|string',
            'gender' => 'required|in:boy,girl,other',
        ]);

        $student->update($data);

        return redirect()->route('admin.users')->with('success', 'Student updated successfully.');
    }

    public function aiJobs()
    {
        $jobs = AiJob::with('teacher', 'chapter')->latest()->paginate(20);

        return view('admin.ai-jobs', compact('jobs'));
    }

    public function analytics(Request $request)
    {
        $standards = Subject::distinct()->pluck('class')->sort()->values();
        $selectedStandard = $request->get('standard');

        $query = Subject::select(
            'subjects.class as standard',
            'subjects.name',
            DB::raw('COUNT(DISTINCT books.id) as total_books'),
            DB::raw('COUNT(DISTINCT chapters.id) as total_chapters'),
            DB::raw('COUNT(DISTINCT questions.id) as total_questions')
        )
            ->leftJoin('books', 'subjects.id', '=', 'books.subject_id')
            ->leftJoin('chapters', 'books.id', '=', 'chapters.book_id')
            ->leftJoin('questions', 'chapters.id', '=', 'questions.chapter_id');

        if ($selectedStandard) {
            $query->where('subjects.class', $selectedStandard);
        }

        $analytics = $query->groupBy('subjects.class', 'subjects.name')
            ->orderBy('subjects.class')
            ->get();

        return view('admin.analytics', compact('analytics', 'standards', 'selectedStandard'));
    }

    public function settings()
    {
        $settings = [
            'groq_api_key' => Setting::get('groq_api_key', env('GROQ_API_KEY')),
            'groq_model' => Setting::get('groq_model', env('GROQ_MODEL', 'llama-3.1-8b-instant')),
            'default_prompt' => Setting::get('default_prompt', 'You are an educational assistant. Generate MCQs based on the following content...'),
        ];

        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'groq_api_key' => 'nullable|string',
            'groq_model' => 'nullable|string',
            'default_prompt' => 'nullable|string',
        ]);

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Settings updated successfully.');
    }

    public function leagueSettings()
    {
        $leagues = ['bronze', 'silver', 'gold', 'diamond', 'champion'];
        $levels = [5, 4, 3, 2, 1];

        $thresholds = [];
        foreach ($leagues as $league) {
            foreach ($levels as $level) {
                $key = "league_{$league}_{$level}_xp";
                $thresholds[$key] = Setting::get($key, 0);
            }
        }

        return view('admin.league-settings', compact('leagues', 'levels', 'thresholds'));
    }

    public function updateLeagueSettings(Request $request)
    {
        $data = $request->validate([
            'thresholds' => 'required|array',
            'thresholds.*' => 'required|integer|min:0',
        ]);

        foreach ($data['thresholds'] as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'League thresholds updated successfully.');
    }

    public function exportPdf(int $id)
    {
        $chapter = \App\Models\Chapter::with('questions')->findOrFail($id);

        return view('teacher.export-pdf', compact('chapter'));
    }
}
