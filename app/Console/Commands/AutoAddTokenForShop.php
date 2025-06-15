<?php

namespace App\Console\Commands;

use App\Enums\ProductType;
use App\Enums\TokenStockStatus;
use App\Mail\ReminderMail;
use App\Models\Setting;
use App\Models\ShopTokenStocks;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AutoAddTokenForShop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-add-token-for-shop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $shopTokenStocks = ShopTokenStocks::query()
                ->with(['shop.admin'])
                ->where('available_end_date', '<', now()->format('Y-m-d H:i:s'))
                ->where('status', TokenStockStatus::NO_ADDED)
                ->get();

            foreach ($shopTokenStocks as $shopTokenStock) {
                $this->handleShopTokenStock($shopTokenStock);
            }

            return true;
        } catch (\Exception $exception) {
            Log::error("AUTO-ADD-TOKEN-FOR-SHOP: Error " . $exception->getMessage());
            Log::error($exception);

            return;
        }
    }

    /**
     * @param $shopTokenStock
     * @return void
     */
    protected function handleShopTokenStock($shopTokenStock): void
    {
        try {
            DB::beginTransaction();

            $shop = $shopTokenStock->shop;

            if (!isset($shop)) {
                return;
            }

            $admin = $shop->admin;

            $admin->current_token_qty += $shopTokenStock->token_qty;
            $admin->token_expired_date += $shopTokenStock->available_end_date;
            $admin->save();

            $shopTokenStock->status = TokenStockStatus::ADDED;
            $shopTokenStock->save();

            Log::info("AUTO-ADD-TOKEN-FOR-SHOP: id: " . $shopTokenStock->id);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            Log::error("AUTO-ADD-TOKEN-FOR-SHOP: Error " . $exception->getMessage());
            Log::error($exception);

            return;
        }
    }
}
