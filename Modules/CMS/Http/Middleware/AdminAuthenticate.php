<?php

namespace Modules\CMS\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminAuthenticate extends Middleware
{
    public function handle($request, \Closure $next, ...$guards)
    {
        if ($this->auth->guard('admin')->check()) {
            $this->auth->shouldUse('admin');
            
            // Share admin info with all views
            Inertia::share('auth.user', fn () => [
                'id' => Auth::guard('admin')->user()->id,
                'name' => Auth::guard('admin')->user()->name,
                'email' => Auth::guard('admin')->user()->email,
                'role' => Auth::guard('admin')->user()->role,
                'status' => Auth::guard('admin')->user()->status,
            ]);
            
            return $next($request);
        }

        $this->unauthenticated($request, ['admin']);
    }

    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login.form');
    }
} 