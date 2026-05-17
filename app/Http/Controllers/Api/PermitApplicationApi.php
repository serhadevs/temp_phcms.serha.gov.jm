<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PermitApplication;
use App\Models\SignOff;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PermitApplicationApi extends Controller
{

    public function index()
    {
        return view('verify.index');
    }
    public function fetchApplications($permit_no)
    {
        try {
            $applicant = PermitApplication::with(
                'permitCategory:id,name',
                'payment',
                'establishmentClinics',
                'signOffs',
                'testResults',
                'healthInterviews.healthInterviewSymptom.symptoms',
                'appointment',
                'messages'
            )
                ->where('permit_no', $permit_no)->first();

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

    // public function verifyPermit($permit_no)
    // {
    //     try {

    //         // $applicant = PermitApplication::with('permitCategory', 'signOffs')
    //         //     ->where('permit_no', $permit_no)
    //         //     ->first();

    //         $applicant = PermitApplication::with(
    //         'permitCategory',
    //         'payment',
    //         'establishmentClinics',
    //         'signOffs',
    //         'testResults',
    //         'healthInterviews.healthInterviewSymptom.symptoms',
    //         'appointment.editTransactions',
    //         'messages'
    //     )->where('permit_no', $permit_no)->first();

    //         if (!$applicant) {
    //             Log::warning('Permit not found', [
    //                 'permit_no' => $permit_no,
    //                 'ip' => request()->ip(),
    //             ]);

    //             return response()->json([
    //                 "success" => false,
    //                 "message" => "Permit not found"
    //             ], 404);
    //         }


    //         // Check if permit is expired
    //         $expiry = optional($applicant->signOffs)->expiry_date;
    //         $isExpired = $expiry
    //             ? Carbon::now()->gt(Carbon::parse($expiry))
    //             : false;

    //         if ($isExpired) {
    //             Log::info('Expired permit verification attempted', [
    //                 'permit_no' => $permit_no,
    //                 'expiry_date' => $expiry,
    //                 'ip' => request()->ip(),
    //             ]);

    //             return response()->json([
    //                 "success" => false,
    //                 "message" => "This permit has expired",
    //                 "expiry_date" => Carbon::parse($expiry)->format('d M Y'),
    //             ], 410);  
    //         }

    //         $signOff = $applicant->signOffs;

    //         // Track API verification access
    //         $signOff->trackAccess(
    //             'viewed',
    //             'api_qr_scan',
    //             request()
    //         );

    //         // Generate secure token using cryptographically random bytes
    //         $token = bin2hex(random_bytes(32));
    //         $tokenHash = hash('sha256', $token);

    //         // Insert token into database
    //         DB::table('verification_tokens')->insert([
    //             'permit_application_id' => $applicant->id,
    //             'token_hash'           => $tokenHash,
    //             'ip_address'           => request()->ip(),
    //             'user_agent'           => request()->userAgent(),
    //             'expires_at'           => now()->addMinutes(5),
    //             'used'                 => false,
    //             'created_at'           => now(),
    //             'updated_at'           => now(),
    //         ]);

    //         // Create temporary signed URL with token (not hash)
    //         $url = URL::temporarySignedRoute(
    //             'verify.certificate',
    //             now()->addMinutes(5),
    //             ['token' => $token]
    //         );

    //         // Store verification in session
    //         session([
    //             'verified_permit_id' => $applicant->id,
    //             'verified_permit_hash' => hash_hmac(
    //                 'sha256',
    //                 $applicant->permit_no . $applicant->date_of_birth,
    //                 config('app.key')
    //             ),
    //             'permit_is_expired' => $isExpired,
    //         ]);

    //         Log::info('Permit verified successfully', [
    //             'permit_no' => $permit_no,
    //             'permit_id' => $applicant->id,
    //             'ip' => request()->ip(),
    //         ]);



    //         return redirect($url);
    //     } catch (\Throwable $e) {
    //         return response()->json([
    //             "status" => "error",
    //             "message" => "Verification failed"
    //         ], 500);
    //     }
    // }

    public function verifyPermit($permit_no)
{
    try {

        $applicant = PermitApplication::with(
            'permitCategory',
            'payment',
            'establishmentClinics',
            'signOffs',
            'testResults',
            'healthInterviews.healthInterviewSymptom.symptoms',
            'appointment.editTransactions',
            'messages'
        )
        ->where('permit_no', $permit_no)
        ->first();

        if (!$applicant) {
            Log::warning('Permit not found', [
                'permit_no' => $permit_no,
                'ip' => request()->ip(),
            ]);

            return response()->json([
                "success" => false,
                "message" => "Permit not found"
            ], 404);
        }

        /**
         * Sign-off is OPTIONAL
         * Never let it control the flow
         */
        $signOff = $applicant->signOffs;

        // Safe expiry check (even if no signoff exists)
        $expiry = optional($signOff)->expiry_date;

        $isExpired = $expiry
            ? now()->gt(Carbon::parse($expiry))
            : false;

        if ($isExpired) {
            Log::info('Expired permit verification attempted', [
                'permit_no' => $permit_no,
                'expiry_date' => $expiry,
                'ip' => request()->ip(),
            ]);

            return response()->json([
                "success" => false,
                "message" => "This permit has expired",
                "expiry_date" => Carbon::parse($expiry)->format('d M Y'),
            ], 410);
        }

        /**
         * OPTIONAL tracking only (never break flow)
         */
        if ($signOff) {
            $signOff->trackAccess(
                'viewed',
                'api_qr_scan',
                request()
            );
        }

        /**
         * ALWAYS generate token (signed off or not)
         */
        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);

        DB::table('verification_tokens')->insert([
            'permit_application_id' => $applicant->id,
            'token_hash'            => $tokenHash,
            'ip_address'            => request()->ip(),
            'user_agent'            => request()->userAgent(),
            'expires_at'            => now()->addMinutes(5),
            'used'                  => false,
            'created_at'            => now(),
            'updated_at'            => now(),
        ]);

        $url = URL::temporarySignedRoute(
            'verify.certificate',
            now()->addMinutes(5),
            ['token' => $token]
        );

        session([
            'verified_permit_id' => $applicant->id,
            'verified_permit_hash' => hash_hmac(
                'sha256',
                $applicant->permit_no . $applicant->date_of_birth,
                config('app.key')
            ),
            'permit_is_expired' => $isExpired,
            'permit_has_signoff' => !is_null($signOff),
        ]);

        Log::info('Permit verification successful', [
            'permit_no' => $permit_no,
            'permit_id' => $applicant->id,
            'has_signoff' => !is_null($signOff),
            'ip' => request()->ip(),
        ]);

        /**
         * ALWAYS redirect
         */
        return redirect($url);

    } catch (\Throwable $e) {

        Log::error('Permit verification failed', [
            'permit_no' => $permit_no,
            'error' => $e->getMessage(),
            'ip' => request()->ip(),
        ]);

        return response()->json([
            "status" => "error",
            "message" => "Verification failed"
        ], 500);
    }
}


    public function retrievePermit(Request $request)
    {
        $validated = $request->validate(
            [
                'firstname'     => 'required|string',
                'lastname'      => 'required|string',
                'date_of_birth' => 'required|date',
                'permit_no'     => 'required|string',
            ],
            [
                'firstname.required' => 'Please enter your first name.',
                'lastname.required' => 'Please enter your last name.',
                'date_of_birth.required' => 'Please select your date of birth.',
                'date_of_birth.date' => 'Please enter a valid date of birth.',
                'permit_no.required' => 'Please enter your permit number.',
            ],
            [
                'firstname'     => 'First Name',
                'lastname'      => 'Last Name',
                'date_of_birth' => 'Date of Birth',
                'permit_no'     => 'Permit Number',
            ]
        );

        $firstname = strtolower(trim($validated['firstname']));
        $lastname = strtolower(trim($validated['lastname']));
        $dob = $validated['date_of_birth'];
        $permitNo = strtoupper(trim($validated['permit_no']));

        DB::table('retrieval_attempts')->insert([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'date_of_birth' => $dob,
            'permit_no' => $permitNo,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'success' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        // $applicant = PermitApplication::where($validated)->first();

        $applicant = PermitApplication::whereRaw('LOWER(firstname) = ?', [$firstname])
            ->whereRaw('LOWER(lastname) = ?', [$lastname])
            ->whereDate('date_of_birth', $dob)
            ->whereRaw('UPPER(permit_no) = ?', [$permitNo])
            ->first();

        if (!$applicant) {
            Log::warning('Permit retrieval failed - not found', [
                'permit_no' => $permitNo,
                'ip' => $request->ip(),
            ]);

            // Don't reveal if permit exists or not (security)
            return back()->withErrors([
                'not_found' => 'No application found. Please check your details and try again.'
            ]);
        }

        $signOff = SignOff::where('application_id', $applicant->id)
            ->where('is_granted', 1)
            ->latest()
            ->first();

        $permitStatus = 'not_signed_off';
        $expiry = null;
        $isExpired = false;

        if ($signOff) {
            $expiry = $signOff->expiry_date;
            $isExpired = $expiry
                ? now()->gt(Carbon::parse($expiry))
                : false;

            $permitStatus = $isExpired ? 'expired' : 'valid';

            // Track access ONLY if signed off
            $signOff->trackAccess(
                'viewed',
                'web_portal_form',
                $request
            );
        }

        DB::table('retrieval_attempts')
            ->where('ip_address', $request->ip())
            ->where('created_at', '>', now()->subSeconds(10))
            ->update(['success' => true]);

        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);

        DB::table('verification_tokens')->insert([
            'permit_application_id' => $applicant->id,
            'token_hash'        => $tokenHash,
            'ip_address'   => request()->ip(),
            'user_agent'   => request()->userAgent(),
            'expires_at'   => now()->addMinutes(5),
            'created_at'   => now(),
            'updated_at'   => now(),
            'used' => false
        ]);

        $url = URL::temporarySignedRoute(
            'verify.certificate',
            now()->addMinutes(5),
            ['token' => $token]
        );

        Log::info('Permit retrieved successfully', [
            'permit_id' => $applicant->id,
            'permit_no' => $applicant->permit_no,
            'ip' => $request->ip(),
        ]);
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
            'permit_status' => $permitStatus,
            'permit_is_expired' => $isExpired,
        ]);

        return redirect($url);
    }

    public function showCertificate(Request $request, $token)
    {
        $tokenHash = hash('sha256', $token);

        $record = DB::table('verification_tokens')
            ->where('token_hash', $tokenHash)
            ->first();

        if (!$record) {
            abort(403, 'Invalid verification link.');
        }


        if (now()->gt(Carbon::parse($record->expires_at))) {
            abort(403, 'Verification link expired.');
        }

        DB::table('verification_tokens')
            ->where('token_hash', $tokenHash)
            ->update(['used' => true, 'used_at' => now()]);

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
            ->view('verify.certificate', compact('applicant', 'isExpired', 'token'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');
    }



    public function downloadCertificate($id)
    {
        $applicant = PermitApplication::with([
            'permitCategory',
            'signOffs',
            'testResults'
        ])->findOrFail($id);

        // Check if expired
        if ($applicant->signOffs && now()->gt($applicant->signOffs->expiry_date)) {
            Log::warning('Expired permit download attempted', [
                'permit_id' => $id,
                'ip' => request()->ip(),
            ]);
            abort(403, 'Expired permits cannot be downloaded.');
        }

        $signOff = $applicant->signOffs;

        // Track download
        $signOff->trackAccess(
            'downloaded',
            'web_portal_download',
            request()
        );

        // Generate QR code
        $qrUrl = url('/api/verify-permit/' . $applicant->permit_no);
        $qrImage = base64_encode(
            QrCode::format('png')
                ->size(160)
                ->margin(1)
                ->generate($qrUrl)
        );

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('verify.permitCardPdf', [
            'applicant' => $applicant,
            'qrImage' => $qrImage,
        ])->setPaper('A4');

        Log::info('Certificate downloaded', [
            'permit_id' => $applicant->id,
            'permit_no' => $applicant->permit_no,
            'ip' => request()->ip(),
        ]);

        return $pdf->download('Food_Handlers_Permit_' . $applicant->permit_no . '.pdf')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
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

    public function qrVerify(Request $request)
    {
        // Validate query string from QR
        $data = $request->validate([
            'firstname'     => 'required|string',
            'lastname'      => 'required|string',
            'date_of_birth' => 'required|date',
            'permit_no'     => 'required|string',
        ]);

        // Call your existing POST API internally
        $response = Http::post(url('/api/verify-permit/retrieve'), $data);

        if (!$response->successful()) {
            abort(404, 'Permit not found');
        }

        $token = $response->json()['token'];

        return redirect("/verify-permit/certificate/$token");
    }
}
