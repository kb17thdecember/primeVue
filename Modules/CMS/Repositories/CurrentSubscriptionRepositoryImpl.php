<?php

namespace Modules\CMS\Repositories;

use App\Filters\FiltersOperator;
use App\Models\CurrentSubscription;
use App\Repositories\BaseRepositoryImpl;
use Modules\CMS\Contracts\Repositories\CurrentSubscriptionRepository;
use Spatie\QueryBuilder\AllowedFilter;

class CurrentSubscriptionRepositoryImpl extends BaseRepositoryImpl implements CurrentSubscriptionRepository
{
    public string $model = CurrentSubscription::class;

    public array $includes = [];

    public array $sorts = ['id'];

    protected function setAllowedFilters(): void
    {
        $this->filters = [

        ];
    }
}
