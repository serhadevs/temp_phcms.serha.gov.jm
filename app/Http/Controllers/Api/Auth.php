<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequestApi;
use App\Http\Requests\UserLoginRequest;
use App\Models\OnlineUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
}
