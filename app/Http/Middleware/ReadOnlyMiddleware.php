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

        // Only check if the user is logged in
        if ($user && $user->isAuditor()) {

            // Block all non-GET requests (POST, PUT, PATCH, DELETE)
            if (! $request->isMethod('get')) {

                // You can return a redirect (for web) or JSON (for API)
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Auditors have read-only access.'], 403);
                }

                return redirect()->back()->with('error', 'Auditors have read-only access.');
            }
        }

        return $next($request);
    
    }
}
