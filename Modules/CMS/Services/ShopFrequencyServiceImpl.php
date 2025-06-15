<?php

namespace Modules\CMS\Services;

use App\Enums\PaymentStatus;
use App\Enums\Role;
use App\Models\Order;
use App\Models\SubscriberHistory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\CMS\Contracts\Repositories\ShopFrequencyRepository;
use Modules\CMS\Contracts\Repositories\ShopRepository;
use Modules\CMS\Contracts\Services\ShopFrequencyService;

class ShopFrequencyServiceImpl implements ShopFrequencyService
{
    public function __construct(
        private readonly ShopFrequencyRepository $shopFrequencyRepository,
        private readonly ShopRepository $shopRepository
    ) {}

    /**
     * @return Collection
     */
    public function getShopFrequency(): Collection
    {
        $currentUser = Auth::user();

        return $this->shopFrequencyRepository
            ->handle()
            ->when(Role::SHOP->is($currentUser->role), fn ($q) => $q->where('shop_id', $currentUser->shop_id))
            ->get();
    }

    /**
     * @return array
     */
    public function getDataDashboardReport(): array
    {
        try {
            $result = [];

            $currentUser = Auth::user();

            $orders = SubscriberHistory::query()
                ->when(Role::SHOP->is($currentUser->role), fn ($q) => $q->where('shop_id', $currentUser->shop_id))
                ->where('payment_status', PaymentStatus::SUCCESS->value)
                ->get();

            $dateStartMonth = now()->startOfMonth()->format('Y-m-d');

            $orderInCurrentMonth = $orders->filter(function (SubscriberHistory $order) use ($dateStartMonth) {
                return $order->created_at->format('Y-m-d') >= $dateStartMonth;
            });

            $shopFrequency = $this->shopFrequencyRepository
                ->handle()
                ->when(Role::SHOP->is($currentUser->role), fn ($q) => $q->where('shop_id', $currentUser->shop_id))
                ->get();

            return [
                'order_qty' => count($orders),
                'order_qty_current_month' => count($orderInCurrentMonth),
                'revenue' => $orders->sum('price'),
                'revenue_current_month' => $orderInCurrentMonth->sum('price'),
                'token_available' => $currentUser->shop?->current_token_qty,
                'token_used' => $shopFrequency->sum('daily_count'),
            ];
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            Log::error($exception);

            return [];
        }
    }
}
