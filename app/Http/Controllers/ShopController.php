<?php

namespace App\Http\Controllers;

use App\Http\Requests\CanDeliverRequest;
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
        Shop::create($request->validated());

        return redirect('/');
    }


    public function canDeliver(CanDeliverRequest $request)
    {
        $validated = $request->normalisePostcode($request);

        $stores = $this->canDeliverService->getCanDeliver($validated['postcode']);

        return view('index', compact('stores'));
    }
}
