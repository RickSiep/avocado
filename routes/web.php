<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ApiController::class, 'welcome']);

// Get a specific movie
Route::get('/{id}', [ApiController::class, 'show_movie'])->where('id', '[0-9]+')->name('movie');

// Favorite a movie
Route::post('/{id}', [ApiController::class, 'save_favorite'])->where('id', '[0-9]+')
    ->name('favorite');

// Delete a favorite
Route::delete('/{id}', [ApiController::class, 'delete_favorite'])->where('id', '[0-9]+')
    ->name('delete');
