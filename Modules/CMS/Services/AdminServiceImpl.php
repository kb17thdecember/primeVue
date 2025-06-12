<?php

namespace Modules\CMS\Services;

use Illuminate\Database\Eloquent\Model;
use Modules\CMS\Contracts\Repositories\AdminRepository;
use Modules\CMS\Contracts\Services\AdminService;
use Modules\CMS\Http\Requests\Admin\StoreRequest;

class AdminServiceImpl implements AdminService
{
    public function __construct
    (
        private readonly AdminRepository $adminRepository
    ) {}

    /**
     * @param StoreRequest $request
     * @param int $shopId
     * @return Model
     */
    public function store(StoreRequest $request, int $shopId): Model
    {
        $admin = $request->validated();
        $admin['shop_id'] = $shopId;

        return $this->adminRepository->handle()->create($admin);
    }
}