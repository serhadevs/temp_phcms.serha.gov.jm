<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use App\Models\PermitApplication;
use App\Models\PermitCategory;
use App\Models\PermitTestResults;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;


class PermitTestResultsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function outstanding(Request $request)
    {
        date_default_timezone_set('Etc/GMT+5');
        $filterTimeline = "";
        $id = $request->route('id');
        $today = date_format(new Datetime(), "Y-m-d");

        if ($id == "0") {
            $outstanding_permits = Payments::with('permitApplications.permitCategory', 'permitApplications.testResults')
                ->has('permitApplications')
                ->where('facility_id', auth()->user()->facility_id)
                ->where('application_type_id', 1)
                ->where('created_at', '>', $today)
                ->doesntHave('permitApplications.testResults')
                ->get();
            return view('test_center.food_handlers_permit.oustanding', compact('outstanding_permits'));
        } else if ($id == "1") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
        } else if ($id == "7") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        } else if ($id == "30") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        } else if ($id == "90") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        }

        $outstanding_permits = Payments::with('permitApplications.permitCategory', 'permitApplications.testResults')
            ->where('facility_id', auth()->user()->facility_id)
            ->has('permitApplications')
            ->where('application_type_id', 1)
            ->whereBetween('created_at', [$filterTimeline, $today])
            ->doesntHave('permitApplications.testResults')
            ->get();

        return view('test_center.food_handlers_permit.oustanding', compact('outstanding_permits'));
    }

    public function outstandingCustom(Request $request)
    {
        date_default_timezone_set('Etc/GMT+5');
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'interval' => 'nullable|numeric|max:6'
        ]);

        $outstanding_permits = Payments::with('permitApplications.permitCategory', 'permitApplications.testResults')
            ->where('facility_id', auth()->user()->facility_id)
            ->has('permitApplications')
            ->where('application_type_id', 1)
            ->whereBetween('created_at', [$timeline['starting_date'], $timeline['ending_date'] . ' 23:59:59'])
            ->doesntHave('permitApplications.testResults')
            ->get();

        return view('test_center.food_handlers_permit.oustanding', compact('outstanding_permits'));
    }

    public function index(Request $request)
    {
        $test_results = [];
        date_default_timezone_set('Etc/GMT+5');
        $filterTimeline = "";
        $id = $request->route('id');
        $today = date_format(new Datetime(), "Y-m-d");
        $tonight = new DateTime($today . " 23:59:59");
        $yesterday = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
        $last_week = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        $thirty_days = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        $last_ninety_days = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");

        if ($id == "0") {
            $test_results =  PermitApplication::with('permitCategory', 'testResults')
                ->whereRelation('testResults', 'facility_id', Auth()->user()->facility_id)
                ->has('testResults')
                ->whereRelation('testResults', 'created_at', '>', $today)
                ->get();
            return view('test_center.food_handlers_permit.index', compact('test_results'));
        } else if ($id == "1") {
            $filterTimeline = $yesterday;
        } else if ($id == "7") {
            $filterTimeline = $last_week;
        } else if ($id == "30") {
            $filterTimeline = $thirty_days;
        } else if ($id == "90") {
            $filterTimeline = $last_ninety_days;
        }

        $test_results =  PermitApplication::with('permitCategory', 'user', 'testResults')
            ->whereRelation('testResults', 'facility_id', Auth()->user()->facility_id)
            ->has('testResults')
            ->whereRelation('testResults', 'created_at', '>', $filterTimeline)
            ->whereRelation('testResults', 'created_at', '<', $today)
            ->get();

        return view('test_center.food_handlers_permit.index', compact('test_results'));
    }

    public function customFilterProcessedResults(Request $request)
    {
        date_default_timezone_set('Etc/GMT+5');
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'interval' => 'nullable|numeric|max:6'
        ]);

        $test_results =  PermitApplication::with('permitCategory', 'user', 'testResults')
            ->whereRelation('testResults', 'facility_id', Auth()->user()->facility_id)
            ->has('testResults')
            ->whereRelation('testResults', 'created_at', '>', $timeline['starting_date'])
            ->whereRelation('testResults', 'created_at', '<', $timeline['ending_date'] . " 23:59:59")
            ->get();
        return view('test_center.food_handlers_permit.index', compact('test_results'));
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

    public function permitResults(Request $request)
    {
        $permit_id = $request->route('id');
        $permit_application = PermitApplication::with('appointment.examDate.examSites', 'establishmentClinics')
            ->find($permit_id);

        $permit_categories = PermitCategory::all();
        return view('test_center.food_handlers_permit.create', compact('permit_application', 'permit_categories'));
    }

    public function addPermitResults(Request $request)
    {
        $permit_results = $request->validate([
            'staff_contact' => 'required',
            'overall_score' => 'required|numeric|max:100|min:0'
        ]);

        $permit_results['application_type_id'] = 1;
        $permit_results['application_id'] = $request->application_id;
        $permit_results['test_location'] = $request->test_location;
        $permit_results['comments'] = $request->comments;
        $permit_results['user_id'] = Auth()->user()->id;
        $permit_results['test_date'] = $request->test_date;
        $permit_results['facility_id'] = Auth()->user()->facility_id;

        $new_permit_results = PermitTestResults::create($permit_results);

        if (!$new_permit_results) {
            return redirect()->route('test-results.permit.index', ['id' => 0])->with('error', 'Test Results could not be added');
        }

        return redirect()->route('test-results.permit.index', ['id' => 0])->with('success', 'Test Results successfully added');
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
