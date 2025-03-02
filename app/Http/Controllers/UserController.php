<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function show($id)
    {
        $query = request()->query();
        $search_by_book_title = !!array_key_exists("book_title", $query) ? mb_strtolower($query["book_title"], "UTF-8") : '';
        $search_by_book_category = !!array_key_exists("book_category", $query) ? $query["book_category"] : null;
        $search_by_book_author = !!array_key_exists("book_author", $query) ? $query["book_author"] : null;
        $search_by_book_year = !!array_key_exists("book_year", $query) ? $query["book_year"] : null;
        $search_by_book_status = !!array_key_exists("book_status", $query) ? $query["book_status"] : null;

        $user = User::findOrFail($id);

        $books = Book::whereIn('id', array_map(
            function(Array $array) {return $array['id'];},
            array_filter($user->sub_books->toArray(), function($book) use ($search_by_book_title) {
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

        return view('common-content.user', [
            'user' => $user,
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
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $query = $request->query();
        $redirect_to = 'admin-panel.users';
        $parameters = [];
        if (array_key_exists("redirect_to", $query)) {
            $redirect_to = $query["redirect_to"];
        }
        if (array_key_exists("query_parameters", $query)) {
            $parameters = $query["query_parameters"];
        }

        if (!$request->has('is_admin')) {
            $request->merge(['is_admin' => 0]);
        }

        $request->validate([
            'id' => ['required', 'integer'],
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'is_admin' => ['required'],
        ]);

        $user = User::where('id', $request->id)->get()->first();
        if ($user) {
            $user->name = $request->name;
            $user->is_admin = !!($request->is_admin);
            $user->save();
        }

        return redirect(route($redirect_to, $parameters, absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $query = $request->query();
        $redirect_to = 'admin-panel.users';
        $parameters = [];
        if (array_key_exists("redirect_to", $query)) {
            $redirect_to = $query["redirect_to"];
        }
        if (array_key_exists("query_parameters", $query)) {
            $parameters = $query["query_parameters"];
        }

        if (!$request->has('is_active')) {
            $request->merge(['is_active' => 0]);
        }

        $request->validate([
            'id' => ['required', 'integer'],
            'is_active' => ['required'],
        ]);

        $user = User::where('id', $request->id)->get()->first();
        if ($user) {
            $user->is_active = !!($request->is_active);
            $user->save();
        }

        return redirect(route($redirect_to, $parameters, absolute: false));
    }

}
