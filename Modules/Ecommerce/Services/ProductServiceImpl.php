<?php

namespace Modules\Ecommerce\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Modules\Ecommerce\Contracts\Repositories\ProductRepository;
use Modules\Ecommerce\Contracts\Services\ProductService;
use Spatie\QueryBuilder\QueryBuilder;

class ProductServiceImpl implements ProductService
{
    /**
     * @param ProductRepository $productRepository
     */
    public function __construct(
        private readonly ProductRepository $productRepository,
    ) {}

    /**
     * @return Collection
     */
    public function getLimitProducts(): Collection
    {
        return $this->productRepository->limit(new Request(['limit' => 10]));
    }
}