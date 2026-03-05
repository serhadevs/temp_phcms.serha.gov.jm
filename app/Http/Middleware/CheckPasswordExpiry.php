<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckPasswordExpiry
{
    /**
     * Number of days before password expires.
     */
    protected $passwordExpiryDays = 90; // you can adjust this

    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->password_changed_at) {
            $lastChange = Carbon::parse($user->password_changed_at);
            $daysSinceChange = $lastChange->diffInDays(now());

            
            if ($daysSinceChange >= $this->passwordExpiryDays && !$request->routeIs('user.changepassword', 'user.password.change', 'logout')) {
                return redirect()->route('user.changepassword')
                    ->with('error', 'Your password has expired. Please update it to continue.');
            }
        }

        return $next($request);
    }
}
