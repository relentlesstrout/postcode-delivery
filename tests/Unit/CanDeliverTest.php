<?php

namespace Tests\Unit;

use App\Actions\CanDeliverAction;
use App\Actions\PostcodeCoordinatesAction;
use App\DTOs\Coordinates;
use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CanDeliverTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     */
    public function test_a_postcode_within_max_deliverable_distance_can_be_delivered(): void
    {
        Shop::factory()->create([
            'name' => 'Test Shop',
            'latitude' => 57.14414,
            'longitude' => -2.114871,
            'is_open' => true,
            'type' => 'takeaway',
            'max_delivery_distance' => 10.0,
        ]);

        $userPostcode = 'AB101XG';

        $postcodeCoordinatesAction = $this->createMock(PostcodeCoordinatesAction::class);
        $postcodeCoordinatesAction
            ->method('execute')
            ->willReturn(new Coordinates(
                latitude: 57.14414,
                longitude: -2.114871,
            ));

        $canDeliverAction = new CanDeliverAction($postcodeCoordinatesAction);

        $shop = $canDeliverAction->execute($userPostcode);

        $this->assertCount(1, $shop);

        $shop = $shop->first();

        /** @var Shop $shop */
        $this->assertSame('Test Shop', $shop->name);
        $this->assertSame(57.14414, $shop->latitude);
        $this->assertSame(-2.114871, $shop->longitude);
        $this->assertTrue((bool)$shop->is_open);
        $this->assertSame('takeaway', $shop->type);
        $this->assertSame(10.0, $shop->max_delivery_distance);
    }

    public function test_a_postcode_outside_max_deliverable_distance_cannot_be_delivered(): void
    {
        Shop::factory()->create([
            'name' => 'Test Shop',
            'latitude' => 57.14414,
            'longitude' => -2.114871,
            'is_open' => true,
            'type' => 'takeaway',
            'max_delivery_distance' => 10.0,
        ]);

        $userPostcode = 'ZE39JY';

        $postcodeCoordinatesAction = $this->createMock(PostcodeCoordinatesAction::class);
        $postcodeCoordinatesAction
            ->method('execute')
            ->willReturn(new Coordinates(
                latitude: 59.89157,
                longitude: -1.31385,
            ));

        $canDeliverAction = new CanDeliverAction($postcodeCoordinatesAction);

        $canDeliverShop = $canDeliverAction->execute($userPostcode);

        $this->assertCount(0, $canDeliverShop);
    }
}
