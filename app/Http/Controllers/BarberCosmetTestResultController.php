<?php

namespace App\Http\Controllers;

use App\Models\EditTransactions;
use App\Models\EditTransactionsChangedColumns;
use App\Models\HealthCertApplications;
use App\Models\TestResult;
use App\Models\User;
use Illuminate\Http\Request;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;

class BarberCosmetTestResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        if (auth()->user()->default_filter_id != "") {
            $id = auth()->user()->default_filter_id;
        }

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
        } else if ($id == "180") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-180 days"), "Y-m-d");
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
        if (auth()->user()->default_filter_id != "") {
            $id = auth()->user()->default_filter_id;
        }

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
        } else if ($id == "180") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-180 days"), "Y-m-d");
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
        $application = HealthCertApplications::with('appointment.examDate.examSites', 'testResults')
            ->find($id);

        $is_view = 1;
        $system_operation_type_id = 3;
        $app_type_id = 6;

        return view('test_center.barber_cosmet.edit', compact('application', 'is_view', 'system_operation_type_id', 'app_type_id'));
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
        $system_operation_type_id = 3;
        $app_type_id = 6;

        return view('test_center.barber_cosmet.edit', compact('application', 'system_operation_type_id', 'app_type_id'));
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
        $test_results_updated = $request->validate([
            'staff_contact' => 'required',
            'comments' => 'nullable',
            'overall_score' => 'required|numeric|max:100|min:0',
            'edit_reason' => 'required'
        ]);

        try {
            if ($results = TestResult::with('healthCertApplication')
                ->where('facility_id', auth()->user()->facility_id)
                ->where('application_type_id', 2)
                ->find($id)
            ) {
                if (!empty($results->healthCertApplication)) {
                    if ($results->healthCertApplication?->sign_off_status != '1') {
                        $edit_reason = $test_results_updated["edit_reason"];
                        unset($test_results_updated["edit_reason"]);
                        if (!empty($differences = array_diff_assoc($test_results_updated, TestResult::select('staff_contact', 'comments', 'overall_score')->find($id)->toArray()))) {
                            DB::beginTransaction();
                            if ($edit_transaction = EditTransactions::create([
                                'application_type_id' => 2,
                                'table_id' => $id,
                                'system_operation_type_id' => 3,
                                'edit_type_id' => 1,
                                'user_id' => auth()->user()->id,
                                'facility_id' => auth()->user()->facility_id,
                                'reason' => $edit_reason
                            ])) {
                                foreach ($differences as $key => $value) {
                                    if (!EditTransactionsChangedColumns::create([
                                        'edit_transaction_id' => $edit_transaction->id,
                                        'column_name' => $key,
                                        'old_value' => $results->toArray()[$key],
                                        'new_value' => $test_results_updated[$key]
                                    ])) {
                                        throw new Exception("Error updating test results. Unable to record changed fields");
                                    }
                                }
                                if ($results->update($test_results_updated)) {
                                    DB::commit();
                                    return redirect()->route('test-results.barber-cosmet.view', ['id' => $results->healthCertApplication?->id])->with('success', 'Test Results for Barber/Cosmet Application' . $results->healthCertApplication?->firstname . ' ' . $results->healthCertApplication?->lastname . ':' . $results->healthCertApplication?->id . ' has been updated successfully.');
                                }
                            } else {
                                throw new Exception("Error updating test results. Unable to initiate transaction");
                            }
                        } else {
                            throw new Exception("No fields were changed. Nothing to be updated.");
                        }
                    } else {
                        throw new Exception("This application was already signed off. Results cannot be edited");
                    }
                } else {
                    throw new Exception("The application for these results no longer exist.");
                }
            } else {
                throw new Exception("This test result either no longer exists or does not belong to your facility.");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

        // $result = TestResult::find($id);
        // $application = HealthCertApplications::where('id', $result->application_id)->first();

        // if ($result->update($test_results)) {
        //     return redirect()->route('test-results.barber-cosmet.processed', ['id' => 0])->with('success', 'Test Results for ' . $application->firstname . ' ' . $application->lastname . ' has been updated successfully.');
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            if ($results = TestResult::with('healthCertApplication')
                ->where('facility_id', auth()->user()->facility_id)
                ->where('application_type_id', 2)
                ->find($id)
            ) {
                if (!empty($results->healthCertApplication)) {
                    if ($results->healthCertApplication?->sign_off_status != '1') {
                        DB::beginTransaction();
                        if (EditTransactions::create([
                            'application_type_id' => 2,
                            'table_id' => $id,
                            'system_operation_type_id' => 3,
                            'edit_type_id' => 2,
                            'user_id' => auth()->user()->id,
                            'facility_id' => auth()->user()->facility_id,
                            'reason' => $request->data['reason']
                        ])) {
                            if ($results->update(['deleted_at' => date('Y-m-d H:i:s')])) {
                                DB::commit();
                                return [
                                    'success',
                                    'Test Results for ' . $results->healthCertApplication?->firstname . ' ' . $results->healthCertApplication?->lastname . ':' . $results->healthCertApplication?->id . ' has been deleted successfully.'
                                ];
                            } else {
                                throw new Exception("Unable to delete test results. Error deleting record");
                            }
                        } else {
                            throw new Exception("Error deleting test results. Unable to initiate transaction");
                        }
                    } else {
                        throw new Exception('This application has already been signed off. Results cannot be edited');
                    }
                } else {
                    throw new Exception('Application for these results no longer exist');
                }
            } else {
                throw new Exception("These test results either no longer exist or does not belong to your facility.");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
