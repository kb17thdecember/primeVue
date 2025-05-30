<?php

namespace Modules\CMS\Contracts\Services;


use App\Models\Brand;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\CMS\Http\Requests\Brand\StoreRequest;
use Modules\CMS\Http\Requests\Brand\UpdateRequest;

interface BrandService
{
    /**
     * @return Collection
     */
    public function getAllBrands(): Collection;

    /**
     * @param StoreRequest $request
     * @return Brand
     */
    public function store(StoreRequest $request): Brand;

    /**
     * @param int $brand
     * @return Brand
     */
    public function edit(int $brand): Brand;

    /**
     * @param int $brand
     * @param UpdateRequest $request
     * @return Model
     */
    public function update(int $brand, UpdateRequest $request): Model;

    /**
     * @param int $brand
     * @return Model
     */
    public function delete(int $brand): Model;
}