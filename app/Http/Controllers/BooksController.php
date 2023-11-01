<?php

namespace App\Http\Controllers;

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
    public function index()
    {

    }

}
