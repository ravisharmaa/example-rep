<?php

namespace App\Http\Controllers;

use App\Subscription;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class SubscriptionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['update']);
    }

    public function index()
    {
        // check the permission
        abort_if(Gate::denies('view-subscriptions'), 403);

        // fetch subscriptions
        $subscriptions = Subscription::with('user')->get();

        return view('item-subscriptions.index', compact('subscriptions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        // check if  the subscription exists
        abort_if(auth()->user()->subscriptions()->where('item_id', \request('item_id'))->exists(), 401);

        // announce the notification
        auth()->user()->subscribe()->announce();

        return response('Your request has been queued for mail, Thanks for the patience', 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return void
     */
    public function update(Subscription $subscription)
    {
        $subscription->approve()->inform();

        return view('subscriptions.approved');
    }

    /**
     * @param Subscription $subscription
     * @return ResponseFactory|Response
     */
    public function destroy(Subscription $subscription)
    {

        if ($subscription->returned_at) {
            return \response('You have already returned the device', 200);
        }

        $subscription->revoke()->announce();

        return response('You have successfully returned the device', 200);
    }
}
