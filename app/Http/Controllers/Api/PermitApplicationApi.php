<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PermitApplication;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

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

    // public function permitRetrieval(Request $request)
    // {
    //     try {

    //         //Validate
    //         $validated = $request->validate([
    //             'firstname'      => 'required|string',
    //             'lastname'       => 'required|string',
    //             'date_of_birth'  => 'required|date',
    //             'permit_no'      => 'required|string',
    //         ]);

    //         //Find applicant
    //         $applicant = PermitApplication::with([
    //             'permitCategory',
    //             'payment',
    //             'establishmentClinics',
    //             'signOffs',
    //             'testResults',
    //             'healthInterviews.healthInterviewSymptom.symptoms',
    //             'appointment.editTransactions',
    //             'messages'
    //         ])
    //             // ->where('firstname', $validated['firstname'])
    //             // ->where('lastname', $validated['lastname'])
    //             // ->whereDate('date_of_birth', $validated['date_of_birth'])
    //             // ->where('permit_no', $validated['permit_no'])
    //              ->where($validated)
    //             ->first();


    //         if (!$applicant) {
    //             return back()
    //                 ->withInput()
    //                 ->withErrors([
    //                     'not_found' => 'No application found with the provided details.'
    //                 ]);
    //         }


    //         $expiry = optional($applicant->signOffs)->expiry_date;
    //         $isExpired = $expiry ? Carbon::now()->gt(Carbon::parse($expiry)) : false;


    //         return view('verify.certificate', compact('applicant', 'isExpired'));
    //     } catch (ValidationException $e) {

    //         // Validation automatically redirects back, but we log it too
    //         Log::warning('Permit Retrieval Validation Failed', [
    //             'errors' => $e->errors(),
    //             'input'  => $request->all()
    //         ]);

    //         throw $e; // important: let Laravel handle redirect

    //     } catch (Exception $e) {

    //         // Log full error for debugging
    //         Log::error('Permit Retrieval Failed', [
    //             'message' => $e->getMessage(),
    //             'trace'   => $e->getTraceAsString(),
    //             'input'   => $request->all()
    //         ]);

    //         return back()
    //             ->withInput()
    //             ->with('error', 'An unexpected error occurred. Please try again.');
    //     }
    // }
    public function retrievePermit(Request $request)
    {
        $validated = $request->validate([
            'firstname'      => 'required|string',
            'lastname'       => 'required|string',
            'date_of_birth'  => 'required|date',
            'permit_no'      => 'required|string',
        ]);

        $applicant = PermitApplication::where($validated)->first();

        if (!$applicant) {
            return back()->withErrors([
                'not_found' => 'No application found.'
            ]);
        }

        $token = hash('sha256', Str::random(120));

        DB::table('verification_tokens')->insert([
            'permit_application_id' => $applicant->id,
            'token'        => $token,
            'ip_address'   => request()->ip(),
            'user_agent'   => request()->userAgent(),
            'expires_at'   => now()->addMinutes(5), // VERY SHORT LIFE
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        // 🔐 Create SIGNED URL
        $url = URL::temporarySignedRoute(
            'verify.certificate',
            now()->addMinutes(5),
            ['token' => $token]
        );

        return redirect($url);
    }

    public function showCertificate(Request $request, $token)
{
    // 1️⃣ Find token
    $record = DB::table('verification_tokens')
        ->where('token', $token)
        ->first();

    if (!$record) {
        abort(403, 'Invalid verification link.');
    }

    // 2️⃣ Expiry check
    if (now()->gt($record->expires_at)) {
        abort(403, 'Verification link expired.');
    }

    // 3️⃣ One-time use check
    if ($record->used_at !== null) {
        abort(403, 'This link was already used.');
    }

    // 4️⃣ IP binding check
    if ($record->ip_address !== $request->ip()) {
        abort(403, 'Device/IP mismatch.');
    }

    // 5️⃣ Device binding check
    if ($record->user_agent !== $request->userAgent()) {
        abort(403, 'Device mismatch.');
    }

    // 6️⃣ Mark token as USED immediately
    DB::table('verification_tokens')
        ->where('id', $record->id)
        ->update(['used_at' => now()]);

    // 7️⃣ Load applicant
    // $applicant = PermitApplication::with([
    //     'permitCategory','signOffs','testResults'
    // ])->findOrFail($record->applicant_id);

    $applicant = PermitApplication::with('permitCategory', 'payment', 'establishmentClinics', 'signOffs', 'testResults', 'healthInterviews.healthInterviewSymptom.symptoms', 'appointment.editTransactions', 'messages')
                ->findOrFail($record->permit_application_id);

    $expiry = optional($applicant->signOffs)->expiry_date;
    $isExpired = $expiry ? now()->gt($expiry) : false;

    // 8️⃣ Prevent caching completely
    return response()
        ->view('verify.certificate', compact('applicant','isExpired'))
        ->header('Cache-Control','no-store, no-cache, must-revalidate, max-age=0')
        ->header('Pragma','no-cache')
        ->header('Expires','Sat, 01 Jan 1990 00:00:00 GMT');
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
