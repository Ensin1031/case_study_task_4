<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{

    public function basket(Request $request): View
    {
        return view('shop.basket', [
            'user' => $request->user(),
        ]);
    }

    public function purchases(Request $request): View
    {
        return view('shop.purchases', [
            'user' => $request->user(),
        ]);
    }

}
