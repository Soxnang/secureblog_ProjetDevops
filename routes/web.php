<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('posts.index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes protégées AVANT les routes dynamiques
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/posts/create', [PostController::class, 'create'])
        ->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])
        ->name('posts.store');
    Route::get('/posts/{post:slug}/edit', [PostController::class, 'edit'])
        ->name('posts.edit');
    Route::patch('/posts/{post:slug}', [PostController::class, 'update'])
        ->name('posts.update');
    Route::delete('/posts/{post:slug}', [PostController::class, 'destroy'])
        ->name('posts.destroy');
});

// Routes publiques APRÈS
Route::get('/posts', [PostController::class, 'index'])
    ->name('posts.index');
Route::get('/posts/{post:slug}', [PostController::class, 'show'])
    ->name('posts.show');

// Profil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';
