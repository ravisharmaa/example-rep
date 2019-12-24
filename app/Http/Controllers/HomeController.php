<?php

namespace App\Http\Controllers;

use App\Attendance;

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
        //$attendances = Attendance::withTrashed()->get();

        return view('home', [
            'subscriptions' => auth()->user()->subscriptions,
        ]);


        //return view('home', compact('attendances'));
    }
}
