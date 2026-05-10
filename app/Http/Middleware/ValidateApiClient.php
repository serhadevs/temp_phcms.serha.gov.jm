<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ValidateApiClient
{
    public function handle(Request $request, Closure $next)
    {
        $clientId = $request->header('X-Client-ID');
        $clientSecret = $request->header('X-Client-Secret');

        Log::info('API Client Validation', [
            'received_client_id' => $clientId,
            'received_client_secret' => $clientSecret ? 'provided' : 'missing',
        ]);

        if (!$clientId || !$clientSecret) {
            Log::warning('Missing API credentials');
            return response()->json([
                'status' => 'failed',
                'message' => 'Missing API credentials'
            ], 401);
        }

        // Get credentials from .env
        $validClientId = env('MOBILE_APP_CLIENT_ID');
        $validClientSecret = env('MOBILE_APP_CLIENT_SECRET');

        // Validate against .env values
        if ($clientId !== $validClientId || $clientSecret !== $validClientSecret) {
            Log::warning('Invalid API credentials', [
                'client_id' => $clientId
            ]);
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid API credentials'
            ], 401);
        }

        Log::info('API Client validated successfully', [
            'client_id' => $clientId
        ]);

        return $next($request);
    }
}