<?php

namespace Modules\CMS\Contracts\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Modules\CMS\Http\Requests\Product\StoreRequest;

interface ProductService
{
    /**
     * @return Collection
     */
    public function getAllProducts(): Collection;

    /**
     * @param StoreRequest $request
     * @return Product
     */
    public function store(StoreRequest $request): Product;
}
