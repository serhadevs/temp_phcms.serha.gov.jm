<?php

namespace App\Http\Controllers;

use App\Models\HealthCertApplications;
use App\Models\TestResult;
use App\Models\User;
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
        $today = date_format(new Datetime(), "Y-m-d");
        $filterTimeline = "";
        if ($id == "0") {
            $filterTimeline = $today;
        } else if ($id == "1") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
            $test_results = HealthCertApplications::with('payment', 'testResults')
                ->has('testResults')
                ->has('payment')
                ->whereHas('testResults', function ($query) {
                    $query->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten());
                })
                ->whereRelation('testResults', 'created_at', '>', $filterTimeline)
                ->whereRelation('testResults', 'created_at', '<', $today)
                ->get();
            return view('test_center.barber_cosmet.index', compact('test_results'));
        } else if ($id == "7") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        } else if ($id == "30") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        } else if ($id == "90") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        }
        $test_results = HealthCertApplications::with('payment', 'testResults')
            ->has('testResults')
            ->whereHas('testResults', function ($query) {
                $query->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten());
            })
            ->has('payment')
            ->whereRelation('testResults', 'created_at', '>', $filterTimeline)
            ->get();

        return view('test_center.barber_cosmet.index', compact('test_results'));
    }

    public function customIndex(Request $request)
    {
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'interval' => 'nullable|numeric|max:6'
        ]);

        $test_results = HealthCertApplications::with('payment', 'testResults')
            ->has('testResults')
            ->has('payment')
            ->whereHas('testResults', function ($query) {
                $query->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten());
            })
            ->whereRelation('testResults', 'created_at', '>', $timeline['starting_date'])
            ->whereRelation('testResults', 'created_at', '<', $timeline['ending_date'] . " 23:59:59")
            ->get();

        return view('test_center.barber_cosmet.index', compact('test_results'));
    }

    public function outstanding($id)
    {
        $today = date_format(new Datetime(), "Y-m-d");
        $filterTimeline = "";
        if ($id == "0") {
            $filterTimeline = $today;
        } else if ($id == "1") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
            $applications = HealthCertApplications::with('testResults', 'payment', 'signOff')
                ->has('payment')
                ->doesntHave('testResults')
                ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
                ->whereBetween('created_at', [$filterTimeline, $today])
                ->get();

            $is_result = 1;

            return view('test_center.barber_cosmet.outstanding', compact('applications', 'is_result'));
        } else if ($id == "7") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        } else if ($id == "30") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        } else if ($id == "90") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        }

        $applications = HealthCertApplications::with('testResults', 'payment', 'signOff')
            ->has('payment')
            ->doesntHave('testResults')
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->where('created_at', '>', $filterTimeline)
            ->get();

        $is_result = 1;

        return view('test_center.barber_cosmet.outstanding', compact('applications', 'is_result'));
    }


    public function customOutstanding(Request $request)
    {
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'interval' => 'nullable|numeric|max:6'
        ]);

        $applications = HealthCertApplications::with('testResults', 'payment', 'signOff')
            ->has('payment')
            ->doesntHave('testResults')
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('created_at', [$timeline['starting_date'], $timeline['ending_date'] . " 23:59:59"])
            ->get();

        $is_result = 1;

        return view('test_center.barber_cosmet.outstanding', compact('applications', 'is_result'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $application = HealthCertApplications::with('testResults', 'appointment.examDate.examSites')
            ->find($id);

        return view('test_center.barber_cosmet.create', compact('application'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $test_results = $request->validate([
            'staff_contact' => 'required',
            'comments' => 'nullable',
            'overall_score' => 'required|numeric|max:100|min:0'
        ]);

        $application = HealthCertApplications::with('appointment.examDate.examSites')->find($id);

        $test_results['application_type_id'] = 2;
        $test_results['application_id'] = $application->id;
        $test_results['test_location'] = $application->appointment?->first()?->examDate?->examSites?->name ? $application->appointment?->first()?->examDate?->examSites?->name : 'N/A';
        $test_results['user_id'] = auth()->user()->id;
        $test_results['test_date'] = $application->appointment?->first()?->appointment_date;
        $test_results['facility_id'] = auth()->user()->facility_id;

        if (TestResult::create($test_results)) {
            return redirect()->route('test-results.barber-cosmet.processed', ['id' => 0])->with('success', 'Test Results for ' . $application->firstname . ' ' . $application->lastname . ' has been entered successfully');
        }

        return redirect()->route('test-results.barber-cosmet.processed', ['id' => 0])->with('error', 'Error processing test results.');
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
        $application = HealthCertApplications::with('appointment.examDate.examSites', 'testResults')
            ->find($id);

        return view('test_center.barber_cosmet.edit', compact('application'));
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
        $test_results = $request->validate([
            'staff_contact' => 'required',
            'comments' => 'nullable',
            'overall_score' => 'required|numeric|max:100|min:0'
        ]);

        $result = TestResult::find($id);
        $application = HealthCertApplications::where('id', $result->application_id)->first();

        if ($result->update($test_results)) {
            return redirect()->route('test-results.barber-cosmet.processed', ['id' => 0])->with('success', 'Test Results for ' . $application->firstname . ' ' . $application->lastname . ' has been updated successfully.');
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
