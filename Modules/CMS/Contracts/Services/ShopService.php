<?php

namespace Modules\CMS\Contracts\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\CMS\Http\Requests\Shop\StoreRequest;

interface ShopService
{
    /**
     * @return Collection
     */
    public function getAllShops(): Collection;

    /**
     * @param StoreRequest $request
     * @return Model
     */
    public function create(StoreRequest $request): Model;

    /**
     * @return Model
     */
    public function show(): Model;

    /**
     * @return Model
     */
    public function updateStatus(): Model;
}