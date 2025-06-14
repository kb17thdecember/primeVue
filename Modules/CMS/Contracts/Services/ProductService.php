<?php

namespace Modules\CMS\Contracts\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\CMS\Http\Requests\Product\StoreRequest;
use Modules\CMS\Http\Requests\Product\UpdateRequest;

interface ProductService
{
    /**
     * @return Collection
     */
    public function getAllProducts(): Collection;

    /**
     * @return array
     */
    public function getAllPricing(): array;

    /**
     * @param StoreRequest $request
     * @return Product
     */
    public function store(StoreRequest $request): Product;

    /**
     * @param int $product
     * @return Model
     */
    public function edit(int $product): Model;

    /**
     * @param int $product
     * @param UpdateRequest $request
     * @return Model
     */
    public function update(int $product, UpdateRequest $request): Model;

    /**
     * @param int $product
     * @return Model
     */
    public function delete(int $product): Model;
}
