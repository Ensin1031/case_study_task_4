<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BookController extends Controller
{

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
            'category_name' => ['required', 'string', 'min:3', 'max:30'],
        ]);
        $category = Category::where('id', $request->id)->get()->first();
        if ($category) {
            $category->category_name = $request->category_name;
            $category->save();
        }

        $response = redirect(route($redirect_to, $parameters, absolute: false))->with('cu_category_id', $category->id);

        if ($request->has('cu_author_id')) {
            $response = $response->with('cu_author_id', $request->cu_author_id);
        }

        return $response;
    }

    public function destroy(Request $request): RedirectResponse
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
        ]);

        $category = Category::where('id', $request->id)->get()->first();
        if ($category) {
            $category->delete();
        }

        $response = redirect(route($redirect_to, $parameters, absolute: false))->with('cu_category_id', '');

        if ($request->has('cu_author_id')) {
            $response = $response->with('cu_author_id', $request->cu_author_id);
        }

        return $response;
    }
}
