<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequestApi;
use App\Http\Requests\UserLoginRequest;
use App\Mail\SendOnboardingEmail;
use App\Models\OnlineUser;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class Auth extends Controller
{
    // public function login(LoginRequestApi $request){

    //     //Validate the user information
    //    $credentials = $request->validated();

    //     $user = OnlineUser::where('email',$credentials['email'])->first();

    //     //dd($user);
    //     if(!$user || !Hash::check($credentials['password'],$user->password)){
    //         return response([
    //             'status' => 'failed',
    //             'message' => 'Invalid Username or Password',

    //         ],422);
    //     }

    //     $user->tokens()->delete();
    //     //Create Token 

    //     $token = $user->createToken('PHCMS')->plainTextToken;
    //     // $access_token = $user->createToken('ACCESS_TOKEN')->plainTextToken;

    //     // Return the response
    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Login successful',
    //         'user' => $user,
    //         'token' => $token,
    //         // 'access_token' => $access_token,
    //         'token_type'    => 'Bearer'

    //     ], 200);

    // // return response()->json([
    // //         "message" => "API reached successfully"
    // //     ]);

    // }

    public function login(LoginRequestApi $request)
    {
        $credentials = $request->validated();

        $user = OnlineUser::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid Username or Password',
            ], 422);
        }

        $user->tokens()->delete();

        // Create Token with client info
        $token = $user->createToken('PHCMS', ['mobile_app'])->plainTextToken;

        Log::info('Mobile app login', [
            'user_id' => $user->id,
            'client_id' => request()->header('X-Client-ID'),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|digits:6'
        ]);

        $user = OnlineUser::where('email', $request->email)
            ->where('activation_code', $request->code)
            ->first();

        if (!$user) {
            return response()->json([
                'message' => 'Invalid code'
            ], 400);
        }

        if (now()->greaterThan($user->activation_expires_at)) {
            return response()->json([
                'message' => 'Code expired'
            ], 400);
        }

        return response()->json([
            'message' => 'Code verified',
            'email' => $user->email
        ]);
    }

    public function setPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = OnlineUser::where('email', $request->email)->firstOrFail();

        $user->update([
            'password' => Hash::make($request->password),
            'activated_at' => now(),
            'email_verified_at' => now(),
            'activation_code' => null,
            'activation_expires_at' => null,
        ]);

        // create auth token for mobile login
        $token = $user->createToken('mobile')->plainTextToken;
        $email = $user->email;

        // send onboarding complete email
        $this->sendOnboardingCompleteEmail($user,$email);

        return response()->json([
            'message' => 'Account activated successfully',
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        Log::channel('systemOperations')->info('API logout');
        //Find the current logged in user
        $user = $request->user();

        if ($user) {
            // Revoke the token that was used to authenticate the current request
            $request->user()->currentAccessToken()->delete();

            // Optionally, you can revoke all tokens by calling $user->tokens()->delete();
            // $user->tokens()->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Logout successful'
            ], 200);
        }

        return response()->json([
            'status' => 'failed',
            'message' => 'No authenticated user found'
        ], 401);
    }

    public function loginuser(UserLoginRequest $request)
    {
        Log::channel('systemOperations')->info('API login');

        //Validate the user information
        $credentials = $request->validated();

        $user = User::where('email', $credentials['email'])->first();

        //dd($user);
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid Username or Password',

            ], 422);
        }

        //Create Token 

        $token = $user->createToken('PHCMS')->plainTextToken;



        // Return the response
        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            //'user' => $user,
            'access_token' => $token,
            'token_type'    => 'Bearer'

        ], 201);
    }

    private function sendOnboardingCompleteEmail($user,$email){
    try {
            Mail::to($email)->queue(new SendOnboardingEmail($user,$email));
            Log::channel('systemOperations')->info('Onboarding Email Email Sent to', ['applicant' => $email]);
        } catch (Exception $e) {
            Log::channel('systemOperations')->error('Activation Email Sent to', ['message' => $e->getMessage()]);
        }
    }
}
