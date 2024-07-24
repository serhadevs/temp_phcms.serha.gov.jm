<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\SendPermitApplicationEmailJob;
use App\Models\Messages;
use App\Models\PermitApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Messaging extends Controller
{
   
    public function index(){
        return view('food_handlers_permit.message');
        
    }

    public function sendMessage(Request $request){

        $permit_application_id = $request->id;
       

        function AppointmentEmail($permit_application_id)
        {
            $sendEmailInfo = PermitApplication::with('permitCategory', 'appointment', 'user')->find($permit_application_id);
            $appointment = DB::table('appointments')
                ->join('exam_dates', 'exam_dates.id', '=', 'appointments.exam_date_id')
                ->join('exam_sites', 'exam_sites.id', '=', 'exam_dates.exam_site_id')
                ->where('appointments.facility_id', auth()->user()->facility_id)
                ->where('appointments.permit_application_id', $sendEmailInfo->id)
                ->where('exam_dates.application_type_id', 1)
                ->orderBy('appointments.created_at', 'desc')
                ->first();

            try {
                if ($sendEmailInfo->email) {
                    dispatch(new SendPermitApplicationEmailJob($sendEmailInfo, $appointment));
                    Messages::create([
                        'permit_application_id' => $sendEmailInfo->id,
                        'email_type_id' => 5,
                        'to' => $sendEmailInfo->email,
                        'status' => 'sent',
                        'error_message' => 'none',
                        'user_id' => auth()->user()->id,
                        'sent_at' => \Carbon\Carbon::now()
                    ]);
                    
                } else {
                    Messages::create([
                        'permit_application_id' => $sendEmailInfo->id,
                        'email_type_id' => 5,
                        'to' => $sendEmailInfo->email,
                        'status' => 'failed',
                        'error_message' => 'Unknown error',
                        'user_id' => auth()->user()->id,
                        'sent_at' => \Carbon\Carbon::now()
                    ]);
                     return redirect()->route('permit.application.view', ['id' => $permit_application_id])->with('error', 'Unable to find a message for that user');
                }
            } catch (\Exception $e) {
                
                Log::error('Error dispatching email job: ' . $e->getMessage());
            }

        }
        AppointmentEmail($permit_application_id);

        return redirect()->route('permit.application.view', ['id' => $permit_application_id])->with('success', 'Appointment Email Resent');
        
    }
}
