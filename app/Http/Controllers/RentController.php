<?php

namespace App\Http\Controllers;

use App\Models\Rent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RentController extends Controller
{

    public function active(Request $request): View
    {
        $user = $request->user();
        $rents = $user->active_rents();
        return view('rent.active', [
            'user' => $request->user(),
            'rents' => $rents->get(),
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

}
