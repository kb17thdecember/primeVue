<?php

namespace Modules\CMS\Contracts\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ShopFrequencyService
{
    /**
     * @return Collection
     */
    public function getShopFrequency(): Collection;

    /**
     * @return array
     */
    public function getDataDashboardReport(): array;
}
