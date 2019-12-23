<?php

namespace App\Http\Controllers;

use App\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Response;

class AttendancesController extends Controller
{
    public function index($email)
    {
        return Attendance::distinct()->select('item_name')
            ->where('email', $email)->get();
    }

    /**
     * Shows the form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('attendances.create');
    }

    /**
     * Stores the data.
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function store()
    {
        foreach (\request('item_name') as $item) {
            Attendance::where('item_name', $item)->first()->delete();
        }

        return response('You have success fully entered the device', 200);
    }

    /**
     * Updates the attendances record.
     *
     * @return void
     */
    public function update()
    {
        foreach (request('item_name') as $item) {
            Attendance::create([
                'item_name' => $item,
                'email' => \request('email'),
                'out_time' => Carbon::today()->toDateString(),
            ]);
        }

        return response('You have success fully entered the device', 200);
    }
}
