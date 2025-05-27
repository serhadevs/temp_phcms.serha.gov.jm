<?php

namespace App\Jobs;

use App\Mail\PaymentEmail;
use App\Mail\SendCredentials;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendCredentialEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $newUser;
    public $stringPassword;
    public function __construct($newUser, $stringPassword)
    {
        $this->newUser = $newUser;
        $this->stringPassword = $stringPassword;
    }
   

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (empty($this->newUser->email)) {
            Log::error('Unable to send email to user' . $this->newUser->firstname . ''. $this->newUser->lastname);
            return;
        }

        try {
            Mail::to($this->newUser->email)->send(new SendCredentials($this->newUser,$this->stringPassword));
        } catch (\Throwable $e) {
            Log::error('Failed to send user email', [
                'user' => $this->newUser,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }
}
