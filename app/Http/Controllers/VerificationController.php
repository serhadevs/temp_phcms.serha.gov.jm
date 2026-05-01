<?php

namespace App\Http\Controllers;

use App\Models\PermitApplication;

class VerificationController extends Controller
{
    public function show($permit_no)
    {
        $applicant = PermitApplication::with(
            'permitCategory',
            'payment',
            'establishmentClinics',
            'signOffs'
        )->where('permit_no', $permit_no)->first();

        if (!$applicant) {
            return view('verify', [
                'found' => false
            ]);
        }

        return view('verify', [
            'found' => true,
            'applicant' => $applicant
        ]);
    }
}