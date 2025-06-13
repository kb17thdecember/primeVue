<?php

namespace Modules\CMS\Contracts\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface SettingService
{
    /**
     * @return mixed
     */
    public function getSetting(): mixed;

    /**
     * @param Request $request
     * @return Model
     */
    public function updateSetting(Request $request): Model;
}