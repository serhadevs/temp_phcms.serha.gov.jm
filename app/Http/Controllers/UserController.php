<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\SendCredentialEmail;
use App\Jobs\SendResetPasswordEmail;
use App\Mail\ForgetPasswordMail;
use App\Mail\SendCredentials;
use App\Models\Facility;
use App\Models\LoginActivity;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class UserController extends Controller
{

    //Show Users that are currently registered on the system
    //Shows currently logged in users
    //Route: /settings/users

    private function getUsers($facility_id = null, $role_id = null)
    {
        $query = User::where('status', 1)
            ->whereNull('deleted_at');

        if ($facility_id) {
            $query->where('facility_id', $facility_id);
        }

        if ($role_id) {
            $query->where('role_id', $role_id);
        }


        return $query->latest('created_at')
            ->get([
                'last_seen',
                'id',
                'firstname',
                'lastname',
                'facility_id',
                'role_id',
                'telephone',
                'email',
                'status',
                'created_at',
                'updated_at'
            ]);
    }

    public function index()
    {

        $roles = [1,8];
        if (in_array(Auth::user()->role_id,$roles)) {

            $users = $this->getUsers();

            // $users = User::where('status', 1)->latest('created_at')->get();
            // dd($users->take(5));
            $facilities = Facility::all();
            $roles = DB::table('roles')->pluck('name', 'id');
            //dd($roles);
        } elseif (Auth::user()->role_id == 2) {
            // $users = User::where('facility_id', Auth::user()->facility_id)->get();
            $users = $this->getUsers();
            $roles = DB::table('roles')->pluck('name', 'id');
            //dd($roles);
        }



        return view('users.index', compact('users', 'facilities', 'roles'));
    }

    //Filter Users By Facility
    //Route: /settings/users/filter
    //This function filters the users by facility

    public function filterUsers(Request $request)
    {

        $facility_id = $request->facility_id;
        //dd($facility_id);
        $role_id = $request->role_id;
        $users = $this->getUsers($facility_id, $role_id);
        $facilities = Facility::all();
        $roles = DB::table('roles')->pluck('name', 'id');

        return view('users.index', compact('users', 'facilities', 'roles'));
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
        return view('users.loggedusers', compact('loginUsers', 'loginUsersCount', 'ksaCount', 'sttCount', 'stcCount'));
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

        //dd($email);

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

        $user->password = Hash::make("password123");
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
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.confirmed' => 'The password confirmation does not match.',
        ]);

        $user = User::find(auth()->id()); // Ensures it's a proper Eloquent model

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        // Prevent reuse of old password
        if (Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Your new password cannot be the same as your current password.');
        }

        // Update password and timestamp
        $user->password = Hash::make($request->password);
        $user->password_changed_at = now();
        $user->save();

        return redirect()
            ->route('dashboard.dashboard')
            ->with('success', 'Your password was reset successfully.');
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
        $incomingFields = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'facility_id' => 'required',
            'telephone' => 'required',
            'email' => 'required|email|unique:users',
            'role_id' => 'required',
        ]);

        // Set additional fields
        $incomingFields['status'] = 1;

        // Make password a random string of 8 characters
        $stringPassword = Str::random(8);

        $incomingFields['password'] = bcrypt($stringPassword);
        $incomingFields['last_seen'] = date('Y-m-d H:i:s');

        // Create the user
        $user = User::create($incomingFields);

        if (!$user) {
            return redirect()->route('user.index')->with('error', 'Unable to add the user');
        }

        // Send Email to the newly created user (use $user instead of querying again)
        dispatch(new SendCredentialEmail($user, $stringPassword));

        return redirect()->route('users')->with('success', 'User was added');
    }

    public function switchFacility(Request $request)
    {
        return view("users.switch_facility");
    }

    public function viewEditForm($id)
    {

        //Check to see if the user is authorized to edit an application

        if (!in_array(auth()->user()->role_id, [1, 2])) {
            return redirect()->back()->with('error', 'You are not authorized to make edits to users');
        }

        //Define in an array what you want from the database

        $userData = ['users.id', 'firstname', 'lastname', 'facility_id', 'role_id', 'telephone', 'email', 'status', 'roles.name'];
        //Find the user in the database

        $user = User::join('roles', 'roles.id', '=', 'users.role_id')->where('users.id', $id)->select($userData)->first();
        $roles = DB::table('roles')->where('name', '!=', 'Super Admin')->get();
        //dd($roles);
        //Throw error if the user is not found in the database

        if (!$user) {
            return redirect()->back()->with('error', 'There is no user found in the database');
        }

        return view('users.edit', compact('user', 'roles'));
    }

    public function editUser(Request $request, $id)
    {
        //Get the values from the request 

        $validatedData = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'facility_id' => 'required',
            'telephone' => 'required',
            'email' => 'required',
            'role_id' => 'required',
        ]);

        try {
            //Find the user in the database

            $user = User::findOrFirst($id);

            //Throw error if the user is not found

            if (!$user) {
                return redirect()->back()->with('error', 'The user was not found in the database');
            }

            //Update changed password_changed_at 
            $user->password_changed_at = now();

            //Update the fields 
            $user_update = User::where('id', $request->id)->update($validatedData);

            if ($user_update) {
                return redirect()->route('users')->with(['success' => 'The user was updated successfully']);
            }

            //Error if the user update is not valid
            return redirect()->route('users')->with(['error' => 'Error updating record or nothing to update']);
        } catch (\Exception $e) {
            return redirect()->route('users')->with(['error' => 'Exception Error:' . $e->getMessage()]);
        } catch (QueryException $e) {
            return redirect()->route('users')->with(['error' => 'Query Exception:' . $e->getMessage()]);
        }
    }

    // public function destroy(Request $request,$id)
    // {
    //     //Find the user in the database
    //     $user_id = $request->user_id;
    //     $user = User::find($user_id);

    //     //dd($user);
    //     //Throw error if there is no results from the query 
    //     if (!$user) {
    //         return redirect()->back()->with('error', 'Unable to find the user in the database');
    //     }

    //     $newStatus = $user->status === "1" ? "0" : "1";
    //     User::where('id', $user->id)->update(['status' => $newStatus]);


    //     if (!$newStatus) {
    //         return redirect()->back()->with('error', 'Unable to change status in the database');
    //     }

    //     return redirect()->back()->with("success", "Success!");
    // }

    public function deactivateUser($id)
    {
        // Find the user in the database by ID
        $user = User::find($id);

        // Throw error if the user is not found
        if (!$user) {
            return redirect()->back()->with('error', 'Unable to find the user in the database');
        }

        // Check if the user's status is already 0
        if ($user->status === "0") {
            return redirect()->back()->with('error', 'User is already deactivated');
        }

        // Update the user's status to 0
        $updateResult = User::where('id', $user->id)->update(['status' => "0"]);

        // Check if the update was successful
        if (!$updateResult) {
            return redirect()->back()->with('error', 'Unable to deactivate user in the database');
        }

        return redirect()->back()->with("success", "User successfully deactivated");
    }

    //Reset All Passwords
    //Route: settings/reset-password/all'
    //This function allows the Super Admin to set the password_changed_at field to the current date. 
    public function resetAllPasswords(Request $request)
{
    $validatedData = $request->validate([
        'password' => 'required|string',
        'password_changed_at' => 'required|date',
    ]);

    // Verify that the password entered matches the current user's password
    if (!Hash::check($validatedData['password'], Auth::user()->password)) {
        return redirect()->back()
            ->withErrors(['password' => 'The password you entered is incorrect. Please try again.'])
            ->withInput();
    }

    try {
        // Update the password_changed_at field for all active users except the current user
        $updateResult = User::where('id', '!=', Auth::id())
            ->where('status', 1)
            ->update(['password_changed_at' => Carbon::parse($validatedData['password_changed_at'])]);

        if ($updateResult === 0) {
            return redirect()->back()->with('info', 'No active users found to update.');
        }

        return redirect()->back()->with('success', 'All user passwords have been reset successfully. ' . $updateResult . ' user(s) updated.');
        
    } catch (\Exception $e) {
        Log::error('Password reset error: ' . $e->getMessage());
        return redirect()->back()
            ->withErrors(['password' => 'An error occurred while resetting passwords. Please try again.'])
            ->withInput();
    }
}
}
