<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Subscription;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Response;
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

        abort_if(is_null($subscription), 403, 'You are un-authorized complete this. Please ask for subscription');

        $subscription->user->attendances()->create([
            'subscription_id' => $subscription->id,
            'in_time' => now(),
        ]);

        return response('success', 200);
    }

    /**
     * @return ResponseFactory|Response
     */
    public function update()
    {
        $subscription = Subscription::where('item_name', request('item_name'))->first();

        abort_if(is_null($subscription), 403, 'You are un-authorized complete this.');

        Attendance::with(['user' => function ($query) {
            $query->where('email', request('email'))->first()
                ->with(['subscriptions' => function ($query) {
                    $query->where('item_name', request('item_name'))->first();
                }]);
        }])->latest()->first()->update([
            'out_time' => now(),
        ]);

        return response('success', 200);
    }
}
