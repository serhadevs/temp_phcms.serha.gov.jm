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

            // Allow POST only to specific whitelisted routes (by route name)
            $allowedPostRoutes = [
                'user.password.change',
                'password.change',
                'switch.update',
                'reports.general.generate',
                'reports.general.count',
                'reports.appcount',
                'reports.onsite.show',
                'reports.signoffs.show',
                'reports.productivity',
                'reports.category.show',
                'reports.establishment.show',
                'reports.inspections.show',
                'collectedcards.store',
                'reports.payment.index',
                'reports.payment.show',
                'report.summary.show',
                'report.transaction.show',
                'reports.printed-cards.show',
                'reports.collected-cards.show',
                'logout',
            ];

            // Get the current route name
            $currentRouteName = $request->route() ? $request->route()->getName() : null;

            // If not a GET and not one of the whitelisted routes
            if (!$request->isMethod('get') &&
                !in_array($currentRouteName, $allowedPostRoutes)) {

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