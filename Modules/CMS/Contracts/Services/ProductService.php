<?php

namespace Modules\CMS\Contracts\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
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

    /**
     * @param int $product
     * @return Model
     */
    public function delete(int $product): Model;
}
