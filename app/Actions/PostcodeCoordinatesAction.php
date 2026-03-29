<?php

namespace App\Actions;

use App\DTOs\Coordinates;
use App\Models\Postcode;

class PostcodeCoordinatesAction
{
    public function execute(string $postcode): Coordinates
    {
        $postcode = Postcode::where('postcode', $postcode)->first();
        return new Coordinates(
            latitude: $postcode->latitude,
            longitude: $postcode->longitude
        );
    }
}
