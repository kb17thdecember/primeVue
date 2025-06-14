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
     * @param int $productId
     * @param array $requestData
     * @return bool
     */
    public function store(int $productId, array $requestData): bool
    {
        try {
            DB::beginTransaction();

            $product = $this->productRepository->handle(new Request())->findOrFail($productId);

            $currentUser = auth()->user();

            if (ProductType::ONE_TIME->is($product->type)) {
                $resultPayment = $this->handleOneTimePayment($currentUser, $product, $requestData);

            } else {
                $resultPayment = $this->handleSubscribePayment($currentUser, $product, $requestData);
            }

            $resultPaymentStatus = $resultPayment['result'];
            $paymentResponse = $resultPayment['response'];

            $this->subscriberHistoryRepository->create([
                'payment_data' => [
                    'request' => $requestData,
                    'response' => $paymentResponse,
                ],
                'shop_id' => $currentUser->shop_id,
                'product_id' => $productId,
                'price' => $product->price,
                'type' => $product->type->value,
                'payment_status' => $resultPaymentStatus ? PaymentStatus::SUCCESS->value : PaymentStatus::FAILED->value,
            ]);

            DB::commit();

            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error(__METHOD__ . " error:" . $exception->getMessage());
            Log::error($exception);

            return false;
        }
    }

    /**
     * @param $user
     * @param Product $product
     * @param $requestData
     * @return array
     */
    private function handleOneTimePayment($user, Product $product, $requestData): array
    {
        try {
            $paymentMethod = $requestData['payment_method'];

            $user->addPaymentMethod($paymentMethod);
            $result = $user->charge($product->price, $paymentMethod);

            return [
                'result' => true,
                'response' => $result
            ];
        } catch (\Exception $exception) {
            Log::error(__METHOD__ . " error:" . $exception->getMessage());
            Log::error($exception);

            return [
                'result' => false,
                'response' => $exception->getMessage()
            ];
        }
    }

    /**
     * @param $user
     * @param Product $product
     * @param $requestData
     * @return array
     */
    private function handleSubscribePayment($user, Product $product, $requestData): array
    {
        try {
            $paymentMethod = $requestData['payment_method'];

            $user->addPaymentMethod($paymentMethod);
            $result = $user->charge($product->price, $paymentMethod);

            return [
                'result' => true,
                'response' => $result
            ];
        } catch (\Exception $exception) {
            Log::error(__METHOD__ . " error:" . $exception->getMessage());
            Log::error($exception);

            return [
                'result' => false,
                'response' => $exception->getMessage()
            ];
        }
    }
}
