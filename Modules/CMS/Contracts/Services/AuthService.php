<?php

namespace Modules\CMS\Contracts\Services;

use Illuminate\Http\Request;
use Modules\Cms\Http\Requests\LoginRequest;

interface AuthService
{
    /**
     * @param LoginRequest $request
     * @return void
     */
    public function login(LoginRequest $request): void;

    /**
     * @param Request $request
     * @return void
     */
    public function logout(Request $request): void;
}
