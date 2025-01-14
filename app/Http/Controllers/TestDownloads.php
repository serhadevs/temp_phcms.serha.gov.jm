<?php

namespace App\Http\Controllers;

use App\Jobs\CheckPermitZippedJobs;
use App\Jobs\CheckZippedJobs;
use App\Jobs\FoodEstJob;
use App\Jobs\PermitJob;
use App\Jobs\TouristEstJob;
use App\Models\Downloads;
use App\Models\EstablishmentApplications;
use App\Models\PermitApplication;
use App\Models\TouristEstablishments;
use App\Models\ZippedApplications;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

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
        foreach ($downloads as $download) {
            unlink(storage_path("app/public/") . $download->download_url);
            foreach ($download->zippedApplications as $zippedApp) {
                $zippedApp->update(['written' => 2]);
            }
            $download->update(['deleted_at' => '2025-01-01 00:00:00']);
        }
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
        // $downloads = DB::table('downloads')->whereBetween('created_at', [$date_1 . ' ' . $time_1, $date_2 . ' ' . $time_2])
        //     ->where('application_type_id', 3)
        //     ->get();

        // // dd($downloads);
        $downloads = DB::table('downloads')
            ->where('id', $id)
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
            if (file_exists(storage_path($path)) && ($file = fopen(storage_path($path), 'r')) !== false) {
                while ($line = fgets($file)) {
                    explode('Z', explode("\t", $line)[5])[0];
                    $array[$i] =  explode('Z', explode("\t", $line)[5])[0];
                    $i++;
                }
                fclose($file);
            }
            // dd($array);
            DB::beginTransaction();
            foreach ($array as $permit_no) {
                if ($establishment = EstablishmentApplications::where('permit_no', $permit_no)->first()) {
                    ZippedApplications::where('application_id', $establishment->id)
                        ->where('application_type_id', 3)
                        ->update(['written' => 1]);
                }
            }
            DB::commit();
        }
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
