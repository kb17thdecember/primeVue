<?php

namespace Modules\CMS\Repositories;

use App\Models\Setting;
use App\Repositories\BaseRepositoryImpl;
use Modules\CMS\Contracts\Repositories\SettingRepository;

class SettingRepositoryImpl extends BaseRepositoryImpl implements SettingRepository
{
    public string $model = Setting::class;

    public array $includes = [];

    public array $sorts = [];

    protected function setAllowedFilters(): void
    {
        $this->filters = [];
    }
}