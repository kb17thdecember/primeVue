<?php

namespace Modules\CMS\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\CMS\Contracts\Services\ProductService;

class PricingController extends Controller
{
    public function __construct(protected ProductService $productService)
    {

    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        $pricingCollection = $this->productService->getAllPricing();
        $stipeKey = env('STRIPE_KEY');

        return Inertia::render('pricing/Index', [
            'pricingCollection' => $pricingCollection,
            'stripeKey' => $stipeKey
        ]);
    }

    public function allType()
    {
        return Inertia::render('orders/Index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cms::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('cms::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('cms::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
