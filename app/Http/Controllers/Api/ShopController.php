<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CanDeliverRequest;
use App\Http\Requests\NearbyRequest;
use App\Http\Resources\ShopResource;
use App\Models\Shop;
use App\Actions\NearbyStoresAction;
use App\Actions\CanDeliverAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;


class ShopController extends Controller
{
    public function __construct(
        private NearbyStoresAction $nearbyStoresAction,
        private CanDeliverAction   $canDeliverAction
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
