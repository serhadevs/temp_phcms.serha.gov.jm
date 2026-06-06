<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EstablishmentApplications;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class EstablishmentsApi extends Controller
{
    public function index()
    {
        return view('verify.establishments');
    }

    public function viewLicense(Request $request)
    {

        $validated = request()->validate([
            'application_number' => 'nullable'
        ]);

        $est_application = EstablishmentApplications::with(
            'testResults',
            'signOff',
            'signOff.user:id,firstname,lastname',
            'establishmentCategory:id,name'
        )->find($validated['application_number']);

        if (!$est_application) {
            return back()->withErrors([
                'not_found' => 'No application/license found. Please check your details.'
            ]);
        }

        // DB::table('retrieval_attempts')->insert([
        //     'firstname' => $firstname,
        //     'lastname' => $lastname,
        //     'date_of_birth' => $dob,
        //     'permit_no' => $permitNo,
        //     'ip_address' => $request->ip(),
        //     'user_agent' => $request->userAgent(),
        //     'success' => true,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
        // dd($est_application);

        $token = bin2hex(random_bytes(32));

        DB::table('verification_tokens')->insert([
            'permit_application_id' => $est_application->id,
            'token_hash'            => hash('sha256', $token),
            'ip_address'            => $request->ip(),
            'user_agent'            => $request->userAgent(),
            'expires_at'            => now()->addMinutes(5),
            'used'                  => false,
            'created_at'            => now(),
            'updated_at'            => now(),
        ]);


        $url = URL::temporarySignedRoute(
            'verify.license',
            now()->addMinutes(5),
            ['token' => $token]
        );

        session([
            'verified_license_id' => $est_application->id,
            'verified_license_hash' => hash_hmac(
                'sha256',
                $est_application->permit_no . $est_application->id,
                config('app.key')
            ),
        ]);

        return redirect($url);
    }

    public function showLicense(Request $request, String $token)
    {
        $tokenHash = hash('sha256', $token);

        $record = DB::table('verification_tokens')
            ->where('token_hash', $tokenHash)
            ->first();

            //dd($record);

        if (!$record) {
            abort(403, 'Invalid verification link.');
        }

        if (now()->gt(Carbon::parse($record->expires_at))) {
            abort(403, 'Verification link expired.');
        }

        DB::table('verification_tokens')
            ->where('token_hash', $tokenHash)
            ->update(['used' => true, 'used_at' => now()]);

             $est_application = EstablishmentApplications::with(
            'testResults',
            'signOff',
            'signOff.user:id,firstname,lastname',
            'establishmentCategory:id,name'
        )->find($record->permit_application_id);


            return response()
            ->view('verify.license', compact('est_application','token'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');
    }
}
