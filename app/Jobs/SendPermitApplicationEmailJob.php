<?php

namespace App\Jobs;

use App\Mail\SendPermitApplicationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPermitApplicationEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $sendEmailInfo;
    public $appointment;
    public function __construct($sendEmailInfo,$appointment)
    {
        $this->sendEmailInfo = $sendEmailInfo;
        $this->appointment = $appointment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->sendEmailInfo->email)->send(new SendPermitApplicationMail($this->sendEmailInfo,$this->appointment));
    }
}
