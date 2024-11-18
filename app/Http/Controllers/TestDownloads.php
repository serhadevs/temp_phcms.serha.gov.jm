<?php

namespace App\Http\Controllers;

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

    public function testCheckJob(){
        CheckZippedJobs::dispatch();
    }

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
