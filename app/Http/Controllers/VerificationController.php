<?php

namespace App\Http\Controllers;

use App\Models\PermitApplication;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    public function show($permit_no)
    {
        Log::channel('systemOperations')->info('Viewing permit verification', ['permit_no' => $permit_no]);
        $applicant = PermitApplication::with(
            'permitCategory',
            'payment',
            'establishmentClinics',
            'signOffs'
        )->where('permit_no', $permit_no)->first();

        if (!$applicant) {
            return view('verify.verify', [
                'found' => false
            ]);
        }

        return view('verify.verify', [
            'found' => true,
            'applicant' => $applicant
        ]);
    }
}