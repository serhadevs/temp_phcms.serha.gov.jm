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

    public function checkDownloads(Request $request)
    {
        $start_date = "";
        $end_date = "";
        if ($request->route('num') == 1) {
            $start_date = $request->route('date') . " 00:00:00";
            $end_date = $request->route('date') . " 12:00:00";
        } else {
            $start_date = $request->route('date') . " 12:00:00";
            $end_date = $request->route('date') . " 23:59:59";
        }

        try {
            $downloads = Downloads::where('application_type_id', 1)
                ->whereBetween('created_at', ["2024-04-08 12:00:00", "2024-04-08 23:59:59"])
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
                dd($array);
                DB::beginTransaction();
                foreach ($array as $permit_no) {
                    $permit_id = PermitApplication::where('permit_no', $permit_no)->first()->id;
                    ZippedApplications::where('application_id', $permit_id)
                        ->where('application_type_id', 1)
                        ->first()
                        ->update(['written' => 1]);
                }
                DB::commit();
            }
            return "success job";
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
        // CheckPermitZippedJobs::dispatch();
        // return "success";
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
