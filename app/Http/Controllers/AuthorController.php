<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AuthorController extends Controller
{

    public function show($id)
    {
        $query = request()->query();
        $search_by_book_title = !!array_key_exists("book_title", $query) ? mb_strtolower($query["book_title"], "UTF-8") : '';
        $search_by_book_category = !!array_key_exists("book_category", $query) ? $query["book_category"] : null;
        $search_by_book_author = !!array_key_exists("book_author", $query) ? $query["book_author"] : null;
        $search_by_book_year = !!array_key_exists("book_year", $query) ? $query["book_year"] : null;
        $search_by_book_status = !!array_key_exists("book_status", $query) ? $query["book_status"] : null;

        $author = Author::findOrFail($id);

        $books = Book::whereIn('id', array_map(
            function(Array $array) {return $array['id'];},
            array_filter($author->books->toArray(), function($book) use ($search_by_book_title) {
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

        return view('common-content.author', [
            'author' => $author,
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
        ]);
    }

    public function store(Request $request)
    {
        $query = $request->query();
        $redirect_to = 'admin-panel.authors';
        $parameters = [];
        if (array_key_exists("redirect_to", $query)) {
            $redirect_to = $query["redirect_to"];
        }
        if (array_key_exists("query_parameters", $query)) {
            $parameters = $query["query_parameters"];
        }

        $request->validate([
            'author_name' => ['required', 'string', 'min:3', 'max:70'],
            'about_author' => ['required', 'string'],
        ]);
        $author = Author::where('author_name', $request->author_name)->get()->first();

        $file= $request->file('author_photo');
        $filename= date('YmdHi').$file->getClientOriginalName();
        $file-> move(public_path('Image/authors'), $filename);

        if (!$author) {
            $author = Author::create([
                'author_name' => $request->author_name,
                'about_author' => $request->about_author,
                'author_photo' => $filename,
            ]);
        } else {
            $author->update([
                'author_name' => $request->author_name,
                'about_author' => $request->about_author,
                'author_photo' => $filename,
            ]);
        }

        $response = redirect(route($redirect_to, $parameters, absolute: false))->with('cu_author_id', $author->id);

        if ($request->has('cu_category_id')) {
            $response = $response->with('cu_category_id', $request->cu_category_id);
        }

        return $response;
    }

    public function update(Request $request): RedirectResponse
    {
        $query = $request->query();
        $redirect_to = 'admin-panel.authors';
        $parameters = [];
        if (array_key_exists("redirect_to", $query)) {
            $redirect_to = $query["redirect_to"];
        }
        if (array_key_exists("query_parameters", $query)) {
            $parameters = $query["query_parameters"];
        }

        $request->validate([
            'id' => ['required', 'integer'],
            'author_name' => ['required', 'string', 'min:3', 'max:70'],
            'about_author' => ['required', 'string'],
        ]);
        $author = Author::find($request->id);

        $file= $request->file('author_photo');
        $filename= date('YmdHi').$file->getClientOriginalName();
        $file-> move(public_path('Image/authors'), $filename);

        if (!$author) {
            $author = Author::create([
                'author_name' => $request->author_name,
                'about_author' => $request->about_author,
                'author_photo' => $filename,
            ]);
        } else {
            $author->update([
                'author_name' => $request->author_name,
                'about_author' => $request->about_author,
                'author_photo' => $filename,
            ]);
        }

        $response = redirect(route($redirect_to, $parameters, absolute: false))->with('cu_author_id', $author->id);

        if ($request->has('cu_category_id')) {
            $response = $response->with('cu_category_id', $request->cu_category_id);
        }

        return $response;
    }

    public function destroy(Request $request): RedirectResponse
    {
        $query = $request->query();
        $redirect_to = 'admin-panel.authors';
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

        $author = Author::find($request->id);
        if ($author) {
            $author->delete();
        }

        $response = redirect(route($redirect_to, $parameters, absolute: false))->with('cu_author_id', '');

        if ($request->has('cu_category_id')) {
            $response = $response->with('cu_category_id', $request->cu_category_id);
        }

        return $response;
    }
}
