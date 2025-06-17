<?php

namespace Modules\CMS\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Modules\CMS\Contracts\Services\SubscriberHistoryService;
use Modules\CMS\Http\Requests\Order\StoreOrderRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        $resultStore = $this->subscriberHistoryService->store($productId);

        if (!$resultStore['result']) {
            return redirect()->back()->with('toast_error', $resultStore['message']);
        }

        return Inertia::location($resultStore['stripeUrl']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function stripePaymentSuccess(Request $request)
    {
        $sessionId = $request->get('session_id');
        try {
            $handleSuccessPaymentSession = $this->subscriberHistoryService->handleStripePaymentSuccess($sessionId);

            if (!$handleSuccessPaymentSession) {
                return redirect()->route('pricing.index')->with('toast_error', 'Handle payment session success failed');
            }

            return redirect()->route('pricing.index')->with('toast_success', 'Payment success!');
        } catch (\Exception $e) {
            Log::error(__METHOD__ . " error:" . $e->getMessage());
            Log::error($e);

            return redirect()->route('pricing.index')->with('toast_error', $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function stripePaymentCancel(Request $request)
    {
        $sessionId = $request->get('session_id');
        try {
            $handleSuccessPaymentSession = $this->subscriberHistoryService->handleStripePaymentCancel($sessionId);

            if (!$handleSuccessPaymentSession) {
                return redirect()->route('pricing.index')->with('toast_error', 'Handle payment session cancel was failed');
            }

            return redirect()->route('pricing.index')->with('toast_warn', 'Payment is cancelled!');
        } catch (\Exception $e) {
            Log::error(__METHOD__ . " error:" . $e->getMessage());
            Log::error($e);

            return redirect()->route('pricing.index')->with('toast_error', $e->getMessage());
        }
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
