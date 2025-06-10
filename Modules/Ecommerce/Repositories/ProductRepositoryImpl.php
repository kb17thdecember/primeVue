<?php

namespace Modules\Ecommerce\Repositories;

use App\Filters\FiltersOperator;
use App\Models\Product;
use App\Repositories\BaseRepositoryImpl;
use Modules\Ecommerce\Contracts\Repositories\ProductRepository;
use Spatie\QueryBuilder\AllowedFilter;

class ProductRepositoryImpl extends BaseRepositoryImpl implements ProductRepository
{
    public string $model = Product::class;

    public array $includes = [
        'brand',
        'category',
        'category.parent',
        'category.children'
    ];

    public array $sorts = ['id'];

    protected function setAllowedFilters(): void
    {
        $this->filters = [
            AllowedFilter::custom('id', new FiltersOperator())
        ];
    }
}
