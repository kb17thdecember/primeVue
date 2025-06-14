<?php

namespace Modules\CMS\Services;

use App\Enums\PaymentStatus;
use App\Enums\ProductType;
use App\Models\Admin;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\CMS\Contracts\Repositories\ProductRepository;
use Modules\CMS\Contracts\Repositories\SubscriberHistoryRepository;
use Modules\CMS\Contracts\Services\SubscriberHistoryService;
use Stripe\SetupIntent;
use Stripe\Stripe;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class SubscriberHistoryServiceImpl implements SubscriberHistoryService
{
    public function __construct(
        private SubscriberHistoryRepository $subscriberHistoryRepository,
        private ProductRepository $productRepository,
    ) {}

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        $condition = new Request(['sort' => '-id']);

        return $this->subscriberHistoryRepository->handle($condition)->get();
    }

    /**
     * @return array
     */
    public function stripeSetupIntent(): array
    {
        try {
            $currentUser = auth()->user();

            Stripe::setApiKey(env('STRIPE_SECRET'));

            $intent = SetupIntent::create([
                'customer' => $currentUser->createOrGetStripeCustomer()->id,
                'payment_method_types' => ['card']
            ]);

            return [
                'result' => true,
                'client_secret' => $intent->client_secret
            ];
        } catch (\Exception $exception) {
            Log::error(__METHOD__ . " error:" . $exception->getMessage());
            Log::error($exception);

            return [
                'result' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * @param string $sessionId
     * @return bool
     */
    public function handleStripePaymentSuccess(string $sessionId): bool
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            if (!$session) {
                Log::error(__METHOD__ . " payment session not found: session_id => $sessionId");

                return false;
            }

            $request = new Request([
                'payment_session_id' => $session->id,
            ]);

            $subscriberHistory = $this->subscriberHistoryRepository->handle($request)->first();

            if (!$subscriberHistory) {
                Log::error(__METHOD__ . " Not found subscribe history has session_id => $sessionId");

                return false;
            }


            if ($subscriberHistory->payment_status === PaymentStatus::PENDING) {
                $subscriberHistory->payment_status = PaymentStatus::SUCCESS->value;

                $subscriberHistory->save();
            }

            return true;
        } catch (\Exception $e) {
            Log::error(__METHOD__ . " error:" . $e->getMessage());
            Log::error($e);

            return false;
        }
    }

    /**
     * @param string $sessionId
     * @return bool
     */
    public function handleStripePaymentCancel(string $sessionId): bool
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            if (!$session) {
                Log::error(__METHOD__ . " payment session not found: session_id => $sessionId");

                return false;
            }

            $request = new Request([
                'payment_session_id' => $session->id,
            ]);

            $subscriberHistory = $this->subscriberHistoryRepository->handle($request)->first();

            if (!$subscriberHistory) {
                Log::error(__METHOD__ . " Not found subscribe history has session_id => $sessionId");

                return false;
            }


            if ($subscriberHistory->payment_status === PaymentStatus::PENDING) {
                $subscriberHistory->payment_status = PaymentStatus::CANCEL->value;

                $subscriberHistory->save();
            }

            return true;
        } catch (\Exception $e) {
            Log::error(__METHOD__ . " error:" . $e->getMessage());
            Log::error($e);

            return false;
        }
    }

    /**
     * @param int $productId
     * @return array
     */
    public function store(int $productId): array
    {
        try {
            DB::beginTransaction();

            $product = $this->productRepository->handle(new Request())->findOrFail($productId);

            $currentUser = auth()->user();

            if (ProductType::ONE_TIME->is($product->type)) {
                $resultPayment = $this->handleOneTimePayment($currentUser, $product);

            } else {
                $resultPayment = $this->handleSubscribePayment($currentUser, $product);
            }

            if (!$resultPayment['result']) {
                DB::rollBack();

                return $resultPayment;
            }

            DB::commit();

            return [
                "result" => true,
                "stripeUrl" => $resultPayment['stripeUrl']
            ];
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error(__METHOD__ . " error:" . $exception->getMessage());
            Log::error($exception);

            return [
                "result" => false,
                "message" => $exception->getMessage()
            ];
        }
    }

    /**
     * @param $user
     * @param Product $product
     * @return array
     */
    private function handleOneTimePayment($user, Product $product): array
    {
        try {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $price =  (int)($product->price * 100);
            $lineItems = [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $product->name,
                        ],
                        'unit_amount' => $price,
                    ],
                    'quantity' => 1,
                ]
            ];

            $checkout_session = $stripe->checkout->sessions->create([
                'line_items' =>  $lineItems,
                'mode' => 'payment',
                'success_url' => route('orders.stripePaymentSuccess') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('orders.stripePaymentCancel') . '?session_id={CHECKOUT_SESSION_ID}',
            ]);

            $this->subscriberHistoryRepository->create([
                'payment_session_id' => $checkout_session->id,
                'payment_data' => '',
                'shop_id' => $user->shop_id,
                'product_id' => $product->id,
                'price' => $price,
                'type' => $product->type->value,
                'payment_status' => PaymentStatus::PENDING->value,
            ]);

            return [
                "result" => true,
                "stripeUrl" => $checkout_session->url
            ];
        } catch (\Exception $exception) {
            Log::error(__METHOD__ . " error:" . $exception->getMessage());
            Log::error($exception);

            return [
                "result" => false,
                "message" => $exception->getMessage()
            ];
        }
    }

    /**
     * @param $user
     * @param Product $product
     * @param $requestData
     * @return bool
     */
    private function handleSubscribePayment($user, Product $product): bool
    {
        try {
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }
}
