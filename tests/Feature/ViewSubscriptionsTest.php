<?php

namespace Tests\Feature;

use App\Subscription;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewSubscriptionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function guest_can_not_view_subscriptions()
    {
        $this->withExceptionHandling();

        $this->get(route('items.subscriptions.index'))->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function random_user_cannot_view_all_subscriptions()
    {
        $this->withExceptionHandling();

        $user = factory(User::class)->create([
           'email' => 'random.user@javra.com',
        ]);

        $this->actingAs($user)->get(route('items.subscriptions.index'))->assertStatus(403);
    }

    /**
     * @test
     */
    public function super_user_can_view_all_previous_subscriptions()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            'email' => 'infra@javra.com',
        ]);

        factory(Subscription::class)->create([
           'requested_at' => now(),
           'approved_at' => now(),
            'approved_by' => 'random@user.com',
        ]);

        $this->actingAs($user)->get(route('items.subscriptions.index'))
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_can_view_his_subscriptions()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        factory(Subscription::class, 2)->create([
            'user_id' => $user->id,
             'approved_at' => now()
        ]);

        $response = $this->get(route('user.subscriptions.index', ['email' => $user->email]));

        $this->assertCount(2, $response['subscriptions']);
    }
}
