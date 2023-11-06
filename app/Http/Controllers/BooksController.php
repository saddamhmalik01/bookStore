<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Books;
use App\Models\Genre;
use App\Models\Publisher;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class BooksController extends Controller
{
    public function getBooksForDatabaseSeeding()
    {
        $books =  Http::get('fakerapi.it/api/v1/books?_quantity=500');
        return $books->json();

    }
    public function index(Request $request): JsonResponse
    {
        $searchQuery = $request->input('search');
        $filters = $request->only(['published_on', 'genre_id', 'author_id', 'publisher_id']);
        $limit = $request->input('limit', 12);
        $sortBy = $request->input('sort_by', 'title');

        $books = Books::search($searchQuery);

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


    public function getFilters()
    {
        return successResponse('',[
            'genre_id'=> 'Genre',
            'author_id' => 'Author',
            'publisher_id' => 'Publisher'
        ]);
    }

    public function getFilterValues(Request $request): JsonResponse
    {
        $filter = $request->filter;
        $filter_values = ($filter == 'author_id') ? $this->getAuthorsList()
            : (($filter == 'genre_id') ? $this->getGenreList() : $this->getPublisherList());
        return successResponse('',$filter_values);
    }

    private function getAuthorsList(): Collection
    {
        return Author::all('id','name');
    }

    private function getGenreList(): Collection
    {
       return Genre::all('id','name');
    }

    private function getPublisherList(): Collection
    {
        return Publisher::all('id','name');
    }

    public function getBookDetails($id): JsonResponse
    {
      $book = Books::find($id);
        return ($book) ? successResponse('',[
            'bookDetails'=> $book,
            'authors' => Author::all('id','name'),
            'publishers' => Publisher::all('id','name'),
            'genres' => Genre::all('id','name')
        ])
            : errorResponse('Something went wrong');
    }

    public function updateBookDetails(Request $request): JsonResponse
    {
        $book_id = $request->id;
        $book = Books::find($book_id);
        if($book){
            $data = [
                'title' => $request->title,
                'author_id' => $request->author_id,
                'genre_id' => $request->genre_id,
                'description' => $request->description,
                'isbn' => $request->isbn,
                'image' => $request->image,
                'published_on' => $request->published_on,
                'publisher_id' => $request->publisher_id,
                'updated_at' => Carbon::now(),
            ];

            return $book->update($data)
                ? successResponse('Book Updated Successfully')
                : errorResponse('Something went wrong');
        }
        return errorResponse('Book not found');
    }

    public function doDirtyThings(): void
    {
//      $books->searchable();
    }


}
