<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        $validated = $request->validate([
            'postcode' => ['required', 'string', 'regex:/^[A-Z]{1,2}[0-9][0-9A-Z]?\s*[0-9][A-Z]{2}$/i'],
            'radius_km' => 'sometimes|numeric|min:0|max:30',
        ]);

        $shops = $this->nearbyStoresService->getNearbyStores(
            $validated['postcode'],
            $validated['radius_km'],
        );
        return response()->json($shops);
    }

    public function canDeliver(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'postcode' => ['required', 'string', 'regex:/^[A-Z]{1,2}[0-9][0-9A-Z]?\s*[0-9][A-Z]{2}$/i'],
        ]);

        $shops = $this->canDeliverService->getCanDeliver($validated['postcode']);
        return response()->json($shops);
    }
}
