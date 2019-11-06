<?php

namespace Tests\Unit;

use App\Device;
use App\Events\DeviceWasRequested;
use App\Mail\RequestForwarded;
use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class DeviceTest extends TestCase
{
    use RefreshDatabase;


    /**
     * @test
     */
    public function it_belongs_to_user()
    {
        $device = factory(Device::class)->create();

        $this->assertInstanceOf(BelongsTo::class, $device->user());
    }

    /**
     * @test
     */
    public function it_has_many_subscriptions()
    {
        $device = factory(Device::class)->create();

        $this->assertInstanceOf(HasMany::class, $device->subscriptions());
    }

    /**
     * @test
     */
    public function it_can_be_subscribed()
    {
        $user = factory(User::class)->create();

        $device = factory(Device::class)->create([
            'user_id' => $user->id,
        ]);

        $device->subscribe($user);

        $this->assertSame(1, $device->subscriptions()->count());
    }

    /**
     * @test
     */
    public function it_notifies_the_concerned_on_subscription()
    {
        Event::fake();

        $user = factory(User::class)->create();

        $this->actingAs($user);

        $device = factory(Device::class)->create([
            'user_id' => $user->id,
        ]);

        $device->subscribe($user)->notify();

        Mail::fake();

        Mail::assertNothingSent();

        Event::assertDispatched(DeviceWasRequested::class, function ($event) use ($device) {
           return $event->deviceSubscription->subscription_id === $device->subscriptions()->latest()->first()->subscription_id;
        });

        $deviceSubscription = $device->subscriptions()->latest()->first();

        Mail::assertSent(RequestForwarded::class, function ($mail) use ($user) {
           dd($mail);
        });

    }

}
