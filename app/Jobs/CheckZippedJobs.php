<?php

namespace App\Jobs;

use App\Models\Downloads;
use App\Models\EstablishmentApplications;
use App\Models\PermitApplication;
use App\Models\ZippedApplications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CheckZippedJobs implements ShouldQueue
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
        //Check if Permit Applications said to have been zipped were actually written to the files
        $downloads = Downloads::where('created_at', '>', '2024-02-01')
            ->where('application_type_id', 1)
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
            // return "success";
        }

        //Check if Food Establishments said to have been zipped were actually written to the files
        $downloads = Downloads::where('created_at', '>', '2024-02-01')
            ->where('application_type_id', 3)
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
            $path = 'app/public/downloads/establishment-txts/' . $file_date .
                ($rand_string != "" ? "_" . $rand_string : '')
                . '/' . $facility_id . '/' . $facility_id . '-' .
                $file_date . '-Food_Establishment.txt';
            // dd($path);
            // $path = "app/public/downloads/establishment-txts/2024-07-26/KSA/KSA-2024-07-26-Food_Establishment.txt";
            // $file = fopen(storage_path($path), 'r');
            $i = 0;
            if (file_exists(storage_path($path)) && ($file = fopen(storage_path($path), 'r')) !== false) {
                while ($line = fgets($file)) {
                    explode('Z', explode("\t", $line)[5])[0];
                    $array[$i] =  explode('Z', explode("\t", $line)[5])[0];
                    $i++;
                }
                fclose($file);
            }
            DB::beginTransaction();
            foreach ($array as $permit_no) {
                $est_id = EstablishmentApplications::where('permit_no', $permit_no)
                    ->first()
                    ->id;
                ZippedApplications::where('application_id', $est_id)
                    ->where('application_type_id', 3)
                    ->update(['written' => 1]);
            }
            DB::commit();
            // return "success";
        }
    }
}
