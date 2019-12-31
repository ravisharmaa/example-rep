<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
/*
 * Create Subscriptions from external Api
 */
Route::get('subscriptions', 'SubscriptionsController@index')->name('items.subscriptions.index');
Route::post('subscriptions/create', 'SubscriptionsController@store')->name('items.subscriptions.store');
Route::get('subscriptions/{subscription}/edit', 'SubscriptionsController@update')->name('items.subscriptions.update');
Route::post('subscriptions/{subscription}/delete', 'SubscriptionsController@destroy')->name('items.subscriptions.destroy');

/*
 * RejectSubscriptions
 */
Route::get('subscriptions/{subscription}/reject', 'RejectedSubscriptionsController@update')->name('items.subscriptions.reject');

/*
 * Create Device Subscription from internal database
 */
Route::post('subscription/{device}/create', 'DeviceSubscriptionsController@store')->name('subscriptions.store');
Route::get('subscription/{deviceSubscription}/edit', 'DeviceSubscriptionsController@edit')->name('subscriptions.edit');
Route::post('subscription/{deviceSubscription}/update', 'DeviceSubscriptionsController@update')->name('subscriptions.update');
Route::delete('subscription/{deviceSubscription}/delete', 'DeviceSubscriptionsController@destroy')->name('subscriptions.destroy');

/*
 * Departments Route
 */
Route::post('departments/create', 'DepartmentsController@store')->name('departments.store');

/*
 * Attendances for Subscriptions
 */

Route::get('subscription/attendances', 'SubscriptionAttendancesController@create')->name('subscriptions.attendances.create');
Route::post('subscription/attendances', 'SubscriptionAttendancesController@store')->name('subscriptions.attendances.create');
Route::patch('subscription/attendances', 'SubscriptionAttendancesController@update')->name('subscriptions.attendances.update');

/*
 * Daily Attendances
 */
Route::get('attendances', 'AttendancesController@create')->name('attendances.create');
Route::post('attendances', 'AttendancesController@store')->name('attendances.create');
Route::patch('attendances', 'AttendancesController@delete')->name('attendances.update');
