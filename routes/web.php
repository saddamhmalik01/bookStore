<?php

use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(AdminMiddleware::class)->get('/admin', function () {
    return view('admin');
})->where('any', '.*');

Route::get('/admin/{any}', function () {
    return view('admin');
})->where('any', '.*');

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*')->name('/client');
