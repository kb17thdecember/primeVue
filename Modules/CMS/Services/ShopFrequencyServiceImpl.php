<?php

namespace Modules\CMS\Services;

use App\Enums\Role;
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
        $currentUser = Auth::user();
        $requestData = Role::SHOP->is($currentUser->role)
            ? ['id' => $currentUser->shop_id]
            : [];
        $condition = new Request($requestData);
        $shop = $this->shopRepository->handle($condition)->first();

        $apiKey = $shop?->api_key;

        return $this->shopFrequencyRepository->handle(new Request(['api_key' => $apiKey]))->get();
    }
}
