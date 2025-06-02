<?php

namespace Modules\CMS\Repositories;

use App\Filters\AllowedFilter;
use App\Filters\FiltersOperator;
use App\Models\Category;
use App\Repositories\BaseRepositoryImpl;
use Modules\CMS\Contracts\Repositories\CategoryRepository;

class CategoryRepositoryImpl extends BaseRepositoryImpl implements CategoryRepository
{
    public string $model = Category::class;

    public array $includes = [
        'parent',
        'children'
    ];

    public array $sorts = [
        'id',
        'display_order'
    ];

    protected function setAllowedFilters(): void
    {
        $this->filters = [
            AllowedFilter::custom('id', new FiltersOperator()),
            AllowedFilter::custom('parent_id', new FiltersOperator()),
            AllowedFilter::custom('status', new FiltersOperator()),
        ];
    }
}
