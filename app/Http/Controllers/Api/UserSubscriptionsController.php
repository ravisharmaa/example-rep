<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Subscription;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class UserSubscriptionsController extends Controller
{
    /**
     * @param string $email
     * @return ResponseFactory|Response
     */
    public function index($email)
    {
        if (request()->has('deleted')) {
            $subscriptions = Subscription::whereHas('attendances', function ($query) use ($email) {
                $query->whereNotNull('out_time');
            })->whereHas('user', function ($q) use ($email) {
                $q->whereEmail($email);
            })->whereNotNull('approved_at')->get();
        } else {
            $subscriptions = Subscription::with(['user' => function ($query) use ($email) {
                return $query->where('email', $email);
            }])->whereNotNull('approved_at')->get();
        }

        return response([
            'subscriptions' => $subscriptions,
        ], 200);
    }
}
