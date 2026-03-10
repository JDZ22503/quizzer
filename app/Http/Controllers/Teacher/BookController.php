<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateQuestionsJob;
use App\Models\Book;
use App\Models\Chapter;
use App\Services\ChapterDetectorService;
use App\Services\PDFParserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'pdf'        => 'required|file|mimes:pdf|max:51200',
            'title'      => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        // Store PDF
        $path = $request->file('pdf')->store('books', 'public');

        // Create book record
        $book = Book::create([
            'teacher_id' => auth('sanctum')->id(),
            'subject_id' => $request->subject_id,
            'title'      => $request->title,
            'pdf_path'   => $path,
            'status'     => 'uploaded',
        ]);

        // Extract text from PDF
        $parser = new PDFParserService();
        $text   = $parser->extractText(Storage::disk('public')->path($path));

        // Detect chapters
        $chapterService = new ChapterDetectorService();
        $chapters       = $chapterService->detectChapters($text);

        if (empty($chapters)) {
            $book->update(['status' => 'no_chapters_found']);
            return response()->json([
                'status'  => 'no_chapters_found',
                'message' => 'No chapters detected in this PDF. Make sure chapter headings follow the pattern "Chapter N Title".',
            ], 422);
        }

        // Create Chapter records and dispatch jobs
        foreach ($chapters as $chapter) {
            $ch = Chapter::create([
                'book_id'        => $book->id,
                'title'          => $chapter['title'],
                'chapter_number' => $chapter['chapter_number'],
                'content'        => $chapter['content'],
            ]);

            GenerateQuestionsJob::dispatch($ch);
        }

        $book->update(['status' => 'processing']);

        return response()->json([
            'status'   => 'processing',
            'book_id'  => $book->id,
            'chapters' => count($chapters),
            'message'  => count($chapters) . ' chapter(s) detected. MCQ generation queued.',
        ]);
    }

    public function show(Book $book): JsonResponse
    {
        return response()->json($book->load('chapters.questions'));
    }
}
