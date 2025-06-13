<?php

namespace Modules\CMS\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ShopFrequency;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ShopFrequencyController extends Controller
{
    public function index(): JsonResponse
    {
        $shop = Auth::guard('admin')->user()->shop;

        if (!$shop || !$shop->api_key) {
            return response()->json([
                'labels' => [],
                'data' => [],
            ]);
        }

        $records = ShopFrequency::query()
            ->where('api_key', $shop->api_key)
            ->orderBy('date')
            ->limit(30)
            ->get();

        return response()->json([
            'labels' => $records->pluck('date')->map(fn ($d) => \Carbon\Carbon::parse($d)->format('Y-m-d')),
            'data' => $records->pluck('daily_count'),
        ]);
    }
}
