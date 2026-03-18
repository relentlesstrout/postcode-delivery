<?php

namespace Tests\Unit;

use App\Models\Shop;
use App\Services\CanDeliverService;
use App\Services\PostcodeCoordinatesService;
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

        $postcodeCoordinatesService = $this->createMock(PostcodeCoordinatesService::class);
        $postcodeCoordinatesService
            ->method('getCoordinates')
            ->willReturn([
                'latitude' => 57.14414,
                'longitude' => -2.114871,
        ]);

        $canDeliverService = new CanDeliverService($postcodeCoordinatesService);

        $canDeliverShop = $canDeliverService->getCanDeliver($userPostcode);

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

        $postcodeCoordinatesService = $this->createMock(PostcodeCoordinatesService::class);
        $postcodeCoordinatesService
            ->method('getCoordinates')
            ->willReturn([
                'latitude' => 59.891572,
                'longitude' => -1.313847,
            ]);

        $canDeliverService = new CanDeliverService($postcodeCoordinatesService);

        $canDeliverShop = $canDeliverService->getCanDeliver($userPostcode);

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
