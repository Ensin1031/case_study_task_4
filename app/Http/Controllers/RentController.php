<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class RentController extends Controller
{

    public function active(Request $request): View
    {
        return view('rent.active', [
            'user' => $request->user(),
        ]);
    }

    public function archive(Request $request): View
    {
        return view('rent.archive', [
            'user' => $request->user(),
        ]);
    }

}
