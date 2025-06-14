<?php

namespace Modules\CMS\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\CMS\Contracts\Services\SubscriberHistoryService;
use Modules\CMS\Http\Requests\Order\StoreOrderRequest;

class OrderController extends Controller
{
    public function __construct(protected SubscriberHistoryService $subscriberHistoryService)
    {

    }
    /**
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('orders/Index');
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
    public function stripePaymentIntent(StoreOrderRequest $request)
    {
        $productId = $request->get('productId');

        $resultStore = $this->subscriberHistoryService->store($productId, $request->all());

        if (!$resultStore) {
            return redirect()->back()->with('error', 'Thanh toán thất bại');
        }

        return redirect()->back()->with('success', 'Thanh toán thành công');
    }

    /**
     * @param Request $request
     * @return void
     */
    public function stripePaymentSuccess(Request $request)
    {
        dd($request->all());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function stripeSetupIntent(Request $request): JsonResponse
    {
        $resultStripeSetupIntent = $this->subscriberHistoryService->stripeSetupIntent();

        if (!$resultStripeSetupIntent['result']) {
            return response()->json([
                'message' => "Server internal error! Please try again later",
            ], 500);
        }

        return response()->json([
            'client_secret' => $resultStripeSetupIntent['client_secret'],
        ], 200);
    }

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
