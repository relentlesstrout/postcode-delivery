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
        $shops = Shop::all()
            ->sortBy('is_open, desc');

        return view('index', compact('shops'));
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
            'type' => 'required|in:takeaway, shop, restaurant',
            'max_delivery_distance' => 'required|numeric',
        ]);

        return redirect('/');
    }


    public function canDeliver(Request $request)
    {
        $validated = $request->validate([
            'postcode' => ['required', 'string', 'regex:/^[A-Z]{1,2}[0-9][0-9A-Z]?\s*[0-9][A-Z]{2}$/i'],
        ]);

        $shops = $this->canDeliverService->getCanDeliver($validated['postcode']);

        return view('index', compact('shops'));
    }
}
