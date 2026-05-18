<?php

namespace App\Http\Controllers;

use App\Jobs\SendOnlineUserVerifyEmail;
use App\Jobs\SendPaymentReceiptEmail;
use App\Jobs\SendPermitApplicationEmailJob;
use App\Mail\PermitReadyMail;
use App\Mail\SendActivationEmail;
use App\Models\Messages;
use App\Models\OnlineUser;
use App\Models\PermitApplication;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Contracts\Mail\Mailable;


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
        Log::channel('systemOperations')->info('Sending appointment email', ['user_id' => auth()->user()->id]);
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

    public function sendPermitReadyEmail($application)
    {
        if (!empty($application->email)) {
            try {

                Mail::to($application->email)->queue(new PermitReadyMail($application));
                Log::channel('systemOperations')->info('E-Card Email Sent', ['user_id' => auth()->user()->id, 'application_id' => $application->permit_no]);
            } catch (Exception $e) {

                Log::error('Failed to send sign-off email to ' . $application->email . ': ' . $e->getMessage());
            }
        }
    }
    public function createOnlineUserAccount($permit_no)
    {
        //Check to see if the user already has an account in the online_users table

        $online_applicant = OnlineUser::where('permit_no', $permit_no)->first();

        if ($online_applicant) {
            Log::channel('systemOperations')->info('The user already has an online account. No account was created', ['user_id' => auth()->user()->id]);
            return;
        }

        try {
            DB::beginTransaction();

            // Look up applicant
            $applicant = PermitApplication::where('permit_no', $permit_no)->firstOrFail();
            $applicantEmail = $applicant->email;

            // Validate email
            if (!$this->validateEmail($applicantEmail)) {
                throw new Exception('Invalid email address.');
            }

            // Generate credentials
            $passwordHash = Hash::make(Str::random(20));
            $token = Str::random(64);
            $activationCode = rand(100000, 999999);

            // Create online user
            $newOnlineUser = OnlineUser::create([
                'permit_no' => $applicant->permit_no,
                'email' => $applicantEmail,
                'password' => $passwordHash,
                'activation_token' => $token,
                'activation_expires_at' => now()->addDays(7),
                'activation_code' => $activationCode
            ]);

            // Build activation link
            // $activationLink = url("/activate-account?token=$token&email=$applicantEmail");

            DB::commit();

            Log::channel('systemOperations')->info(
                'Online profile created for the user',
                [
                    'user_id' => auth()->user()->id,
                    'online_user_id' => $newOnlineUser->id,
                    'permit_no' => $permit_no
                ]
            );

            // Send activation email AFTER commit
            $this->sendActivationEmail($applicantEmail, $applicant, $activationCode);
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('systemOperations')->error(
                'Unable to create online profile',
                ['message' => $e->getMessage()]
            );
        }
    }

    private function validateEmail($applicantEmail)
    {
        if (filter_var($applicantEmail, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    private function sendActivationEmail($applicantEmail, $applicant, $activationCode)
    {
        try {
            Mail::to($applicantEmail)->send(
                new SendActivationEmail($applicantEmail, $applicant, $activationCode)
            );

            Log::channel('systemOperations')->info(
                'Activation Email Sent',
                ['application_id' => $applicant->id]
            );
        } catch (Exception $e) {
            Log::channel('systemOperations')->error(
                'Activation Email Failed',
                ['message' => $e->getMessage()]
            );
        }
    }
}
