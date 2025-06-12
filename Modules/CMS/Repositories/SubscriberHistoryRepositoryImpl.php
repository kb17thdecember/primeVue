<?php

namespace Modules\CMS\Repositories;

use App\Filters\FiltersOperator;
use App\Models\SubscriberHistory;
use App\Repositories\BaseRepositoryImpl;
use Modules\CMS\Contracts\Repositories\SubscriberHistoryRepository;
use Spatie\QueryBuilder\AllowedFilter;

class SubscriberHistoryRepositoryImpl extends BaseRepositoryImpl implements SubscriberHistoryRepository
{
    public string $model = SubscriberHistory::class;

    public array $includes = [
        'shop',
        'product',
    ];

    public array $sorts = ['id'];

    protected function setAllowedFilters(): void
    {
        $this->filters = [
            AllowedFilter::custom('id', new FiltersOperator())
        ];
    }
}
