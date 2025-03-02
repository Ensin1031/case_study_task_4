<?php

namespace App\Http\Controllers;

use App\Models\Rent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RentController extends Controller
{

    public function active(Request $request): View
    {
        $user = $request->user();
        $active_rents = $user->active_rents();
        $overdue_rents = $user->overdue_rents();
        return view('rent.active', [
            'user' => $request->user(),
            'active_rents' => $active_rents->get(),
            'overdue_rents' => $overdue_rents->get(),
            'statuses' => Rent::ACTIVE_STATUSES,
        ]);
    }

    public function archive(Request $request): View
    {
        $user = $request->user();
        $rents = $user->archive_rents();
        return view('rent.archive', [
            'user' => $request->user(),
            'rents' => $rents->get(),
            'statuses' => Rent::ARCHIVE_STATUSES,
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
            'rent_period_days' => ['required', 'integer'],
        ]);

        $shop_row = Rent::where('user_id', $request->user_id)->where('book_id', $request->book_id)->where('was_closed', 0)->get()->first();
        $end_at = today()->modify('+'.$request->rent_period_days.' days');

        if (!$shop_row) {
            Rent::create([
                'user_id' => $request->user_id,
                'book_id' => $request->book_id,
                'rent_period_days' => $request->rent_period_days,
                'end_at' => $end_at->format('Y-m-d'),
                'was_closed' => false,
            ]);
        }

        return redirect(route($redirect_to, $parameters, absolute: false));
    }

    public function extend_update(Request $request): RedirectResponse
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
            'rent_period_days' => ['required', 'integer'],
        ]);

        $rent = Rent::findOrFail($request->id);
        $end_at = today()->modify('+'.$request->rent_period_days.' days');

        $update_data = [
            'rent_period_days' => $request->rent_period_days,
            'end_at' => $end_at->format('Y-m-d'),
            'was_closed' => false,
        ];

        $rent->update($update_data);

        return redirect(route($redirect_to, $parameters, absolute: false));
    }

    public function close_update(Request $request): RedirectResponse
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
        $rent = Rent::findOrFail($request->id);
        $update_data = [
            'was_closed' => true,
        ];

        $rent->update($update_data);

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
        $rent_row = Rent::find($request->id);

        if (!!$rent_row) {
            $rent_row->delete();
        }

        return redirect(route($redirect_to, $parameters, absolute: false));
    }

}
