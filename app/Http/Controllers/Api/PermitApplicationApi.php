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

    //             dd($isExpired);

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

    // public function verifyPermit($permit_no)
    // {
    //     try {

    //         $applicant = PermitApplication::where('permit_no', $permit_no)->first();

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

    //         $state = $this->resolvePermitState($applicant);

    //         // optional tracking
    //         if ($state['signOff']) {
    //             $state['signOff']->trackAccess(
    //                 'viewed',
    //                 'api_qr_scan',
    //                 request()
    //             );
    //         }

    //         // Generate secure token using cryptographically random bytes
    //         $token = bin2hex(random_bytes(32));
    //         $tokenHash = hash('sha256', $token);

    //         // Store token
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

    //         // Create signed URL
    //         $url = URL::temporarySignedRoute(
    //             'verify.certificate',
    //             now()->addMinutes(5),
    //             ['token' => $token]
    //         );

    //         // Store verification in session (consistent + safe boolean)
    //         session([
    //             'verified_permit_id' => $applicant->id,
    //             'verified_permit_hash' => hash_hmac(
    //                 'sha256',
    //                 $applicant->permit_no . $applicant->date_of_birth,
    //                 config('app.key')
    //             ),
    //             'permit_status' => $state['permitStatus'],
    //             'permit_is_expired' => $state['isExpired'],
    //         ]);

    //         Log::info('Permit verified successfully', [
    //             'permit_no' => $permit_no,
    //             'permit_id' => $applicant->id,
    //             'ip' => request()->ip(),
    //         ]);

    //         return redirect($url);
    //     } catch (\Throwable $e) {

    //         Log::error('Permit verification failed', [
    //             'error' => $e->getMessage(),
    //             'permit_no' => $permit_no,
    //         ]);

    //         return response()->json([
    //             "status" => "error",
    //             "message" => "Verification failed"
    //         ], 500);
    //     }
    // }

    // public function verifyPermit($permit_no)
    // {
    //     try {

    //         $applicant = PermitApplication::where('permit_no', $permit_no)->first();

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

    //         $state = $this->resolvePermitStateUnified($applicant);

    //         // if ($state['signOff']) {
    //         //     $state['signOff']->trackAccess(
    //         //         'viewed',
    //         //         'api_qr_scan',
    //         //         request()
    //         //     );
    //         // }

    //         if (
    //             $state['signOff'] &&
    //             !empty($state['signOff']->ecard_id)
    //         ) {
    //             $state['signOff']->trackAccess(
    //                 'viewed',
    //                 'api_qr_scan',
    //                 request()
    //             );
    //         }

    //         $token = bin2hex(random_bytes(32));

    //         DB::table('verification_tokens')->insert([
    //             'permit_application_id' => $applicant->id,
    //             'token_hash'           => hash('sha256', $token),
    //             'ip_address'           => request()->ip(),
    //             'user_agent'           => request()->userAgent(),
    //             'expires_at'           => now()->addMinutes(5),
    //             'used'                 => false,
    //             'created_at'           => now(),
    //             'updated_at'           => now(),
    //         ]);

    //         $url = URL::temporarySignedRoute(
    //             'verify.certificate',
    //             now()->addMinutes(5),
    //             ['token' => $token]
    //         );

    //         // 🔥 SAME STATE ALWAYS
    //         session([
    //             'verified_permit_id' => $applicant->id,
    //             'verified_permit_hash' => hash_hmac(
    //                 'sha256',
    //                 $applicant->permit_no . $applicant->date_of_birth,
    //                 config('app.key')
    //             ),
    //             'permit_status' => $state['permitStatus'],
    //             'permit_is_expired' => $state['isExpired'],
    //         ]);

    //         Log::info('Permit verified successfully', [
    //             'permit_no' => $permit_no,
    //             'permit_id' => $applicant->id,
    //             'ip' => request()->ip(),
    //         ]);

    //         return redirect($url);
    //     } catch (\Throwable $e) {

    //         Log::error('Permit verification failed', [
    //             'error' => $e->getMessage(),
    //             'permit_no' => $permit_no,
    //         ]);

    //         return response()->json([
    //             "status" => "error",
    //             "message" => "Verification failed"
    //         ], 500);
    //     }
    // }

    // public function verifyPermit($permit_no)
    // {
    //     try {

    //         // $applicant = PermitApplication::where('permit_no', $permit_no)->first();
    //         $applicant = PermitApplication::with('signOffs')->where('permit_no', $permit_no)->first();

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

    //         // Unified state (only for signOff existence, NOT expiry display)
    //         $state = $this->resolvePermitStateUnified($applicant);

    //         $signOff = $state['signOff'];

    //         // 🚨 SAFE tracking: only if ecard exists
    //         if ($signOff && !empty($signOff->ecard_id)) {
    //             $signOff->trackAccess(
    //                 'viewed',
    //                 'api_qr_scan',
    //                 request()
    //             );
    //         }

    //         // Generate token
    //         $token = bin2hex(random_bytes(32));

    //         DB::table('verification_tokens')->insert([
    //             'permit_application_id' => $applicant->id,
    //             'token_hash'            => hash('sha256', $token),
    //             'ip_address'            => request()->ip(),
    //             'user_agent'            => request()->userAgent(),
    //             'expires_at'            => now()->addMinutes(5),
    //             'used'                  => false,
    //             'created_at'            => now(),
    //             'updated_at'            => now(),
    //         ]);

    //         $url = URL::temporarySignedRoute(
    //             'verify.certificate',
    //             now()->addMinutes(5),
    //             ['token' => $token]
    //         );

    //         // 🚨 ONLY AUTH STATE (NO EXPIRY HERE)
    //         session([
    //             'verified_permit_id' => $applicant->id,
    //             'verified_permit_hash' => hash_hmac(
    //                 'sha256',
    //                 $applicant->permit_no . $applicant->date_of_birth,
    //                 config('app.key')
    //             ),
    //         ]);

    //         return redirect($url);
    //     } catch (\Throwable $e) {

    //         Log::error('Permit verification failed', [
    //             'error' => $e->getMessage(),
    //             'permit_no' => $permit_no,
    //         ]);

    //         return response()->json([
    //             "status" => "error",
    //             "message" => "Verification failed"
    //         ], 500);
    //     }
    // }

    // public function retrievePermit(Request $request)
    // {
    //     $validated = $request->validate(
    //         [
    //             'firstname'     => 'required|string',
    //             'lastname'      => 'required|string',
    //             'date_of_birth' => 'required|date',
    //             'permit_no'     => 'required|string',
    //         ],
    //         [
    //             'firstname.required' => 'Please enter your first name.',
    //             'lastname.required' => 'Please enter your last name.',
    //             'date_of_birth.required' => 'Please select your date of birth.',
    //             'date_of_birth.date' => 'Please enter a valid date of birth.',
    //             'permit_no.required' => 'Please enter your permit number.',
    //         ],
    //         [
    //             'firstname'     => 'First Name',
    //             'lastname'      => 'Last Name',
    //             'date_of_birth' => 'Date of Birth',
    //             'permit_no'     => 'Permit Number',
    //         ]
    //     );

    //     $firstname = strtolower(trim($validated['firstname']));
    //     $lastname = strtolower(trim($validated['lastname']));
    //     $dob = $validated['date_of_birth'];
    //     $permitNo = strtoupper(trim($validated['permit_no']));

    //     DB::table('retrieval_attempts')->insert([
    //         'firstname' => $firstname,
    //         'lastname' => $lastname,
    //         'date_of_birth' => $dob,
    //         'permit_no' => $permitNo,
    //         'ip_address' => $request->ip(),
    //         'user_agent' => $request->userAgent(),
    //         'success' => false,
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ]);


    //     // $applicant = PermitApplication::where($validated)->first();
    //     $applicant = PermitApplication::whereRaw('LOWER(firstname) = ?', [$firstname])
    //         ->whereRaw('LOWER(lastname) = ?', [$lastname])
    //         ->whereDate('date_of_birth', $dob)
    //         ->whereRaw('UPPER(permit_no) = ?', [$permitNo])
    //         ->first();

    //     if (!$applicant) {
    //         Log::warning('Permit retrieval failed - not found', [
    //             'permit_no' => $permitNo,
    //             'ip' => $request->ip(),
    //         ]);

    //         // Don't reveal if permit exists or not (security)
    //         return back()->withErrors([
    //             'not_found' => 'No application found. Please check your details and try again.'
    //         ]);
    //     }

    //     $signOff = SignOff::where('application_id', $applicant->id)
    //         ->where('is_granted', 1)
    //         ->latest()
    //         ->first();

    //     $permitStatus = 'not_signed_off';
    //     $expiry = null;
    //     $isExpired = false;

    //     if ($signOff) {
    //         $expiry = $signOff->expiry_date;
    //         $isExpired = $expiry
    //             ? now()->gt(Carbon::parse($expiry))
    //             : false;

    //         $permitStatus = $isExpired ? 'expired' : 'valid';

    //         // Track access ONLY if signed off
    //         $signOff->trackAccess(
    //             'viewed',
    //             'web_portal_form',
    //             $request
    //         );
    //     }

    //     DB::table('retrieval_attempts')
    //         ->where('ip_address', $request->ip())
    //         ->where('created_at', '>', now()->subSeconds(10))
    //         ->update(['success' => true]);

    //     $token = bin2hex(random_bytes(32));
    //     $tokenHash = hash('sha256', $token);

    //     DB::table('verification_tokens')->insert([
    //         'permit_application_id' => $applicant->id,
    //         'token_hash'        => $tokenHash,
    //         'ip_address'   => request()->ip(),
    //         'user_agent'   => request()->userAgent(),
    //         'expires_at'   => now()->addMinutes(5),
    //         'created_at'   => now(),
    //         'updated_at'   => now(),
    //         'used' => false
    //     ]);

    //     $url = URL::temporarySignedRoute(
    //         'verify.certificate',
    //         now()->addMinutes(5),
    //         ['token' => $token]
    //     );

    //     Log::info('Permit retrieved successfully', [
    //         'permit_id' => $applicant->id,
    //         'permit_no' => $applicant->permit_no,
    //         'ip' => $request->ip(),
    //     ]);
    //     $expiry = optional($applicant->signOffs)->expiry_date;

    //     $isExpired = $expiry
    //         ? Carbon::now()->gt(Carbon::parse($expiry))
    //         : false;

    //     session([
    //         'verified_permit_id' => $applicant->id,
    //         'verified_permit_hash' => hash_hmac(
    //             'sha256',
    //             $applicant->permit_no . $applicant->date_of_birth,
    //             config('app.key')
    //         ),
    //         'permit_status' => $permitStatus,
    //         'permit_is_expired' => $isExpired,
    //     ]);

    //     return redirect($url);
    // }


    // public function verifyPermit($permit_no) Working
    // {
    //     $permitNo = strtoupper(trim($permit_no));
    //     $applicant = PermitApplication::with('signOffs')
    //         ->where('permit_no', $permitNo)
    //         ->first();

    //     if (!$applicant) {
    //         return [
    //             'success' => false,
    //             'message' => 'No application found.',
    //             'applicant' => null,
    //             'token' => null,
    //             'url' => null,
    //         ];
    //     }

    //     DB::table('retrieval_attempts')->insert([
    //         'firstname' => strtolower(trim($applicant->firstname)) ?: null,
    //         'lastname' => strtolower(trim($applicant->lastname)) ?: null,
    //         'date_of_birth' => $applicant->date_of_birth,
    //         'permit_no' => $permitNo,
    //         'ip_address' => request()->ip(),
    //         'user_agent' => substr(request()->userAgent(), 0, 255),
    //         'success' => true,
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ]);

    //     $state = $this->resolvePermitStateUnified($applicant);

    //     // if ($state['signOff']) {
    //     //     $state['signOff']->trackAccess(
    //     //         'viewed',
    //     //         'web_portal_form',
    //     //         request()
    //     //     );
    //     // }

    //     $token = bin2hex(random_bytes(32));

    //     DB::table('verification_tokens')->insert([
    //         'permit_application_id' => $applicant->id,
    //         'token_hash' => hash('sha256', $token),
    //         'ip_address' => request()->ip(),
    //         'user_agent' => substr(request()->userAgent(), 0, 255),
    //         'expires_at' => now()->addMinutes(5),
    //         'used' => false,
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ]);

    //     $url = URL::temporarySignedRoute(
    //         'verify.certificate',
    //         now()->addMinutes(5),
    //         ['token' => $token]
    //     );
    //     $signOff = $applicant->signOffs?->first();
    //     $expiry = $signOff?->expiry_date;
    //     $isExpired = false;

    //     if ($expiry) {
    //         $isExpired = \Carbon\Carbon::parse($expiry)->isPast();
    //     }

    //     // 🔥 SINGLE SOURCE OF TRUTH (IMPORTANT)
    //     session([
    //         'verified_permit_id' => $applicant->id,
    //         'verified_permit_hash' => hash_hmac(
    //             'sha256',
    //             $applicant->permit_no . $applicant->date_of_birth,
    //             config('app.key')
    //         ),
    //         'permit_status' => $state['permitStatus'],
    //         'permit_is_expired' => $state['isExpired'],
    //     ]);

    //     return redirect($url);
    // }



