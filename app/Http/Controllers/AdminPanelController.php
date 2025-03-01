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
                    'title' => 'Удалено'
                ],
            ],
            'categories' => Category::all(),
            'authors' => Author::all(),
        ]);
    }

    public function work_with_categories(Request $request): View
    {
        $query = $request->query();
        $search_by_category_name = !!array_key_exists("search_by_category_name", $query)
            ? mb_strtolower($query["search_by_category_name"], "UTF-8")
            : '';
        return view('admin-panel.categories', [
            'user' => $request->user(),
            'categories' => Category::whereIn('id', array_map(
                function(Array $array) {return $array['id'];},
                array_filter(Category::all()->toArray(), function($category) use ($search_by_category_name) {
                    return str_contains(mb_strtolower($category['category_name'], "UTF-8"), $search_by_category_name);
                })
            ))->get(),
        ]);
    }

    public function work_with_authors(Request $request): View
    {
        $query = $request->query();
        $search_by_author_name = !!array_key_exists("search_by_author_name", $query)
            ? mb_strtolower($query["search_by_author_name"], "UTF-8")
            : '';
        return view('admin-panel.authors', [
            'user' => $request->user(),
            'authors' => Author::whereIn('id', array_map(
                function(Array $array) {return $array['id'];},
                array_filter(Author::all()->toArray(), function($author) use ($search_by_author_name) {
                    return str_contains(mb_strtolower($author['author_name'], "UTF-8"), $search_by_author_name);
                })
            ))->get(),
        ]);
    }

    public function work_with_users(Request $request): View
    {
        $query = $request->query();
        $search_by_user = !!array_key_exists("search_by_user", $query)
            ? mb_strtolower($query["search_by_user"], "UTF-8")
            : '';
        return view('admin-panel.users', [
            'user' => $request->user(),
            'users' => User::whereIn('id', array_map(
                function(Array $array) {return $array['id'];},
                array_filter(User::all()->toArray(), function($user) use ($search_by_user) {
                    return str_contains(mb_strtolower($user['name'], "UTF-8"), $search_by_user)
                        || str_contains(mb_strtolower($user['email'], "UTF-8"), $search_by_user);
                })
            ))->get(),
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
