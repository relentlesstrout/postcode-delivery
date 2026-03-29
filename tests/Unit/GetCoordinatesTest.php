<?php

namespace Tests\Unit;

use App\Models\Postcode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\PostcodeCoordinatesAction;
use Tests\TestCase;

class GetCoordinatesTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_user_postcode_returns_coordinates(): void
    {
        $userPostcode = 'SW1A 2AA';

        Postcode::factory()->create([
            'postcode' => 'SW1A 2AA',
            'latitude' => 51.507351,
            'longitude' => -0.127758,
        ]);

        $postcodeCoordinatesAction = new PostcodeCoordinatesAction();
        $coordinates = $postcodeCoordinatesAction->execute($userPostcode);

        $this->assertEquals(
            [
                'latitude' => 51.507351,
                'longitude' => -0.127758,
            ],
            [
                'latitude' => $coordinates->latitude,
                'longitude' => $coordinates->longitude,
            ]
        );

    }
}
