<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSessionExpired
{
    public function handle(Request $request, Closure $next)
    {
        // Check if session has expired
        if ($request->session()->has('last_activity')) {
            $lastActivity = $request->session()->get('last_activity');
            $sessionTimeout = config('session.lifetime') * 60; // Convert minutes to seconds

            if (time() - $lastActivity > $sessionTimeout) {
                Auth::logout();
                $request->session()->flush();
                return redirect()->route('login')->with('message', 'Sesi Anda telah berakhir. Silakan login kembali.');
            }
        }

        // Update last activity time for logged in users
        if (Auth::check()) {
            $request->session()->put('last_activity', time());
        }

        return $next($request);
    }
}