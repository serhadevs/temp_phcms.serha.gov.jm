<?php

namespace App\Http\Controllers;

use App\Models\EditTransactions;
use App\Models\EditTransactionsChangedColumns;
use App\Models\Payments;
use App\Models\PermitApplication;
use App\Models\PermitCategory;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use Exception;

class PermitTestResultsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function outstanding($id)
    {
        if (auth()->user()->default_filter_id != "") {
            $id = auth()->user()->default_filter_id;
        }

        date_default_timezone_set('Etc/GMT+5');
        $filterTimeline = "";
        $today = date_format(new Datetime(), "Y-m-d");

        if ($id == "0") {
            $filterTimeline = $today;
        } else if ($id == "1") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");

            $outstanding_permits = Payments::with('permitApplications.permitCategory', 'permitApplications.testResults')
                ->where('facility_id', auth()->user()->facility_id)
                ->has('permitApplications')
                ->where('application_type_id', 1)
                ->whereBetween('created_at', [$filterTimeline, $today])
                ->doesntHave('permitApplications.testResults')
                ->get();

            return view('test_center.food_handlers_permit.oustanding', compact('outstanding_permits'));
        } else if ($id == "7") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        } else if ($id == "30") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        } else if ($id == "90") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        } else if ($id == "180") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-180 days"), "Y-m-d");
        }

        $outstanding_permits = Payments::with('permitApplications.permitCategory', 'permitApplications.testResults')
            ->has('permitApplications')
            ->where('facility_id', auth()->user()->facility_id)
            ->where('application_type_id', 1)
            ->where('created_at', '>', $filterTimeline)
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

    public function index($id)
    {
        if (auth()->user()->default_filter_id != "") {
            $id = auth()->user()->default_filter_id;
        }

        $test_results = [];
        date_default_timezone_set('Etc/GMT+5');
        $filterTimeline = "";
        $today = date_format(new Datetime(), "Y-m-d");

        if ($id == "0") {
            $filterTimeline = $today;
        } else if ($id == "1") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");

            $test_results =  PermitApplication::with('permitCategory', 'user', 'testResults')
                ->whereRelation('testResults', 'facility_id', Auth()->user()->facility_id)
                ->has('testResults')
                ->whereRelation('testResults', 'created_at', '>', $filterTimeline)
                ->whereRelation('testResults', 'created_at', '<', $today)
                ->get();

            return view('test_center.food_handlers_permit.index', compact('test_results'));
        } else if ($id == "7") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        } else if ($id == "30") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        } else if ($id == "90") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        } else if ($id == "180") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-180 days"), "Y-m-d");
        }

        $test_results =  PermitApplication::with('permitCategory', 'testResults')
            ->whereRelation('testResults', 'facility_id', Auth()->user()->facility_id)
            ->has('testResults')
            ->whereRelation('testResults', 'created_at', '>', $filterTimeline)
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

        $new_permit_results = TestResult::create($permit_results);

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
    // public function show($id)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            if ($result = TestResult::find($id)) {
                if ($permit_application = PermitApplication::with('testResults', 'user')->find($result->application_id)) {
                    if ($permit_application->sign_off_status != '1') {
                        if ($permit_application->user?->facility_id == auth()->user()->facility_id) {
                            $permit_categories = PermitCategory::all();
                            $system_operation_type_id = 3;
                            $app_type_id = 1;
                            return view('test_center.food_handlers_permit.edit', compact('permit_application', 'permit_categories', 'system_operation_type_id', 'app_type_id'));
                        } else {
                            throw new Exception('This application was not added at your location. You cannot edit these test results.');
                        }
                    } else {
                        throw new Exception('This permit application has already been signed off. THerefore test results cannot be edited.');
                    }
                } else {
                    throw new Exception('Permit Application linked to this application does not exist. Therefore these test results cannot be edited.');
                }
            } else {
                throw new Exception('This Test Result does not exist.');
            }
        } catch (Exception $e) {
            return redirect()->route('test-results.permit.index', ['id' => 0])->with('error', $e->getMessage());
        }
    }


    public function show($id)
    {
        try {
            if ($result = TestResult::find($id)) {
                if ($permit_application = PermitApplication::with('testResults', 'user')->find($result->application_id)) {
                    if ($permit_application->sign_off_status != '1') {
                        if ($permit_application->user?->facility_id == auth()->user()->facility_id) {
                            $permit_categories = PermitCategory::all();
                            $system_operation_type_id = 3;
                            $app_type_id = 1;
                            $is_view = 1;
                            return view('test_center.food_handlers_permit.edit', compact('permit_application', 'permit_categories', 'is_view', 'system_operation_type_id', 'app_type_id'));
                        } else {
                            throw new Exception('This application was not added at your location. You cannot edit these test results.');
                        }
                    } else {
                        throw new Exception('This permit application has already been signed off. THerefore test results cannot be edited.');
                    }
                } else {
                    throw new Exception('Permit Application linked to this application does not exist. Therefore these test results cannot be edited.');
                }
            } else {
                throw new Exception('This Test Result does not exist.');
            }
        } catch (Exception $e) {
            return redirect()->route('test-results.permit.index', ['id' => 0])->with('error', $e->getMessage());
        }
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
        $updated_results = $request->validate([
            'staff_contact' => 'required',
            'overall_score' => 'required|numeric|max:100|min:0',
            'comments' => 'nullable',
            'edit_reason' => 'required'
        ]);

        try {
            if ($results = TestResult::find($id)) {
                if ($application = PermitApplication::with('user')->find($results->application_id)) {
                    if ($application->sign_off_status != '1') {
                        if ($results->facility_id == auth()->user()->facility_id) {
                            $edit_reason = $updated_results['edit_reason'];
                            unset($updated_results['edit_reason']);
                            if (!empty($differences = array_diff_assoc($updated_results, TestResult::select('staff_contact', 'overall_score', 'comments')->find($id)->toArray()))) {
                                DB::beginTransaction();
                                if ($transaction = EditTransactions::create([
                                    'application_type_id' => 1,
                                    'table_id' => $results->id,
                                    'system_operation_type_id' => 3,
                                    'edit_type_id' => 1,
                                    'user_id' => auth()->user()->id,
                                    'facility_id' => auth()->user()->facility_id,
                                    'reason' => $edit_reason
                                ])) {
                                    foreach ($differences as $key => $edit) {
                                        EditTransactionsChangedColumns::create([
                                            'edit_transaction_id' => $transaction->id,
                                            'column_name' => $key,
                                            'old_value' => $results->toArray()[$key],
                                            'new_value' => $updated_results[$key]
                                        ]);
                                    }
                                    if ($results->update($updated_results)) {
                                        DB::commit();
                                        return redirect()->route('test-results.permits.view', ['id' => $results->id])->with('success', 'Test Results for ' . $application->id . ':' . $application->firstname . ' ' . $application->lastname . ' has been updated successfully.');
                                    }
                                }
                            } else {
                                throw new Exception('Nothing was updated in your entries.');
                            }
                        } else {
                            throw new Exception('This test result does not belong to your facility.');
                        }
                    } else {
                        throw new Exception('The application for these test results have already been signed off.');
                    }
                } else {
                    throw new Exception('This Permit Application does not exist.');
                }
            } else {
                throw new Exception('These test results do not exist.');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
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
            if ($results = TestResult::with('permitApplication')
                ->where('application_type_id', 1)
                ->where('facility_id', auth()->user()->facility_id)
                ->find($id)
            ) {
                if (!empty($results->permitApplication)) {
                    if ($results->permitApplication?->sign_off_status != '1') {
                        DB::beginTransaction();
                        if (EditTransactions::create([
                            'application_type_id' => 1,
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
                                    'Test Results for Permit Application for ' . $results->permitApplication?->firstname . ' ' . $results->permitApplication?->lastname . ':' . $results->permitApplication?->id . ' has been deleted successfully.'
                                ];
                            } else {
                                throw new Exception("Error deleting test results. Unable to delete record.");
                            }
                        } else {
                            throw new Exception("Error deleting test results. Unable to initiate transaction.");
                        }
                    } else {
                        throw new Exception("The application linked to these test results has already been signed off. Results cannot be deleted");
                    }
                } else {
                    throw new Exception("The application linked to this test result no longer exists");
                }
            } else {
                throw new Exception("This test results either no longer exists or does not belong to your facility.");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
