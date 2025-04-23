<?php

namespace App\Http\Controllers;

use App\Jobs\SendPaymentReceiptEmail;
use App\Jobs\SendPermitApplicationEmailJob;
use App\Models\Messages;
use App\Models\PermitApplication;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Services extends Controller
{
   
    public function sendPaymentEmail($applicant, $register_new_payment, $cashier_name, $receipt_number)
    {
        if ($applicant === null) {
            Log::warning('Attempted to send payment email to null applicant');
            return false;
        }

        $email = $applicant->email ?? null;
        
        if (!$email) {
            Log::warning("No email available for applicant ID: {$applicant->id}");
            return false;
        }

        try {
            // Dispatch email job
            dispatch(new SendPaymentReceiptEmail($email,$applicant, $register_new_payment, $cashier_name, $receipt_number));
            
            // Log successful attempt
            $this->logEmailMessage($applicant->id, $email, 'sent', 'none');
            
            return true;
        } catch (Exception $e) {
            // Log error
            Log::error("Failed to send payment receipt email: {$e->getMessage()}");
            
            // Record error in messages table
            $this->logEmailMessage($applicant->id, $email, 'error', $e->getMessage());
            
            return false;
        }
    }
    
    public function sendAppointmentEmail($new_permit_application){
       
                $sendEmailInfo = PermitApplication::with('permitCategory', 'appointment', 'user')->find($new_permit_application->id);
                $appointment = DB::table('appointments')
                    ->join('exam_dates', 'exam_dates.id', '=', 'appointments.exam_date_id')
                    ->join('exam_sites', 'exam_sites.id', '=', 'exam_dates.exam_site_id')
                    ->where('appointments.facility_id', auth()->user()->facility_id)
                    ->where('appointments.permit_application_id', $sendEmailInfo->id)
                    ->where('exam_dates.application_type_id', 1)
                    ->orderBy('appointments.created_at', 'desc')
                    ->first();


                if ($sendEmailInfo->email) {
                    dispatch(new SendPermitApplicationEmailJob($sendEmailInfo, $appointment));
                    Messages::create([
                        'permit_application_id' => $sendEmailInfo->id,
                        'email_type_id' => 1,
                        'to' => $sendEmailInfo->email,
                        'status' => 'sent',
                        'error_message' => 'none',
                        'user_id' => auth()->user()->id,
                        'sent_at' => \Carbon\Carbon::now()
                    ]);
                }
           
    }
   
    private function logEmailMessage($applicantId, $email, $status, $errorMessage)
    {
        Messages::create([
            'permit_application_id' => $applicantId,
            'email_type_id' => 2,
            'to' => $email,
            'status' => $status,
            'error_message' => $errorMessage,
            'user_id' => auth()->id(),
            'sent_at' => now()
        ]);
    }
}