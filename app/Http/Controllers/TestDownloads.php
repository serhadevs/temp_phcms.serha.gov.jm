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
        $downloads = Downloads::whereIn('id', [
            12966,
            12967,
            12968,
            12969,
            12970,
            12971,
            12972,
            12973,
            12974,
            12975,
            12976,
            12977,
            12978,
            12979,
            12980,
            12981,
            12982,
            12983,
            12984,
            12985,
            12986,
            12987,
            13064,
            13065,
            13066,
            13067,
            13068,
            13069,
            13070,
            13071,
            13072,
            13073,
            13074,
            13075,
            13076,
            13077,
            13078,
            13079,
            13081,
            13082,
            13083,
            13147,
            13148,
            13264,
            13272,
            13339,
            13361,
            13369,
            13370,
            13390,
            13392,
            13393,
            13417,
            13461,
            13467,
            13476,
            13477,
            13479,
            13505,
            13512,
            13540,
            13543,
            13548,
            13550,
            13551,
            13553,
            13554,
            13555,
            13556,
            13557,
            13560,
            13561,
            13563,
            13565,
            13566,
            13567,
            13568,
            13569,
            13571,
            13573,
            13574,
            13600,
            13601,
            13646,
            13652,
            13704,
            13705,
            13706,
            13708,
            13711,
            13713,
            13714,
            13716,
            13750,
            13751,
            13756,
            13757,
            13758,
            13759,
            13760,
            13761,
            13769,
            13771,
            13777,
            13779,
            13780,
            13782,
            13784,
            13786,
            13788,
            13789,
            13790,
            13792,
            13793,
            13812,
            13813,
            13814,
            13815,
            13816,
            13817,
            13818,
            13820,
            13821,
            13823,
            13824,
            13825,
            13826,
            13827,
            13854,
            13858,
            13859,
            13869,
            13873,
            13875,
            13919,
            13932,
            13936,
            13938,
            13940,
            13941,
            13944,
            13945,
            13947,
            14017,
            14020,
            14022,
            14025,
            14027,
            14032,
            14033,
            14034,
            14048,
            14049,
            14067,
            14068,
            14069,
            14071,
            14072,
            14073,
            14074,
            14076,
            14077,
            14079,
            14080,
            14081,
            14082,
            14114,
            14115,
            14116,
            14117,
            14118,
            14161,
            14165,
            14168,
            14169,
            14171,
            14172,
            14173,
            14174,
            14178,
            14180,
            14189,
            14193,
            14194,
            14195,
            14196,
            14208,
            14252,
            14253,
            14259,
            14260,
            14305,
            14306,
            14307,
            14309,
            14310,
            14311,
            14312,
            14314,
            14319,
            14322,
            14324,
            14346,
            14347,
            14348,
            14349,
            14350,
            14351,
            14353,
            14354,
            14355,
            14362,
            14369,
            14458,
            14459,
            14460,
            14461,
            14462,
            14464,
            14466,
            14470,
            14471,
            14472,
            14473,
            14476,
            14479,
            14484,
            14486,
            14494,
            14495,
            14497,
            14498,
            14499,
            14500,
            14503,
            14506,
            14512,
            14514,
            14519,
            14522,
            14535,
            14545,
            14575,
            14577,
            14581,
            14582,
            14583,
            14584,
            14585,
            14586,
            14588,
            14591,
            14592,
            14593,
            14595,
            14598,
            14600,
            14639,
            14661,
            14674,
            14707,
            14711,
            14712,
            14715,
            14716,
            14830,
            14852,
            14854,
            14855,
            14857,
            14858,
            14861,
            14863,
            14864,
            14865,
            14867,
            14869,
            14878,
            14879,
            14880,
            14882,
            14883,
            14884,
            14885,
            14887,
            14888,
            14890,
            14895,
            14896,
            14897,
            14898,
            14899,
            14900,
            14901,
            14906,
            14907,
            14908,
            14913,
            14942,
            14943,
            14948,
            14949,
            14956,
            14960,
            14973,
            14976,
            14979,
            14980,
            14982,
            14983,
            14984,
            14986,
            14987,
            14988,
            14989,
            14990,
            14991,
            14992,
            14994,
            14995,
            15002,
            15018,
            15019,
            15025,
            15026,
            15028,
            15036,
            15040,
            15041,
            15045,
            15047,
            15050,
            15051,
            15053,
            15056,
            15057,
            15061,
            15067,
            15068,
            15069,
            15070,
            15075,
            15118,
            15120,
            15138,
            15141,
            15145,
            15148,
            15149,
            15151,
            15152,
            15155,
            15156,
            15160,
            15163,
            15164,
            15165,
            15167,
            15168,
            15169,
            15171,
            15173,
            15191,
            15195,
            15196,
            15198,
            15199,
            15200,
            15201,
            15202,
            15203,
            15204,
            15208,
            15209,
            15210,
            15213,
            15238,
            15241,
            15242,
            15244,
            15245,
            15246,
            15248,
            15250,
            15251,
            15254,
            15265,
            15266,
            15267,
            15268,
            15269,
            15270,
            15271,
            15272,
            15273,
            15274,
            15275,
            15276,
            15277,
            15278,
            15279,
            15280,
            15283,
            15287,
            15288,
            15290,
            15292,
            15293,
            15296,
            15297,
            15299,
            15300,
            15301,
            15305,
            15306,
            15309
        ])
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
            $download->update(['touched' => 1]);
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
