<?php

namespace App\Filters;

use Spatie\QueryBuilder\AllowedFilter as QueryBuilderAllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AllowedFilter extends QueryBuilderAllowedFilter
{
    /**
     * @param QueryBuilder $query
     * @param $value
     * @return void
     */
    public function filter(QueryBuilder $query, $value): void
    {
        $valueToFilter = $this->resolveValueForFiltering($value);
        if ($this->filterClass instanceof FiltersRelation) {
            $valueToFilter ??= [];
        }

        if (is_null($valueToFilter)) {
            return;
        }

        ($this->filterClass)($query->getEloquentBuilder(), $valueToFilter, $this->internalName);
    }
}
