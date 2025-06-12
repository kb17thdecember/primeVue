<?php

namespace Modules\CMS\Repositories;

use App\Models\Admin;
use App\Repositories\BaseRepositoryImpl;
use Modules\CMS\Contracts\Repositories\AdminRepository;

class AdminRepositoryImpl extends BaseRepositoryImpl implements AdminRepository
{
    public string $model = Admin::class;

    public array $includes = [];

    public array $sorts = [];

    protected function setAllowedFilters(): void
    {
        $this->filters = [];
    }
}