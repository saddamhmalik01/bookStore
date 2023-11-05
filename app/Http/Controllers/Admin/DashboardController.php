<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Books;
use App\Models\Genre;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
//        dd('hello');
        try{
            return successResponse('',[
                'books' =>  Books::all()->count(),
                'authors' => Author::all()->count(),
                'genres' => Genre::all()->count(),
                'publishers' => Publisher::all()->count(),
                'users' => User::all()->count()
            ]);
        } catch (\Exception $e)
        {
            return errorResponse($e->getMessage());
        }

    }
}
