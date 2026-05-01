<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PermitApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PermitApplicationApi extends Controller
{

public function index(){
    return view('verify.index');
}
    public function fetchApplications($permit_no)
    {
        try {
            $applicant = PermitApplication::with('permitCategory', 'payment', 'establishmentClinics', 'signOffs', 'testResults', 'healthInterviews.healthInterviewSymptom.symptoms', 'appointment.editTransactions', 'messages')
                ->where('permit_no', $permit_no)->first();

            //    $filePath = 'public/' . $applicant['permit_no'];

            //    $fileContent = Storage::get($filePath);
            //    $mimeType = Storage::mimeType($filePath);

            if (!$applicant) {
                return response()->json(
                    ['message' => 'No applications found.'],
                    404
                );
            }

            //$token = $applicant->createToken('PHCMS')->plainTextToken;

            return response()->json([
                "status" => "success",
                "applicant" => $applicant,
                // "token" => $token,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching applications.', 'error' => $e->getMessage()], 500);
        }
    }

    public function verifyPermit($permit_no)
    {
        try {

            $applicant = PermitApplication::with('permitCategory', 'signOffs')
                ->where('permit_no', $permit_no)
                ->first();

            if (!$applicant) {
                return response()->json([
                    "status" => "invalid",
                    "message" => "Permit not found"
                ], 404);
            }

            // If not signed off yet → not valid
            if (!$applicant->signOffs) {
                return response()->json([
                    "status" => "pending",
                    "message" => "Permit not yet approved"
                ], 200);
            }

            $expiry = $applicant->signOffs->expiry_date;
            $isExpired = now()->gt($expiry);

            return response()->json([
                "status" => $isExpired ? "expired" : "valid",

                "permit_no" => $applicant->permit_no,

                "name" => trim(
                    $applicant->firstname . " " . $applicant->lastname
                ),

                "category" => $applicant->permitCategory->name ?? "N/A",

                "issued_date" => $applicant->signOffs->sign_off_date,
                "expiry_date" => $expiry,

                "photo" => $applicant->photo_upload
                    ? asset("storage/" . $applicant->photo_upload)
                    : null,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                "status" => "error",
                "message" => "Verification failed"
            ], 500);
        }
    }
}
