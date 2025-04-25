<?php

namespace App\Http\Controllers;

use App\Jobs\SendOnlineUserVerifyEmail;
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
            dispatch(new SendPaymentReceiptEmail($email, $applicant, $register_new_payment, $cashier_name, $receipt_number));

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

    function generatePermitPermitNo()
    {
        do {
            $abbr = DB::table('facilities')
                ->select('abbr')
                //Change this based on preferred pickup location
                ->where('id', 3)
                ->first()->abbr;

            $digit_limit = 4;
            $current_date = date("my");
            $random_digits = str_pad(rand(0, pow(10, $digit_limit) - 1), $digit_limit, '0', STR_PAD_LEFT);
            $permit_no = $abbr . $random_digits . $current_date;

            $permit_no_exist = PermitApplication::where('permit_no', $permit_no)->first();
        } while (!empty($permit_no_exist));
        return $permit_no;
    }

    public function sendOUVerifyEmail($email, $signature_link, $online_user_id)
    {
        try {
            dispatch(new SendOnlineUserVerifyEmail($email, $signature_link, $online_user_id));
            return true;
        } catch (Exception $e) {
            Log::error("Failed to send online user verification email: {$e->getMessage()}");
            return false;
        }
    }

    public function sendAppointmentEmail($new_permit_application)
    {

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
