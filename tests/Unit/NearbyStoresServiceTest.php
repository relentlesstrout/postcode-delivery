<?php

namespace Tests\Unit;

use App\Models\Shop;
use App\Services\PostcodeCoordinatesService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\NearbyStoresService;


class NearbyStoresServiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_nearby_stores_service_returns_nearby_stores(): void
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
        $radiusKm = 2;

        $postcodeCoordinatesService = $this->createMock(PostcodeCoordinatesService::class);
        $postcodeCoordinatesService
            ->method('getCoordinates')
            ->willReturn([
                'latitude' => 57.14414,
                'longitude' => -2.114871,
            ]);

        $nearbyStoresService = new NearbyStoresService($postcodeCoordinatesService);
        $shops = $nearbyStoresService->getNearbyStores($userPostcode, $radiusKm);

        $this->assertTrue($shops->contains(function ($shop) {
            return $shop->name === 'Test Shop'
                && $shop->latitude == 57.14414
                && $shop->longitude == -2.114871
                && $shop->is_open == true
                && $shop->type === 'takeaway'
                && $shop->max_delivery_distance == 10;
        }));
    }

    public function test_nearby_stores_service_returns_empty_collection_if_no_shops_found(): void
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
        $radiusKm = 2;

        $postcodeCoordinatesService = $this->createMock(PostcodeCoordinatesService::class);
        $postcodeCoordinatesService
            ->method('getCoordinates')
            ->willReturn([
                'latitude' => 59.891572,
                'longitude' => -1.313847,
            ]);

        $nearbyStoresService = new NearbyStoresService($postcodeCoordinatesService);
        $shops = $nearbyStoresService->getNearbyStores($userPostcode, $radiusKm);

        $this->assertFalse($shops->contains(function ($shop) {
            return $shop->name === 'Test Shop'
                && $shop->latitude == 57.14414
                && $shop->longitude == -2.114871
                && $shop->is_open == true
                && $shop->type === 'takeaway'
                && $shop->max_delivery_distance == 10;
        }));

    }

}
