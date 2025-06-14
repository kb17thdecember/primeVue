<?php

namespace Modules\CMS\Contracts\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\CMS\Http\Requests\Product\StoreRequest;
use Modules\CMS\Http\Requests\Product\UpdateRequest;

interface SubscriberHistoryService
{
    /**
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * @param int $productId
     * @param array $requestData
     * @return bool
     */
    public function store(int $productId, array $requestData): bool;

    /**
     * @return array
     */
    public function stripeSetupIntent(): array;
}
