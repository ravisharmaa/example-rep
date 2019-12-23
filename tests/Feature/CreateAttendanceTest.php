<?php

namespace Tests\Feature;

use App\Attendance;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateAttendanceTest extends TestCase
{
    use RefreshDatabase;

    protected $devicesReceivedFromApi = [
        'item_name' => [
            'Item 1',
            'Item 2',
            'Item3',
            ],
        'email' => 'random@user.com',
    ];

    /**
     * @test
     */

    public function guest_can_get_items_for_today()
    {
        $this->withoutExceptionHandling();

        factory(Attendance::class)->create([
            'in_time' => Carbon::today()->toDateTimeString()
        ]);

        $this->get(route('attendances.index'));

    }
    /**
     * @test
     */
    public function guests_can_view_attendances_form()
    {
        $this->withoutExceptionHandling();

        $response = $this->get(route('attendances.create'));

        $response->assertViewIs('attendances.create');
    }

    /**
     * @test
     */
    public function guests_can_check_in_with_devices_received_from_api()
    {
        $this->withoutExceptionHandling();

        $this->post(route('attendances.create'), $this->devicesReceivedFromApi);

        $this->assertDatabaseHas('attendances', [
           'item_name' => $this->devicesReceivedFromApi['item_name'][0],
           'email' => $this->devicesReceivedFromApi['email'],
           'in_time' => now(),
        ]);
    }

    /**
     * @test
     */
    public function guest_can_check_out_with_the_devices_received_from_api()
    {
        $this->withoutExceptionHandling();

        $attendance = factory(Attendance::class)->create([
            'item_name' => 'Item 1',
            'email' => 'random@user.com',
            'in_time' => now(),
        ]);

        $this->patch(route('attendances.update'), $this->devicesReceivedFromApi);

        $this->assertNotNull($attendance->fresh()->out_time);
    }

    /**
     * @test
     */

    public function guests_can_not_checkout_with_un_attended_devices()
    {
        $this->withoutExceptionHandling();

        $this->patch(route('attendances.update'), $this->devicesReceivedFromApi)
        ->assertStatus(422);
    }
}
