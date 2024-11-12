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

    public $user;
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (empty($this->user->email)) {
            Log::error('Unable to send email to user' . $this->user->firstname . ''. $this->user->lastname);
            return;
        }

        try {
            Mail::to($this->user->email)->send(new SendCredentials($this->user->firstname,$this->user->lastname));
        } catch (\Throwable $e) {
            Log::error('Failed to send user email', [
                'user' => $this->user,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }
}
