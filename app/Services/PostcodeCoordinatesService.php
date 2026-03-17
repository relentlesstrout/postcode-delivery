<?php

namespace App\Services;

use App\Models\Postcode;

class PostcodeCoodinatesService
{
    public function getCoodinates(string $postcode): array
    {
        $postcode = Postcode::where('postcode', $postcode)->first();
        return [
            'latitude' => $postcode->latitude,
            'longitude' => $postcode->longitude,
        ];
    }
}
