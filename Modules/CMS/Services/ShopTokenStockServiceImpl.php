<?php

namespace Modules\CMS\Services;

use Modules\CMS\Contracts\Repositories\ShopTokenStockRepository;
use Modules\CMS\Contracts\Services\ShopTokenStockService;

class ShopTokenStockServiceImpl implements ShopTokenStockService
{
    public function __construct(
        private readonly ShopTokenStockRepository $shopTokenStockRepository
    ) {}
}
