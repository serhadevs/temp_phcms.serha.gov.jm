<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PermitApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PermitApplicationApi extends Controller
{

    public function index()
    {
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

    public function permitRetrieval(Request $request)
    {
        try {
            // 1. Validate all required fields at once
            $validated = $request->validate([
                'firstname' => 'required|string',
                'lastname'  => 'required|string',
                'date_of_birth'   => 'required|date',
                'permit_no' => 'required|string',
            ]);

            // 2. Query the database matching ALL four fields
            $applicant = PermitApplication::with([
                'permitCategory',
                'payment',
                'establishmentClinics',
                'signOffs',
                'testResults',
                'healthInterviews.healthInterviewSymptom.symptoms',
                'appointment.editTransactions',
                'messages'
            ])
                ->where('firstname', $validated['firstname'])
                ->where('lastname', $validated['lastname'])
                ->where('date_of_birth', $validated['date_of_birth'])
                ->where('permit_no', $validated['permit_no'])
                ->first();

            // 3. Handle Not Found
            // if (!$applicant) {
            //     return response()->json([
            //         'message' => 'No application found for the provided details.',
            //         'status'  => 'not_found'
            //     ], 404);
            // }

            return view('verify.certificate', compact('applicant'));

            // 4. Handle Success
            // return response()->json([
            //     'status'    => 'success',
            //     'applicant' => $applicant,
            // ], 200);
        } catch (ValidationException $e) {
            // return response()->json([
            //     'message' => 'Validation failed',
            //     'errors'  => $e->errors(),
            //     'status'  => 'validation_error'
            // ], 422);
        } catch (Exception $e) {
            // return response()->json([
            //     'message' => 'An error occurred during permit retrieval.',
            //     'error'   => $e->getMessage(),
            //     'status'  => 'error'
            // ], 500);
        }
    }

    public function downloadCertificate($id)
    {
        // Find the applicant using the ID passed from the frontend
        $applicant = PermitApplication::with([
            'permitCategory',
            'establishmentClinics',
            'signOffs'
        ])->findOrFail($id);

        // Generate the PDF from the blade view
        $pdf = Pdf::loadView('verify.certificate', compact('applicant'));

        // Download the file
        return $pdf->download('Permit_Confirmation_' . $applicant->permit_no . '.pdf');
    }
}
