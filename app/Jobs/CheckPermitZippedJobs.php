<?php

namespace App\Jobs;

use App\Models\Downloads;
use App\Models\PermitApplication;
use App\Models\ZippedApplications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Exception;

class CheckPermitZippedJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    protected $start_date;

    protected $end_date;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            $downloads = Downloads::where('application_type_id', 1)
            ->whereBetween('created_at', [$this->start_date, $this->end_date])
            // ->whereBetween('created_at', ['2024-04-08 12:00:00', '2024-04-08 23:59:59'])
            ->get();

        foreach ($downloads as $download) {
            $array = [];
            $rand_string = "";
            $file_name_separated = explode('-', explode('/', $download->download_url)[2]);
            $facility_id = $file_name_separated[0];
            $file_date = $file_name_separated[1] . '-' . $file_name_separated[2] . '-';
            if (str_contains($download->download_url, '_')) {
                $file_date = $file_date . explode('_', $file_name_separated[3])[0];
                $rand_string = str_replace('.zip', '', explode('_', $file_name_separated[3])[1]);
            } else {
                $file_date = $file_date . str_replace(".zip", '', $file_name_separated[3]);
            }
            $path = 'app/public/downloads/txts/' . $file_date .
                ($rand_string != "" ? "_" . $rand_string : '')
                . '/' . $facility_id . '/' . $facility_id . '-' .
                $file_date . '-Food_Handler_Permits.txt';
            // $path = 'app/public/downloads/txts/2024-05-29_8787/KSA/KSA-2024-05-29-Food_Handler_Permits.txt';
            $i = 0;
            // $file = fopen(storage_path($path), 'r');
            if (file_exists(storage_path($path)) && ($file = fopen(storage_path($path), 'r')) !== false) {
                while ($line = fgets($file)) {
                    $array[$i] =  explode('.', explode("\t", $line)[8])[0];
                    $i++;
                }
                fclose($file);
            }
            DB::beginTransaction();
            foreach ($array as $permit_no) {
                $permit_id = PermitApplication::where('permit_no', $permit_no)->first()->id;
                ZippedApplications::where('application_id', $permit_id)
                    ->where('application_type_id', 1)
                    ->first()
                    ->update(['written' => 1]);
            }
            DB::commit();
            return "success";
        }
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
}
