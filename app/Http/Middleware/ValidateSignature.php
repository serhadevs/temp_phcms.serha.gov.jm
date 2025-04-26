<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateSignature
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
        $expires = $request->query('expires');
        $onlineUser = $request->query('online_user');
        $signature = $request->query('signature');
        
        // Check if the signature parameters exist
        if (!$expires || !$onlineUser || !$signature) {
            return redirect()->route('permit.online.application.resend')->with('error', 'Invalid access attempt');
        }
        
        // Check if the signature has expired
        if (time() > $expires) {
            return redirect()->route('permit.online.application.resend')->with('error', 'Signature has expired');
        }
        
        // Validate the signature
        $validSignature = $this->generateSignature($expires, $onlineUser);
        
        if ($signature !== $validSignature) {
            return redirect()->route('permit.online.application.resend')->with('error', 'Invalid signature');
        }
        
        return $next($request);
    }
    
    /**
     * Generate a signature based on the provided parameters.
     *
     * @param  string  $expires
     * @param  string  $onlineUser
     * @return string
     */
    private function generateSignature($expires, $onlineUser)
    {
        $data = "expires={$expires}&online_user={$onlineUser}";
        return hash_hmac('sha256', $data, config('app.key'));
    }
    
}