// public function verifyPermit($permit_no)
// {
//     $permitNo = strtoupper(trim($permit_no));
 
//     $applicant = PermitApplication::with('signOffs')
//         ->whereRaw('UPPER(permit_no) = ?', [$permitNo])
//         ->first();
 
//     if (!$applicant) {
//         return [
//             'success' => false,
//             'message' => 'No application found.',
//             'applicant' => null,
//             'token' => null,
//             'url' => null,
//         ];
//     }
 
//     DB::table('retrieval_attempts')->insert([
//         'firstname' => strtolower(trim($applicant->firstname)) ?: null,
//         'lastname' => strtolower(trim($applicant->lastname)) ?: null,
//         'date_of_birth' => $applicant->date_of_birth,
//         'permit_no' => $permitNo,
//         'ip_address' => request()->ip(),
//         'user_agent' => substr(request()->userAgent(), 0, 255),
//         'success' => true,
//         'created_at' => now(),
//         'updated_at' => now(),
//     ]);
 
//     $state = $this->resolvePermitStateUnified($applicant);
 
//     // if ($state['signOff']) {
//     //     $state['signOff']->trackAccess(
//     //         'viewed',
//     //         'web_portal_form',
//     //         request()
//     //     );
//     // }
 
//     // 🔥 ROBUST EXPIRY CALCULATION
//     $isExpired = false;
//     $signOff = null;
//     $expiry = null;
 
