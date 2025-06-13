<?php

namespace Modules\CMS\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $condition = new Request([
            'id' => Auth::user()->shop_id,
        ]);
        $shop = $this->shopRepository->handle($condition)->first();

        $apiKey = $shop->api_key;

        return $this->shopFrequencyRepository->handle(new Request(['api_key' => $apiKey]))->get();
    }
}