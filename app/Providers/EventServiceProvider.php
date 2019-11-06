<?php

namespace App\Providers;

use App\Events\DeviceWasRequested;
use App\Events\SubscriptionWasGranted;
use App\Listeners\NotifySubscriber;
use App\Listeners\SendNotificationEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // Device Subscription Event
        DeviceWasRequested::class => [
            SendNotificationEmail::class,
        ],

        // SubscriptionWasGranted

        SubscriptionWasGranted::class => [
            NotifySubscriber::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot()
    {
        parent::boot();
    }
}
