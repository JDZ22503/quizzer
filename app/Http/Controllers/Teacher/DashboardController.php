<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateQuestionsJob;
use App\Models\Book;
use App\Models\Chapter;
use App\Models\Subject;
use App\Models\AiJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        $selectedStandard = $request->query('standard');
        $selectedSubjectId = $request->query('subject_id');

        // Base query for teacher's books
        $query = Book::where('teacher_id', $teacher->id)
            ->with(['subject', 'chapters.questions']);

        $subjects = collect();

        if ($selectedStandard) {
            // Get all unique subjects this teacher has books for in this standard
            $subjects = Subject::where('class', $selectedStandard)
                ->whereHas('books', function($q) use ($teacher) {
                    $q->where('teacher_id', $teacher->id);
                })
                ->get();

            // Filter books by standard
            $query->whereHas('subject', function($q) use ($selectedStandard) {
                $q->where('class', $selectedStandard);
            });

            // Further filter by subject if selected
            if ($selectedSubjectId) {
                $query->where('subject_id', $selectedSubjectId);
            }
        }

        $books = $query->latest()->get();

        // New: AI Job stats
        $aiJobsPending = \App\Models\AiJob::where('teacher_id', $teacher->id)
            ->whereIn('status', ['pending', 'processing'])
            ->count();
        
        $aiJobsCompleted = \App\Models\AiJob::where('teacher_id', $teacher->id)
            ->where('status', 'completed')
            ->count();

        // New: Overall stats for the selected standard/subject
        $totalChapters = $books->sum(fn($b) => $b->chapters->count());
        $totalQuestions = $books->sum(fn($b) => $b->chapters->sum(fn($c) => $c->questions->count()));

        return view('teacher.dashboard', compact(
            'teacher', 
            'books', 
            'selectedStandard', 
            'subjects', 
            'selectedSubjectId',
            'aiJobsPending',
            'aiJobsCompleted',
            'totalChapters',
            'totalQuestions'
        ));
    }


    public function bookShow(Book $book)
    {
        $book->load('subject', 'chapters.questions', 'chapters.topics');
        return view('teacher.book-show', compact('book'));
    }

    public function updatePreference(Request $request, Book $book)
    {
        $request->validate([
            'question_preference' => 'required|string|in:mcq,qa,topic',
        ]);

        $book->update([
            'question_preference' => $request->question_preference,
        ]);

        return back()->with('success', 'Default question format updated!');
    }

    public function addChapterForm(Book $book)
    {
        return view('teacher.add-chapter', compact('book'));
    }

    public function storeChapter(Request $request, Book $book)
    {
        $request->validate([
            'title'          => 'required|string|max:255',
            'chapter_number' => 'required|integer',
            'content'        => $request->question_format === 'topic' ? 'nullable|string' : 'required|string',
            'question_format' => 'required|string|in:mcq,qa,topic',
            'topic_title'    => $request->question_format === 'topic' ? 'required|string|max:255' : 'nullable|string|max:255',
        ]);

        $chapter = Chapter::create([
            'book_id'        => $book->id,
            'title'          => $request->title,
            'chapter_number' => $request->chapter_number,
            'content'        => $request->content,
        ]);

        $aiJob = AiJob::create([
            'teacher_id' => Auth::guard('teacher')->id(),
            'chapter_id' => $chapter->id,
            'status'     => 'pending',
        ]);

        GenerateQuestionsJob::dispatch($chapter, null, $request->question_format, $request->topic_title, $aiJob->job_id);

        return redirect()->route('teacher.book.show', $book)
            ->with('success', 'Chapter added manually. MCQ generation is processing.');
    }

    public function deleteChapter(Chapter $chapter)
    {
        $bookId = $chapter->book_id;
        
        // This relies on cascading deletes in the DB if configured,
        // or Eloquent events, but manually deleting questions is safer.
        $chapter->questions()->delete();
        $chapter->delete();

        return redirect()->route('teacher.book.show', $bookId)
            ->with('success', 'Chapter and its questions deleted successfully.');
    }

    public function updateChapter(Request $request, Chapter $chapter)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $chapter->update($data);

        return back()->with('success', 'Chapter updated successfully.');
    }

    public function deleteQuestions(Chapter $chapter, string $type)
    {
        $chapter->questions()->where('type', $type)->delete();

        return redirect()->route('teacher.book.show', $chapter->book_id)
            ->with('success', strtoupper($type) . ' questions deleted successfully.');
    }

    public function appendContentForm(Chapter $chapter)
    {
        $chapter->load('book');
        return view('teacher.append-chapter-content', compact('chapter'));
    }

    public function appendContentStore(Request $request, Chapter $chapter)
    {
        $request->validate([
            'content' => $request->question_format === 'topic' ? 'nullable|string' : 'required|string',
            'question_format' => 'required|string|in:mcq,qa,topic',
            'topic_title'    => $request->question_format === 'topic' ? 'required|string|max:255' : 'nullable|string|max:255',
        ]);

        $newContent = $request->content;

        // Append to existing content in DB
        $chapter->update([
            'content' => $chapter->content . "\n\n" . $newContent,
        ]);

        $aiJob = AiJob::create([
            'teacher_id' => Auth::guard('teacher')->id(),
            'chapter_id' => $chapter->id,
            'status'     => 'pending',
            'prompt'     => 'Appended content: ' . mb_substr($newContent, 0, 50) . '...',
        ]);

        // Dispatch job to generate MCQs *only* for the newly appended text
        GenerateQuestionsJob::dispatch($chapter, $newContent, $request->question_format, $request->topic_title, $aiJob->job_id);

        return redirect()->route('teacher.book.show', $chapter->book_id)
            ->with('success', 'New content appended. MCQ generation for new text is processing.');
    }
    public function deleteTopic(\App\Models\Topic $topic)
    {
        $bookId = $topic->chapter->book_id;
        $topic->delete();

        return redirect()->route('teacher.book.show', $bookId)
            ->with('success', 'Topic explanation deleted successfully.');
    }

    public function subjectShow(Subject $subject)
    {
        $teacher = Auth::guard('teacher')->user();
        $books = Book::where('teacher_id', $teacher->id)
            ->where('subject_id', $subject->id)
            ->withCount('chapters')
            ->get();
        
        return view('teacher.subject-show', compact('subject', 'books'));
    }

    public function chapterShow(Request $request, Chapter $chapter)
    {
        $jobId = $request->query('job_id');
        
        $questions = $chapter->questions();
        $topics = $chapter->topics();

        if ($jobId) {
            $questions->where('ai_job_id', $jobId);
            $topics->where('ai_job_id', $jobId);
        }

        $questions = $questions->get();
        $topics = $topics->get();

        $chapter->load('book.subject');
        
        return view('teacher.chapter-show', [
            'chapter' => $chapter,
            'questions' => $questions,
            'topics' => $topics,
            'filteredByJob' => $jobId ? true : false
        ]);
    }

    public function aiMonitor()
    {
        $teacher = Auth::guard('teacher')->user();
        $jobs = \App\Models\AiJob::where('teacher_id', $teacher->id)
            ->with('chapter.book')
            ->latest()
            ->paginate(15);
        
        return view('teacher.ai-monitor', compact('jobs'));
    }

    public function analytics()
    {
        $teacher = Auth::guard('teacher')->user();
        // Simplified for now, will enhance later
        return view('teacher.analytics', compact('teacher'));
    }

    public function settings()
    {
        $teacher = Auth::guard('teacher')->user();
        return view('teacher.settings', compact('teacher'));
    }

    public function updateSettings(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,' . $teacher->id,
            'preferred_language' => 'nullable|string',
        ]);

        $teacher->update($request->only('name', 'email', 'preferred_language'));

        return back()->with('success', 'Settings updated successfully.');
    }
}
