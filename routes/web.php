<?php

use App\Http\Controllers\AdminPanelController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/admin-panel/users', [AdminPanelController::class, 'work_with_users'])->name('admin-panel.users');
    Route::get('/admin-panel/books', [AdminPanelController::class, 'work_with_books'])->name('admin-panel.books');
    Route::get('/admin-panel/categories', [AdminPanelController::class, 'work_with_categories'])->name('admin-panel.categories');
    Route::get('/admin-panel/authors', [AdminPanelController::class, 'work_with_authors'])->name('admin-panel.authors');

    Route::patch('/user', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user', [UserController::class, 'destroy'])->name('user.destroy');

    Route::post('/book', [BookController::class, 'store'])->name('book.create');

    Route::post('/category', [CategoryController::class, 'store'])->name('category.create');
    Route::patch('/category', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/category', [CategoryController::class, 'destroy'])->name('category.destroy');

    Route::post('/author', [AuthorController::class, 'store'])->name('author.create');
    Route::patch('/author', [AuthorController::class, 'update'])->name('author.update');
    Route::delete('/author', [AuthorController::class, 'destroy'])->name('author.destroy');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
