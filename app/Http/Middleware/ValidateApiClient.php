<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiClient;

class ValidateApiClient
{
    public function handle(Request $request, Closure $next)
    {
        $clientId = $request->header('X-Client-ID');
        $clientSecret = $request->header('X-Client-Secret');

        if (!$clientId || !$clientSecret) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Missing API credentials'
            ], 401);
        }

        $client = ApiClient::where('client_id', $clientId)
            ->where('client_secret', $clientSecret)
            ->where('is_active', true)
            ->first();

        if (!$client) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid API credentials'
            ], 401);
        }

        return $next($request);
    }
}