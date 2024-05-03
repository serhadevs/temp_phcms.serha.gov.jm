<?php

namespace App\Http\Controllers;

use App\Models\TestResult;
use App\Models\TouristEstablishments;
use App\Models\User;
use Illuminate\Http\Request;
use DateTime;

class TouristEstTestResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $today = date_format(new Datetime(), "Y-m-d");
        $filterTimeline = "";

        if ($id == "0") {
            $filterTimeline = $today;
        } else if ($id == "1") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
            $app_type_id = 6;
            $applications = TouristEstablishments::with('payments', 'user', 'testResults')
                ->has('payments')
                ->has('testResults')
                ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
                ->whereRelation('testResults', 'created_at', '>', $filterTimeline)
                ->whereRelation('testResults', 'created_at', '<', $today)
                ->get();
            return view('test_center.tourist_est.index', compact('applications', 'app_type_id'));
        } else if ($id == "7") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        } else if ($id == "30") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        } else if ($id == "90") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        }

        $app_type_id = 6;
        $applications = TouristEstablishments::with('payments', 'user', 'testResults')
            ->has('payments')
            ->has('testResults')
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereRelation('testResults', 'created_at', '>', $filterTimeline)
            ->get();

        return view('test_center.tourist_est.index', compact('applications', 'app_type_id'));
    }

    public function customIndex(Request $request)
    {
        date_default_timezone_set('Etc/GMT+5');
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'interval' => 'nullable|numeric|max:6'
        ]);

        $app_type_id = 6;

        $applications = TouristEstablishments::with('payments', 'user', 'testResults')
            ->has('payments')
            ->has('testResults')
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereRelation('testResults', 'created_at', '>', $timeline['starting_date'])
            ->whereRelation('testResults', 'created_at', '<', $timeline['ending_date'] . ' 23:59:59')
            ->get();

        return view('test_center.tourist_est.index', compact('applications', 'app_type_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $application = TouristEstablishments::with('payments', 'testResults')
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->find($id);

        return view('test_center.tourist_est.create', compact('application'));
    }

    public function outstanding($id)
    {
        $today = date_format(new Datetime(), "Y-m-d");
        $filterTimeline = "";

        if ($id == "0") {
            $filterTimeline = $today;
        } else if ($id == "1") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
            $applications = TouristEstablishments::with('payments', 'testResults')
                ->has('payments')
                ->doesntHave('testResults')
                ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
                ->whereBetween('created_at', [$filterTimeline, $today])
                ->get();

            $is_results = 1;
            return view('test_center.tourist_est.outstanding', compact('applications', 'is_results'));
        } else if ($id == "7") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        } else if ($id == "30") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        } else if ($id == "90") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        }

        $is_results = 1;

        $applications = TouristEstablishments::with('payments', 'testResults')
            ->has('payments')
            ->doesntHave('testResults')
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->where('created_at', '>', $filterTimeline)
            ->get();
        return view('test_center.tourist_est.outstanding', compact('applications', 'is_results'));
    }

    public function outstandingCustom(Request $request)
    {
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'interval' => 'nullable|numeric|max:6'
        ]);

        $applications = TouristEstablishments::with('payments', 'testResults')
            ->has('payments')
            ->doesntHave('testResults')
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('created_at', [$timeline['starting_date'], $timeline['ending_date'] . " 23:59:59"])
            ->get();
        $is_results = 1;
        return view('test_center.tourist_est.outstanding', compact('applications', 'is_results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $tourist_est_results = $request->validate([
            'staff_contact' => 'required',
            'test_date' => 'required',
            'overall_score' => 'required|numeric|min:0|max:100',
            'critical_score' => 'required|numeric|min:0|max:100',
            'comments' => 'nullable',
            'test_location' => 'required'
        ]);

        $establishment = TouristEstablishments::find($id);

        $tourist_est_results['user_id'] = auth()->user()->id;
        $tourist_est_results['facility_id'] = auth()->user()->facility_id;
        $tourist_est_results['application_type_id'] = 6;
        $tourist_est_results['application_id'] = $id;

        if (TestResult::create($tourist_est_results)) {
            return redirect()->route('test-results.tourist-establishments.index.filter', ['id' => 0])->with('success', 'Test Results has been successfully for ' . $establishment->establishment_name . '.');
        }

        return redirect()->route('test-results.tourist-establishments.index.filter', ['id' => 0])->with('error', 'Error processing test results.');
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
        $application = TouristEstablishments::with('testResults')
            ->find($id);

        return view('test_center.tourist_est.edit', compact('application'));
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
        $tourist_est_results = $request->validate([
            'staff_contact' => 'required',
            'test_date' => 'required',
            'overall_score' => 'required|numeric|min:0|max:100',
            'critical_score' => 'required|numeric|min:0|max:100',
            'comments' => 'nullable',
            'test_location' => 'required'
        ]);

        $results = TestResult::find($id);
        $establishment = TouristEstablishments::find($results->application_id);

        if ($results->update($tourist_est_results)) {
            return redirect()->route('test-results.tourist-establishments.index.filter', ['id' => 0])->with('success', 'Test Results for Tourist Establishment ' . $establishment->establishment_name . ' has been updated successfully.');
        }
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
