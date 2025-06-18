<?php

namespace Modules\CMS\Services;

use App\Enums\PaymentStatus;
use App\Enums\ProductType;
use App\Enums\Role;
use App\Enums\TokenStockStatus;
use App\Models\CurrentSubscription;
use App\Models\Product;
use App\Models\SubscriberHistory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\CMS\Contracts\Repositories\CurrentSubscriptionRepository;
use Modules\CMS\Contracts\Repositories\ProductRepository;
use Modules\CMS\Contracts\Repositories\ShopTokenStockRepository;
use Modules\CMS\Contracts\Repositories\SubscriberHistoryRepository;
use Modules\CMS\Contracts\Services\SubscriberHistoryService;
use Stripe\SetupIntent;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\StripeClient;

readonly class SubscriberHistoryServiceImpl implements SubscriberHistoryService
{
    public function __construct(
        private SubscriberHistoryRepository $subscriberHistoryRepository,
        private ProductRepository           $productRepository,
        private ShopTokenStockRepository    $shopTokenStockRepository,
        private CurrentSubscriptionRepository   $currentSubscriptionRepository,
    )
    {
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        $currentUser = Auth::user();

        return $this->subscriberHistoryRepository
            ->handle()
            ->when(Role::SHOP->is($currentUser->role), fn($q) => $q->where('shop_id', $currentUser->shop_id))
            ->orderByDesc('created_at')
            ->get();
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
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $currentUser = auth()->user();

            if (Role::ADMIN->is($currentUser->role)) {
                Log::error("Admin can not subscribe product");

                return false;
            }

            $session = Session::retrieve($sessionId);

            if (!$session) {
                Log::error(__METHOD__ . " payment session not found: session_id => $sessionId");

                return false;
            }

            $subscriberHistory = $this->subscriberHistoryRepository->handle()
                ->where('payment_session_id', $session->id)
                ->with(['product', 'shop'])
                ->first();

            if (!$subscriberHistory) {
                Log::error(__METHOD__ . " Not found subscribe history has session_id => $sessionId");

                return false;
            }

            if ($subscriberHistory->payment_status === PaymentStatus::PENDING) {
                $subscriberHistory->payment_data = $session->toArray();
                $subscriberHistory->payment_status = PaymentStatus::SUCCESS->value;
                $subscriberHistory->save();

                $this->handleTokenStockAfterSuccessPayment($subscriberHistory, $currentUser);
            }

            return true;
        } catch (\Exception $e) {
            Log::error(__METHOD__ . " error:" . $e->getMessage());
            Log::error($e);

            return false;
        }
    }

    /**
     * @param $subscriberHistory
     * @param $currentUser
     * @return bool
     */
    protected function handleTokenStockAfterSuccessPayment($subscriberHistory, $currentUser): bool
    {
        try {
            DB::beginTransaction();
            $product = $subscriberHistory->product;

            $availableEndDate = match ($product->type) {
                ProductType::ONE_TIME->value => now()->addDays($product->day_available)->format('Y-m-d H:i:s'),
                ProductType::WEEKLY => now()->addDays(7)->format('Y-m-d H:i:s'),
                ProductType::MONTHLY => now()->addMonth()->format('Y-m-d H:i:s'),
                ProductType::YEARLY => now()->addYear()->format('Y-m-d H:i:s'),
                ProductType::EVERY_THREE_MONTH => now()->addMonths(3)->format('Y-m-d H:i:s'),
                ProductType::EVERY_SIX_MONTH => now()->addMonths(6)->format('Y-m-d H:i:s'),
                default => null
            };

            $this->shopTokenStockRepository->create([
                'shop_id' => $subscriberHistory->shop_id,
                'product_id' => $product->id,
                'token_qty' => $product->token_qty,
                'available_start_date' => now()->format('Y-m-d H:i:s'),
                'available_end_date' => $availableEndDate,
                'status' => TokenStockStatus::ADDED
            ]);

            $currentUser->shop->currentSubscriotion?->delete();

            $this->currentSubscriptionRepository->create([
                'shop_id' => $subscriberHistory->shop_id,
                'admin_id' => $currentUser->id,
                'product_id' => $product->id,
            ]);

            $shop = $subscriberHistory->shop;
            if (!isset($shop)) {
                DB::rollBack();
            }
            $shop->token_expired_date = $availableEndDate;
            $shop->current_token_qty += $product->token_qty;
            $shop->save();

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
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
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = Session::retrieve($sessionId);

            if (!$session) {
                Log::error(__METHOD__ . " payment session not found: session_id => $sessionId");

                return false;
            }

            $subscriberHistory = $this->subscriberHistoryRepository->handle()
                ->where('payment_session_id', $session->id)
                ->with(['product', 'shop'])
                ->first();

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
     * @return CurrentSubscription|null
     */
    public function getCurrentSubscriber(): ?CurrentSubscription
    {
        try {
            $currentUser = Auth::user();
            $currentUser->load('shop.currentSubscription.product');

            return $currentUser->shop->currentSubscription;
        } catch (\Exception $e) {
            Log::error(__METHOD__ . " error:" . $e->getMessage());
            Log::error($e);

            return null;
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
                $resultPayment = $this->handleOnetimePayment($currentUser, $product);
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
    private function handleOnetimePayment($user, Product $product): array
    {
        try {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $lineItems = [
                [
                    'price' => $product->stripe_price_id,
                    'quantity' => 1,
                ]
            ];

            $newSubscriberHistory = $this->subscriberHistoryRepository->create([
                'payment_session_id' => null,
                'payment_price_id' => $product->stripe_price_id,
                'payment_data' => '',
                'shop_id' => $user->shop_id,
                'product_id' => $product->id,
                'price' => $product->price,
                'type' => $product->type->value,
                'payment_status' => PaymentStatus::PENDING->value,
            ]);

            $checkoutSession = $stripe->checkout->sessions->create([
                'line_items' => $lineItems,
                'mode' => 'payment',
                'metadata' => [
                    'subscriber_history_id' => $newSubscriberHistory->id,
                    'admin_id' => $user->id
                ],
                'success_url' => route('orders.stripePaymentSuccess') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('orders.stripePaymentCancel') . '?session_id={CHECKOUT_SESSION_ID}',
            ]);

            $newSubscriberHistory->payment_session_id = $checkoutSession->id;
            $newSubscriberHistory->save();

            return [
                "result" => true,
                "stripeUrl" => $checkoutSession->url
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
     * @return array
     */
    private function handleSubscribePayment($user, Product $product): array
    {
        try {
            $priceId = $product->stripe_price_id;

            $newSubscriberHistory = $this->subscriberHistoryRepository->create([
                'payment_session_id' => null,
                'payment_price_id' => $priceId,
                'payment_data' => '',
                'shop_id' => $user->shop_id,
                'product_id' => $product->id,
                'price' => $product->price,
                'type' => $product->type->value,
                'payment_status' => PaymentStatus::PENDING->value,
            ]);

            $stripeSession = $user->newSubscription(env('STRIPE_SUBSCRIPTION_NAME', "default"), $priceId)
                ->checkout([
                    'success_url' => route('orders.stripePaymentSuccess') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('orders.stripePaymentCancel') . '?session_id={CHECKOUT_SESSION_ID}',
                ]);

            $newSubscriberHistory->payment_session_id = $stripeSession->id;
            $newSubscriberHistory->save();

            return [
                'result' => true,
                'stripeUrl' => $stripeSession->url
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
}
