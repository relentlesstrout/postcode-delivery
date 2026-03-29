<?php

namespace App\Http\Controllers\Api;

use App\Actions\CanDeliverAction;
use App\Actions\NearbyStoresAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CanDeliverRequest;
use App\Http\Requests\NearbyRequest;
use App\Http\Resources\ShopResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ShopController extends Controller
{
    public function __construct(
        private NearbyStoresAction $nearbyStoresAction,
        private CanDeliverAction $canDeliverAction
    ) {}

    public function nearby(NearbyRequest $request): ResourceCollection
    {
        $validated = $request->validated();

        $shops = $this->nearbyStoresAction->execute(
            $request->normalisePostcode(),
            $validated['radius_km'],
        );

        return ShopResource::collection($shops);
    }

    public function canDeliver(CanDeliverRequest $request): ResourceCollection
    {
        $validated = $request->normalisePostcode($request);

        $shops = $this->canDeliverAction->execute($validated);

        return ShopResource::collection($shops);
    }
}
