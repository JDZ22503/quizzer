<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Chapter;
use App\Models\Subject;
use App\Jobs\GenerateQuestionsJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManualMcqController extends Controller
{
    public function create(Request $request)
    {
        $selectedStandard = $request->query('standard');
        
        $query = Subject::orderBy('name');
        
        if ($selectedStandard) {
            $query->where('class', $selectedStandard);
        }
        
        $subjects = $query->get();
        
        return view('teacher.create-manual', compact('subjects', 'selectedStandard'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_id'     => 'required|exists:subjects,id',
            'title'          => 'required|string|max:255',
            'standard'       => 'required|string|max:50',
            'chapter_title'  => 'required|string|max:255',
            'content'        => 'required|string|min:50',
            'question_format' => 'required|string|in:mcq,qa',
            'medium'         => 'required|in:english,gujarati',
        ]);

        // Create a representation for the "Book" (which is now just a container for the manual text)
        $book = Book::create([
            'teacher_id' => Auth::guard('teacher')->id(),
            'subject_id' => $request->subject_id,
            'title'      => $request->title . " (Gr " . $request->standard . ")",
            'pdf_path'   => null, // No PDF for manual entry
            'status'     => 'processing',
            'question_preference' => $request->question_format,
            'medium'     => $request->medium,
        ]);

        $chapter = Chapter::create([
            'book_id'        => $book->id,
            'title'          => $request->chapter_title,
            'chapter_number' => 1,
            'content'        => $request->content,
        ]);

        // Dispatch background job for question generation
        GenerateQuestionsJob::dispatch($chapter, null, $request->question_format);

        return redirect()->route('teacher.book.show', $book)
            ->with('success', 'Content saved! AI is now generating MCQs in the background.');
    }
}
