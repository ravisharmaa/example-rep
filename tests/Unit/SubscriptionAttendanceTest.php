<?php

namespace Tests\Unit;

use App\SubscriptionAttendance;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionAttendanceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_has_a_user()
    {
        $attendance = factory(SubscriptionAttendance::class)->create();

        $this->assertInstanceOf(BelongsTo::class, $attendance->user());
    }
}
