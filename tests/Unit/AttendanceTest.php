<?php

namespace Tests\Unit;

use App\Attendance;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_has_a_user()
    {
        $this->markTestSkipped();

        $attendance = factory(Attendance::class)->create();

        $this->assertInstanceOf(BelongsTo::class, $attendance->user());
    }
}
