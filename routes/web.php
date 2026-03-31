<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Dashboard/Feed routes
Route::get('/home', [PostController::class, 'index'])->name('home');
Route::get('/dashboard', [PostController::class, 'index'])->name('dashboard');

// Posts routes
Route::resource('posts', PostController::class)->except(['index', 'show']);
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// Likes
Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])->name('posts.like');

// Comments
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

// Profile routes
Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/profile/{user}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile/{user}', [ProfileController::class, 'update'])->name('profile.update');

// Follow system
Route::post('/users/{user}/follow', [FollowController::class, 'follow'])->name('users.follow');
Route::delete('/users/{user}/unfollow', [FollowController::class, 'unfollow'])->name('users.unfollow');