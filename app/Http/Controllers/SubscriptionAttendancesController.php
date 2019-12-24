<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Subscription;
use App\SubscriptionAttendance;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Response;
use Illuminate\View\View;

class SubscriptionAttendancesController extends Controller
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
        $subscription = Subscription::whereIn('item_name', request('item_name'))->get();

        abort_if(is_null($subscription), 403, 'You are un-authorized complete this. Please ask for subscription');

        $subscription->each(function ($sub) {
            $sub->user->attendances()->create([
                'subscription_id' => $sub->id,
                'out_time' => now(),
            ]);
        });

        return response('success', 200);
    }

    /**
     * @return ResponseFactory|Response
     *
     * @throws \Exception
     */
    public function update()
    {
        SubscriptionAttendance::with(['user' => function ($query) {
            $query->where('email', request('email'))->first()
                ->with(['subscriptions' => function ($query) {
                    $query->whereIn('item_name', request('item_name'))->get();
                }]);
        }])->first()->delete();

        return response('success', 200);
    }
}
