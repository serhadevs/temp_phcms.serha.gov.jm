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

        // Get credentials from config
        $validClientId = config('api.mobile.client_id');
        $validClientSecret = config('api.mobile.client_secret');

        Log::info('Comparing credentials', [
            'received_id' => $clientId,
            'valid_id' => $validClientId,
            'match' => $clientId === $validClientId
        ]);

        // Validate against config values
        if ($clientId !== $validClientId || $clientSecret !== $validClientSecret) {
            Log::warning('Invalid API credentials', [
                'received_client_id' => $clientId,
                'expected_client_id' => $validClientId
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