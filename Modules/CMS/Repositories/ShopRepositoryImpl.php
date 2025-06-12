<?php

namespace Modules\CMS\Repositories;

use App\Filters\FiltersOperator;
use App\Models\Shop;
use App\Repositories\BaseRepositoryImpl;
use Modules\CMS\Contracts\Repositories\ShopRepository;
use Spatie\QueryBuilder\AllowedFilter;

class ShopRepositoryImpl extends BaseRepositoryImpl implements ShopRepository
{
    public string $model = Shop::class;

    public array $includes = [];

    public array $sorts = ['id'];

    protected function setAllowedFilters(): void
    {
        $this->filters = [
            AllowedFilter::custom('id', new FiltersOperator()),
            AllowedFilter::custom('admin_id', new FiltersOperator()),
        ];
    }
}