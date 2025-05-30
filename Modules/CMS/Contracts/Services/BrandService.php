<?php

namespace Modules\CMS\Contracts\Services;


use App\Models\Brand;
use Illuminate\Database\Eloquent\Collection;
use Modules\CMS\Http\Requests\Brand\StoreRequest;

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
}