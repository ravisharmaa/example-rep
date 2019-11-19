<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function guest_can_register_himself()
    {
        $user = factory(User::class)->make();

        $this->post('/login', $user->toArray())->assertRedirect('/');

        $this->assertDatabaseHas('users', $user->toArray());
    }
}
