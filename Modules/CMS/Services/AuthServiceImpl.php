<?php

namespace Modules\CMS\Services;

use App\Enums\AdminStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Modules\CMS\Contracts\Services\AuthService;
use Modules\CMS\Http\Requests\LoginRequest;

class AuthServiceImpl implements AuthService
{
    /**
     * @param LoginRequest $request
     * @return void
     * @throws ValidationException
     */
    public function login(LoginRequest $request): void
    {
        $data = $request->only(['email', 'password']);

        if (!Auth::guard('admin')->attempt($data, $request->input('remember_token'))) {
            throw ValidationException::withMessages([
                'email' => 'Invalid email or password',
            ]);
        }

        if (AdminStatus::ENABLE->value !== Auth::guard('admin')->user()->status->value) {
            Auth::guard('admin')->logout();

            throw ValidationException::withMessages([
                'email' => 'Your account is inactive',
            ]);
        }

        $request->session()->regenerate();
    }

    /**
     * @param Request $request
     * @return void
     */
    public function logout(Request $request): void
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
    }
}
