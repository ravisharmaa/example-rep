<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Subscription;
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
//        $subscription = Subscription::whereIn('item_name', request('item_name'))->get();
        $subscription = Subscription::whereHas('user', function ($query) {
            $query->whereEmail(request('email'));
        })->whereIn('item_name', request('item_name'))->get();

        abort_if($subscription->isEmpty(), 403, 'Sorry you do not have any subscriptions');

        $subscription->each(function ($sub) {
            $sub->user->attendances()->create([
                'subscription_id' => $sub->id,
                'out_time' => now(),
            ]);
        });

        return response('You have successfully exited with your device.', 200);
    }

    /**
     * @return ResponseFactory|Response
     *
     * @throws \Exception
     */
    public function update()
    {
        //$subscription = Subscription::whereIn('item_name', request('item_name'))->get();

        $subscription = Subscription::whereHas('user', function ($query) {
            $query->whereEmail(request('email'));
        })->whereIn('item_name', request('item_name'))->get();

        abort_if($subscription->isEmpty(), 403, 'You are unauthorised to complete this');

        $subscription->each(function ($sub) {
            $sub->user->attendances()->first()->delete();
        });

        return response('You have successfully entered with your device.', 200);
    }
}
