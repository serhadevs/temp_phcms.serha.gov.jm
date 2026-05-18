<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class SwitchFacilityController extends Controller
{
    public function index(){
        Log::channel('systemOperations')->info('Loading switch facility form', ['user_id' => auth()->user()->id]);
        return view('switchfacility.create');
    }

    public function update(Request $request)
    {
        Log::channel('systemOperations')->info('Updating facility', ['user_id' => auth()->user()->id]);
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
            Log::channel('systemOperations')->error('Failed to update facility: ' . $e->getMessage(), ['user_id' => auth()->user()->id]);
            // Handle any exceptions that occur during the update process
            return back()->with('error', 'An error occurred while updating location: ' . $e->getMessage());
        }  catch (QueryException $e){
            Log::channel('systemOperations')->error('Failed to update facility: ' . $e->getMessage(), ['user_id' => auth()->user()->id]);
            return redirect()->with('error', 'Unable to fetch data from the database!', $e->getMessage());
        }
    }
    
}