//     // Handle both single model and collection
//     if ($applicant->signOffs) {
//         if (is_collection($applicant->signOffs)) {
//             $signOff = $applicant->signOffs->first();
//         } else {
//             $signOff = $applicant->signOffs;
//         }
//     }
 
//     // Only mark as expired if we have a sign-off AND it's actually granted AND has expiry date
//     if ($signOff && $signOff->is_granted && $signOff->expiry_date) {
//         $expiry = \Carbon\Carbon::parse($signOff->expiry_date);
//         $isExpired = $expiry->isPast();
//     }
 
//     // Debug log (remove after testing)
//     Log::info('Permit expiry check', [
//         'permit_no' => $permitNo,
//         'has_signoff' => $signOff ? true : false,
//         'is_granted' => $signOff?->is_granted ?? false,
//         'expiry_date' => $signOff?->expiry_date ?? null,
//         'is_expired' => $isExpired,
//         'today' => now()->toDateString(),
//     ]);
 
//     $token = bin2hex(random_bytes(32));
 
//     DB::table('verification_tokens')->insert([
//         'permit_application_id' => $applicant->id,
//         'token_hash' => hash('sha256', $token),
//         'ip_address' => request()->ip(),
//         'user_agent' => substr(request()->userAgent(), 0, 255),
//         'expires_at' => now()->addMinutes(5),
//         'used' => false,
//         'created_at' => now(),
//         'updated_at' => now(),
//     ]);
 
