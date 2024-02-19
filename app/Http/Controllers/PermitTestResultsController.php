<?php

namespace App\Http\Controllers;

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
    public function outstandingResults()
    {
        $today = new DateTime();
        $six_months_to_date = date_format(date_modify($today, "-6 months"), "Y-m-d");

        $outstanding_permits = DB::table('payments')
            ->join('permit_applications', 'payments.application_id', '=', 'permit_applications.id')
            ->join('permit_categories', 'permit_applications.permit_category_id', '=', 'permit_categories.id')
            ->join('test_results', 'test_results.application_id', '=', 'payments.application_id', 'left outer')
            ->where('payments.application_type_id', '=', '1')
            ->where('payments.facility_id', '=', Auth()->user()->facility_id)
            ->where('payments.created_at', '>', $six_months_to_date)
            ->where('test_results.id', '=', NULL)
            ->selectRaw("permit_applications.id as app_number, permit_categories.name as category, permit_applications.firstname as firstname, permit_applications.middlename as middlename, permit_applications.lastname as lastname, permit_applications.address as address, permit_applications.date_of_birth as date_of_birth, permit_applications.gender as gender, payments.created_at as payment_date, test_results.id as test_id")
            ->get();

        return json_encode($outstanding_permits);
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

        // $test_results = PermitTestResults::with('permit_application.permitCategory')->where('facility_id', Auth()->user()->facility_id)->where('created_at', '>', '2023-10-10')->get();

        if ($id == "0") {
            $filterTimeline = $today;
        } else if ($id == "1") {
            $filterTimeline = $yesterday;
        } else if ($id == "7") {
            $filterTimeline = $last_week;
        } else if ($id == "30") {
            $filterTimeline = $thirty_days;
        } else if ($id == "90") {
            $filterTimeline = $last_ninety_days;
        }

        $test_results =  PermitTestResults::with('permit_application.permitCategory')
            ->where('facility_id', Auth()->user()->facility_id)
            ->where('application_type_id', '=', '1')
            ->whereBetween('created_at', [$filterTimeline, $tonight])
            ->whereRelation('permit_application', 'deleted_at', '=', NULL)
            ->get();

        $outstanding = $this->outstandingResults();
        return view('test_center.food_handlers_permit.index', compact('test_results', 'outstanding'));
    }

    public function customFilterProcessedResults(Request $request)
    {
        date_default_timezone_set('Etc/GMT+5');
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'interval' => 'nullable|numeric|max:6'
        ]);

        $test_results =  PermitTestResults::with('permit_application.permitCategory')
            ->where('facility_id', Auth()->user()->facility_id)
            ->where('application_type_id', '=', '1')
            ->whereBetween('created_at', [$timeline['starting_date'], $timeline['ending_date']])
            ->whereRelation('permit_application', 'deleted_at', '=', NULL)
            ->get();
        $outstanding = $this->outstandingResults();
        return view('test_center.food_handlers_permit.index', compact('test_results', 'outstanding'));
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
        $permit_applications = DB::table('permit_applications')
            ->where('permit_applications.id', $permit_id)
            ->get();

        $permit_appointments = DB::table('appointments')
            ->leftJoin('facilities', 'facilities.id', '=', 'appointments.facility_id')
            ->leftJoin('exam_dates', 'exam_dates.id', '=', 'appointments.exam_date_id')
            ->leftJoin('exam_sites', 'exam_dates.exam_site_id', '=', 'exam_sites.id')
            ->where('appointments.permit_application_id', $permit_id)
            ->orderBy('appointments.appointment_date', 'desc')
            ->first();
        // dd($permit_appointments);

        $permit_categories = PermitCategory::all();
        return view('test_center.food_handlers_permit.create', compact('permit_applications', 'permit_categories', 'permit_appointments'));
    }

    public function addPermitResults(Request $request)
    {
        $permit_results = $request->validate([
            'staff_contact' => 'required',
            'overall_score' => 'required|numeric|max:100|min:0'
        ]);

        $permit_results['application_type_id'] = $request->application_type_id;
        $permit_results['application_id'] = $request->application_id;
        $permit_results['test_location'] = $request->test_location;
        $permit_results['comments'] = $request->comments;
        $permit_results['user_id'] = Auth()->user()->id;
        $permit_results['test_date'] = $request->test_date;
        $permit_results['facility_id'] = Auth()->user()->facility_id;

        $new_permit_results = PermitTestResults::create($permit_results);

        if (!$new_permit_results) {
            return redirect()->route('dashboard')->with(['error' => 'Test Results could not be added']);
        }

        return redirect()->route('dashboard.dashboard')->with(['success' => 'Test Results successfully added']);
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
