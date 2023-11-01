<?php

namespace App\Http\Controllers;

use App\Models\Books;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class BooksController extends Controller
{
    public function getBooksForDatabaseSeeding()
    {
        $books =  Http::get('fakerapi.it/api/v1/books?_quantity=100');
        return $books->json();

    }
    public function index(Request $request): JsonResponse
    {
        $searchQuery = $request->input('search');
        $filters = $request->only(['published_on', 'genre_id', 'author_id', 'publisher_id']);
        $limit = $request->input('limit', 2);
        $sortBy = $request->input('sort_by', 'title');
//dd($sortBy);
        $books = Books::search($searchQuery);

        if (!empty($filters['published_on'])) {
            $books->where('published_on', $filters['published_on']);
        }
        if (!empty($filters['genre_id'])) {
            $books->where('genre_id', $filters['genre_id']);
        }
        if (!empty($filters['publisher_id'])) {
            $books->where('publisher_id', $filters['publisher_id']);
        }
        if (!empty($filters['author_id'])) {
            $books->where('author_id', $filters['author_id']);
        }

        $direction = $request->input('sort_direction', 'asc');

        $books->orderBy($sortBy, $direction);

        return successResponse('', $books->paginate($limit));
    }


}
