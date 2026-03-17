<?php

namespace App\Services;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Collection;

class CanDeliverService
{
    public function __construct(
        private PostcodeCoordinatesService $postcodeCoordinatesService,
    ) {}
    public function getCanDeliver(string $postcode): Collection
    {
        $userCoordinates = $this->postcodeCoordinatesService->getCoordinates($postcode);
        $userLatitude = $userCoordinates['latitude'];
        $userLongitude = $userCoordinates['longitude'];

        $degrees = 20 / 111;

        return Shop::query()
            ->whereBetween('latitude', [$userLatitude - $degrees, $userLatitude + $degrees])
            ->whereBetween('longitude', [$userLongitude - $degrees, $userLongitude + $degrees])
            ->selectRaw("
           *,
            (6371 * acos(
                cos(radians(?))
                * cos(radians(latitude))
                * cos(radians(longitude) - radians(?))
                + sin(radians(?))
                * sin(radians(latitude))
            )) AS distance
        ", [$userLatitude, $userLongitude, $userLatitude])
            ->having('distance', '<=', 'max_delivery_distance')
            ->orderBy('distance')
            ->get();
    }
}
