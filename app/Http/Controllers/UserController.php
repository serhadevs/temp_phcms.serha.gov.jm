<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {

        if (Auth::user()->role_id == 1) {
            $users = DB::table('users')->get();
        } elseif (Auth::user()->role_id == 2) {
          $users = User::where('facility_id', Auth::user()->facility_id)->get();
        }


        return view('users.index', compact('users'));
    }




    public function store(Request $request)
    {
    }

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
}
