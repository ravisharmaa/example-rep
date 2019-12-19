<?php

namespace App\Http\Controllers;

use App\Subscription;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class AttendancesController extends Controller
{
    /**
     * @return Factory|View
     */
    public function create()
    {
        return view('attendances.create');
    }

    /**
     * Stores a new attendance.
     */
    public function store()
    {
        $subscription = Subscription::where('item_name', request('item_name'))->first();

        $subscription->user->attendances()->create([
            'subscription_id' => $subscription->id,
            'updated_at' => null,
        ]);
    }
}
