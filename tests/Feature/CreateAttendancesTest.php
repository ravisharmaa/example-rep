<?php

namespace Tests\Feature;

use App\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateAttendancesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function guards_can_view_attendances_form()
    {
        $this->withoutExceptionHandling();

        $response = $this->get(route('attendances.create'));

        $response->assertViewIs('attendances.create');
    }

    /**
     * @test
     */
    public function guests_can_add_attendances()
    {
        $this->withoutExceptionHandling();

        $subscription = factory(Subscription::class)->create();


        $this->post(route('attendances.create'), [
            'item_name' => $subscription->item_name,
            'email' => $subscription->user->email,
        ]);

        $this->assertDatabaseHas('attendances', [
           'subscription_id' => $subscription->id,
           'user_id' => $subscription->user->id,
        ]);
    }
}
