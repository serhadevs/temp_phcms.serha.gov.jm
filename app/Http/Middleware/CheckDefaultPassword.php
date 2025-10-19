<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CheckDefaultPassword
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user) {
            // Skip middleware check for password change routes
            if ($request->routeIs('user.changepassword', 'user.password.change', 'logout')) {
                return $next($request);
            }

            // Check if the password matches the default 'password123'
            if (Hash::check('password123', $user->password)) {
                return redirect()->route('user.changepassword')
                    ->with('error', 'Please change your default password before continuing.');
            }
        }

        return $next($request);
    }
}
