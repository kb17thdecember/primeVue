<?php

namespace Modules\CMS\Contracts\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Modules\CMS\Http\Requests\Shop\UpdateKeyRequest;
use Modules\CMS\Http\Requests\Shop\StoreRequest;
use Modules\CMS\Http\Requests\Shop\UpdateRequest;

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
     * @param int $shop
     * @return Model
     */
    public function edit(int $shop): Model;

    /**
     * @param UpdateRequest $request
     * @param int $shop
     * @return Model
     */
    public function update(UpdateRequest $request, int $shop): Model;

    /**
     * @return Model
     */
    public function updateRequestKey(): Model;

    /**
     * @param UpdateKeyRequest $request
     * @param $id
     * @return Model
     */
    public function updateApiKey(UpdateKeyRequest $request, $id): Model;
}