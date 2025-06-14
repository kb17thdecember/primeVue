<?php

namespace Modules\CMS\Services;

use App\Enums\RequestKeyFlag;
use App\Enums\StatusPrefix;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\CMS\Contracts\Repositories\ShopRepository;
use Modules\CMS\Contracts\Services\ShopService;
use Modules\CMS\Http\Requests\Shop\KeyRequest;
use Modules\CMS\Http\Requests\Shop\StoreRequest;
use Modules\CMS\Http\Requests\Shop\UpdateRequest;

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
//            'admin_id' => Auth::user()->id
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
            'id' => Auth::user()->shop_id,
            'admin_id' => Auth::user()->id
        ]);

        return $this->shopRepository->handle($condition)->firstOrFail();
    }

    /**
     * @param int $shop
     * @return Model
     */
    public function edit(int $shop): Model
    {
        $condition = new Request([
            'id' => $shop
        ]);

        return $this->shopRepository->handle($condition)->firstOrFail();
    }

    /**
     * @param UpdateRequest $request
     * @param int $shop
     * @return Model
     */
    public function update(UpdateRequest $request, int $shop): Model
    {
        $data = $request->validated();
        $condition = new Request([
            'id' => $shop
        ]);
        $shopData = $this->shopRepository->handle($condition)->firstOrFail();

        return$this->shopRepository->updateModel($shopData, $data);
    }

    /**
     * @return Model
     */
    public function updateRequestKey(): Model
    {
        $shop = $this->show();
        $requestKey = [
            'request_key_flag' => RequestKeyFlag::REQUEST->value
        ];

        return $this->shopRepository->updateModel($shop, $requestKey);
    }

    /**
     * @param KeyRequest $request
     * @return Model
     */
    public function updateApiKey(KeyRequest $request): Model
    {
        $data = $request->validated();
        $shop = $this->show();

        return $this->shopRepository->updateModel($shop, $data);
    }
}
