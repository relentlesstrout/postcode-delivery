<?php

namespace App\Http\Controllers;

use App\Actions\CanDeliverAction;
use App\Http\Requests\CanDeliverRequest;
use App\Http\Requests\StoreShopRequest;
use App\Models\Shop;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ShopController extends Controller
{
    public function __construct(
        private CanDeliverAction $canDeliverAction
    ) {}

    public function index(): Factory|View
    {
        $stores = Shop::all();

        return view('index', compact('stores'));
    }

    public function create(): Factory|View
    {
        return view('create');
    }

    public function store(StoreShopRequest $request)
    {
        Shop::create($request->validated());

        return redirect('/');
    }

    public function canDeliver(CanDeliverRequest $request)
    {
        $validated = $request->normalisePostcode();

        $stores = $this->canDeliverAction->execute($validated);

        return view('index', compact('stores'));
    }
}
