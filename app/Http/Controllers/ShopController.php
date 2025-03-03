<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{

    public function basket(Request $request): View
    {
        $user = $request->user();
        $basket_purchases = $user->basket_purchases();
        $paid_purchases = $user->paid_purchases();
        return view('shop.basket', [
            'user' => $user,
            'basket_purchases' => $basket_purchases->get(),
            'paid_purchases' => $paid_purchases->get(),
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

    public function store(Request $request): RedirectResponse
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
            'user_id' => ['required', 'integer'],
            'book_id' => ['required', 'integer'],
        ]);
        $shop_row = Shop::where('user_id', $request->user_id)->where('book_id', $request->book_id)->where('status', Shop::STATUS_BASKET)->get()->first();

        if (!$shop_row) {
            Shop::create([
                'user_id' => $request->user_id,
                'book_id' => $request->book_id,
            ]);
        }

        return redirect(route($redirect_to, $parameters, absolute: false));
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
        ]);
        $shop_row = Shop::findOrFail($request->id);
        $update_data = [];

        if ($shop_row->status == Shop::STATUS_BASKET) {
            $update_data['status'] = Shop::STATUS_PAID;
            $update_data['paid_at'] = today()->format('Y-m-d');
        }
        if ($shop_row->status == Shop::STATUS_PAID) {
            $update_data['status'] = Shop::STATUS_BUY;
            $update_data['buy_at'] = today()->format('Y-m-d');
        }

        $shop_row->update($update_data);

        return redirect(route($redirect_to, $parameters, absolute: false));
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
        $shop_row = Shop::find($request->id);

        if (!!$shop_row) {
            $shop_row->delete();
        }

        return redirect(route($redirect_to, $parameters, absolute: false));
    }

}
