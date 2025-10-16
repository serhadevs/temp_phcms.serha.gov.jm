<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ReadOnlyMiddleware
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
        $user = $request->user();

        if ($user && $user->isAuditor()) {

            // Allow POST only to specific whitelisted routes
            $allowedPostRoutes = [
                'user.password.change',
                'switch.update',
                'logout',
            ];

            // If not a GET and not one of the whitelisted routes
            if (! $request->isMethod('get') &&
                ! in_array($request->path(), $allowedPostRoutes)) {

                // For API or AJAX requests
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Auditors have read-only access.'], 403);
                }

                // For normal web requests
                return redirect()->back()->with('error', 'Auditors have read-only access.');
            }
        }

        return $next($request);
    }
}
