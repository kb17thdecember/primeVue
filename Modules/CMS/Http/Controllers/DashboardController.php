<?php

namespace Modules\CMS\Http\Controllers;

use App\Models\ShopFrequency;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Modules\CMS\Contracts\Services\ShopFrequencyService;

class DashboardController extends \App\Http\Controllers\Controller
{
    public function __construct(
        private readonly ShopFrequencyService $shopFrequencyService,
    ) {}
    public function index()
    {
        $shopFrequency = $this->shopFrequencyService->getShopFrequency();
        $dataReport = $this->shopFrequencyService->getDataDashboardReport();

        return Inertia::render('dashboard/Home', [
            'frequency' => $shopFrequency,
            'dataReport' => $dataReport
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function shopFrequency(Request $request): JsonResponse
    {
        $apiKey = $request->query('api_key');

        if (!$apiKey) {
            return response()->json([
                'labels' => [],
                'data' => [],
            ]);
        }

        $today = Carbon::today();
        $startDate = $today->copy()->subDays(29);

        $frequencies = ShopFrequency::query()
            ->where('api_key', $apiKey)
            ->whereBetween('date', [$startDate, $today])
            ->orderBy('date')
            ->get(['date', 'daily_count']);

        $labels = [];
        $data = [];

        $dateMap = $frequencies->keyBy(fn($item) => $item->date->format('Y-m-d'));

        for ($date = $startDate->copy(); $date->lte($today); $date->addDay()) {
            $key = $date->format('Y-m-d');
            $labels[] = $key;
            $data[] = optional($dateMap[$key])->daily_count ?? 0;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
