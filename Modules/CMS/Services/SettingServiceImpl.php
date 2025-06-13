<?php

namespace Modules\CMS\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Modules\CMS\Contracts\Repositories\SettingRepository;
use Modules\CMS\Contracts\Services\SettingService;

class SettingServiceImpl implements SettingService
{
    public function __construct
    (
        private readonly SettingRepository $settingRepository,
    ) {}

    /**
     * @return mixed|string
     */
    public function getSetting(): mixed
    {
        $setting = $this->settingRepository->handle()->first();

        return $setting ? $setting->remaining : '';
    }

    /**
     * @param Request $request
     * @return Model
     */
    public function updateSetting(Request $request): Model
    {
        $data = [
            'remaining' => $request->get('remaining'),
        ];

        $setting = $this->settingRepository->handle()->first();

        if ($setting) {
            return $this->settingRepository->updateModel($setting, $data);
        }

        return $this->settingRepository->create($data);
    }
}