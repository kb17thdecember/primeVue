<?php

namespace Modules\CMS\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\CMS\Contracts\Repositories\ProductRepository;
use Modules\CMS\Contracts\Services\ProductService;

class ProductServiceImpl implements ProductService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
    ) {}

    public function getAllProducts(): Collection
    {
        return $this->productRepository->handle()->get();
    }
}
