<?php

namespace App\Actions;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;


class NearbyStoresAction
{
    public function __construct(
        private PostcodeCoordinatesAction $postcodeCoordinatesAction,
    ) {}

    public function execute(string $postcode, float $radius_km): \Illuminate\Support\Collection
    {
        $userCoordinates = $this->postcodeCoordinatesAction->execute($postcode);
        $userLatitude = $userCoordinates['latitude'];
        $userLongitude = $userCoordinates['longitude'];

        $degrees = $radius_km / 111;

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
            ->orderBy('distance')
            ->get();
    }
}
