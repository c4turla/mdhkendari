<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        // If the session has expired, redirect to login
        if (!Auth::check() && $request->session()->has('last_activity')) {
            $lastActivity = $request->session()->get('last_activity');
            $sessionTimeout = config('session.lifetime') * 60;

            if (time() - $lastActivity > $sessionTimeout) {
                Auth::logout();
                $request->session()->flush();
                return redirect()->route('login')->with('message', 'Sesi Anda telah berakhir. Silakan login kembali.');
            }
        }

        return $next($request);
    }
}