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
     * @return array
     */
    public function store(int $productId): array;

    /**
     * @return array
     */
    public function stripeSetupIntent(): array;

    /**
     * @param string $sessionId
     * @return bool
     */
    public function handleStripePaymentSuccess(string $sessionId): bool;

    /**
     * @param string $sessionId
     * @return bool
     */
    public function handleStripePaymentCancel(string $sessionId): bool;
}
