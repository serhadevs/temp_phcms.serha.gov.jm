<?php

namespace App\Jobs;

use App\Mail\AfternoonReportMail;
use App\Models\EstablishmentApplications;
use App\Models\EstablishmentClinics;
use App\Models\MailingList;
use App\Models\Payments;
use App\Models\PermitApplication;
use App\Models\SignOff as ModelsSignOff;
use App\Models\SwimmingPoolsApplications;
use App\Models\TestResult;
use App\Models\TouristEstablishments;
use App\Models\User;
use App\Notifications\SignOff;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendAfternoonDailyReport implements ShouldQueue
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

        $permit_applications = PermitApplication::with('permitCategory')
            ->where('created_at', '>', '2025-05-01')
            ->get();

        $establishment_applications_count = EstablishmentApplications::with('establishmentCategory')
            ->where('created_at', '>', \Carbon\Carbon::today()->setHour(0))
            ->count();

        $swimming_pool_count = SwimmingPoolsApplications::where('created_at', '>', \Carbon\Carbon::today()->setHour(0))
            ->count();

        $establishment_clinics_count = EstablishmentClinics::where('created_at', '>', \Carbon\Carbon::today()->setHour(0))
            ->count();

        $tourist_application_count = TouristEstablishments::where('created_at', '>', \Carbon\Carbon::today()->setHour(0))
            ->count();

        $test_results_count = TestResult::where('created_at', '>', \Carbon\Carbon::today()->setHour(0))
            ->count();

        $sign_off_count = ModelsSignOff::where('created_at', '>', \Carbon\Carbon::today()->setHour(0))
            ->count();

        $total_sth_payments = Payments::where('facility_id', 1)
            ->where('created_at', '>', \Carbon\Carbon::today()->setHour(0))
            ->sum('total_cost');

        $total_stt_payments = Payments::where('facility_id', 2)
            ->where('created_at', '>', \Carbon\Carbon::today()->setHour(0))
            ->sum('total_cost');

        $total_ksa_payments = Payments::where('facility_id', 3)
            ->where('created_at', '>', \Carbon\Carbon::today()->setHour(0))
            ->sum('total_cost');

        $mailing_list = MailingList::where('is_active', 1)->get();

        foreach ($mailing_list as $mail) {
            Mail::to($mail->email)->send(new AfternoonReportMail(
                $users,
                $roles,
                $database_status,
                $permit_applications,
                $establishment_applications_count,
                $swimming_pool_count,
                $establishment_clinics_count,
                $tourist_application_count,
                $test_results_count,
                $sign_off_count,
                $total_sth_payments,
                $total_stt_payments,
                $total_ksa_payments
            ));
        }
    }
}
