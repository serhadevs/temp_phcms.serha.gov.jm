<?php

namespace App\Jobs;

use App\Mail\MorningReportMail;
use App\Models\MailingList;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendMorningDailyReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = User::where('last_seen', '>', \Carbon\Carbon::today()->setHour(0))
            ->get();
        $roles = DB::table('roles')->pluck('name', 'id');

        $database_status = User::first() ? true : false;

        $mailing_list = MailingList::where('is_active', 1)->get();

        foreach ($mailing_list as $mail) {
            Mail::to($mail->email)->send(new MorningReportMail(
                $users,
                $roles,
                $database_status
            ));
        }
    }
}
