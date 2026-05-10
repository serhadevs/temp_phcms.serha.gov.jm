<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiClient;
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
            'headers' => $request->headers->all()
        ]);

        if (!$clientId || !$clientSecret) {
            Log::warning('Missing API credentials');
            return response()->json([
                'status' => 'failed',
                'message' => 'Missing API credentials'
            ], 401);
        }

        $client = ApiClient::where('client_id', $clientId)
            ->where('client_secret', $clientSecret)
            ->where('is_active', true)
            ->first();

        Log::info('Client lookup result', [
            'found' => $client ? 'yes' : 'no'
        ]);

        if (!$client) {
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