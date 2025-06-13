<?php

namespace Modules\CMS\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\CMS\Contracts\Services\SettingService;

class SettingController extends Controller
{
    public function __construct
    (
        private readonly SettingService $settingService
    ){}

    /**
     * @return Response
     */
    public function edit(): Response
    {
        $data = $this->settingService->getSetting();
        return Inertia::render('settings/Update', [
            'setting' => $data
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $this->settingService->updateSetting($request);

        return redirect()->route('settings.edit');
    }
}
