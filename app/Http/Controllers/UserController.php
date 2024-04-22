<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\SendResetPasswordEmail;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ForgetPasswordMail;
use App\Models\Facility;
use App\Models\LoginActivity;
use App\Models\Role;

class UserController extends Controller
{

    //Show Users that are currently registered on the system
    //Shows currently logged in users
    //Route: /settings/users
    public function index()
    {

        if (Auth::user()->role_id == 1) {
            $users = DB::table('users')->get();
        } elseif (Auth::user()->role_id == 2) {
            $users = User::where('facility_id', Auth::user()->facility_id)->get();
        }

        $currentUsers = User::select("*")
            ->whereNotNull('last_seen')
            ->orderBy('last_seen', 'DESC')
            ->get();



        //dd($currentUsers);


        return view('users.index', compact('users', 'currentUsers'));
    }

    //Shows currently logged in users
    public function onlineUsers(Request $request)
    {
        $currentUsers = User::whereNotNull('last_seen')
            ->whereNotNull('last_seen')
            ->orderBy('last_seen', 'DESC')
            ->get();

        return view('users.onlineusers', compact('currentUsers'));
    }

    //Shows the logged in locations of each user

    public function loginUsersLocations()
    {
        $loginUsers = LoginActivity::join('users', 'login_activity.user_id', '=', 'users.id')->whereNull('logout_time')->get();
        $loginUsersCount = LoginActivity::join('users', 'login_activity.user_id', '=', 'users.id')->count();
        $ksaCount = LoginActivity::join('users', 'login_activity.user_id', '=', 'users.id')
            ->where('login_activity.facility_id', '3')->whereNull('logout_time')->count();
            $sttCount = LoginActivity::join('users', 'login_activity.user_id', '=', 'users.id')
            ->where('login_activity.facility_id', '2')->whereNull('logout_time')->count();
            $stcCount = LoginActivity::join('users', 'login_activity.user_id', '=', 'users.id')
            ->where('login_activity.facility_id', '1')->whereNull('logout_time')->count();

        //dd($loginUsers);
        return view('users.loggedusers', compact('loginUsers', 'loginUsersCount','ksaCount','sttCount','stcCount'));
    }






    //Forget Password Page View
    //Route: /forget-password
    //This function serves you the forget password reset form to the user.
    public function forgetPasswordPage()
    {
        return view('auth.forgetpassword');
    }

    //Forget Password Function 
    //Route: /forget-password 
    //This function is responsible for handling the forget password functionality.
    //When invoked, it initiates the process for resetting a user's password by sending
    //a password reset email to the user's registered email address.

    public function forgetpassword(Request $request)
    {

        //Get the email from the request 
        $email = $request->email;

        //dd($email);
        //Find the user using the User Model
        $user = User::where('email', '=', $email)->first();

        //Check to see if the email is empty before
        if (!empty($user)) {

            //Create the remember token and store in the database
            $user->remember_token = Str::random(40);
            $user->save();

            //Send the Mail to the user 

            dispatch(new SendResetPasswordEmail($user));
            // Mail::to($user->email)->send(new ForgetPasswordMail($user));

            //Return a success message after the email is sent. 
            return redirect()->back()->with('success', "The reset password email was sent to your email. Please check your email");
        } else {
            return redirect()->back()->with('error', "The email you provided does not exist");
        }
    }

    public function reset($token)
    {
        $user = User::where('remember_token', '=', $token)->first();

        if (!empty($user)) {

            $data['user'] = $user;
            return view('auth.resetpassword', compact('data'));
        } else {
            return redirect()->back()->with('error', 'Unknown error occured');
        }
    }



    public function post_reset($token, Request $request)
    {

        $newpassword = $request->password;
        $confirmpassword = $request->password_confirmation;
        $user = User::where('remember_token', '=', $token)->first();

        //Throw error if the token does not match in the db

        if ($user->remember_token != $token) {
            return redirect()->redirect("forget.password")->with('error', 'Your reset password token has expired. Please resend the reset password email');
        }

        if (!empty($user)) {

            //Check to see if the password and the confirmation password is the same
            if ($newpassword == $confirmpassword) {
                $user->password = Hash::make($newpassword);
                $user->remember_token = Str::random(40);
                $user->save();
                return redirect()->route('login')->with('success', 'You have successfully reset your password. You can proceed to login');
            } else {
                return redirect()->back()->with('error', 'The password and the confirmation password is not correct. Please re-enter');
            }
        } else {
            return redirect()->back()->with('error', 'Unknown error occured');
        }
    }


    //Reset Password for the Admin 
    //Route: /settings/users/reset-password
    //This function allows the Super Admin to reset the password for any users. 
    //It resets the password for the user in the database to password123
    public function resetpassword($id)
    {
        //Find the user
        $user = User::find($id);

        if (!$user) {
            return "User not found";
        }

        $user->password = bcrypt("password123");
        $user->save();
        $user->refresh();

        return back()->with('success', 'Password reset successfully.');
    }

    public function restore($id)
    {
        // Find the soft-deleted user
        $user = User::withTrashed()->find($id);

        // Check if the user exists
        if (!$user) {
            return 'User not found';
        }

        // Restore the soft-deleted user
        $user->restore();

        return back()->with('success', 'User was restored.');
    }

    public function changepasswordMe()
    {
        return view('auth.changepassword');
    }

    public function store(Request $request)
    {
        $incomingFields = $request->validate([
            "password" => "required",
            "confirm_password" => "required|same:password"
        ]);

        $password = $incomingFields['password'];

        // Find user in the database
        $user = User::find(auth()->user()->id);

        if (!empty($user)) {
            // Change the user password
            $user->password = Hash::make($password);
            $user->save();

            // return redirect()->route("dashboard")->with("success", "Your password was reset successfully");
            return view('dashboard.dashboard')->with('success', 'Your password was reset successfully');

        } else {
            return redirect()->back()->with('error', "Unable to find user");
        }
    }

    public function createuser()
    {
        //Role 2 which is an admin will not be able to add a superadmin
      
            // $roles = Role::where('name', '!=', 'Super Admin')->get();
            // dd($roles);
            // return view('users.create', compact('roles'));
        // } else {
            $roles = DB::table('roles')->get();
            //dd($roles);
            return view('users.create', compact('roles'));
        //}
    }

    public function addUser(Request $request)

    {
        //Only the superadmin and admins can add users
        // if (in_array($request->user()->role_id,[3,4,5,6,7,8,9,10])) {
        //     return redirect()->route('user.create')->with('error', 'You are not authorized to perform this action.');
        // }

        $incomingFields = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'facility_id' => 'required',
            'telephone' => 'required',
            'email' => 'required|email|unique:users',
            'role_id' => 'required',

        ]);

        //dd($incomingFields);

        $incomingFields['status'] = 1;
        $incomingFields['password'] = bcrypt('password123');
        $incomingFields['last_seen'] = date('Y-m-d H:i:s');
        $user = User::create($incomingFields);

        //dd($user);

        if (!$user) {
            return redirect()->route('user.index')->with('error', 'Unable to add the user');
        }

        return redirect()->route('users')->with('success', 'User was added');
    }

   public function switchFacility(Request $request){
        return view("users.switch_facility");
        
   }
}
