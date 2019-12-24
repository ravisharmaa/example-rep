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
            'Item 3',
            ],
        'email' => 'random@user.com',
    ];


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
        ]);
    }

    /**
     * @test
     */
    public function guest_can_check_out_with_the_devices_received_from_api()
    {
        $this->withExceptionHandling();

        $attendance = factory(Attendance::class)->create([
            'item_name' => 'Item 1',
            'email' => 'random@user.com'
        ]);

        $this->patch(route('attendances.update'), [
            'item_name' => $this->devicesReceivedFromApi['item_name'],
            'email' => $this->devicesReceivedFromApi['email']
        ]);

        $this->assertNotNull($attendance->fresh()->deleted_at);
    }

    /**
     * @test
     */

    public function guests_can_not_checkout_with_un_attended_devices()
    {
        $this->withExceptionHandling();

        $this->patch(route('attendances.update'), $this->devicesReceivedFromApi)
        ->assertStatus(404);
    }
}
