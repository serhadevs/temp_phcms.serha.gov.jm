<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AfternoonReportMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $users;
    public $roles;
    public $database_status;
    public $permit_applications;
    public $establishment_applications_count;
    public $swimming_pool_count;
    public $establishment_clinics_count;
    public $tourist_application_count;
    public $test_results_count;
    public $sign_off_count;
    public $total_sth_payments;
    public $total_stt_payments;
    public $total_ksa_payments;

    public function __construct($users, $roles, $database_status, $permit_applications, $establishment_applications_count, $swimming_pool_count, $establishment_clinics_count, $tourist_application_count, $test_results_count, $sign_off_count, $total_sth_payments, $total_stt_payments, $total_ksa_payments)
    {
        $this->users = $users;
        $this->roles = $roles;
        $this->database_status = $database_status;
        $this->permit_applications = $permit_applications;
        $this->establishment_applications_count = $establishment_applications_count;
        $this->swimming_pool_count = $swimming_pool_count;
        $this->establishment_clinics_count = $establishment_clinics_count;
        $this->tourist_application_count = $tourist_application_count;
        $this->test_results_count = $test_results_count;
        $this->sign_off_count = $sign_off_count;
        $this->total_sth_payments = $total_sth_payments;
        $this->total_stt_payments = $total_stt_payments;
        $this->total_ksa_payments = $total_ksa_payments;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Afternoon Report Mail',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.reports.afternoon_daily_report',
            with: ['users' => $this->users, 'roles' => $this->roles, 'database_status' => $this->database_status, 'permit_applications' => $this->permit_applications, 'establishment_applications' => $this->establishment_applications_count, 'swimming_pool_count' => $this->swimming_pool_count, 'establishment_clinics_count' => $this->establishment_clinics_count, 'tourist_application_count' => $this->tourist_application_count, 'test_results_count' => $this->test_results_count, 'sign_off_count' => $this->sign_off_count, 'total_sth_payments' => $this->total_sth_payments, 'total_stt_payments' => $this->total_stt_payments, 'total_ksa_payments' => $this->total_ksa_payments,]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
