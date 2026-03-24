<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Services\CanDeliverService;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function __construct(
        private CanDeliverService $canDeliverService
    ) {}


    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $stores = Shop::all();
        return view('index', compact('stores'));
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('create');
    }

    public function store(Request $request): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'is_open' => 'required|boolean',
            'type' => 'required|in:takeaway,shop,restaurant',
            'max_delivery_distance' => 'required|numeric',
        ]);

        Shop::create([
            'name' => $validated['name'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'is_open' => $validated['is_open'],
            'type' => $validated['type'],
            'max_delivery_distance' => $validated['max_delivery_distance'],
    ]);
        return redirect('/');
    }


    public function canDeliver(Request $request)
    {
        $validated = $request->validate([
            'postcode' => ['required', 'string', 'regex:/^[A-Z]{1,2}[0-9][0-9A-Z]?\s*[0-9][A-Z]{2}$/i'],
        ]);

        //Remove spaces from postcode
        $validated['postcode'] = str_replace(' ', '', $validated['postcode']);

        $stores = $this->canDeliverService->getCanDeliver($validated['postcode']);

        return view('index', compact('stores'));
    }
}
