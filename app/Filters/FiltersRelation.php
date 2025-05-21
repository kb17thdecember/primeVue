<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersRelation implements Filter
{
    protected string $class;

    protected bool $exists;

    /**
     * @param string $class
     * @param bool $exists
     */
    public function __construct(string $class, bool $exists = true)
    {
        $this->class = $class;
        $this->exists = $exists;
    }

    /**
     * @param Builder $query
     * @param $value
     * @param string $property
     * @return mixed
     * @throws \Exception
     */
    public function __invoke(Builder $query, $value, string $property)
    {
        $method = $this->exists ? 'whereHas' : 'whereDoesntHave';

        return $query->{$method}($property, fn ($query) => resolve($this->class)->handle(new Request($value), $query));
    }
}
