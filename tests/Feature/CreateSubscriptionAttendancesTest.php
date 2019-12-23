<?php

namespace Tests\Feature;

use App\Subscription;
use App\SubscriptionAttendance;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateSubscriptionAttendancesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function guests_can_view_attendances_form()
    {
        $this->withoutExceptionHandling();

        $response = $this->get(route('subscriptions.attendances.create'));

        $response->assertViewIs('attendances.create');
    }

    /**
     * @test
     */
    public function guests_can_check_in_with_subscription()
    {
        $this->withoutExceptionHandling();

        $subscription = factory(Subscription::class)->create();

        $this->post(route('subscriptions.attendances.create'), [
            'item_name' => $subscription->item_name,
            'email' => $subscription->user->email,
        ]);

        $this->assertDatabaseHas('subscription_attendances', [
           'subscription_id' => $subscription->id,
           'user_id' => $subscription->user->id,
           'in_time' => now(),
        ]);

        $this->assertNotNull($subscription->user->attendances->first->in_time);
    }

    /**
     * @test
     */
    public function guests_can_not_check_in_with_un_subscribed_devices()
    {
        $this->withExceptionHandling();

        $user = factory(User::class)->create();

        $subscription = factory(Subscription::class)->make();

        $this->post(route('subscriptions.attendances.create'), [
           'item_name' => $subscription->item_name,
           'email' => $user->email,
        ])->assertStatus(403);
    }

    /**
     * @test
     */
    public function guest_can_check_out_with_subscription()
    {
        $this->withExceptionHandling();

        $subscriptionOfMobile = factory(Subscription::class)->create();

        $subscriptionOfLaptop = factory(Subscription::class)->create();

        factory(SubscriptionAttendance::class)->create([
            'user_id' => $subscriptionOfMobile->user->id,
            'subscription_id' => $subscriptionOfMobile->id,
            'in_time' => now(),
        ]);

        $this->patch(route('subscriptions.attendances.update'), [
           'item_name' => $subscriptionOfMobile->item_name,
           'email' => $subscriptionOfMobile->user->email,
        ]);

        $this->assertNotNull($subscriptionOfMobile->user->attendances->fresh()->first->out_time);

        $this->assertNull($subscriptionOfLaptop->user->attendances->fresh()->first->out_time);
    }
}
