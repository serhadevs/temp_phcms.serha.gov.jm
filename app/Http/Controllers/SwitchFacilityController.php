<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class SwitchFacilityController extends Controller
{
    public function index(){
        return view('switchfacility.create');
    }

    public function update(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            "location" => "required"
        ]);
    
        try {

            if(auth()->user()->facility_id == $validatedData['location']){
                return back()->with('error', 'You are already assigned to this location');
            }
            // Find the logged-in user in the users table
            $user = User::find(auth()->user()->id);

           
    
            // Throw an error if the user is not found
            if (!$user) {
                return back()->with('error', 'Unable to find user');
            }
    
            // Update the facility_id in the users table
            $user->facility_id = $validatedData['location'];
            $user->save();
    
            return redirect()->route('dashboard.dashboard')->with('success', 'Location updated successfully');
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the update process
            return back()->with('error', 'An error occurred while updating location: ' . $e->getMessage());
        }  catch (QueryException $e){
            return redirect()->with('error', 'Unable to fetch data from the database!', $e->getMessage());
        }
    }
    
}
