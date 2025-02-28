<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AuthorController extends Controller
{

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
        $author = Author::where('id', $request->id)->get()->first();

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

        $author = Author::where('id', $request->id)->get()->first();
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
