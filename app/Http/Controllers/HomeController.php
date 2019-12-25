<?php

namespace App\Http\Controllers;

use App\Attendance;
use Illuminate\Auth\Access\Gate;

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

        return view('home', [
            'subscriptions' => auth()->user()->subscriptions,
        ]);
    }
}
