<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentEmail extends Mailable
{
    use Queueable, SerializesModels;

   
    public $register_new_payment;
    public $applicant;
    public $cashier_name;
    public $receipt_number;
    public function __construct($register_new_payment,$applicant,$cashier_name,$receipt_number)
    {
        $this->register_new_payment = $register_new_payment;
        $this->applicant = $applicant;
        $this->cashier_name = $cashier_name;
        $this->receipt_number = $receipt_number;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Payment Confirmation',
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
            markdown: 'emails.paymentemail',
            with:[
                'new_payment' => $this->register_new_payment,
                'applicant' => $this->applicant,
                'cashier_name' => $this->cashier_name,
                'receipt_number' => $this->receipt_number,
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
