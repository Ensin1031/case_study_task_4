<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AdminPanelController extends Controller
{
    public function edit(Request $request): View
    {
        // TODO
        return view('admin-panel.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        return Redirect::to('/');  // TODO
    }

    public function destroy(Request $request): RedirectResponse
    {
        return Redirect::to('/');  // TODO
    }
}