//     // 🔥 SINGLE SOURCE OF TRUTH - SET SESSION WITH CORRECT EXPIRY STATUS
//     session([
//         'verified_permit_id' => $applicant->id,
//         'verified_permit_hash' => hash_hmac(
//             'sha256',
//             $applicant->permit_no . $applicant->date_of_birth,
//             config('app.key')
//         ),
//         'permit_status' => $state['permitStatus'],
//         'permit_is_expired' => $isExpired,
//     ]);
 
//     $url = URL::temporarySignedRoute(
//         'verify.certificate',
//         now()->addMinutes(5),
//         ['token' => $token]
//     );
//   return redirect($url);
    
// }

public function verifyPermit($permit_no)
{
    $permitNo = strtoupper(trim($permit_no));
 
    $applicant = PermitApplication::with('signOffs')
        ->whereRaw('UPPER(permit_no) = ?', [$permitNo])
        ->first();
 
    if (!$applicant) {
        return [
            'success' => false,
            'message' => 'No application found.',
            'applicant' => null,
            'token' => null,
            'url' => null,
        ];
    }
 
    DB::table('retrieval_attempts')->insert([
        'firstname' => strtolower(trim($applicant->firstname)) ?: null,
        'lastname' => strtolower(trim($applicant->lastname)) ?: null,
        'date_of_birth' => $applicant->date_of_birth,
        'permit_no' => $permitNo,
        'ip_address' => request()->ip(),
        'user_agent' => substr(request()->userAgent(), 0, 255),
        'success' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
 
    $state = $this->resolvePermitStateUnified($applicant);
 
    // if ($state['signOff']) {
    //     $state['signOff']->trackAccess(
    //         'viewed',
    //         'api',
    //         request()
    //     );
    // }
 
    // 🔥 ROBUST EXPIRY CALCULATION
    $isExpired = false;
    $signOff = null;
    $expiry = null;
 
    // Handle both single model and collection using Laravel helpers
    if ($applicant->signOffs) {
        // Check if it's a collection using instanceof
        if ($applicant->signOffs instanceof \Illuminate\Database\Eloquent\Collection) {
            $signOff = $applicant->signOffs->first();
        } else {
            // It's a single model
            $signOff = $applicant->signOffs;
        }
    }
 
    // Only mark as expired if we have a sign-off AND it's actually granted AND has expiry date
    if ($signOff && $signOff->is_granted && $signOff->expiry_date) {
        $expiry = \Carbon\Carbon::parse($signOff->expiry_date);
        $isExpired = $expiry->isPast();
    }
 
    // Debug log (remove after testing)
    Log::info('Permit expiry check', [
        'permit_no' => $permitNo,
        'has_signoff' => $signOff ? true : false,
        'is_granted' => $signOff?->is_granted ?? false,
        'expiry_date' => $signOff?->expiry_date ?? null,
        'is_expired' => $isExpired,
        'today' => now()->toDateString(),
    ]);
 
    $token = bin2hex(random_bytes(32));
 
    DB::table('verification_tokens')->insert([
        'permit_application_id' => $applicant->id,
        'token_hash' => hash('sha256', $token),
        'ip_address' => request()->ip(),
        'user_agent' => substr(request()->userAgent(), 0, 255),
        'expires_at' => now()->addMinutes(5),
        'used' => false,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
 
    // 🔥 SINGLE SOURCE OF TRUTH - SET SESSION WITH CORRECT EXPIRY STATUS
    session([
        'verified_permit_id' => $applicant->id,
        'verified_permit_hash' => hash_hmac(
            'sha256',
            $applicant->permit_no . $applicant->date_of_birth,
            config('app.key')
        ),
        'permit_status' => $state['permitStatus'],
        'permit_is_expired' => $isExpired,
    ]);
 
    $url = URL::temporarySignedRoute(
        'verify.certificate',
        now()->addMinutes(5),
        ['token' => $token]
    );
 
    return redirect($url);
}

    public function retrievePermit(Request $request)
    {
        $validated = $request->validate([
            'firstname'     => 'required|string',
            'lastname'      => 'required|string',
            'date_of_birth' => 'required|date',
            'permit_no'     => 'required|string',
        ]);

        $firstname = strtolower(trim($validated['firstname']));
        $lastname  = strtolower(trim($validated['lastname']));
        $dob       = $validated['date_of_birth'];
        $permitNo  = strtoupper(trim($validated['permit_no']));

        $applicant = PermitApplication::whereRaw('LOWER(firstname) = ?', [$firstname])
            ->whereRaw('LOWER(lastname) = ?', [$lastname])
            ->whereDate('date_of_birth', $dob)
            ->whereRaw('UPPER(permit_no) = ?', [$permitNo])
            ->first();

        if (!$applicant) {
            return back()->withErrors([
                'not_found' => 'No application found. Please check your details.'
            ]);
        }

        DB::table('retrieval_attempts')->insert([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'date_of_birth' => $dob,
            'permit_no' => $permitNo,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'success' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $state = $this->resolvePermitStateUnified($applicant);

        if ($state['signOff']) {
            $state['signOff']->trackAccess(
                'viewed',
                'web_portal_form',
                $request
            );
        }

        $token = bin2hex(random_bytes(32));

        DB::table('verification_tokens')->insert([
            'permit_application_id' => $applicant->id,
            'token_hash'            => hash('sha256', $token),
            'ip_address'            => $request->ip(),
            'user_agent'            => $request->userAgent(),
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

        // 🔥 SINGLE SOURCE OF TRUTH (IMPORTANT)
        session([
            'verified_permit_id' => $applicant->id,
            'verified_permit_hash' => hash_hmac(
                'sha256',
                $applicant->permit_no . $applicant->date_of_birth,
                config('app.key')
            ),
            'permit_status' => $state['permitStatus'],
            'permit_is_expired' => $state['isExpired'],
        ]);

        return redirect($url);
    }





    // public function showCertificate(Request $request, $token) - Working
    // {
    //     $tokenHash = hash('sha256', $token);

    //     $record = DB::table('verification_tokens')
    //         ->where('token_hash', $tokenHash)
    //         ->first();

    //     if (!$record) {
    //         abort(403, 'Invalid verification link.');
    //     }


    //     if (now()->gt(Carbon::parse($record->expires_at))) {
    //         abort(403, 'Verification link expired.');
    //     }

    //     DB::table('verification_tokens')
    //         ->where('token_hash', $tokenHash)
    //         ->update(['used' => true, 'used_at' => now()]);

    //     $applicant = PermitApplication::with(
    //         'permitCategory',
    //         'payment',
    //         'establishmentClinics',
    //         'signOffs',
    //         'testResults',
    //         'healthInterviews.healthInterviewSymptom.symptoms',
    //         'appointment.editTransactions',
    //         'messages'
    //     )->findOrFail($record->permit_application_id);

    //     $signOff = $applicant->signOffs?->first();

    //     // Get expiry date from sign-off
    //     $expiry = $signOff?->expiry_date;

    //     // Determine if permit is expired
    //     $isExpired = false;
    //     if ($expiry) {
    //         $isExpired = \Carbon\Carbon::parse($expiry)->isPast();
    //     }

    //     $isExpired = session('permit_is_expired', false);


    //     return response()
    //         ->view('verify.certificate', compact('applicant', 'signOff', 'expiry', 'isExpired', 'token'))
    //         ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
    //         ->header('Pragma', 'no-cache')
    //         ->header('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');
    // }
    // public function showCertificate(Request $request, $token)
    // {
    //     $tokenHash = hash('sha256', $token);

    //     $record = DB::table('verification_tokens')
    //         ->where('token_hash', $tokenHash)
    //         ->first();

    //     if (!$record) {
    //         abort(403, 'Invalid verification link.');
    //     }

    //     if (now()->gt(\Carbon\Carbon::parse($record->expires_at))) {
    //         abort(403, 'Verification link expired.');
    //     }

    //     DB::table('verification_tokens')
    //         ->where('token_hash', $tokenHash)
    //         ->update(['used' => true, 'used_at' => now()]);

    //     $applicant = PermitApplication::with([
    //         'permitCategory',
    //         'payment',
    //         'establishmentClinics',
    //         'signOffs',
    //         'testResults',
    //         'healthInterviews.healthInterviewSymptom.symptoms',
    //         'appointment.editTransactions',
    //         'messages'
    //     ])->findOrFail($record->permit_application_id);

    //     // 🔥 SINGLE SOURCE OF TRUTH (DATABASE ONLY)
    //     $signOff = $applicant->signOffs?->first();

    //     $expiry = $signOff?->expiry_date;

    //     $isExpired = false;

    //     if ($expiry) {
    //         $isExpired = \Carbon\Carbon::parse($expiry)->isPast();
    //     }

    //     return response()
    //         ->view('verify.certificate', compact('applicant', 'isExpired', 'token'))
    //         ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
    //         ->header('Pragma', 'no-cache')
    //         ->header('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');
    // }

//     public function showCertificate(Request $request, $token)
// {
//     $tokenHash = hash('sha256', $token);
 
//     $record = DB::table('verification_tokens')
//         ->where('token_hash', $tokenHash)
//         ->first();
 
//     if (!$record) {
//         abort(403, 'Invalid verification link.');
//     }
 
//     if (now()->gt(Carbon::parse($record->expires_at))) {
//         abort(403, 'Verification link expired.');
//     }
 
//     DB::table('verification_tokens')
//         ->where('token_hash', $tokenHash)
//         ->update(['used' => true, 'used_at' => now()]);
 
//     $applicant = PermitApplication::with(
//         'permitCategory',
//         'payment',
//         'establishmentClinics',
//         'signOffs',
//         'testResults',
//         'healthInterviews.healthInterviewSymptom.symptoms',
//         'appointment.editTransactions',
//         'messages'
//     )->findOrFail($record->permit_application_id);
 
//     // Get the sign-off record
//     $signOff = $applicant->signOffs?->first();
 
//     // Get expiry date from sign-off
//     $expiry = $signOff?->expiry_date;
 
//     // Determine if permit is expired
//     $isExpired = false;
//     if ($expiry) {
//         $isExpired = \Carbon\Carbon::parse($expiry)->isPast();
//     }
 
//     // 🔥 FALLBACK: If no expiry found in DB, check session (set by verifyPermit)
//     if (!$isExpired && session()->has('permit_is_expired')) {
//         $isExpired = session('permit_is_expired');
//     }
 
//     return response()
//         ->view('verify.certificate', compact('applicant', 'signOff', 'expiry', 'isExpired', 'token'))
//         ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
//         ->header('Pragma', 'no-cache')
//         ->header('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');
// }

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
 
    // 🔥 ROBUST EXPIRY CALCULATION
    $isExpired = false;
    $signOff = null;
    $expiry = null;
 
    // Handle both single model and collection
    if ($applicant->signOffs) {
        if (is_collection($applicant->signOffs)) {
            $signOff = $applicant->signOffs->first();
        } else {
            $signOff = $applicant->signOffs;
        }
    }
 
    // Only mark as expired if we have a sign-off AND it's actually granted AND has expiry date
    if ($signOff && $signOff->is_granted && $signOff->expiry_date) {
        $expiry = \Carbon\Carbon::parse($signOff->expiry_date);
        $isExpired = $expiry->isPast();
    }
 
    // Debug log (remove after testing)
    Log::info('Certificate display expiry check', [
        'permit_no' => $applicant->permit_no,
        'has_signoff' => $signOff ? true : false,
        'is_granted' => $signOff?->is_granted ?? false,
        'expiry_date' => $signOff?->expiry_date ?? null,
        'is_expired' => $isExpired,
        'today' => now()->toDateString(),
    ]);
 
    return response()
        ->view('verify.certificate', compact('applicant', 'signOff', 'expiry', 'isExpired', 'token'))
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

    private function processPermitVerification($applicant, Request $request, string $source)
    {
        $signOff = $applicant->signOffs;

        // expiry (ONLY ONE SOURCE OF TRUTH)
        $expiry = optional($signOff)->expiry_date;

        $isExpired = $expiry
            ? now()->gt(Carbon::parse($expiry))
            : false;

        $permitStatus = $signOff
            ? ($isExpired ? 'expired' : 'valid')
            : 'not_signed_off';

        // optional tracking (never blocks flow)
        if ($signOff) {
            $signOff->trackAccess('viewed', $source, $request);
        }

        // ALWAYS generate token
        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);

        DB::table('verification_tokens')->insert([
            'permit_application_id' => $applicant->id,
            'token_hash'            => $tokenHash,
            'ip_address'            => $request->ip(),
            'user_agent'            => $request->userAgent(),
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
            'permit_status' => $permitStatus,
            'permit_is_expired' => $isExpired,
        ]);

        Log::info('Permit verified', [
            'permit_id' => $applicant->id,
            'permit_no' => $applicant->permit_no,
            'source' => $source,
            'ip' => $request->ip(),
        ]);

        return redirect($url);
    }

    // private function resolvePermitState($applicant)
    // {

    //     $signOff = $applicant->signOffs()
    //         ->where('is_granted', 1)
    //         ->latest()
    //         ->first();

    //     $expiry = optional($signOff)->expiry_date;

    //     $isExpired = $expiry
    //         ? now()->gt(Carbon::parse($expiry))
    //         : false;

    //     $permitStatus = $signOff
    //         ? ($isExpired ? 'expired' : 'valid')
    //         : 'not_signed_off';

    //     return [
    //         'signOff' => $signOff,
    //         'expiry' => $expiry,
    //         'isExpired' => $isExpired,
    //         'permitStatus' => $permitStatus,
    //     ];
    // }

    private function resolvePermitStateUnified($applicant)
    {
        $signOff = $applicant->signOffs?->first();

        $expiry = $signOff?->expiry_date;

        $isExpired = false;

        if ($expiry) {
            $isExpired = \Carbon\Carbon::parse($expiry)->isPast();
        }

        return [
            'signOff' => $signOff,
            'permitStatus' => $signOff?->is_granted ? 'APPROVED' : 'PENDING',
            'isExpired' => $isExpired,
        ];
    }
}
