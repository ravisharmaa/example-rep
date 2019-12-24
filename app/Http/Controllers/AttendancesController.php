<?php

namespace App\Http\Controllers;

use App\Attendance;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

    public function store()
    {
        foreach (request('item_name') as $item) {
            Attendance::create([
                'item_name' => $item,
                'email' => \request('email'),
                'in_time' => now(),
            ]);
        }

        return response('You have successfully entered the devices', 200);
    }

    /**
     * Stores the data.
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function delete()
    {
        foreach (\request('item_name') as $item) {
            $attendance = Attendance::where('item_name', $item)->first();

            if (is_null($attendance)) {
                throw new ModelNotFoundException();
            }

            try {
                $attendance->delete();
            } catch (ModelNotFoundException $e) {
                return response('Could not complete your request fully, please check again', 422);
            }
        }

        return response('You have success fully entered the devices', 200);
    }
}
