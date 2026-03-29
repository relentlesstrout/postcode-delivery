<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CanDeliverRequest;
use App\Http\Requests\NearbyRequest;
use App\Models\Shop;
use App\Services\NearbyStoresService;
use App\Services\CanDeliverService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class ShopController extends Controller
{
    public function __construct(
        private NearbyStoresService $nearbyStoresService,
        private CanDeliverService $canDeliverService
    ) {}

    public function nearby(Request $request): JsonResponse
    {
        $validated = $request->normalisePostcode($request);

        $shops = $this->nearbyStoresService->getNearbyStores(
            $validated['postcode'],
            $validated['radius_km'],
        );

        return response()->json($shops);
    }

    public function canDeliver(NearbyRequest $request)
    {
        $validated = $request->normalisePostcode($request);

        $shops = $this->canDeliverService->getCanDeliver($validated['postcode']);

        return response()->json($shops);
    }
}
