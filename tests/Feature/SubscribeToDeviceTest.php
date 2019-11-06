<?php

namespace Tests\Feature;

use App\Device;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscribeToDeviceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function guests_cannot_subscribe_device()
    {
        $this->withExceptionHandling();

        $device = factory(Device::class)->create();

        $this->postJson(route('subscriptions.store', ['device' => $device]))
            ->assertStatus(401);
    }

    /**
     * @test
     */
    public function authorised_user_can_ask_for_device_subscription()
    {
        $this->withExceptionHandling();

        $johnDoe = factory(User::class)->create();

        $deviceRelatedToJohnDoe = factory(Device::class)->create(['user_id' => $johnDoe->id]);

        $this->actingAs($johnDoe)->post(route('subscriptions.store', ['device' => $deviceRelatedToJohnDoe]));

        $this->assertDatabaseHas('device_subscriptions', [
          'user_id' => $johnDoe->id,
          'device_id' => $deviceRelatedToJohnDoe->id,
          'requested_at' => now(),
        ]);
    }

    /**
     * @test
     */
    public function user_may_not_subscribe_others_devices()
    {
        $this->withExceptionHandling();

        $johnDoe = factory(User::class)->create();

        $deviceRelatedToJohnDoe = factory(Device::class)->create(['user_id' => $johnDoe->id]);

        $deviceNotRelatedToJohnDoe = factory(Device::class)->create();

        $this->actingAs($johnDoe)
            ->post(route('subscriptions.store', ['device' => $deviceNotRelatedToJohnDoe]))
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function device_can_be_subscribed_unless_not_reissued()
    {
        $this->withExceptionHandling();

        $johnDoe = factory(User::class)->create();

        $device = factory(Device::class)->create([
            'user_id' => $johnDoe->id,
        ]);

        $this->actingAs($johnDoe)->post(route('subscriptions.store', ['device' => $device]));

        $this->assertDatabaseHas('device_subscriptions', [
            'user_id' => $johnDoe->id,
            'device_id' => $device->id,
            'requested_at' => now(),
        ]);

        $this->actingAs($johnDoe)->post(route('subscriptions.store', ['device' => $device]))
            ->assertStatus(403);
    }


}
