<?php

namespace Tests\Unit;

use App\Device;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeviceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_belongs_to_user()
    {
        $device = factory(Device::class)->create();

        $this->assertInstanceOf(BelongsTo::class, $device->user());
    }

    /**
     * @test
     */
    public function it_has_many_subscriptions()
    {
        $device = factory(Device::class)->create();

        $this->assertInstanceOf(HasMany::class, $device->subscriptions());
    }
}
