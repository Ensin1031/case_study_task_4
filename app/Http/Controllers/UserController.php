<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view(
            'common-content.user',
            get_books_data(books: $user->sub_books, request: request(), set_in_result: ['user' => $user])
        );
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
