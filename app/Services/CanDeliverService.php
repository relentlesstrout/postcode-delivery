<?php

namespace App\Services;

use App\Models\Shop;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class CanDeliverService
{
    public function __construct(
        private PostcodeCoordinatesService $postcodeCoordinatesService,
    ) {}
    public function getCanDeliver(string $postcode): \Illuminate\Support\Collection
    {
        $userCoordinates = $this->postcodeCoordinatesService->getCoordinates($postcode);
        $userLatitude = $userCoordinates['latitude'];
        $userLongitude = $userCoordinates['longitude'];

        $degrees = 20 / 111;

        $subquery = Shop::query()
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
    ", [$userLatitude, $userLongitude, $userLatitude]);

        return DB::table(DB::raw("({$subquery->toSql()}) as sub"))
            ->mergeBindings($subquery->getQuery())
            ->where('distance', '<=', DB::raw('max_delivery_distance'))
            ->where('is_open', true)
            ->orderBy('distance')
            ->get();
    }
}
