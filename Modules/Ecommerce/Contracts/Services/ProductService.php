<?php

namespace Modules\Ecommerce\Contracts\Services;

use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\QueryBuilder;

interface ProductService
{
    /**
     * @return QueryBuilder
     */
    public function getLimitProducts(): Collection;
}
