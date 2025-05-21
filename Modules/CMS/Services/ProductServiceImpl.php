<?php

namespace Modules\CMS\Services;

use Modules\CMS\Contracts\Repositories\ProductRepository;
use Modules\CMS\Contracts\Services\ProductService;

class ProductServiceImpl implements ProductService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
    ) {}

    public function getAllProducts()
    {
        return $this->productRepository->get();
    }
}
