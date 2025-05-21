<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersOperator implements Filter
{
    /**
     * @param Builder $query
     * @param $value
     * @param string $property
     * @return void
     */
    public function __invoke(Builder $query, $value, string $property): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_array($value)) {
            $query->where($query->qualifyColumn($property), $value);

            return;
        }

        if (array_key_exists('ne', $value) && is_array($value['ne'])) {
            foreach ($value['ne'] as $valueNe) {
                $query->where($property, '<>', $valueNe);
            }

            return;
        }

        if (count($value) > 0 && array_keys($value) === range(0, count($value) - 1)) {
            $query->whereIn($query->qualifyColumn($property), $value);

            return;
        }

        foreach (['gte' => '>=', 'lte' => '<=', 'gt' => '>', 'lt' => '<', 'ne' => '<>'] as $key => $operator) {
            if (Arr::exists($value, $key)) {
                $query->where($property, $operator, $value[$key]);
            }
        }

        if (Arr::exists($value, 'null')) {
            $statement = $value['null'] ? 'whereNull' : 'whereNotNull';
            $query->{$statement}($property);
        }
    }
}
