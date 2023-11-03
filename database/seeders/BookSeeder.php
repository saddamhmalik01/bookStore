<?php

namespace Database\Seeders;

use App\Http\Controllers\BooksController;
use App\Models\Author;
use App\Models\Books;
use App\Models\Genre;
use App\Models\Publisher;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bookController = new BooksController();
        $books = $bookController->getBooksForDatabaseSeeding();
        $this->seedBooksTable($books['data']);
    }
    private function seedBooksTable(mixed $books): void
    {
        foreach ($books as $book) {
            try {
                $books_model = new Books();
                $is_book = $books_model->where('isbn', $book['isbn'])->orwhere('title', $book['title'])->first();
                if (!$is_book) {
                    $books_model->create([
                        'title' => $book['title'],
                        'author_id' => $this->seedAuthorTable($book['author']),
                        'genre_id' => $this->seedGenreTable($book['genre']),
                        'description' => $book['description'],
                        'isbn' => $book['isbn'],
                        'image' => $book['image'],
                        'published_on' => $book['published'],
                        'publisher_id' => $this->seedPublisherTable($book['publisher']),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }

            }catch (\Exception $e){
                dd($e);
            }

        }
    }

    private function seedAuthorTable($author)
    {
        $authorModel = new Author();
        $is_author = $authorModel->where('name',$author)->first();
        return ($is_author) ? $is_author->id : $authorModel->create(['name'=> $author])->id;
    }

    private function seedGenreTable(mixed $genre)
    {
        $genre_model = new Genre();
        $is_genre = $genre_model->where('name',$genre)->first();
        return $is_genre ? $is_genre->id : $genre_model->create(['name'=>$genre])->id;
    }

    private function seedPublisherTable(mixed $publisher)
    {
        $publisher_model = new Publisher();
        $is_publisher = $publisher_model->where('name',$publisher)->first();
        return $is_publisher ? $is_publisher->id : $publisher_model->create(['name'=> $publisher])->id;
    }

}
