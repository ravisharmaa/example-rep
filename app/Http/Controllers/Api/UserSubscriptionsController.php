<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Subscription;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class UserSubscriptionsController extends Controller
{
    /**
     * @return ResponseFactory|Response
     */
    public function index()
    {
        $subscriptions = Subscription::with(['user' => function ($query) {
            return $query->where('email', \request('email'));
        }])->whereNotNull('approved_at')->get();

        return response([
            'subscriptions' => $subscriptions
        ], 200);
    }
}
