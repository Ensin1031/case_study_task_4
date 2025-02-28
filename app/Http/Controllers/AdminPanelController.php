<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AdminPanelController extends Controller
{

    public function work_with_books(Request $request): View
    {
        $books = Book::all();
        // TODO
        return view('admin-panel.books', [
            'user' => $request->user(),
            'books' => $books,
            'categories' => Category::all(),
            'authors' => Author::all(),
        ]);
    }

    public function work_with_categories(Request $request): View
    {
        // TODO
        return view('admin-panel.categories', [
            'user' => $request->user(),
            'categories' => Category::all(),
        ]);
    }

    public function work_with_authors(Request $request): View
    {
        // TODO
        return view('admin-panel.authors', [
            'user' => $request->user(),
            'authors' => Author::all(),
        ]);
    }

    public function work_with_users(Request $request): View
    {
        // TODO
        return view('admin-panel.users', [
            'user' => $request->user(),
            'users' => User::all(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        return Redirect::to('/');  // TODO
    }

    public function destroy(Request $request): RedirectResponse
    {
        return Redirect::to('/');  // TODO
    }
}
