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
Route::post('subscription/{device}/create', 'DeviceSubscriptionsController@store')->name('subscriptions.store');
Route::get('subscription/{deviceSubscription}/edit', 'DeviceSubscriptionsController@edit')->name('subscriptions.edit');
Route::post('subscription/{deviceSubscription}/update', 'DeviceSubscriptionsController@update')->name('subscriptions.update');
Route::delete('subscription/{deviceSubscription}/delete', 'DeviceSubscriptionsController@destroy')->name('subscriptions.destroy');
