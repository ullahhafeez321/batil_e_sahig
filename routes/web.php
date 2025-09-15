<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

# Home Route
Route::get('/home', [HomeController::class, 'index'])->name('home');

# Post Routes
Route::resource('post',PostController::class);
# Post Like Route
Route::post('/like/{id}',[PostController::class, 'like'])->name('post.like');

# Comment Routes
Route::get('/comment/{id}',[CommentController::class, 'show'])->name('comment.show');
Route::post('/comment',[CommentController::class, 'store'])->name('comment.store');


# User Profile Routes
Route::resource('user',UserController::class);

# Follow Routes
Route::get('/followers/{id}', [FollowerController::class, 'index'])->name('follow');
Route::post('/user/follow/{id}', [UserController::class, 'follow'])->name('user.follow');

# Search Route
Route::get('/search', [UserController::class, 'search'])->name('user.search');