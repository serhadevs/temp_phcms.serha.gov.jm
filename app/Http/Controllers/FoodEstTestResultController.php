<?php

namespace App\Http\Controllers;

use App\Models\EstablishmentApplications;
use App\Models\TestResult;
use Illuminate\Http\Request;
use DateTime;

class FoodEstTestResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id = $request->route('id');
        $filterTimeline = "";
        $app_type_id = 3;

        if ($id == "0") {
            $today = date_format(new Datetime(), "Y-m-d");
            $applications = EstablishmentApplications::with('establishmentCategory', 'testResults', 'user')
                ->has('testResults')
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->whereRelation('testResults', 'created_at', '>', $today)
                ->get();
            return view('test_center.food_est.index', compact('applications', 'app_type_id'));
        } else if ($id == "1") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
        } else if ($id == "7") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        } else if ($id == "30") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        } else if ($id == "90") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        }

        $applications = EstablishmentApplications::with('establishmentCategory', 'testResults', 'user')
            ->has('testResults')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->whereRelation('testResults', 'created_at', '>', $filterTimeline)
            ->whereRelation('testResults', 'created_at', '<', date_format(new Datetime(), "Y-m-d"))
            ->get();

        return view('test_center.food_est.index', compact('applications', 'app_type_id'));
    }

    public function customIndex(Request $request)
    {
        date_default_timezone_set('Etc/GMT+5');
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'interval' => 'nullable|numeric|max:6'
        ]);

        $app_type_id = 3;

        $applications = EstablishmentApplications::with('establishmentCategory', 'testResults', 'user')
            ->has('testResults')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->whereRelation('testResults', 'created_at', '>', $timeline['starting_date'])
            ->whereRelation('testResults', 'created_at', '<', $timeline['ending_date'] . " 23:59:59")
            ->get();

        return view('test_center.food_est.index', compact('applications', 'app_type_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $application = EstablishmentApplications::with('establishmentCategory')
            ->find($request->route('id'));

        $app_type_id = '3';

        return view('test_center.food_est.create', compact('application', 'app_type_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $food_est = $request->validate([
            'visit_purpose' => 'required',
            'staff_contact' => 'required',
            'test_date' => 'required',
            'overall_score' => 'required|numeric|min:0|max:100',
            'critical_score' => 'required|numeric|min:0|max:100',
            'comments' => 'nullable',
            'application_id' => 'required',
            'test_location' => 'required'
        ]);

        $food_est["application_type_id"] = 3;
        $food_est["user_id"] = auth()->user()->id;
        $food_est["facility_id"] = auth()->user()->facility_id;

        if (TestResult::create($food_est)) {
            return redirect()->route('test-results.food-est.index', ['id' => 0])->with('success', 'Food Establishment Test Results have been entered successfully for application id: ' . $food_est['application_id'] . '.');
        } else {
            return redirect()->route('test-results.food-est.index', ['id' => 0])->with('error', 'Error processing results for application id ' . $food_est['application_id']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function outstanding(Request $request)
    {
        $id = $request->route('id');
        $today = date_format(new Datetime(), "Y-m-d");
        $filterTimeline = "";
        $app_type_id = 3;

        if ($id == "0") {
            $applications = EstablishmentApplications::with('establishmentCategory', 'testResults', 'operators', 'user')
                ->doesntHave('testResults')
                ->where('created_at', '>', $today)
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->get();

            return view('test_center.food_est.outstanding', compact('applications', 'app_type_id'));
        } else if ($id == "1") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
        } else if ($id == "7") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        } else if ($id == "30") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        } else if ($id == "90") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        }

        $applications = EstablishmentApplications::with('establishmentCategory', 'testResults', 'operators', 'user')
            ->doesntHave('testResults')
            ->where('created_at', '>', $filterTimeline)
            ->where('created_at', '<', $today)
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->get();

        return view('test_center.food_est.outstanding', compact('applications', 'app_type_id'));
    }

    public function outstandingCustom(Request $request)
    {
        date_default_timezone_set('Etc/GMT+5');
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'interval' => 'nullable|numeric|max:6'
        ]);

        $app_type_id = 3;

        $applications = EstablishmentApplications::with('establishmentCategory', 'testResults', 'operators', 'user')
            ->doesntHave('testResults')
            ->where('created_at', '>', $timeline['starting_date'])
            ->where('created_at', '<', $timeline['ending_date'] . " 23:59:59")
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->get();

        return view('test_center.food_est.outstanding', compact('applications', 'app_type_id'));
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
