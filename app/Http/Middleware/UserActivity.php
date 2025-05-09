<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class UserActivity
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
        /**
         * Middleware to track user activity and update their online status.
         *
         * This middleware performs the following actions:
         * - Checks if the user is authenticated.
         * - Sets a cache entry to indicate that the user is online, with a 1-minute expiration time.
         * - Updates the `last_seen` timestamp in the database for the authenticated user.
         * - Logs an error if the `last_seen` update fails.
         *
         * @throws Exception If there is an issue updating the user's `last_seen` timestamp.
         *
         * Dependencies:
         * - `Auth` facade: Used to check if the user is authenticated and retrieve the authenticated user's details.
         * - `Carbon`: Used to handle date and time operations.
         * - `Cache` facade: Used to store the user's online status in the cache.
         * - `User` model: Used to update the user's `last_seen` timestamp in the database.
         * - `Log` facade: Used to log errors if the database update fails.
         */
        if (Auth::check()) {
            $expireTime = Carbon::now()->addMinutes(1); 
            Cache::put('user-is-online-' . Auth::user()->id, true, $expireTime);
            try {
            User::where('id', Auth::user()->id)->update(['last_seen' => Carbon::now()]);
            } catch (Exception $e) {
            Log::error('Failed to update last seen time for user ID ' . Auth::user()->id . ': ' . $e->getMessage());
            }
        }
    
        return $next($request);
    }
    
}
