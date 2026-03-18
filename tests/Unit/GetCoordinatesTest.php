<?php

namespace Tests\Unit;

use App\Models\Postcode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\PostcodeCoordinatesService;
use PHPUnit\Framework\TestCase;

class GetCoordinatesTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_user_postcode_returns_coordinates(): void
    {
        $userPostcode = 'SW1A 2AA';

        $seededProcessedPostcode = Postcode::factory()->create([
            'postcode' => 'SW1A 2AA',
            'latitude' => 51.507351,
            'longitude' => -0.127758,
        ]);

        $postcodeCoordinatesService = new PostcodeCoordinatesService();
        $coordinates = $postcodeCoordinatesService->getCoordinates($userPostcode);

        $this->assertEquals(
            [
                'latitude' => 51.507351,
                'longitude' => -0.127758,
            ],
            [
                'latitude' => $coordinates['latitude'],
                'longitude' => $coordinates['longitude'],
            ]
        );

    }
}
