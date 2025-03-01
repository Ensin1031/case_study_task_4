<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function store(Request $request)
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
            'category_name' => ['required', 'string', 'min:3', 'max:30'],
            'about_category' => ['required', 'string'],
        ]);
        $category = Category::where('category_name', $request->category_name)->get()->first();
        if (!$category) {
            $category = Category::create([
                'category_name' => $request->category_name,
                'about_category' => $request->about_category,
            ]);
        } else {
            $category->update([
                'category_name' => $request->category_name,
                'about_category' => $request->about_category,
            ]);
        }

        $response = redirect(route($redirect_to, $parameters, absolute: false))->with('cu_category_id', $category->id);

        if ($request->has('cu_author_id')) {
            $response = $response->with('cu_author_id', $request->cu_author_id);
        }

        return $response;
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
            'about_category' => ['required', 'string'],
        ]);
        $category = Category::where('id', $request->id)->get()->first();
        if (!$category) {
            $category = Category::create([
                'category_name' => $request->category_name,
                'about_category' => $request->about_category,
            ]);
        } else {
            $category->update([
                'category_name' => $request->category_name,
                'about_category' => $request->about_category,
            ]);
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
