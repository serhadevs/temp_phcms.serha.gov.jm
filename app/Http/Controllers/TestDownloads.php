<?php

namespace App\Http\Controllers;

use App\Jobs\CheckPermitZippedJobs;
use App\Jobs\CheckZippedJobs;
use App\Jobs\FoodEstJob;
use App\Jobs\PermitJob;
use App\Jobs\TouristEstJob;
use App\Models\Appointments;
use App\Models\Downloads;
use App\Models\EstablishmentApplications;
use App\Models\ExamDates;
use App\Models\PermitApplication;
use App\Models\TouristEstablishments;
use App\Models\ZippedApplications;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use DateTime;
use Illuminate\Support\Facades\Storage;
use File;
use Illuminate\Support\Facades\File as FacadesFile;

class TestDownloads extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // try {
        PermitJob::dispatch();
        FoodEstJob::dispatch();
        TouristEstJob::dispatch();

        // return 'success';
        // } catch (Exception $e) {
        //     return $e->getMessage();
        // }
    }

    public function customCheckDownloads(Request $request)
    {
        $start_date = $request->route('date') . " 00:00:00";
        $end_date = $request->route('date') . " 23:59:59";

        try {
            // if ($request->route('num') == 0) {
            $downloads = Downloads::where('application_type_id', 1)
                ->whereBetween('created_at', [$start_date, $end_date])
                ->whereBetween('application_amount', [$request->route('num_1'), $request->route('num_2')])
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
                $i = 0;
                if (file_exists(storage_path($path)) && ($file = fopen(storage_path($path), 'r')) !== false) {
                    while ($line = fgets($file)) {
                        $array[$i] =  explode('.', explode("\t", $line)[8])[0];
                        $i++;
                    }
                    fclose($file);
                }
                // $array2 = array_chunk($array, ceil(count($array) / 2))[1];
                DB::beginTransaction();
                foreach ($array as $permit_no) {
                    if ($permit = PermitApplication::where('permit_no', $permit_no)->first()) {
                        $permit_id = $permit->id;
                        if ($zip = ZippedApplications::where('application_id', $permit_id)
                            ->where('application_type_id', 1)
                            // ->where('written', NULL)
                            ->first()
                        ) {
                            $zip->update(['written' => 1]);
                        }
                    }
                }
                DB::commit();
            }
            return "success job";
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function cleanUpDownloads()
    {
        $downloads = Downloads::with('zippedApplications')
            ->where('application_type_id', 3)
            ->where('created_at', '>', '2024-01-15')
            ->where('download_date', NULL)
            ->get();

        DB::beginTransaction();
        // foreach ($downloads as $download) {
        //     // unlink(storage_path("app/public/") . $download->download_url);
        //     foreach ($download->zippedApplications as $zippedApp) {
        //         $zippedApp->update(['written' => 2]);
        //     }
        //     $download->update(['deleted_at' => '2025-01-01 00:00:00']);
        // }
        DB::commit();
    }

    public function checkDownloads(Request $request)
    {
        $start_date = $request->route('date') . " 00:00:00";
        $end_date = $request->route('date') . " 23:59:59";

        try {
            if ($request->route('num') == 0) {
                $downloads = Downloads::where('application_type_id', 1)
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->get();
            } else {
                $array = explode(',', $request->route('num'));
                $downloads = Downloads::where('application_type_id', 1)
                    ->whereIn('id', $array)
                    ->get();
            }

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
                $i = 0;
                if (file_exists(storage_path($path)) && ($file = fopen(storage_path($path), 'r')) !== false) {
                    while ($line = fgets($file)) {
                        $array[$i] =  explode('.', explode("\t", $line)[8])[0];
                        $i++;
                    }
                    fclose($file);
                }
                // $array2 = array_chunk($array, ceil(count($array) / 2))[1];
                DB::beginTransaction();
                foreach ($array as $permit_no) {
                    if ($permit = PermitApplication::where('permit_no', $permit_no)->first()) {
                        $permit_id = $permit->id;
                        if ($zip = ZippedApplications::where('application_id', $permit_id)
                            ->where('application_type_id', 1)
                            // ->where('written', NULL)
                            ->first()
                        ) {
                            $zip->update(['written' => 1]);
                        }
                    }
                }
                DB::commit();
            }
            return "success job";
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function checkFoodEstDownloads($id)
    {
        $downloads = Downloads::where('id', $id)
            ->where('touched', NULL)
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
            $i = 0;
            // dd($path);
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
                if ($establishment = EstablishmentApplications::where('permit_no', $permit_no)->first()) {
                    ZippedApplications::where('application_id', $establishment->id)
                        ->where('application_type_id', 3)
                        ->update(['written' => 1]);
                }
            }
            $download->update(['touched' => 1]);
            DB::commit();
        }

        return $id . "\n";
    }

    public function writeAllFoodEstablishments()
    {
        $file = fopen('food-establishments.txt', 'a') or die('Unable to open file!');

        foreach (
            EstablishmentApplications::with('signOff', 'user', 'testResults', 'operators')
                ->has('testResults')
                ->has('signOff')
                ->whereRelation('signOff', 'sign_off_date', '>', '2024-02-01')
                ->get() as $item
        ) {
            fwrite($file, trim(ucwords(strtolower($item->establishment_name))) . "\t" . trim(ucwords(strtolower($item->operators[0]?->name_of_operator))) . "\t"
                . trim(ucwords(strtolower($item->establishment_address))) . "\t" . trim(ucwords(strtolower($item->establishment_address))) . "\t"
                . $item->permit_no . "Z" . $item->zone . "-0010233" . "\r\n");
        }
        fclose($file);
    }

    public function testTourist()
    {
        dd(TouristEstablishments::with('managers', 'services')
            ->where('created_at', '>', '2023-07-01')
            // ->doesntHave('managers')
            // ->doesntHave('services')
            ->get());
    }

    public function clearAppointments()
    {
        $path = storage_path("app/public/test.csv");

        $handle = fopen($path, "r");
        $rows = [];
        $i = 1;
        $contents = "id,facility_id,permit_category_id,application_type_id,exam_day,exam_start_time,exam_site_id,deleted_at\n";

        while (($row = fgetcsv($handle)) !== false) {
            $rows = $row;
            $exam_dates = ExamDates::where('facility_id', $rows[0])
                ->where('permit_category_id', $rows[1])
                ->where('application_type_id', $rows[2])
                ->where('exam_day', $rows[3])
                ->where('exam_start_time', $rows[4])
                ->where('exam_site_id', $rows[5])
                ->get();
            $contents .= "Group " . $i . "," . "Group " . $i . "," . "Group " . $i . "," . "Group " . $i . "," . "Group " . $i . "," . "Group " . $i . "," . "Group " . $i . "," . "Group " . $i . "\n";

            foreach ($exam_dates as $date) {
                $contents .= $date->id . "," . $date->facility_id . "," . $date->permit_category_id . "," . $date->application_type_id . "," . $date->exam_day . "," . $date->exam_start_time . "," . $date->exam_site_id . "," . ($date->deleted_at ? $date->deleted_at : "Not Deleted") . "\n";
            }
            $i++;
        }
        fclose($handle);

        // dd($contents);
        $myfile = fopen("duplicates.csv", "w") or die("Unable to open file!");
        fwrite($myfile, $contents);
        fclose($myfile);

        return "success";
    }

    public function sanitizeAppointments()
    {
        $path = storage_path("app/public/to_delete_9.csv");

        $handle = fopen($path, "r");
        $rows = [];

        while (($row = fgetcsv($handle)) !== false) {
            DB::beginTransaction();
            $rows = $row;
            $appointments = Appointments::where('exam_date_id', $rows[0])->get();
            $exam_date = ExamDates::find($rows[0]);

            foreach ($appointments as $appointment) {
                $appointment->update(['exam_date_id' => $rows[1]]);
            }

            // dd($rows[0]);
            if ($exam_date->deleted_at == NULL) {
                $exam_date->update(['deleted_at' => '2025-04-09 18:00:00']);
            }
            DB::commit();
        }

        fclose($handle);
    }

    public function copyDownloadsToBeChecked()
    {
        $urls = [
            [13067,    "downloads/establishment-archives/STC-2023-12-01.zip"],
            [13070,    "downloads/establishment-archives/STC-2023-12-08.zip"],
            [13071,    "downloads/establishment-archives/STC-2023-12-18.zip"],
            [13072,    "downloads/establishment-archives/STC-2023-12-09.zip"],
            [13073,    "downloads/establishment-archives/STC-2023-12-05.zip"],
            [13074,    "downloads/establishment-archives/STC-2023-12-04.zip"],
            [13075,    "downloads/establishment-archives/STC-2023-12-07.zip"],
            [13077,    "downloads/establishment-archives/STC-2023-11-06.zip"],
            [13078,    "downloads/establishment-archives/STC-2023-11-20.zip"],
            [13079,    "downloads/establishment-archives/STC-2023-12-14.zip"],
            [13080,    "downloads/establishment-archives/STC-2023-12-23.zip"],
            [13081,    "downloads/establishment-archives/STC-2023-12-28.zip"],
            [13082,    "downloads/establishment-archives/STC-2023-12-19.zip"],
            [13083,    "downloads/establishment-archives/STC-2024-01-02.zip"],
            [13106,    "downloads/establishment-archives/STC-2024-02-01.zip"],
            [13110,    "downloads/establishment-archives/STC-2024-02-08.zip"],
            [13121,    "downloads/establishment-archives/STC-2024-01-31.zip"],
            [13126,    "downloads/establishment-archives/STC-2024-02-02.zip"],
            [13139,    "downloads/establishment-archives/KSA-2024-01-09.zip"],
            [13140,    "downloads/establishment-archives/KSA-2023-12-21.zip"],
            [13142,    "downloads/establishment-archives/KSA-2024-01-04.zip"],
            [13143,    "downloads/establishment-archives/KSA-2024-01-05.zip"],
            [13145,    "downloads/establishment-archives/KSA-2023-12-12.zip"],
            [13147,    "downloads/establishment-archives/STC-2024-01-25_9858.zip"],
            [13148,    "downloads/establishment-archives/STC-2024-02-29_9858.zip"],
            [13150,    "downloads/establishment-archives/STC-2024-02-21.zip"],
            [13151,    "downloads/establishment-archives/STC-2024-03-08.zip"],
            [13154,    "downloads/establishment-archives/STC-2024-03-05.zip"],
            [13238,    "downloads/establishment-archives/KSA-2024-02-09.zip"],
            [13245,    "downloads/establishment-archives/KSA-2023-11-23.zip"],
            [13246,    "downloads/establishment-archives/KSA-2024-01-30.zip"],
            [13249,    "downloads/establishment-archives/KSA-2024-01-23.zip"],
            [13263,    "downloads/establishment-archives/KSA-2024-02-12.zip"],
            [13264,    "downloads/establishment-archives/KSA-2024-01-16_4434.zip"],
            [13268,    "downloads/establishment-archives/KSA-2024-01-24.zip"],
            [13272,    "downloads/establishment-archives/KSA-2024-02-01_4434.zip"],
            [13291,    "downloads/establishment-archives/STT-2024-01-26.zip"],
            [13293,    "downloads/establishment-archives/STT-2024-02-06.zip"],
            [13294,    "downloads/establishment-archives/STT-2024-02-13.zip"],
            [13301,    "downloads/establishment-archives/STT-2023-10-05.zip"],
            [13309,    "downloads/establishment-archives/STT-2024-01-31.zip"],
            [13310,    "downloads/establishment-archives/STT-2024-01-08.zip"],
            [13313,    "downloads/establishment-archives/STT-2024-01-25.zip"],
            [13314,    "downloads/establishment-archives/STT-2024-02-02.zip"],
            [13315,    "downloads/establishment-archives/STT-2024-02-05.zip"],
            [13316,    "downloads/establishment-archives/STT-2024-02-16.zip"],
            [13317,    "downloads/establishment-archives/STT-2024-03-06.zip"],
            [13339,    "downloads/establishment-archives/STT-2024-01-04_9589.zip"],
            [13347,    "downloads/establishment-archives/STT-2024-03-01.zip"],
            [13348,    "downloads/establishment-archives/STT-2024-04-11.zip"],
            [13349,    "downloads/establishment-archives/STT-2024-02-29.zip"],
            [13361,    "downloads/establishment-archives/STT-2024-01-09_8041.zip"],
            [13369,    "downloads/establishment-archives/STT-2024-01-11_8041.zip"],
            [13370,    "downloads/establishment-archives/STT-2024-01-15_8041.zip"],
            [13373,    "downloads/establishment-archives/STT-2023-12-18.zip"],
            [13388,    "downloads/establishment-archives/STT-2024-03-07.zip"],
            [13390,    "downloads/establishment-archives/KSA-2024-02-06_3308.zip"],
            [13392,    "downloads/establishment-archives/KSA-2024-02-15_3308.zip"],
            [13393,    "downloads/establishment-archives/KSA-2024-02-13_3308.zip"],
            [13417,    "downloads/establishment-archives/KSA-2024-01-31_4796.zip"],
            [13421,    "downloads/establishment-archives/KSA-2023-11-20.zip"],
            [13424,    "downloads/establishment-archives/KSA-2024-06-10.zip"],
            [13467,    "downloads/establishment-archives/KSA-2024-01-17_5636.zip"],
            [13476,    "downloads/establishment-archives/KSA-2024-02-23_8228.zip"],
            [13477,    "downloads/establishment-archives/KSA-2024-02-28_8228.zip"],
            [13479,    "downloads/establishment-archives/KSA-2024-02-07_8228.zip"],
            [13505,    "downloads/establishment-archives/STC-2024-01-12_2810.zip"],
            [13512,    "downloads/establishment-archives/STC-2024-03-09_2810.zip"],
            [13514,    "downloads/establishment-archives/STC-2024-04-02.zip"],
            [13516,    "downloads/establishment-archives/STC-2024-03-28.zip"],
            [13517,    "downloads/establishment-archives/STC-2024-04-01.zip"],
            [13524,    "downloads/establishment-archives/STC-2024-03-23.zip"],
            [13528,    "downloads/establishment-archives/STC-2024-03-22.zip"],
            [13540,    "downloads/establishment-archives/KSA-2024-04-05_8581.zip"],
            [13543,    "downloads/establishment-archives/KSA-2024-03-26_8581.zip"],
            [13545,    "downloads/establishment-archives/KSA-2024-04-02.zip"],
            [13548,    "downloads/establishment-archives/STC-2024-02-16_8581.zip"],
            [13550,    "downloads/establishment-archives/STC-2024-03-04_8581.zip"],
            [13551,    "downloads/establishment-archives/STC-2024-03-19_8581.zip"],
            [13553,    "downloads/establishment-archives/STC-2024-03-15_8581.zip"],
            [13554,    "downloads/establishment-archives/STC-2024-03-14_8581.zip"],
            [13555,    "downloads/establishment-archives/STC-2024-03-06_8581.zip"],
            [13556,    "downloads/establishment-archives/STC-2024-03-11_8581.zip"],
            [13557,    "downloads/establishment-archives/STC-2024-03-25_8581.zip"],
            [13560,    "downloads/establishment-archives/STC-2024-04-09_8581.zip"],
            [13561,    "downloads/establishment-archives/STC-2024-04-10_8581.zip"],
            [13562,    "downloads/establishment-archives/STC-2024-04-05.zip"],
            [13563,    "downloads/establishment-archives/STC-2024-03-26_8581.zip"],
            [13564,    "downloads/establishment-archives/STC-2024-04-15.zip"],
            [13565,    "downloads/establishment-archives/STC-2024-04-19_8581.zip"],
            [13566,    "downloads/establishment-archives/STC-2024-04-08_8581.zip"],
            [13567,    "downloads/establishment-archives/STC-2024-04-03_8581.zip"],
            [13568,    "downloads/establishment-archives/STC-2024-04-16_8581.zip"],
            [13569,    "downloads/establishment-archives/STC-2024-04-18_8581.zip"],
            [13570,    "downloads/establishment-archives/STC-2024-04-04.zip"],
            [13571,    "downloads/establishment-archives/STC-2024-03-27_8581.zip"],
            [13572,    "downloads/establishment-archives/STC-2024-03-29.zip"],
            [13573,    "downloads/establishment-archives/STC-2024-03-21_8581.zip"],
            [13574,    "downloads/establishment-archives/STC-2024-04-12_8581.zip"],
            [13577,    "downloads/establishment-archives/STC-2024-05-02.zip"],
            [13579,    "downloads/establishment-archives/STC-2024-04-29.zip"],
            [13594,    "downloads/establishment-archives/STC-2024-05-19.zip"],
            [13595,    "downloads/establishment-archives/STC-2024-04-24.zip"],
            [13600,    "downloads/establishment-archives/STC-2024-05-03_4881.zip"],
            [13601,    "downloads/establishment-archives/STC-2024-05-06_4881.zip"],
            [13614,    "downloads/establishment-archives/STT-2023-11-03_2776.zip"],
            [13621,    "downloads/establishment-archives/STT-2023-12-20.zip"],
            [13646,    "downloads/establishment-archives/KSA-2024-01-15_5261.zip"],
            [13647,    "downloads/establishment-archives/KSA-2024-01-10.zip"],
            [13650,    "downloads/establishment-archives/KSA-2024-06-11.zip"],
            [13652,    "downloads/establishment-archives/STC-2024-04-25_5261.zip"],
            [13704,    "downloads/establishment-archives/KSA-2024-03-06_1919.zip"],
            [13705,    "downloads/establishment-archives/KSA-2024-02-05_1919.zip"],
            [13706,    "downloads/establishment-archives/KSA-2024-04-15_1919.zip"],
            [13708,    "downloads/establishment-archives/KSA-2024-03-01_1919.zip"],
            [13711,    "downloads/establishment-archives/KSA-2024-03-27_1919.zip"],
            [13713,    "downloads/establishment-archives/KSA-2024-03-14_1919.zip"],
            [13714,    "downloads/establishment-archives/KSA-2024-03-11_1919.zip"],
            [13716,    "downloads/establishment-archives/KSA-2024-03-12_1919.zip"],
            [13726,    "downloads/establishment-archives/KSA-2024-03-22.zip"],
            [13750,    "downloads/establishment-archives/KSA-2024-03-04_3188.zip"],
            [13751,    "downloads/establishment-archives/KSA-2024-03-15_3188.zip"],
            [13756,    "downloads/establishment-archives/KSA-2024-03-07_3188.zip"],
            [13757,    "downloads/establishment-archives/KSA-2024-04-10_3188.zip"],
            [13758,    "downloads/establishment-archives/KSA-2024-02-22_3188.zip"],
            [13759,    "downloads/establishment-archives/KSA-2024-03-28_3188.zip"],
            [13760,    "downloads/establishment-archives/KSA-2024-04-18_3188.zip"],
            [13761,    "downloads/establishment-archives/KSA-2024-02-20_3188.zip"],
            [13762,    "downloads/establishment-archives/STT-2023-08-24.zip"],
            [13768,    "downloads/establishment-archives/STT-2023-11-07.zip"],
            [13769,    "downloads/establishment-archives/STC-2024-03-13_3188.zip"],
            [13771,    "downloads/establishment-archives/STC-2024-05-08_3188.zip"],
            [13777,    "downloads/establishment-archives/KSA-2024-04-04_6309.zip"],
            [13779,    "downloads/establishment-archives/KSA-2024-04-03_6309.zip"],
            [13780,    "downloads/establishment-archives/KSA-2024-04-19_6309.zip"],
            [13782,    "downloads/establishment-archives/KSA-2024-04-08_6309.zip"],
            [13783,    "downloads/establishment-archives/KSA-2024-04-29.zip"],
            [13784,    "downloads/establishment-archives/KSA-2024-05-02_6309.zip"],
            [13786,    "downloads/establishment-archives/KSA-2024-04-11_6309.zip"],
            [13788,    "downloads/establishment-archives/KSA-2024-04-22_6309.zip"],
            [13789,    "downloads/establishment-archives/KSA-2024-05-03_6309.zip"],
            [13790,    "downloads/establishment-archives/KSA-2024-05-06_6309.zip"],
            [13791,    "downloads/establishment-archives/KSA-2024-05-01.zip"],
            [13792,    "downloads/establishment-archives/KSA-2024-04-25_6309.zip"],
            [13800,    "downloads/establishment-archives/KSA-2024-05-20.zip"],
            [13802,    "downloads/establishment-archives/KSA-2024-05-09.zip"],
            [13804,    "downloads/establishment-archives/KSA-2024-05-21.zip"],
            [13807,    "downloads/establishment-archives/KSA-2024-07-10.zip"],
            [13809,    "downloads/establishment-archives/KSA-2024-06-27.zip"],
            [13812,    "downloads/establishment-archives/STC-2024-02-15_6309.zip"],
            [13813,    "downloads/establishment-archives/STC-2024-03-07_6309.zip"],
            [13814,    "downloads/establishment-archives/STC-2024-05-16_6309.zip"],
            [13815,    "downloads/establishment-archives/STC-2024-05-07_6309.zip"],
            [13816,    "downloads/establishment-archives/STC-2024-05-10_6309.zip"],
            [13817,    "downloads/establishment-archives/STC-2024-05-09_6309.zip"],
            [13818,    "downloads/establishment-archives/STC-2024-05-01_6309.zip"],
            [13820,    "downloads/establishment-archives/STC-2024-04-30_6309.zip"],
            [13821,    "downloads/establishment-archives/STC-2024-05-27_6309.zip"],
            [13823,    "downloads/establishment-archives/STC-2024-05-17_6309.zip"],
            [13824,    "downloads/establishment-archives/STC-2024-05-20_6309.zip"],
            [13825,    "downloads/establishment-archives/STC-2024-05-22_6309.zip"],
            [13827,    "downloads/establishment-archives/STC-2024-05-21_6309.zip"],
            [13831,    "downloads/establishment-archives/STC-2024-05-15.zip"],
            [13841,    "downloads/establishment-archives/STC-2024-06-05.zip"],
            [13843,    "downloads/establishment-archives/STC-2024-06-04.zip"],
            [13844,    "downloads/establishment-archives/STC-2024-05-25.zip"],
            [13845,    "downloads/establishment-archives/STC-2024-05-30.zip"],
            [13854,    "downloads/establishment-archives/KSA-2024-03-18_1940.zip"],
            [13858,    "downloads/establishment-archives/KSA-2024-06-26_1554.zip"],
            [13859,    "downloads/establishment-archives/KSA-2024-07-11_1554.zip"],
            [13862,    "downloads/establishment-archives/KSA-2024-07-12_1554.zip"],
            [13863,    "downloads/establishment-archives/KSA-2024-07-15.zip"],
            [13869,    "downloads/establishment-archives/STT-2024-02-19_2671.zip"],
            [13873,    "downloads/establishment-archives/STT-2024-01-05_2671.zip"],
            [13875,    "downloads/establishment-archives/STT-2024-04-19_2671.zip"],
            [13906,    "downloads/establishment-archives/STT-2024-05-27.zip"],
            [13917,    "downloads/establishment-archives/STT-2023-11-10_2131.zip"],
            [13918,    "downloads/establishment-archives/STT-2023-11-15_2131.zip"],
            [13919,    "downloads/establishment-archives/STT-2023-11-20_2131.zip"],
            [13932,    "downloads/establishment-archives/STT-2023-12-08_2131.zip"],
            [13936,    "downloads/establishment-archives/STT-2024-01-02_2131.zip"],
            [13938,    "downloads/establishment-archives/STT-2024-02-07_2131.zip"],
            [13940,    "downloads/establishment-archives/STT-2024-01-10_2131.zip"],
            [13941,    "downloads/establishment-archives/STT-2024-02-09_2131.zip"],
            [13942,    "downloads/establishment-archives/STT-2024-03-15.zip"],
            [13944,    "downloads/establishment-archives/STT-2024-05-06_2131.zip"],
            [13945,    "downloads/establishment-archives/STT-2024-05-13_2131.zip"],
            [13947,    "downloads/establishment-archives/STT-2024-05-07_2131.zip"],
            [14017,    "downloads/establishment-archives/KSA-2024-07-17_4652.zip"],
            [14020,    "downloads/establishment-archives/KSA-2024-06-19_4652.zip"],
            [14022,    "downloads/establishment-archives/KSA-2024-07-09_4652.zip"],
            [14023,    "downloads/establishment-archives/KSA-2024-07-25.zip"],
            [14025,    "downloads/establishment-archives/STT-2023-10-19_4652.zip"],
            [14026,    "downloads/establishment-archives/STT-2023-10-27_4652.zip"],
            [14027,    "downloads/establishment-archives/STT-2023-12-15_4652.zip"],
            [14029,    "downloads/establishment-archives/STT-2023-11-28_4652.zip"],
            [14032,    "downloads/establishment-archives/STT-2023-12-11_4652.zip"],
            [14033,    "downloads/establishment-archives/STT-2023-12-12_4652.zip"],
            [14034,    "downloads/establishment-archives/STT-2024-01-03_4652.zip"],
            [14037,    "downloads/establishment-archives/STT-2023-11-13_4652.zip"],
            [14039,    "downloads/establishment-archives/STT-2023-12-07_4652.zip"],
            [14041,    "downloads/establishment-archives/STT-2024-12-07.zip"],
            [14048,    "downloads/establishment-archives/KSA-2024-07-19_1644.zip"],
            [14049,    "downloads/establishment-archives/KSA-2024-07-23_1644.zip"],
            [14051,    "downloads/establishment-archives/KSA-2024-07-22.zip"],
            [14067,    "downloads/establishment-archives/STC-2024-05-13_7535.zip"],
            [14068,    "downloads/establishment-archives/STC-2024-05-28_7535.zip"],
            [14069,    "downloads/establishment-archives/STC-2024-06-13_7535.zip"],
            [14071,    "downloads/establishment-archives/STC-2024-06-07_7535.zip"],
            [14072,    "downloads/establishment-archives/STC-2024-06-03_7535.zip"],
            [14073,    "downloads/establishment-archives/STC-2024-05-14_7535.zip"],
            [14074,    "downloads/establishment-archives/STC-2024-06-10_7535.zip"],
            [14076,    "downloads/establishment-archives/STC-2024-06-17_7535.zip"],
            [14077,    "downloads/establishment-archives/STC-2024-06-11_7535.zip"],
            [14079,    "downloads/establishment-archives/STC-2024-06-06_7535.zip"],
            [14080,    "downloads/establishment-archives/STC-2024-05-31_7535.zip"],
            [14081,    "downloads/establishment-archives/STC-2024-06-18_7535.zip"],
            [14082,    "downloads/establishment-archives/STC-2024-06-12_7535.zip"],
            [14083,    "downloads/establishment-archives/STC-2024-07-14.zip"],
            [14086,    "downloads/establishment-archives/STC-2024-06-29.zip"],
            [14090,    "downloads/establishment-archives/STC-2024-06-24.zip"],
            [14094,    "downloads/establishment-archives/STC-2024-06-26.zip"],
            [14098,    "downloads/establishment-archives/STC-2024-07-12.zip"],
            [14114,    "downloads/establishment-archives/STC-2024-03-18_2022.zip"],
            [14115,    "downloads/establishment-archives/STC-2024-04-11_2022.zip"],
            [14116,    "downloads/establishment-archives/STC-2024-04-17_2022.zip"],
            [14117,    "downloads/establishment-archives/STC-2024-06-20_2022.zip"],
            [14123,    "downloads/establishment-archives/KSA-2024-08-08.zip"],
            [14161,    "downloads/establishment-archives/STC-2024-07-19_4148.zip"],
            [14165,    "downloads/establishment-archives/STC-2024-07-18_4148.zip"],
            [14167,    "downloads/establishment-archives/STC-2024-07-30.zip"],
            [14168,    "downloads/establishment-archives/STC-2024-07-10_4148.zip"],
            [14169,    "downloads/establishment-archives/STC-2024-06-27_4148.zip"],
            [14170,    "downloads/establishment-archives/STC-2024-07-25.zip"],
            [14171,    "downloads/establishment-archives/STC-2024-07-01_4148.zip"],
            [14173,    "downloads/establishment-archives/STC-2024-07-09_4148.zip"],
            [14174,    "downloads/establishment-archives/STC-2024-07-15_4148.zip"],
            [14176,    "downloads/establishment-archives/STC-2024-06-28.zip"],
            [14177,    "downloads/establishment-archives/STC-2024-07-17.zip"],
            [14178,    "downloads/establishment-archives/STC-2024-07-08_4148.zip"],
            [14182,    "downloads/establishment-archives/STC-2024-07-29.zip"],
            [14186,    "downloads/establishment-archives/KSA-2024-08-12.zip"],
            [14188,    "downloads/establishment-archives/KSA-2024-08-14.zip"],
            [14189,    "downloads/establishment-archives/KSA-2024-07-26_4148.zip"],
            [14193,    "downloads/establishment-archives/STT-2024-05-14_4148.zip"],
            [14194,    "downloads/establishment-archives/STT-2024-05-24_4148.zip"],
            [14195,    "downloads/establishment-archives/STT-2024-01-19_4148.zip"],
            [14196,    "downloads/establishment-archives/STT-2024-05-15_4148.zip"],
            [14208,    "downloads/establishment-archives/STT-2024-06-20_4148.zip"],
            [14252,    "downloads/establishment-archives/KSA-2024-07-30_7623.zip"],
            [14253,    "downloads/establishment-archives/KSA-2024-08-16_7623.zip"],
            [14254,    "downloads/establishment-archives/KSA-2024-08-15.zip"],
            [14256,    "downloads/establishment-archives/KSA-2024-08-07.zip"],
            [14259,    "downloads/establishment-archives/STT-2024-06-07_7623.zip"],
            [14260,    "downloads/establishment-archives/STT-2024-06-14_7623.zip"],
            [14305,    "downloads/establishment-archives/KSA-2024-08-19_1663.zip"],
            [14306,    "downloads/establishment-archives/KSA-2024-04-12_1663.zip"],
            [14307,    "downloads/establishment-archives/KSA-2024-08-23_1663.zip"],
            [14309,    "downloads/establishment-archives/KSA-2024-06-21_1663.zip"],
            [14310,    "downloads/establishment-archives/KSA-2024-08-22_1663.zip"],
            [14311,    "downloads/establishment-archives/KSA-2024-08-13_1663.zip"],
            [14312,    "downloads/establishment-archives/KSA-2024-07-08_1663.zip"],
            [14314,    "downloads/establishment-archives/KSA-2024-08-02_1663.zip"],
            [14316,    "downloads/establishment-archives/STT-2023-10-10_1663.zip"],
            [14319,    "downloads/establishment-archives/STC-2024-07-16_1831.zip"],
            [14320,    "downloads/establishment-archives/STC-2024-08-24.zip"],
            [14322,    "downloads/establishment-archives/STC-2024-07-24_1831.zip"],
            [14324,    "downloads/establishment-archives/STC-2024-08-08_1831.zip"],
            [14346,    "downloads/establishment-archives/KSA-2024-05-24_8606.zip"],
            [14347,    "downloads/establishment-archives/KSA-2024-05-29_8606.zip"],
            [14348,    "downloads/establishment-archives/KSA-2024-05-22_8606.zip"],
            [14350,    "downloads/establishment-archives/KSA-2024-05-30_8606.zip"],
            [14351,    "downloads/establishment-archives/KSA-2024-06-04_8606.zip"],
            [14353,    "downloads/establishment-archives/KSA-2024-05-31_8606.zip"],
            [14354,    "downloads/establishment-archives/KSA-2024-06-12_8606.zip"],
            [14355,    "downloads/establishment-archives/KSA-2024-06-14_8606.zip"],
            [14356,    "downloads/establishment-archives/KSA-2024-06-06.zip"],
            [14357,    "downloads/establishment-archives/KSA-2024-05-28.zip"],
            [14358,    "downloads/establishment-archives/KSA-2024-06-07.zip"],
            [14362,    "downloads/establishment-archives/KSA-2024-06-20_8606.zip"],
            [14363,    "downloads/establishment-archives/KSA-2024-06-17.zip"],
            [14369,    "downloads/establishment-archives/KSA-2024-08-27_4517.zip"],
            [14415,    "downloads/establishment-archives/KSA-2024-09-05.zip"],
            [14421,    "downloads/establishment-archives/STT-2024-06-28.zip"],
            [14458,    "downloads/establishment-archives/STC-2024-08-14_5784.zip"],
            [14459,    "downloads/establishment-archives/STC-2024-08-05_5784.zip"],
            [14460,    "downloads/establishment-archives/STC-2024-08-22_5784.zip"],
            [14461,    "downloads/establishment-archives/STC-2024-08-10_5784.zip"],
            [14462,    "downloads/establishment-archives/STC-2024-08-13_5784.zip"],
            [14464,    "downloads/establishment-archives/STC-2024-08-12_5784.zip"],
            [14466,    "downloads/establishment-archives/STC-2024-08-07_5784.zip"],
            [14467,    "downloads/establishment-archives/STC-2024-08-26.zip"],
            [14470,    "downloads/establishment-archives/STC-2024-06-14_5784.zip"],
            [14471,    "downloads/establishment-archives/STC-2024-06-19_5784.zip"],
            [14472,    "downloads/establishment-archives/STC-2024-07-31_5784.zip"],
            [14473,    "downloads/establishment-archives/STC-2024-07-26_5784.zip"],
            [14475,    "downloads/establishment-archives/STC-2024-08-23.zip"],
            [14478,    "downloads/establishment-archives/STC-2024-09-01.zip"],
            [14479,    "downloads/establishment-archives/STC-2024-08-15_5784.zip"],
            [14482,    "downloads/establishment-archives/STC-2024-09-07.zip"],
            [14484,    "downloads/establishment-archives/STC-2024-08-09_5784.zip"],
            [14486,    "downloads/establishment-archives/STC-2024-08-16_5784.zip"],
            [14491,    "downloads/establishment-archives/STC-2024-09-04.zip"],
            [14492,    "downloads/establishment-archives/STC-2024-09-10.zip"],
            [14494,    "downloads/establishment-archives/KSA-2024-07-24_5784.zip"],
            [14495,    "downloads/establishment-archives/KSA-2024-06-25_5784.zip"],
            [14497,    "downloads/establishment-archives/KSA-2024-08-29_5784.zip"],
            [14498,    "downloads/establishment-archives/KSA-2024-09-13_5784.zip"],
            [14499,    "downloads/establishment-archives/KSA-2024-06-03_5784.zip"],
            [14500,    "downloads/establishment-archives/KSA-2024-08-30_5784.zip"],
            [14502,    "downloads/establishment-archives/KSA-2024-09-10.zip"],
            [14503,    "downloads/establishment-archives/KSA-2024-09-04_5784.zip"],
            [14504,    "downloads/establishment-archives/KSA-2024-09-12.zip"],
            [14505,    "downloads/establishment-archives/KSA-2024-09-18.zip"],
            [14506,    "downloads/establishment-archives/KSA-2024-07-18_5784.zip"],
            [14507,    "downloads/establishment-archives/KSA-2024-09-19.zip"],
            [14509,    "downloads/establishment-archives/KSA-2024-09-26.zip"],
            [14510,    "downloads/establishment-archives/KSA-2024-08-28.zip"],
            [14512,    "downloads/establishment-archives/STT-2024-06-17_5784.zip"],
            [14514,    "downloads/establishment-archives/STT-2024-05-20_5784.zip"],
            [14519,    "downloads/establishment-archives/STT-2024-07-31_5784.zip"],
            [14522,    "downloads/establishment-archives/STT-2024-05-28_5784.zip"],
            [14535,    "downloads/establishment-archives/STT-2024-07-18_5784.zip"],
            [14545,    "downloads/establishment-archives/STT-2024-07-23_5784.zip"],
            [14548,    "downloads/establishment-archives/STT-2024-09-10.zip"],
            [14556,    "downloads/establishment-archives/STT-2024-08-30.zip"],
            [14577,    "downloads/establishment-archives/KSA-2024-09-09_2439.zip"],
            [14578,    "downloads/establishment-archives/KSA-2024-09-24.zip"],
            [14581,    "downloads/establishment-archives/STT-2023-12-05_2439.zip"],
            [14582,    "downloads/establishment-archives/STT-2023-12-01_2439.zip"],
            [14583,    "downloads/establishment-archives/STT-2024-02-15_2439.zip"],
            [14584,    "downloads/establishment-archives/STT-2023-12-06_2439.zip"],
            [14585,    "downloads/establishment-archives/STT-2023-11-30_2439.zip"],
            [14586,    "downloads/establishment-archives/STT-2024-01-16_2439.zip"],
            [14588,    "downloads/establishment-archives/STT-2023-12-19_2439.zip"],
            [14589,    "downloads/establishment-archives/STT-2023-11-01_2439.zip"],
            [14591,    "downloads/establishment-archives/STT-2023-12-04_2439.zip"],
            [14592,    "downloads/establishment-archives/STT-2023-11-27_2439.zip"],
            [14593,    "downloads/establishment-archives/STT-2023-12-14_2439.zip"],
            [14594,    "downloads/establishment-archives/STT-2023-11-22.zip"],
            [14595,    "downloads/establishment-archives/STT-2024-01-12_2439.zip"],
            [14598,    "downloads/establishment-archives/STT-2024-05-03_2439.zip"],
            [14600,    "downloads/establishment-archives/STT-2024-05-10_2439.zip"],
            [14639,    "downloads/establishment-archives/STC-2024-06-25_7911.zip"],
            [14641,    "downloads/establishment-archives/KSA-2024-10-10.zip"],
            [14644,    "downloads/establishment-archives/KSA-2024-10-02.zip"],
            [14646,    "downloads/establishment-archives/KSA-2024-10-01.zip"],
            [14647,    "downloads/establishment-archives/KSA-2024-10-03.zip"],
            [14661,    "downloads/establishment-archives/STT-2024-08-08_7911.zip"],
            [14674,    "downloads/establishment-archives/KSA-2024-10-04_3933.zip"],
            [14677,    "downloads/establishment-archives/KSA-2024-10-16.zip"],
            [14707,    "downloads/establishment-archives/STC-2024-07-27_4313.zip"],
            [14708,    "downloads/establishment-archives/STC-2024-08-28.zip"],
            [14710,    "downloads/establishment-archives/STT-2024-03-12.zip"],
            [14711,    "downloads/establishment-archives/STT-2023-12-13_4313.zip"],
            [14712,    "downloads/establishment-archives/STT-2024-06-06_4313.zip"],
            [14715,    "downloads/establishment-archives/STT-2024-07-15_4313.zip"],
            [14716,    "downloads/establishment-archives/STT-2024-06-19_4313.zip"],
            [14830,    "downloads/establishment-archives/KSA-2024-10-15_2297.zip"],
            [14833,    "downloads/establishment-archives/KSA-2024-10-17.zip"],
            [14852,    "downloads/establishment-archives/STC-2024-09-12_2436.zip"],
            [14854,    "downloads/establishment-archives/STC-2024-09-02_2436.zip"],
            [14855,    "downloads/establishment-archives/STC-2024-09-11_2436.zip"],
            [14857,    "downloads/establishment-archives/STC-2024-09-05_2436.zip"],
            [14858,    "downloads/establishment-archives/STC-2024-08-29_2436.zip"],
            [14860,    "downloads/establishment-archives/STC-2024-09-25.zip"],
            [14861,    "downloads/establishment-archives/STC-2024-09-06_2436.zip"],
            [14862,    "downloads/establishment-archives/STC-2024-09-16.zip"],
            [14863,    "downloads/establishment-archives/STC-2024-09-09_2436.zip"],
            [14864,    "downloads/establishment-archives/STC-2024-08-19_2436.zip"],
            [14865,    "downloads/establishment-archives/STC-2024-08-27_2436.zip"],
            [14867,    "downloads/establishment-archives/STC-2024-09-13_2436.zip"],
            [14869,    "downloads/establishment-archives/STC-2024-09-03_2436.zip"],
            [14871,    "downloads/establishment-archives/STC-2024-09-23.zip"],
            [14874,    "downloads/establishment-archives/STC-2024-09-27.zip"],
            [14878,    "downloads/establishment-archives/KSA-2024-04-24_2436.zip"],
            [14879,    "downloads/establishment-archives/KSA-2024-09-17_2436.zip"],
            [14880,    "downloads/establishment-archives/KSA-2024-09-03_2436.zip"],
            [14881,    "downloads/establishment-archives/KSA-2024-10-22.zip"],
            [14882,    "downloads/establishment-archives/KSA-2024-06-13_2436.zip"],
            [14883,    "downloads/establishment-archives/KSA-2024-01-19_2436.zip"],
            [14884,    "downloads/establishment-archives/KSA-2024-06-05_2436.zip"],
            [14885,    "downloads/establishment-archives/KSA-2024-10-11_2436.zip"],
            [14887,    "downloads/establishment-archives/KSA-2024-09-11_2436.zip"],
            [14888,    "downloads/establishment-archives/KSA-2024-10-08_2436.zip"],
            [14890,    "downloads/establishment-archives/KSA-2024-10-18_2436.zip"],
            [14895,    "downloads/establishment-archives/STT-2024-01-17_2436.zip"],
            [14896,    "downloads/establishment-archives/STT-2024-01-18_2436.zip"],
            [14897,    "downloads/establishment-archives/STT-2024-06-11_2436.zip"],
            [14898,    "downloads/establishment-archives/STT-2024-01-30_2436.zip"],
            [14899,    "downloads/establishment-archives/STT-2024-06-05_2436.zip"],
            [14900,    "downloads/establishment-archives/STT-2024-05-16_2436.zip"],
            [14901,    "downloads/establishment-archives/STT-2024-05-02_2436.zip"],
            [14906,    "downloads/establishment-archives/STT-2024-07-22_2436.zip"],
            [14907,    "downloads/establishment-archives/STT-2024-08-14_2436.zip"],
            [14908,    "downloads/establishment-archives/STT-2024-08-07_2436.zip"],
            [14913,    "downloads/establishment-archives/STT-2024-08-28_2436.zip"],
            [14915,    "downloads/establishment-archives/STT-2024-10-07.zip"],
            [14942,    "downloads/establishment-archives/STC-2024-09-19_8251.zip"],
            [14943,    "downloads/establishment-archives/STC-2024-09-17_8251.zip"],
            [14947,    "downloads/establishment-archives/STC-2024-10-07.zip"],
            [14948,    "downloads/establishment-archives/STC-2024-09-30_8251.zip"],
            [14949,    "downloads/establishment-archives/STC-2024-09-20_8251.zip"],
            [14950,    "downloads/establishment-archives/STC-2024-10-10.zip"],
            [14952,    "downloads/establishment-archives/STC-2024-10-14.zip"],
            [14953,    "downloads/establishment-archives/STC-2024-10-01.zip"],
            [14956,    "downloads/establishment-archives/STC-2024-10-04_8251.zip"],
            [14958,    "downloads/establishment-archives/STC-2024-10-11.zip"],
            [14960,    "downloads/establishment-archives/STC-2024-09-24_8251.zip"],
            [14964,    "downloads/establishment-archives/STC-2024-10-05.zip"],
            [14966,    "downloads/establishment-archives/STC-2024-10-19.zip"],
            [14967,    "downloads/establishment-archives/KSA-2024-05-08.zip"],
            [14969,    "downloads/establishment-archives/KSA-2024-10-30.zip"],
            [14970,    "downloads/establishment-archives/KSA-2024-11-01.zip"],
            [14972,    "downloads/establishment-archives/KSA-2024-11-04.zip"],
            [14973,    "downloads/establishment-archives/KSA-2024-10-23_8251.zip"],
            [14976,    "downloads/establishment-archives/KSA-2024-10-24_8251.zip"],
            [14978,    "downloads/establishment-archives/KSA-2024-10-31.zip"],
            [14979,    "downloads/establishment-archives/STT-2024-07-10_8251.zip"],
            [14980,    "downloads/establishment-archives/STT-2024-07-09_8251.zip"],
            [14982,    "downloads/establishment-archives/STT-2024-05-09_8251.zip"],
            [14983,    "downloads/establishment-archives/STT-2024-06-13_8251.zip"],
            [14984,    "downloads/establishment-archives/STT-2024-06-04_8251.zip"],
            [14986,    "downloads/establishment-archives/STT-2024-07-16_8251.zip"],
            [14987,    "downloads/establishment-archives/STT-2024-05-30_8251.zip"],
            [14988,    "downloads/establishment-archives/STT-2024-07-17_8251.zip"],
            [14989,    "downloads/establishment-archives/STT-2024-07-12_8251.zip"],
            [14990,    "downloads/establishment-archives/STT-2024-08-13_8251.zip"],
            [14991,    "downloads/establishment-archives/STT-2024-08-20_8251.zip"],
            [14992,    "downloads/establishment-archives/STT-2024-08-26_8251.zip"],
            [14994,    "downloads/establishment-archives/STT-2024-08-19_8251.zip"],
            [14995,    "downloads/establishment-archives/STT-2024-07-30_8251.zip"],
            [15002,    "downloads/establishment-archives/STT-2024-09-02_8251.zip"],
            [15010,    "downloads/establishment-archives/STT-2024-09-24.zip"],
            [15012,    "downloads/establishment-archives/STT-2024-09-11.zip"],
            [15018,    "downloads/establishment-archives/KSA-2024-02-08_7563.zip"],
            [15019,    "downloads/establishment-archives/KSA-2024-10-28_7563.zip"],
            [15025,    "downloads/establishment-archives/KSA-2024-10-25_7563.zip"],
            [15026,    "downloads/establishment-archives/KSA-2024-10-09_7563.zip"],
            [15028,    "downloads/establishment-archives/KSA-2024-11-07_7563.zip"],
            [15036,    "downloads/establishment-archives/KSA-2024-11-06_8948.zip"],
            [15039,    "downloads/establishment-archives/KSA-2024-11-14.zip"],
            [15040,    "downloads/establishment-archives/KSA-2024-11-12_8948.zip"],
            [15041,    "downloads/establishment-archives/KSA-2024-10-14_8948.zip"],
            [15045,    "downloads/establishment-archives/STT-2024-06-18_8948.zip"],
            [15047,    "downloads/establishment-archives/STT-2024-07-19_8948.zip"],
            [15050,    "downloads/establishment-archives/STT-2024-08-09_8948.zip"],
            [15051,    "downloads/establishment-archives/STT-2024-08-12_8948.zip"],
            [15053,    "downloads/establishment-archives/STT-2024-09-17_8948.zip"],
            [15056,    "downloads/establishment-archives/STT-2024-10-01_8948.zip"],
            [15057,    "downloads/establishment-archives/STT-2024-10-11_8948.zip"],
            [15061,    "downloads/establishment-archives/STT-2024-10-02_8948.zip"],
            [15065,    "downloads/establishment-archives/STT-2024-10-03.zip"],
            [15067,    "downloads/establishment-archives/KSA-2024-11-11_8941.zip"],
            [15068,    "downloads/establishment-archives/KSA-2024-11-08_8941.zip"],
            [15069,    "downloads/establishment-archives/KSA-2024-11-20_8941.zip"],
            [15070,    "downloads/establishment-archives/KSA-2024-09-27_8941.zip"],
            [15072,    "downloads/establishment-archives/KSA-2024-11-10.zip"],
            [15074,    "downloads/establishment-archives/KSA-2024-11-18.zip"],
            [15075,    "downloads/establishment-archives/KSA-2024-11-19_8941.zip"],
            [15118,    "downloads/establishment-archives/STC-2024-10-27_8485.zip"],
            [15120,    "downloads/establishment-archives/STC-2024-10-02_8485.zip"],
            [15136,    "downloads/establishment-archives/STC-2024-11-07.zip"],
            [15138,    "downloads/establishment-archives/STC-2024-10-09_6187.zip"],
            [15140,    "downloads/establishment-archives/STC-2024-11-17.zip"],
            [15141,    "downloads/establishment-archives/STC-2024-10-08_6187.zip"],
            [15144,    "downloads/establishment-archives/STC-2024-11-08.zip"],
            [15145,    "downloads/establishment-archives/STC-2024-11-04_6187.zip"],
            [15147,    "downloads/establishment-archives/STC-2024-11-06.zip"],
            [15148,    "downloads/establishment-archives/STC-2024-10-15_6187.zip"],
            [15149,    "downloads/establishment-archives/STC-2024-10-25_6187.zip"],
            [15151,    "downloads/establishment-archives/STC-2024-10-30_6187.zip"],
            [15152,    "downloads/establishment-archives/STC-2024-10-31_6187.zip"],
            [15155,    "downloads/establishment-archives/STC-2024-10-18_6187.zip"],
            [15156,    "downloads/establishment-archives/STC-2024-10-03_6187.zip"],
            [15160,    "downloads/establishment-archives/STC-2024-10-24_6187.zip"],
            [15162,    "downloads/establishment-archives/STC-2024-10-20.zip"],
            [15163,    "downloads/establishment-archives/STT-2024-05-31_6187.zip"],
            [15164,    "downloads/establishment-archives/STT-2024-05-17_6187.zip"],
            [15165,    "downloads/establishment-archives/STT-2024-05-29_6187.zip"],
            [15167,    "downloads/establishment-archives/STT-2024-07-25_6187.zip"],
            [15168,    "downloads/establishment-archives/STT-2024-09-12_6187.zip"],
            [15169,    "downloads/establishment-archives/STT-2024-09-09_6187.zip"],
            [15171,    "downloads/establishment-archives/STT-2024-09-18_6187.zip"],
            [15173,    "downloads/establishment-archives/STT-2024-09-19_6187.zip"],
            [15185,    "downloads/establishment-archives/STT-2024-11-13.zip"],
            [15186,    "downloads/establishment-archives/STT-2024-11-11.zip"],
            [15191,    "downloads/establishment-archives/KSA-2024-10-29_9855.zip"],
            [15193,    "downloads/establishment-archives/KSA-2024-11-28.zip"],
            [15194,    "downloads/establishment-archives/KSA-2024-12-02.zip"],
            [15195,    "downloads/establishment-archives/KSA-2024-11-26_9855.zip"],
            [15196,    "downloads/establishment-archives/KSA-2024-11-25_9855.zip"],
            [15198,    "downloads/establishment-archives/STT-2024-09-03_9855.zip"],
            [15199,    "downloads/establishment-archives/STT-2024-07-11_9855.zip"],
            [15200,    "downloads/establishment-archives/STT-2024-06-10_9855.zip"],
            [15201,    "downloads/establishment-archives/STT-2024-07-29_9855.zip"],
            [15202,    "downloads/establishment-archives/STT-2024-08-15_9855.zip"],
            [15203,    "downloads/establishment-archives/STT-2024-08-29_9855.zip"],
            [15204,    "downloads/establishment-archives/STT-2024-10-15_9855.zip"],
            [15205,    "downloads/establishment-archives/STT-2024-08-05.zip"],
            [15208,    "downloads/establishment-archives/STT-2024-09-04_9855.zip"],
            [15209,    "downloads/establishment-archives/STT-2024-10-10_9855.zip"],
            [15210,    "downloads/establishment-archives/STT-2024-09-06_9855.zip"],
            [15213,    "downloads/establishment-archives/STT-2024-04-29_9855.zip"],
            [15215,    "downloads/establishment-archives/STT-2024-10-31.zip"],
            [15217,    "downloads/establishment-archives/STT-2024-11-18.zip"],
            [15238,    "downloads/establishment-archives/KSA-2024-11-29_3656.zip"],
            [15239,    "downloads/establishment-archives/KSA-2024-12-12.zip"],
            [15240,    "downloads/establishment-archives/KSA-2024-12-10.zip"],
            [15241,    "downloads/establishment-archives/KSA-2024-11-13_3656.zip"],
            [15242,    "downloads/establishment-archives/KSA-2024-11-22_3656.zip"],
            [15243,    "downloads/establishment-archives/KSA-2024-12-06.zip"],
            [15244,    "downloads/establishment-archives/KSA-2024-11-27_3656.zip"],
            [15245,    "downloads/establishment-archives/KSA-2024-11-21_3656.zip"],
            [15246,    "downloads/establishment-archives/KSA-2024-12-04_3656.zip"],
            [15247,    "downloads/establishment-archives/KSA-2024-12-05.zip"],
            [15248,    "downloads/establishment-archives/STT-2024-11-12_3656.zip"],
            [15250,    "downloads/establishment-archives/STT-2024-10-09_3656.zip"],
            [15251,    "downloads/establishment-archives/STT-2024-11-14_3656.zip"],
            [15254,    "downloads/establishment-archives/STT-2024-10-08_3656.zip"],
            [15265,    "downloads/establishment-archives/STT-2024-10-14_4041.zip"],
            [15266,    "downloads/establishment-archives/STT-2024-06-12_4041.zip"],
            [15267,    "downloads/establishment-archives/STT-2024-10-04_4041.zip"],
            [15268,    "downloads/establishment-archives/STT-2024-08-16_4041.zip"],
            [15269,    "downloads/establishment-archives/STT-2024-07-26_4041.zip"],
            [15270,    "downloads/establishment-archives/STT-2024-09-13_4041.zip"],
            [15271,    "downloads/establishment-archives/STT-2024-09-05_4041.zip"],
            [15272,    "downloads/establishment-archives/STT-2024-09-20_4041.zip"],
            [15273,    "downloads/establishment-archives/STT-2024-08-23_4041.zip"],
            [15274,    "downloads/establishment-archives/STT-2024-09-16_4041.zip"],
            [15275,    "downloads/establishment-archives/STT-2024-10-28_4041.zip"],
            [15276,    "downloads/establishment-archives/STT-2024-10-30_4041.zip"],
            [15277,    "downloads/establishment-archives/STT-2024-10-16_4041.zip"],
            [15278,    "downloads/establishment-archives/STT-2024-10-18_4041.zip"],
            [15279,    "downloads/establishment-archives/STT-2024-11-15_4041.zip"],
            [15280,    "downloads/establishment-archives/STT-2024-11-07_4041.zip"],
            [15281,    "downloads/establishment-archives/STT-2024-10-29.zip"],
            [15282,    "downloads/establishment-archives/STT-2024-11-01.zip"],
            [15283,    "downloads/establishment-archives/STT-2024-11-08_4041.zip"],
            [15284,    "downloads/establishment-archives/STT-2024-11-04.zip"],
            [15285,    "downloads/establishment-archives/STT-2024-11-19.zip"],
            [15286,    "downloads/establishment-archives/STC-2024-11-16.zip"],
            [15287,    "downloads/establishment-archives/STC-2024-11-02_1934.zip"],
            [15288,    "downloads/establishment-archives/STC-2024-11-15_1934.zip"],
            [15290,    "downloads/establishment-archives/STC-2024-11-12_1934.zip"],
            [15291,    "downloads/establishment-archives/STC-2024-12-11.zip"],
            [15292,    "downloads/establishment-archives/STC-2024-11-29_1934.zip"],
            [15293,    "downloads/establishment-archives/STC-2024-11-18_1934.zip"],
            [15294,    "downloads/establishment-archives/STC-2024-11-19.zip"],
            [15295,    "downloads/establishment-archives/STC-2024-11-28.zip"],
            [15296,    "downloads/establishment-archives/STC-2024-10-28_1934.zip"],
            [15297,    "downloads/establishment-archives/STC-2024-10-22_1934.zip"],
            [15299,    "downloads/establishment-archives/STC-2024-11-14_1934.zip"],
            [15300,    "downloads/establishment-archives/STC-2024-11-11_1934.zip"],
            [15301,    "downloads/establishment-archives/STC-2024-11-13_1934.zip"],
            [15302,    "downloads/establishment-archives/STC-2024-12-04.zip"],
            [15303,    "downloads/establishment-archives/STC-2024-12-02.zip"],
            [15304,    "downloads/establishment-archives/STC-2024-11-26.zip"],
            [15305,    "downloads/establishment-archives/STC-2024-11-01_1934.zip"],
            [15306,    "downloads/establishment-archives/STC-2024-10-17_1934.zip"],
            [15307,    "downloads/establishment-archives/STC-2024-11-21.zip"],
            [15308,    "downloads/establishment-archives/STC-2024-12-06.zip"],
            [15309,    "downloads/establishment-archives/STC-2024-10-29_1934.zip"],
            [15310,    "downloads/establishment-archives/STC-2024-12-09.zip"],
            [15311,    "downloads/establishment-archives/STC-2024-12-05.zip"],
            [15312,    "downloads/establishment-archives/STC-2024-11-22.zip"],
            [15314,    "downloads/establishment-archives/STC-2024-11-25.zip"],
            [15315,    "downloads/establishment-archives/STC-2024-12-03.zip"],
            [15316,    "downloads/establishment-archives/STC-2024-11-09.zip"]
        ];

        foreach ($urls as $url) {
            // storage_path('app/public/'
            FacadesFile::copy(storage_path('app/public/' . $url[1]), storage_path('app/public/copies/' . $url[0] . '.zip'));
        }

        return 'success';
    }


    public function sanitizeAppointmentsParams($old_date_id, $new_date_id)
    {
        DB::beginTransaction();
        $appointments = Appointments::where('exam_date_id', $old_date_id)->get();
        $old_exam_date = ExamDates::find($old_date_id);

        $new_exam_date = ExamDates::find($new_date_id);

        foreach ($appointments as $appointment) {
            $appointment->update(['exam_date_id' => $new_date_id]);
        }

        if ($old_exam_date->deleted_at == NULL) {
            $old_exam_date->update(['deleted_at' => '2025-04-09 18:00:00']);
        }

        if ($new_exam_date->deleted_at != NULL) {
            $new_exam_date->update(['deleted_at' => NULL]);
        }
        DB::commit();

        return $old_date_id . "," . $new_date_id . "\n";
    }

    // public function testCheckJob(){
    //     CheckZippedJobs::dispatch();
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
