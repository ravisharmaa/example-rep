<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('attendances/{email}', 'AttendancesController@index')->name('attendances.index');
Route::get('user/{email}/subscriptions', 'Api\\UserSubscriptionsController@index')->name('user.subscriptions.index');
Route::get('daily-records', 'Api\\SubscriptionAttendancesController@index')->name('daily-records.index');
