<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{

    public function basket(Request $request): View
    {
        $user = $request->user();
        $purchases = $user->basket_purchases();
        return view('shop.basket', [
            'user' => $user,
            'purchases' => $purchases->get(),
            'statuses' => Shop::BASKET_STATUSES,
        ]);
    }

    public function purchases(Request $request): View
    {
        $user = $request->user();
        $purchases = $user->purchased_books();
        return view('shop.purchases', [
            'user' => $user,
            'purchases' => $purchases->get(),
            'statuses' => Shop::BUY_STATUSES,
        ]);
    }

}
