<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\UserBook;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{

    public function show($id)
    {
        $book = Book::findOrFail($id);
        return view('common-content.book', compact('book'));
    }

    public function index(Request $request): View
    {
        return view(
            'common-content.books',
            get_books_data(books: Book::all(), request: $request, set_in_result: ['user' => $request->user(), 'redirect_to' => 'books.index'])
        );
    }

    public function admin_books(Request $request): View
    {
        return view(
            'admin-panel.books',
            get_books_data(books: Book::all(), request: $request, set_in_result: ['user' => $request->user(), 'redirect_to' => 'admin-panel.books'])
        );
    }

    public function user_books(Request $request): View
    {
        return view(
            'common-content.books',
            get_books_data(books: $request->user()->sub_books, request: $request, set_in_result: ['user' => $request->user(), 'redirect_to' => 'books.user-books'])
        );
    }

    public function store(Request $request)
    {
        $query = $request->query();
        $redirect_to = 'admin-panel.books';
        $parameters = [];
        if (array_key_exists("redirect_to", $query)) {
            $redirect_to = $query["redirect_to"];
        }
        if (array_key_exists("query_parameters", $query)) {
            $parameters = $query["query_parameters"];
        }

        $request->validate([
            'title' => ['required', 'string', 'min:3', 'max:120'],
            'description' => ['required', 'string'],
            'status' => ['required', 'integer'],
            'price' => ['required'],
            'published_year' => ['required', 'integer'],
        ]);

        $data = $request->all();

        $file= $request->file('image');
        $filename= date('YmdHi').$file->getClientOriginalName();
        $file-> move(public_path('Image/books'), $filename);
        $data['image'] = $filename;

        Book::create($data);

        return redirect(route($redirect_to, $parameters, absolute: false));
    }

    public function update(Request $request): RedirectResponse
    {
        $query = $request->query();
        $redirect_to = 'admin-panel.categories';
        $parameters = [];
        if (array_key_exists("redirect_to", $query)) {
            $redirect_to = $query["redirect_to"];
        }
        if (array_key_exists("query_parameters", $query)) {
            $parameters = $query["query_parameters"];
        }

        $request->validate([
            'id' => ['required', 'integer'],
            'title' => ['required', 'string', 'min:3', 'max:120'],
            'description' => ['required', 'string'],
            'status' => ['required', 'integer'],
            'price' => ['required'],
            'published_year' => ['required', 'integer'],
        ]);

        $data = $request->all();

        $file= $request->file('image');
        $filename= date('YmdHi').$file->getClientOriginalName();
        $file-> move(public_path('Image/books'), $filename);
        $data['image'] = $filename;

        $book = Book::find($request->id);
        if ($book) {
            $book->update($data);
        }

        return redirect(route($redirect_to, $parameters, absolute: false));
    }

    public function update_status(Request $request): RedirectResponse
    {
        $query = $request->query();
        $redirect_to = 'admin-panel.books';
        $parameters = [];
        if (array_key_exists("redirect_to", $query)) {
            $redirect_to = $query["redirect_to"];
        }
        if (array_key_exists("query_parameters", $query)) {
            $parameters = $query["query_parameters"];
        }

        $request->validate([
            'id' => ['required', 'integer'],
            'status' => ['required', 'integer'],
        ]);
        $book = Book::find($request->id);
        if ($book) {
            $book->status = $request->status;
            $book->save();
        }

        return redirect(route($redirect_to, $parameters, absolute: false));
    }

    public function update_subs(Request $request): RedirectResponse
    {
        $query = $request->query();
        $redirect_to = 'admin-panel.books';
        $parameters = [];
        if (array_key_exists("redirect_to", $query)) {
            $redirect_to = $query["redirect_to"];
        }
        if (array_key_exists("query_parameters", $query)) {
            $parameters = $query["query_parameters"];
        }

        $request->validate([
            'book_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
        ]);
        $user_book_sub = UserBook::where('book_id', $request->book_id)->where('user_id', $request->user_id)->get()->first();
        if ($user_book_sub) {
            $user_book_sub->delete();
        } else {
            UserBook::create([
                'book_id' => $request->book_id,
                'user_id' => $request->user_id,
            ]);
        }

        return redirect(route($redirect_to, $parameters, absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $query = $request->query();
        $redirect_to = 'admin-panel.books';
        $parameters = [];
        if (array_key_exists("redirect_to", $query)) {
            $redirect_to = $query["redirect_to"];
        }
        if (array_key_exists("query_parameters", $query)) {
            $parameters = $query["query_parameters"];
        }

        $request->validate([
            'id' => ['required', 'integer'],
        ]);

        $book = Book::find($request->id);
        if ($book) {
            $book->delete();
        }

        return redirect(route($redirect_to, $parameters, absolute: false));
    }
}
