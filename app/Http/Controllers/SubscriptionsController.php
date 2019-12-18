<?php

namespace App\Http\Controllers;

use App\Subscription;
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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        // check if  the subscription exists
        abort_if(auth()->user()->subscriptions()->where('item_id', \request('item_id'))->exists(), 401);

        // announce the notification
        auth()->user()->subscribe()->announce();

        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @return void
     */
    public function update(Subscription $subscription)
    {
        $subscription->approve()->inform();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return void
     */
    public function destroy(Subscription $subscription)
    {
        return $subscription->revoke()->announce();
    }
}
