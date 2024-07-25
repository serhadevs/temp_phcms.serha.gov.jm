<?php

namespace App\Jobs;

use App\Mail\PaymentEmail;
use App\Mail\SendPermitApplicationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendPaymentReceiptEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $register_new_payment;
    public $email;
    public $cashier_name;
    public $receipt_number;
    public $applicant;

    public function __construct($email,$applicant,$register_new_payment,$cashier_name,$receipt_number)
    {
        $this->email = $email;
        $this->applicant = $applicant;
        $this->register_new_payment = $register_new_payment;
        $this->cashier_name = $cashier_name;
        $this->receipt_number = $receipt_number;
      
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        if (empty($this->email)) {
            Log::error('Cannot send email: Applicant email is empty', ['applicant_id' => $this->applicant->id]);
            return;
        }

        try {
            Mail::to($this->email)->send(new PaymentEmail($this->register_new_payment,$this->applicant,$this->cashier_name,$this->receipt_number));
        } catch (Throwable $e) {
            Log::error('Failed to send payment receipt email', [
                'applicant' => $this->applicant->id,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
       
    }
}
