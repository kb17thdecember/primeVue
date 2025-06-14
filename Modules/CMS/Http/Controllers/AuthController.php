<?php

namespace Modules\CMS\Http\Controllers;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Shop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Socialite\Facades\Socialite;
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

        return to_route('cms.dashboard');
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

    /**
     * @return RedirectResponse
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $admin = Admin::where('email', $googleUser->getEmail())->first();

            if (!$admin) {
                $admin = Admin::create([
                    'name'     => $googleUser->getName(),
                    'email'    => $googleUser->getEmail(),
                    'password' => bcrypt(uniqid()),
                    'role'     => Role::SHOP->value,
                    'shop_id'  => null,
                    'status'   => 1,
                ]);

                $shop = Shop::create([
                    'admin_id' => $admin->id,
                    'name'     => $googleUser->getName(),
                    'status'   => 1,
                ]);

                $admin->update(['shop_id' => $shop->id]);
            }

            auth('admin')->login($admin, true);

            return redirect()->route('cms.dashboard');

        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->route('login.form')->withErrors(['login' => 'Login Failed.']);
        }
    }
}
