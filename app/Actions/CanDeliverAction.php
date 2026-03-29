<?php

namespace App\Actions;

use App\Models\Shop;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class CanDeliverAction
{
    public function __construct(
        private PostcodeCoordinatesAction $postcodeCoordinatesAction,
    ) {}
    private const int KM_PER_DEGREE = 111;

    public function execute(string $postcode): \Illuminate\Support\Collection
    {
        $userCoordinates = $this->postcodeCoordinatesAction->execute($postcode);
        $userLatitude = $userCoordinates->latitude;
        $userLongitude = $userCoordinates->longitude;

        $degrees = config('default_delivery_radius_km') / self::KM_PER_DEGREE;

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
