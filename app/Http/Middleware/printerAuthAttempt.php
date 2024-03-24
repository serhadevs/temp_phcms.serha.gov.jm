<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class printerAuthAttempt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (($user && password_verify($request->password, $user->password)) && ($user->role_id == 6 || $user->role_id == 1)) {
                return $next($request);
            }
        }
        return $request->application_type == "1" ? redirect()->route('downloads.foodhandlers.index')->with('error', 'Incorrect Credentials or Permissions') : redirect()->route('downloads.foodest.index')->with('error', 'Incorrect Credentials or Permissions');
    }
}
