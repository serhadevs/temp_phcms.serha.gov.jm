<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use App\Models\ExamDates;
use App\Models\ExamSites;
use App\Models\PermitApplication;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{

    public function index()
    {

        $exam_sites = ExamSites::where('facility_id', auth()->user()->facility_id)->get();
        //$exam_sites = ExamDates::where('facility_id', auth()->user()->facility_id)->get();
        //dd($exam_sites);
        return view('appointments.index', ["exam_sites" => $exam_sites]);
    }

    public function show(Request $request)
    {
        $incomingFields = $request->validate([
            "app_date" => "required|date",
            "exam_site" => "required",
            "start_time" => "required"
        ]);

        //dd($incomingFields['app_date']);

        $appointments = Appointments::with('applications','testSites','examDate','examDate.permitCategory')
        ->where('appointment_date', $incomingFields['app_date'])
        ->whereRelation('testSites','facility_id',$incomingFields['exam_site'])
        ->whereRelation('examDate','exam_start_time',$incomingFields['start_time'])
        ->get();

        // // $appointments = PermitApplication::join('appointments', 'appointments.permit_application_id', '=', 'permit_applications.id')
        // //     ->join('exam_dates','exam_dates.exam_date_id','=','appointments.')
        // //     ->where('appointments.appointment_date', $incomingFields['app_date'])->get();

            //dd($incomingFields['exam_site']);
        // $appointments = PermitApplication::with('appointments')
        // ->whereRelation('appointments','appointment_date',$incomingFields['app_date'])->get();
        // $exam_info = ExamSites::where('facility_id',$incomingFields['exam_site'])->first();
        // //->whereRelation('user','facility_id',auth()->user()->facility_id)
        // // ->whereRelation('user.examSite','id',$incomingFields['exam_site'])->get();

        //dd($appointments);
        // // foreach ($appointments as $appointment) {
        // //     $exam = $appointment->appointments->facility_id;
        // // }

        // dd($incomingFields['exam_site']);
        // $exam_info = ExamSites::where('facility_id',$incomingFields['exam_site'])->first();

      

      

       //dd($exam_info);
        if ($appointments->isEmpty()) {
            return redirect()->back()->with('error', 'Unable to find appointments for that date');
        }

        return view('appointments.view', compact('appointments'));
    }
}
