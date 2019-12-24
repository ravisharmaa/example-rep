<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Subscription;
use App\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class UserSubscriptionsController extends Controller
{
    /**
     * @return ResponseFactory|Response
     */
    public function index($email)
    {
        if (!request()->has('deleted')) {
            $subscriptions = Subscription::with(['user' => function ($query) use ($email) {
                return $query->where('email', $email);
            }])->whereNotNull('approved_at')->get();
        } else {
            $subscriptions = Subscription::with(['user' => function ($query) use ($email) {
                $query->with(['attendances' => function ($attendanceQuery) {
                    $attendanceQuery->whereNotNull('out_time')->whereNull('deleted_at');
                }])->where('email', $email)->first();
            }])->whereNotNull('approved_at')->get();
        }

        $subscriptions = $subscriptions->each(function ($sub) {
            $sub->attendances->reject(function ($attendance) use ($sub) {
                if (!is_null($attendance->out_time)) {
                    $attendance->forget($attendance);
                }
            });
        });

        return response([
            'subscriptions' => $subscriptions,
        ], 200);
//        $devices = [];
//        $user = User::where("email", $email)->first();
//        $subs = $user->subscriptions;
//        foreach ($subs as $device) {
//            if($device->attendances()->latest()->first()) {
//                if(!$device->attendances()->latest()->first()->out_time) {
//                    array_push($devices, $device);
//                }
//            }else {
//                array_push($devices, $device);
//            }
//        }
    }
}
