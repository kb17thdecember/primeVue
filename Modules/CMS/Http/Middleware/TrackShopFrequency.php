<?php

namespace Modules\CMS\Http\Middleware;

use App\Enums\ShopStatus;
use App\Mail\ReminderMail;
use App\Models\Setting;
use App\Models\Shop;
use Closure;
use Illuminate\Http\Request;
use App\Models\ShopFrequency;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TrackShopFrequency
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-Shop-Api-Key');

        if (!$apiKey) {
            return response()->json(['error' => 'API key missing'], 400);
        }

        $shop = Shop::where('api_key', $apiKey)->first();

        if (!$shop || ShopStatus::INACTIVE->is($shop->status)) {
            return response()->json(['error' => 'API key not correct'], 403);
        }


        if ($shop->token_expired_date < now()->format('Y-m-d')) {
            return response()->json(['error' => 'API_key is not token enough to use'], 400);
        }

        $this->handleCalculateTokenQty($shop);

        return $next($request);
    }

    protected function handleCalculateTokenQty($shop)
    {
        try {
            DB::beginTransaction();
            $shop->current_token_qty --;
            $shop->save();

            $setting = Setting::first();

            if ($setting->remaining == $shop->current_token_qty) {
                Mail::to($shop->admin->email)->send(new ReminderMail($setting));
            }

            $date = Carbon::today()->toDateString();

            $frequency = ShopFrequency::where('api_key', $shop->api_key)
                ->where('date', $date)
                ->first();

            if ($frequency) {
                $frequency->increment('daily_count');
            } else {
                ShopFrequency::create([
                    'shop_id' => $shop->id,
                    'api_key' => $shop->api_key,
                    'date' => $date,
                    'daily_count' => 1
                ]);
            }

            DB::commit();
            return;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            Log::error($exception);

            return;
        }
    }
}
