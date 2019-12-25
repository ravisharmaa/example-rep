<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DailyRecordsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *
     * @test
     */
    public function admin_can_view_daily_records()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(route('daily-records.index'));

        $this->markAsRisky();
    }
}
