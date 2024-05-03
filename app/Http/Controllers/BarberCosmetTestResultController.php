<?php

namespace App\Http\Controllers;

use App\Models\HealthCertApplications;
use App\Models\TestResult;
use Illuminate\Http\Request;
use DateTime;

class BarberCosmetTestResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        // $today = date_format(new Datetime(), "Y-m-d");
        // $filterTimeline = "";
        // if ($id == "0") {
        //     $filterTimeline = $today;
        // } else if ($id == "1") {
        //     $filterTimeline = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
        //     $applications = TouristEstablishments::with('payments', 'managers', 'services')
        //         ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
        //         ->whereBetween('created_at', [$filterTimeline, $today])
        //         ->get();
        //     return view('tourist_est.index', compact('applications'));
        // } else if ($id == "7") {
        //     $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        // } else if ($id == "30") {
        //     $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        // } else if ($id == "90") {
        //     $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        // }
    }

    public function customIndex(Request $request)
    {
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'interval' => 'nullable|numeric|max:6'
        ]);

        // $processed_results = HealthCertApplications::
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
