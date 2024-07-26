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
                "dateofbirth" => "required|date"
        ]);  
        
        
        try {
            $applicant = PermitApplication::with('permitCategory', 'payment','establishmentClinics', 'signOffs', 'testResults', 'healthInterviews.healthInterviewSymptom.symptoms', 'appointment.editTransactions','messages')
                ->where('firstname', 'LIKE', '%' . $incomingFields['firstname'] . '%')
                ->where('lastname', 'LIKE', '%' . $incomingFields['lastname'] . '%')
                ->where('date_of_birth',$incomingFields['dateofbirth'])
                // ->where('trn',$incomingFields['trn'])
                ->first();

            if (!$applicant) {
                return response()->json(
                    ['message' => 'No applications found.']
                    , 404);
            }

            return response()->json($applicant);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching applications.', 'error' => $e->getMessage()], 500);
        }
    }
}
