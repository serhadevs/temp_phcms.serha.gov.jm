<?php

namespace App\Http\Controllers;

use App\Models\LoginLocation;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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


        try {
          
            // Attempt to log in the user
            if (!Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
                return redirect('/')->with('error', 'Invalid credentials');
            }

            //Check to see if the password is password123 and redirect them to changing the password

            if ($credentials['password'] === 'password123') {
                return redirect('/change-password')->with('info', 'You are required to change your password.');
            }

            $user = Auth::user();
            $request->session()->regenerate();
            Auth::login($user, $request->get('remember_token'));
                
            return redirect()->intended('/dashboard')->with('success', 'User logged in successfully!');
          
        } catch (\Exception $e) {
            return redirect('/')
                ->with('error', 'An error occurred during login: ' . $e->getMessage());
        }
    }


   

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'You have been logged out!');
    }
}