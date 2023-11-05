<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\BooksController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/login', [ AuthController::class,'login' ]);
Route::post('/register', [ AuthController::class,'register' ]);
Route::middleware('auth:sanctum')->group(function(){
   Route::get('/books',[BooksController::class,'index']);
   Route::get('/books/getFilters',[BooksController::class,'getFilters']);
   Route::get('/books/getFilterValues',[BooksController::class,'getFilterValues']);


   Route::middleware(AdminMiddleware::class)->group(function (){
       Route::get('admin/dashboard',[DashboardController::class,'index']);
   });
});
