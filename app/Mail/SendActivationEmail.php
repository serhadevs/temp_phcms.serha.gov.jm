<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendActivationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $applicantEmail;
   
    public $applicant;
    public $activationCode;
    public function __construct($applicantEmail,$applicant,$activationCode)
    {
        $this->applicantEmail = $applicantEmail;
        $this->applicant = $applicant;
        $this->activationCode = $activationCode;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Online Account Activation - Food Handlers Permit App - IDPro',
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
            markdown: 'emails.online_activation',
            with:[
                "applicantEmail" => $this->applicantEmail,
                "application" => $this->applicant,
                "activationCode" => $this->activationCode
            ]
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
