<?php

namespace App\Jobs;

use App\Models\PrintableApplications;
use App\Models\TouristEstablishments;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TouristEstJob implements ShouldQueue
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
        $tourist_ests = TouristEstablishments::all();

        foreach ($tourist_ests as $item) {
            $exists = PrintableApplications::withTrashed()->where('application_id', $item->id)->where('application_type_id', 6)->first();

            if (empty($exists)) {
                PrintableApplications::create([
                    'application_id' => $item->id,
                    'application_type_id' => 6
                ]);
            }
        }
        return "Tourist Est cron successfully completed";
    }
}
