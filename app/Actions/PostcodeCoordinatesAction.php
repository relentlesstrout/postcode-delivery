<?php

namespace App\Actions;

use App\Models\Postcode;

class PostcodeCoordinatesAction
{
    public function execute(string $postcode): array
    {
        $postcode = Postcode::where('postcode', $postcode)->first();
        return [
            'latitude' => $postcode->latitude,
            'longitude' => $postcode->longitude,
        ];
    }
}
