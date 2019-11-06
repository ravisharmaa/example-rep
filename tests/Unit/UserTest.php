<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_has_many_devices()
    {
        $user = factory(User::class)->create();

        $this->assertInstanceOf(HasMany::class, $user->devices());
    }
}
