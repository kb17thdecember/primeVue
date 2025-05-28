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
        'parent'
    ];

    public array $sorts = ['id'];

    protected function setAllowedFilters(): void
    {
        $this->filters = [
            AllowedFilter::custom('id', new FiltersOperator()),
            AllowedFilter::custom('parent_id', new FiltersOperator()),
        ];
    }
}
