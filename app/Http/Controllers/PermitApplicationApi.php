<?php

namespace App\Http\Controllers;

use App\Models\PermitApplication;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PermitApplicationApi extends Controller
{
    public function fetchApplications(Request $request)
    {

        $incomingFields = $request->validate([
                "firstname" => "required | string",
                "lastname" => "required | string",
                // "trn" => "required|string",
        ]);  
        
        
        try {
            $applications = PermitApplication::with('permitCategory', 'payment','testResults')
                // ->where('trn', $incomingFields['trn'])
                ->where('firstname', 'LIKE', '%' . $incomingFields['firstname'] . '%')
                ->where('lastname', 'LIKE', '%' . $incomingFields['lastname'] . '%')
                ->latest()->get();

            if ($applications->isEmpty()) {
                return response()->json(['message' => 'No applications found after the specified date.'], 404);
            }

            return response()->json($applications);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching applications.', 'error' => $e->getMessage()], 500);
        }
    }
}
