<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PermitApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PermitApplicationApi extends Controller
{
    public function fetchApplications($permit_no)
    {
        try {
            $applicant = PermitApplication::with('permitCategory', 'payment','establishmentClinics', 'signOffs', 'testResults', 'healthInterviews.healthInterviewSymptom.symptoms', 'appointment.editTransactions','messages')
               ->where('permit_no',$permit_no)->first();

            //    $filePath = 'public/' . $applicant['permit_no'];

            //    $fileContent = Storage::get($filePath);
            //    $mimeType = Storage::mimeType($filePath);

            if (!$applicant) {
                return response()->json(
                    ['message' => 'No applications found.']
                    , 404);
            }

            //$token = $applicant->createToken('PHCMS')->plainTextToken;

            return response()->json([
                "status" => "success",
                "applicant" => $applicant,
                // "token" => $token,
            ],200);

            
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching applications.', 'error' => $e->getMessage()], 500);
        }
    }
}


