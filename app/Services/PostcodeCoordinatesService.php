<?php

namespace App\Services;

use App\Models\Postcode;

class PostcodeCoordinatesService
{
    public function getCoordinates(string $postcode): array
    {
        $postcode = Postcode::where('postcode', $postcode)->first();
        return [
            'latitude' => $postcode->latitude,
            'longitude' => $postcode->longitude,
        ];
    }
}
