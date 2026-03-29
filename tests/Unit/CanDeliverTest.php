<?php

namespace Tests\Unit;

use App\Models\Shop;
use App\Actions\CanDeliverAction;
use App\Actions\PostcodeCoordinatesAction;
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
            ->willReturn([
                'latitude' => 57.14414,
                'longitude' => -2.114871,
        ]);

        $canDeliverAction = new CanDeliverAction($postcodeCoordinatesAction);

        $canDeliverShop = $canDeliverAction->execute($userPostcode);

        $this->assertTrue($canDeliverShop->contains(function ($shop) {
            return $shop->name === 'Test Shop'
                && $shop->latitude == 57.14414
                && $shop->longitude == -2.114871
                && $shop->is_open == true
                && $shop->type === 'takeaway'
                && $shop->max_delivery_distance == 10;
        }));
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
            ->willReturn([
                'latitude' => 59.891572,
                'longitude' => -1.313847,
            ]);

        $canDeliverAction = new CanDeliverAction($postcodeCoordinatesAction);

        $canDeliverShop = $canDeliverAction->execute($userPostcode);

        $this->assertFalse($canDeliverShop->contains(function ($shop) {
            return $shop->name === 'Test Shop'
                && $shop->latitude == 57.14414
                && $shop->longitude == -2.114871
                && $shop->is_open == true
                && $shop->type === 'takeaway'
                && $shop->max_delivery_distance == 10;
        }));
    }
}
