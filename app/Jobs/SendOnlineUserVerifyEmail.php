<?php

namespace App\Jobs;

use App\Mail\OnlineUsersVerifyEmail;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendOnlineUserVerifyEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $email;
    public $signature_link;
    public $online_user_id;
    public function __construct($email, $signature_link, $online_user_id)
    {
        $this->email = $email;
        $this->signature_link = $signature_link;
        $this->online_user_id = $online_user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (empty($this->email)) {
            Log::error('Cannot send online verification link. Online user email is empty', $this->online_user_id);
            return;
        }

        try {
            Mail::to($this->email)->send(new OnlineUsersVerifyEmail($this->signature_link));
        } catch (Throwable $e) {
            Log::error('Failed to send online user verification email', [
                'online_user_id' => $this->online_user_id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
