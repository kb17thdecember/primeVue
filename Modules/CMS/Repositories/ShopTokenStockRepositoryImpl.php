<?php

namespace Modules\CMS\Repositories;

use App\Filters\FiltersOperator;
use App\Models\ShopFrequency;
use App\Models\ShopTokenStocks;
use App\Repositories\BaseRepositoryImpl;
use Modules\CMS\Contracts\Repositories\ShopTokenStockRepository;
use Spatie\QueryBuilder\AllowedFilter;

class ShopTokenStockRepositoryImpl extends BaseRepositoryImpl implements ShopTokenStockRepository
{
    public string $model = ShopTokenStocks::class;

    public array $includes = [];

    public array $sorts = ['id'];

    protected function setAllowedFilters(): void
    {
        $this->filters = [
            AllowedFilter::custom('shop_id', new FiltersOperator()),
            AllowedFilter::custom('token_qty', new FiltersOperator()),
            AllowedFilter::custom('available_start_date', new FiltersOperator()),
            AllowedFilter::custom('available_end_date', new FiltersOperator()),
            AllowedFilter::custom('status', new FiltersOperator()),
        ];
    }
}
