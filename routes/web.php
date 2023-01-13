<?php

use App\Http\Controllers\ActorController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WatchlistController;
use Illuminate\Support\Facades\Auth;
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
Auth::routes();

Route::get('/actors', [ActorController::class, 'index'])->name('actors');
Route::get('/actors/create', [ActorController::class, 'create'])->name('actors.create');
Route::post('/actors', [ActorController::class, 'store'])->name('actors.store');
Route::get('/actors/{id}', [ActorController::class, 'show'])->name('actors.show');
Route::get('/actors/{id}/edit', [ActorController::class, 'edit'])->name("actors.edit");
Route::put('/actors/{id}', [ActorController::class, 'update'])->name('actors.update');
Route::delete('/actors/{id}', [ActorController::class, 'destroy'])->name('actors.destroy');

Route::redirect('/', '/movies');
Route::redirect('/home', '/movies');
Route::get('/movies', [MovieController::class, 'index'])->name('home');
Route::get('/movies/create', [MovieController::class, 'create'])->name('movies.create');
Route::post('/movies', [MovieController::class, 'store'])->name("movies.store");
Route::get('/movies/sort', [MovieController::class, 'sortBy'])->name('sort');
Route::get('/movies/{id}/edit', [MovieController::class, 'edit'])->name('movies.edit');
ROute::put('/movies/{id}', [MovieController::class, 'update'])->name('movies.update');
Route::post('/movies', [MovieController::class, 'store'])->name("movies.store");
Route::get('/movies/{id}', [MovieController::class, 'show'])->name("movies.show");
Route::get('/movies/search', [MovieController::class, 'indexSearch'])->name("search");
Route::delete('/movies/{id}', [MovieController::class, 'destroy'])->name('movies.destroy');

Route::get('/watchlists', [WatchlistController::class, 'index'])->name('watchlists.search');
Route::post('/watchlists/{id}', [WatchlistController::class, 'store'])->name('watchlists.store');
Route::put('/watchlists/{id}', [WatchlistController::class, 'update'])->name('watchlists.update');

Route::get('/profile', [UserController::class, 'index'])->name('users');
Route::put('/profile/update/{id}', [UserController::class, 'updateProfile'])->name('users.updateProfile');
Route::put('/profile/image/update/{id}', [UserController::class, 'updateImage'])->name('users.updateImage');




