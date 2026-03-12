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
        \App\Jobs\GenerateQuestionsJob::dispatch($chapter, null, $request->question_format);

        return redirect()->route('teacher.book.show', $book)
            ->with('success', 'Content saved! AI is now generating MCQs in the background.');
    }

    public function bulkImport(Request $request)
    {
        $request->validate([
            'chapter_id' => 'required|exists:chapters,id',
            'csv_data'   => 'required|string',
        ]);

        $rows = explode("\n", $request->csv_data);
        $imported = 0;

        foreach ($rows as $row) {
            $data = str_getcsv($row, ",");
            if (count($data) < 6) continue;

            \App\Models\Question::create([
                'chapter_id'     => $request->chapter_id,
                'question'       => $data[0],
                'option_a'       => $data[1],
                'option_b'       => $data[2],
                'option_c'       => $data[3],
                'option_d'       => $data[4],
                'correct_answer' => $data[5],
                'type'           => 'mcq',
            ]);
            $imported++;
        }

        return back()->with('success', "Successfully imported {$imported} questions.");
    }
}
