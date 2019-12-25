<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Subscription;
use App\User;
use Illuminate\Support\Facades\DB;

class SubscriptionAttendancesController extends Controller
{
    public function index()
    {
        $records = DB::table('subscription_attendances as sa')
                    ->leftJoin('subscriptions as sub', 'sa.subscription_id', '=', 'sub.id')
                        ->whereNotNull('sub.approved_at')
                    ->leftJoin('users as u', 'sa.user_id', '=', 'u.id')
                    ->select('sa.id', 'sa.user_id', 'sa.subscription_id', 'sa.deleted_at', 'sa.out_time', 'u.email', 'sub.item_name')
                    ->get();


        return response(['dailyRecords' => $records], 200);
    }
}
