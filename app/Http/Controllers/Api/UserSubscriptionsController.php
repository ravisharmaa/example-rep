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
     *
     * @return ResponseFactory|Response
     */
    public function index($email)
    {
        $subscriptions = (request()->has('attended')) ?
            Subscription::attendedSubscriptionsFor($email) :
            Subscription::approvedSubscriptionsFor($email);

        return response([
            'subscriptions' => $subscriptions->get(),
        ], 200);
    }
}
