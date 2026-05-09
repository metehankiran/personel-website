<?php

declare(strict_types=1);

use App\Http\Controllers\BlogController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/references', [PageController::class, 'references'])->name('references');
Route::get('/stack', [PageController::class, 'stack'])->name('stack');
Route::get('/cv', [PageController::class, 'cv'])->name('cv');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/bookmarks', [PageController::class, 'bookmarks'])->name('bookmarks');

Route::get('/projects', [ProjectController::class, 'index'])->name('projects');
Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');

Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
