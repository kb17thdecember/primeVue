<?php

namespace Modules\CMS\Repositories;

use App\Filters\FiltersOperator;
use App\Models\Shop;
use App\Models\ShopFrequency;
use App\Repositories\BaseRepositoryImpl;
use Modules\CMS\Contracts\Repositories\ShopFrequencyRepository;
use Spatie\QueryBuilder\AllowedFilter;

class ShopFrequencyRepositoryImpl extends BaseRepositoryImpl implements ShopFrequencyRepository
{
    public string $model = ShopFrequency::class;

    public array $includes = [];

    public array $sorts = ['id'];

    protected function setAllowedFilters(): void
    {
        $this->filters = [
            AllowedFilter::custom('id', new FiltersOperator()),
        ];
    }
}