<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CanDeliverRequest;
use App\Http\Requests\NearbyRequest;
use App\Models\Shop;
use App\Actions\NearbyStoresAction;
use App\Actions\CanDeliverAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class ShopController extends Controller
{
    public function __construct(
        private NearbyStoresAction $nearbyStoresAction,
        private CanDeliverAction   $canDeliverAction
    ) {}

    public function nearby(Request $request): JsonResponse
    {
        $validated = $request->normalisePostcode($request);

        $shops = $this->nearbyStoresAction->execute(
            $validated['postcode'],
            $validated['radius_km'],
        );

        return response()->json($shops);
    }

    public function canDeliver(NearbyRequest $request)
    {
        $validated = $request->normalisePostcode($request);

        $shops = $this->canDeliverAction->execute($validated['postcode']);

        return response()->json($shops);
    }
}
