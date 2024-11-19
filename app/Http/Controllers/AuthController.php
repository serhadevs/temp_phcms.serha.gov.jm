<?php

namespace App\Http\Controllers;

use App\Models\LoginActivity;
use App\Models\LoginLocation;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $userAgent = $request->input('userAgent');
        $userPlatform = $request->input('userPlatform');

        try {

            // Attempt to log in the user
            if (!Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
                return redirect('/')->with('error', 'Invalid credentials')->withInput();
            }

            //Check to see if the password is password123 and redirect them to changing the password

            if ($credentials['password'] === 'password123') {
                return redirect('/change-password')->with('info', 'You are required to change your password.');
            }

            //Check to see if they are logged in already by checking the session_id in the
            //loginlocation table

           

            $user = Auth::user();
            $request->session()->regenerate();
            Auth::login($user, $request->get('remember_token'));
            
            //Generate the session id

            $session_id = Str::random(20);
            //Store it in a session 

            session(['session_id' => $session_id]);

            $clientIP = $request->header('X-Real-IP');

            //dd($clientIP);

            $location = LoginActivity::create([
                'login_time' => now(),
                'user_id' => Auth::user()->id,
                'facility_id' => Auth::user()->facility_id,
                'user_agent' => $userAgent,
                'platform' => $userPlatform,
                'session_id' => $session_id,
                'ip_address' => $request->ip()
            ]);

            if (!$location) {
                return redirect('/')->with('error', 'Unable to store login information');
            }

            return redirect()->intended('/dashboard')->with('success', 'User logged in successfully!');
        } catch (\Exception $e) {
            return redirect('/')
                ->with('error', 'An error occurred during login: ' . $e->getMessage());
        }
    }

   



    public function logout(Request $request)
    {
        // $userId = Auth::id();
         //Find the user in the database that matches the id
         $sessionId = Session::get('session_id');

         //dd($sessionId);
         $location = LoginActivity::where('session_id',$sessionId)->first();
 
         //dd($location);
 
         if ($location) {
             $location->update([
                 'logout_time' => now()
             ]);
         }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'You have been logged out!');
    }
}
