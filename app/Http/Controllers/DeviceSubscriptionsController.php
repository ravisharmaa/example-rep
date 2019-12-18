<?php

namespace App\Http\Controllers;

use App\Device;
use App\DeviceSubscription;
use App\Events\SubscriptionWasGranted;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class DeviceSubscriptionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['edit', 'update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function store(Device $device)
    {
        abort_if(
            Gate::denies('subscribe-to-device', $device),
            403,
            'Sorry! We are unable to complete this request.'
        );

        abort_if(
            $device->subscriptions()->exists(),
            403,
            'Sorry ! you are already subscribed to this device.'
        );

        $device->subscribe()->notify();

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return void
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Factory|View
     */
    public function edit(DeviceSubscription $deviceSubscription)
    {
        return view('subscriptions.edit', compact('deviceSubscription'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return RedirectResponse|Redirector
     */
    public function update(DeviceSubscription $deviceSubscription)
    {
        $deviceSubscription->complete();

        event(new SubscriptionWasGranted($deviceSubscription->device, request('approved_by')));

        return redirect('/home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return RedirectResponse
     */
    public function destroy(DeviceSubscription $deviceSubscription)
    {
        $deviceSubscription->revoke();

        return back();
    }
}
