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
            'expires_at'   => now()->addMinutes(5),
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        $url = URL::temporarySignedRoute(
            'verify.certificate',
            now()->addMinutes(5),
            ['token' => $token]
        );

        $expiry = optional($applicant->signOffs)->expiry_date;

        $isExpired = $expiry
            ? Carbon::now()->gt(Carbon::parse($expiry))
            : false;

        session([
            'verified_permit_id' => $applicant->id,
            'verified_permit_hash' => hash_hmac(
                'sha256',
                $applicant->permit_no . $applicant->date_of_birth,
                config('app.key')
            ),
            'permit_is_expired' => $isExpired,
        ]);

        return redirect($url);
    }

    public function showCertificate(Request $request, $token)
    {
        $record = DB::table('verification_tokens')
            ->where('token', $token)
            ->first();

        if (!$record) {
            abort(403, 'Invalid verification link.');
        }


        if (now()->gt(Carbon::parse($record->expires_at))) {
            abort(403, 'Verification link expired.');
        }

        $applicant = PermitApplication::with(
            'permitCategory',
            'payment',
            'establishmentClinics',
            'signOffs',
            'testResults',
            'healthInterviews.healthInterviewSymptom.symptoms',
            'appointment.editTransactions',
            'messages'
        )->findOrFail($record->permit_application_id);

        $expiry = optional($applicant->signOffs)->expiry_date;

        $isExpired = $expiry
            ? now()->gt(Carbon::parse($expiry))
            : false;

        return response()
            ->view('verify.certificate', compact('applicant', 'isExpired'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');
    }

    public function downloadCertificate($id)
    {

        if (!session()->has('verified_permit_id') || !session()->has('verified_permit_hash')) {
            abort(403, 'Session not verified.');
        }


        if (session('verified_permit_id') != $id) {
            abort(403, 'Permit mismatch.');
        }


        $applicant = PermitApplication::with([
            'permitCategory',
            'establishmentClinics',
            'signOffs',
            'testResults'
        ])->findOrFail($id);


        $expectedHash = hash_hmac(
            'sha256',
            $applicant->permit_no . $applicant->date_of_birth,
            config('app.key')
        );

        if (!hash_equals(session('verified_permit_hash'), $expectedHash)) {
            abort(403, 'Security validation failed.');
        }


        if ($applicant->signOffs && now()->gt($applicant->signOffs->expiry_date)) {
            abort(403, 'Expired permits cannot be downloaded.');
        }

        // 6️⃣ Generate PDF
        $pdf = Pdf::loadView('verify.certificate', compact('applicant'))
            ->setPaper('A4', 'portrait');

        // 7️⃣ Force download (no browser caching)
        return $pdf->download(
            'Food_Handlers_Permit_' . $applicant->permit_no . '.pdf'
        )->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    public function generateLink($permitNo)
    {
        $applicant = PermitApplication::with(
            'permitCategory',
            'payment',
            'establishmentClinics',
            'signOffs',
            'testResults',
            'healthInterviews.healthInterviewSymptom.symptoms',
            'appointment.editTransactions',
            'messages'
        )->where('permit_no', $permitNo)->first();

        if (!$applicant) {
            return response()->json(['message' => 'Permit not found'], 404);
        }

        $token = hash('sha256', Str::random(120));

        DB::table('verification_tokens')->insert([
            'permit_application_id' => $applicant->id,
            'token'        => $token,
            'ip_address'   => request()->ip(),
            'user_agent'   => request()->userAgent(),
            'expires_at'   => now()->addMinutes(5),
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
        $url = URL::temporarySignedRoute(
            'verify.certificate',
            now()->addMinutes(10),
            ['token' => $token]
        );

        return response()->json([
            'verify_url' => $url
        ]);
    }
}
