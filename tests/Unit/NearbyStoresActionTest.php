<?php

namespace Tests\Unit;

use App\Actions\NearbyStoresAction;
use App\Actions\PostcodeCoordinatesAction;
use App\DTOs\Coordinates;
use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class NearbyStoresActionTest extends TestCase
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

        $postcodeCoordinatesAction = $this->createMock(PostcodeCoordinatesAction::class);
        $postcodeCoordinatesAction
            ->method('execute')
            ->willReturn(new Coordinates(
                latitude: 57.14414,
                longitude: -2.114871,
            ));

        $nearbyStoresAction = new NearbyStoresAction($postcodeCoordinatesAction);
        $shop = $nearbyStoresAction->execute($userPostcode, $radiusKm);

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

        $postcodeCoordinatesAction = $this->createMock(PostcodeCoordinatesAction::class);
        $postcodeCoordinatesAction
            ->method('execute')
            ->willReturn(new Coordinates(
                latitude: 57.14414,
                longitude: -1.313847
            ));

        $nearbyStoresAction = new NearbyStoresAction($postcodeCoordinatesAction);
        $shops = $nearbyStoresAction->execute($userPostcode, $radiusKm);

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
