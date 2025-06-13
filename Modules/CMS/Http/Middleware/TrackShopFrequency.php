<?php

namespace Modules\CMS\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ShopFrequency;
use Carbon\Carbon;

class TrackShopFrequency
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-Shop-Api-Key');

        if (!$apiKey) {
            return response()->json(['error' => 'API key missing'], 400);
        }

        $date = Carbon::today()->toDateString();

        $frequency = ShopFrequency::where('api_key', $apiKey)
            ->where('date', $date)
            ->first();

        if ($frequency) {
            $frequency->increment('daily_count');
            $frequency->increment('monthly_count');
            $frequency->increment('yearly_count');
        } else {
            ShopFrequency::create([
                'api_key' => $apiKey,
                'date' => $date,
                'daily_count' => 1,
                'monthly_count' => 1,
                'yearly_count' => 1,
            ]);
        }

        return $next($request);
    }
}
