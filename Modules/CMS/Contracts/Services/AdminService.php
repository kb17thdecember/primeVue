<?php

namespace Modules\CMS\Contracts\Services;

use Illuminate\Database\Eloquent\Model;
use Modules\CMS\Http\Requests\Admin\StoreRequest;

interface AdminService
{
    /**
     * @param StoreRequest $request
     * @param int $shopId
     * @return Model
     */
    public function store(StoreRequest $request, int $shopId): Model;
}