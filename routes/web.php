<?php

use App\Http\Controllers\AdminPanelController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    Route::get('/admin-panel/users', [AdminPanelController::class, 'work_with_users'])->name('admin-panel.users');
    Route::get('/admin-panel/books', [BookController::class, 'admin_books'])->name('admin-panel.books');
    Route::get('/admin-panel/categories', [AdminPanelController::class, 'work_with_categories'])->name('admin-panel.categories');
    Route::get('/admin-panel/authors', [AdminPanelController::class, 'work_with_authors'])->name('admin-panel.authors');

    Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
    Route::patch('/user', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user', [UserController::class, 'destroy'])->name('user.destroy');

    Route::get('/books/{id}', [BookController::class, 'show'])->name('book.show');
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/user-books', [BookController::class, 'user_books'])->name('books.user-books');
    Route::post('/books/create', [BookController::class, 'store'])->name('books.create');
    Route::patch('/books/update', [BookController::class, 'update'])->name('book.update');
    Route::patch('/books/update-status', [BookController::class, 'update_status'])->name('book.update-status');
    Route::patch('/books/update-subs', [BookController::class, 'update_subs'])->name('book.update-subs');
    Route::delete('/books/destroy', [BookController::class, 'destroy'])->name('book.destroy');

    Route::get('/category/{id}', [CategoryController::class, 'show'])->name('category.show');
    Route::post('/category', [CategoryController::class, 'store'])->name('category.create');
    Route::patch('/category', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/category', [CategoryController::class, 'destroy'])->name('category.destroy');

    Route::get('/author/{id}', [AuthorController::class, 'show'])->name('author.show');
    Route::post('/author', [AuthorController::class, 'store'])->name('author.create');
    Route::patch('/author', [AuthorController::class, 'update'])->name('author.update');
    Route::delete('/author', [AuthorController::class, 'destroy'])->name('author.destroy');

    Route::get('/shop/basket', [ShopController::class, 'basket'])->name('shop.basket');  // корзина
    Route::get('/shop/purchases', [ShopController::class, 'purchases'])->name('shop.purchases');  // покупки

    Route::get('/rent/active', [RentController::class, 'active'])->name('rent.active');
    Route::get('/rent/archive', [RentController::class, 'archive'])->name('rent.archive');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
