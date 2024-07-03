<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PermitApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmailController extends Controller
{
    public function ApplicationEmail($new_permit_application){
        $sendEmailInfo = PermitApplication::with('permitCategory', 'appointment', 'user')->find($new_permit_application->id);
        $appointment = DB::table('appointments')
            ->join('exam_dates', 'exam_dates.id', '=', 'appointments.exam_date_id')
            ->join('exam_sites', 'exam_sites.id', '=', 'exam_dates.exam_site_id')
            ->where('appointments.facility_id', auth()->user()->facility_id)
            ->where('appointments.permit_application_id', $sendEmailInfo->id)
            ->where('exam_dates.application_type_id', 1)
            ->orderBy('appointments.created_at', 'desc')
            ->first();
        
            return [
                'appointment' => $appointment,
                'sendEmailInfo' => $sendEmailInfo
            ];
    }
}
