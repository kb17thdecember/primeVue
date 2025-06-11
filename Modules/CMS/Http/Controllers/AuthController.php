<?php

namespace Modules\CMS\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\CMS\Contracts\Services\AuthService;
use Modules\CMS\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    /**
     * @param AuthService $authService
     */
    public function __construct(private readonly AuthService $authService)
    {
    }

    /**
     * @return Response
     */
    public function formLogin(): Response
    {
        return Inertia::render('login/Index');
    }

    /**
     * @param LoginRequest $request
     * @return RedirectResponse
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $this->authService->login(request: $request);

        return to_route('dashboard');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        $this->authService->logout(request: $request);

        return to_route('cms.login');
    }
}
