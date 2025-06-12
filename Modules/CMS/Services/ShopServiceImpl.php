<?php

namespace Modules\CMS\Services;

use App\Enums\StatusPrefix;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\CMS\Contracts\Repositories\ShopRepository;
use Modules\CMS\Contracts\Services\ShopService;
use Modules\CMS\Http\Requests\Shop\StoreRequest;

class ShopServiceImpl implements ShopService
{
    public function __construct(
        private readonly ShopRepository $shopRepository
    ) {}

    /**
     * @return Collection
     */
    public function getAllShops(): Collection
    {
        $condition = new Request([
            'admin_id' => Auth::user()->id
        ]);
        return $this->shopRepository->handle($condition)->get();
    }

    /**
     * @param StoreRequest $request
     * @return Model
     */
    public function create(StoreRequest $request): Model
    {
        $data = $request->validated();
        $data['admin_id'] = Auth::user()->id;
        $data['create_at'] = Carbon::now()->format('Y-m-d H:i:s');

        return $this->shopRepository->handle()->create($data);
    }

    /**
     * @return Model
     */
    public function show(): Model
    {
        $condition = new Request([
            'id' => Auth::user()->id
        ]);

        return $this->shopRepository->handle($condition)->firstOrFail();
    }

    /**
     * @return Model
     */
    public function updateStatus(): Model
    {
        $shop = $this->show();
        $status = [
            'status' => StatusPrefix::REQUEST->value
        ];

        return $this->shopRepository->updateModel($shop, $status);
    }
}
