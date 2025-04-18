<?php

namespace App\Console\Commands;

use App\Mail\DailyReportFoodPermits;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendScheduledEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-reminder';
    protected $description = 'Send a scheduled reminder email';

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //Select the email from the users that have the isMailable flag
        $users = User::where('isMailable', 1)->get();
        $send_date = now()->format('Y-m-d H:i:s');
        foreach ($users as $user) {
            Mail::to($user->email)->send(new DailyReportFoodPermits($send_date));
        }
        //Mail::to('test@serha.gov.jm')->send(new DailyReportFoodPermits());
    }
}
