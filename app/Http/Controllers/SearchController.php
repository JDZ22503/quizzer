<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\Question;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');

        if (! $query) {
            return back();
        }

        $books = Book::where('title', 'LIKE', "%{$query}%")->limit(5)->get();
        $chapters = Chapter::where('title', 'LIKE', "%{$query}%")->limit(5)->get();
        $questions = Question::where('question', 'LIKE', "%{$query}%")->limit(10)->get();

        return view('search.results', compact('query', 'books', 'chapters', 'questions'));
    }
}
