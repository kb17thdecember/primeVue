<?php

namespace Modules\CMS\Repositories;

use App\Filters\AllowedFilter;
use App\Filters\FiltersOperator;
use App\Models\Brand;
use App\Repositories\BaseRepositoryImpl;
use Modules\CMS\Contracts\Repositories\BrandRepository;

class BrandRepositoryImpl extends BaseRepositoryImpl implements BrandRepository
{
    public string $model = Brand::class;

    public array $includes = [
    ];

    public array $sorts = [
        'id',
        'display_order'
    ];

    protected function setAllowedFilters(): void
    {
        $this->filters = [
            AllowedFilter::custom('id', new FiltersOperator()),
            AllowedFilter::custom('status', new FiltersOperator()),
        ];
    }
}
