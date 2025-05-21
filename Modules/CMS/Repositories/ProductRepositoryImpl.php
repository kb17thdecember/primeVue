<?php

namespace Modules\CMS\Repositories;

use App\Models\Product;
use App\Contracts\Repositories\ProductRepository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\Filters\FiltersOperator;

class ProductRepositoryImpl extends BaseRepositoryImpl implements ProductRepository
{
    public string $model = Product::class;

    public array $includes = [];

    public array $sorts = ['id'];

    protected function setAllowedFilters(): void
    {
        $this->filters = [
            AllowedFilter::custom('id', new FiltersOperator())
        ];
    }
}
