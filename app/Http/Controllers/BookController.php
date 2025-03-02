<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\UserBook;
use Illuminate\Database\Eloquent\Collection;
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
            $this->get_books_data(books: Book::all(), request: $request, redirect_to: 'books.index')
        );
    }

    public function admin_books(Request $request): View
    {
        return view(
            'admin-panel.books',
            $this->get_books_data(books: Book::all(), request: $request, redirect_to: 'admin-panel.books')
        );
    }

    public function user_books(Request $request): View
    {
        return view(
            'common-content.books',
            $this->get_books_data(books: $request->user()->sub_books, request: $request, redirect_to: 'books.user-books')
        );
    }

    private function get_books_data(Collection $books, Request $request, string $redirect_to): array
    {
        $query = $request->query();
        $search_by_book_title = !!array_key_exists("book_title", $query) ? mb_strtolower($query["book_title"], "UTF-8") : '';
        $search_by_book_category = !!array_key_exists("book_category", $query) ? $query["book_category"] : null;
        $search_by_book_author = !!array_key_exists("book_author", $query) ? $query["book_author"] : null;
        $search_by_book_year = !!array_key_exists("book_year", $query) ? $query["book_year"] : null;
        $search_by_book_status = !!array_key_exists("book_status", $query) ? $query["book_status"] : null;

        $books = Book::whereIn('id', array_map(
            function(Array $array) {return $array['id'];},
            array_filter($books->toArray(), function($book) use ($search_by_book_title) {
                return str_contains(mb_strtolower($book['title'], "UTF-8"), $search_by_book_title);
            })
        ));
        if ($search_by_book_category) {
            $books = $books->where('category_id', $search_by_book_category);
        }
        if ($search_by_book_author) {
            $books = $books->where('author_id', $search_by_book_author);
        }
        if ($search_by_book_year) {
            $books = $books->where('published_year', $search_by_book_year);
        }
        if ($search_by_book_status) {
            $books = $books->where('status', $search_by_book_status);
        }

        return [
            'user' => $request->user(),
            'books' => $books->get(),
            'statuses' => [
                [
                    'id' => 1,
                    'title' => 'Черновик'
                ],
                [
                    'id' => 2,
                    'title' => 'Опубликовано'
                ],
                [
                    'id' => 3,
                    'title' => 'Снято с публикации'
                ],
            ],
            'categories' => Category::all(),
            'authors' => Author::all(),
            'redirect_to' => $redirect_to,
        ];
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
