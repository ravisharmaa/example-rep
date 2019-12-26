<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $subscriptions = null;

        if (Gate::allows('can-create-departments')) {
            $subscriptions = auth()->user()->subscriptions;
        }

        return view('home', [
            'subscriptions' => $subscriptions,
        ]);
    }
}
