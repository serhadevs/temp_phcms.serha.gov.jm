<?php

namespace App\Http\Controllers;

use App\Models\EditTransactions;
use App\Models\EditTransactionsChangedColumns;
use App\Models\SwimmingPoolsApplications;
use App\Models\TestResult;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class SwimmingPoolTestResultController extends Controller
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
        $app_type_id = 5;
        $filterTimeline = "";
        if ($id == "0") {
            $filterTimeline = $today;
        } else if ($id == "1") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
            $applications = SwimmingPoolsApplications::with('payment', 'testResults')
                ->has('testResults')
                // ->has('payment')
                ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
                ->whereRelation('testResults', 'created_at', '>', $filterTimeline)
                ->whereRelation('testResults', 'created_at', '<', $today)
                ->get();
            return view('test_center.swimming_pool.index', compact('applications', 'app_type_id'));
        } else if ($id == "7") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        } else if ($id == "30") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        } else if ($id == "90") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        } else if ($id == "180") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-180 days"), "Y-m-d");
        }


        $applications = SwimmingPoolsApplications::with('payment', 'testResults')
            ->has('testResults')
            // ->has('payment')
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereRelation('testResults', 'created_at', '>', $filterTimeline)
            ->get();

        return view('test_center.swimming_pool.index', compact('applications', 'app_type_id'));
    }

    public function customIndex(Request $request)
    {
        date_default_timezone_set('Etc/GMT+5');
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'interval' => 'nullable|numeric|max:6'
        ]);

        $app_type_id = 5;

        $applications = SwimmingPoolsApplications::with('payment', 'testResults')
            ->has('testResults')
            // ->has('payment')
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereRelation('testResults', 'created_at', '>', $timeline['starting_date'])
            ->whereRelation('testResults', 'created_at', '<', $timeline['ending_date'] . " 23:59:59")
            ->get();

        return view('test_center.swimming_pool.index', compact('applications', 'app_type_id'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        try {
            if ($application = SwimmingPoolsApplications::with('payment')->find($id)) {
                if (!empty($application->payment)) {
                    return view('test_center.swimming_pool.create', compact('application'));
                } else {
                    throw new Exception("There is no payment for this swimming pool so it cannot be processed");
                }
            } else {
                throw new Exception('This swimming pool does not exist');
            }
        } catch (Exception $e) {
            return redirect()->route('test-results.swimming-pools.index', ['id' => 0])->with('error', $e->getMessage());
        }
    }

    public function outstanding($id)
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
            $applications = SwimmingPoolsApplications::with('payment', 'testResults')
                ->doesntHave('testResults')
                ->has('payment')
                ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
                ->whereBetween('created_at', [$filterTimeline, $today])
                ->get();
            return view('test_center.swimming_pool.outstanding', compact('applications'));
        } else if ($id == "7") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        } else if ($id == "30") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        } else if ($id == "90") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        } else if ($id == "180") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-180 days"), "Y-m-d");
        }

        $applications = SwimmingPoolsApplications::with('payment', 'testResults')
            ->doesntHave('testResults')
            ->has('payment')
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->where('created_at', '>', $filterTimeline)
            ->get();

        $is_results = 1;

        return view('test_center.swimming_pool.outstanding', compact('applications', 'is_results'));
    }

    public function customOutstanding(Request $request)
    {
        date_default_timezone_set('Etc/GMT+5');
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'interval' => 'nullable|numeric|max:6'
        ]);

        $applications = SwimmingPoolsApplications::with('payment', 'testResults')
            ->doesntHave('testResults')
            ->has('payment')
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('created_at', [$timeline['starting_date'], $timeline['ending_date'] . " 23:59:59"])
            ->get();

        $is_results = 1;
        return view('test_center.swimming_pool.outstanding', compact('applications', 'is_results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $sp_results = $request->validate([
            'staff_contact' => 'required',
            'test_date' => 'date|required',
            'test_location' => 'required',
            'critical_score' => 'required|numeric|min:0|max:100',
            'overall_score' => 'required|numeric|min:0|max:100',
            'comments' => 'nullable'
        ]);

        $sp_results['user_id'] = auth()->user()->id;
        $sp_results['facility_id'] = auth()->user()->facility_id;
        $sp_results['application_type_id'] = 5;
        $sp_results['application_id'] = $id;

        try {
            if ($sp_application = SwimmingPoolsApplications::with('testResults', 'payment')->find($id)) {
                if (!empty($sp_application->payment)) {
                    if (empty($sp_application->testResults)) {
                        if (TestResult::create($sp_results)) {
                            return redirect()->route('test-results.swimming-pools.index', ['id' => 0])->with('success', 'Test Results for ' . $sp_application->firstname . '' . $sp_application->lastname . ' has been entered successfully.');
                        } else {
                            throw new Exception("Error storing test results.");
                        }
                    } else {
                        throw new Exception("Test Results have already been entered for this swimming pool.");
                    }
                } else {
                    throw new Exception("This swimming pool does not have a payment. Therefore it cannot be processed");
                }
            } else {
                throw new Exception('This swimming pool does not exist');
            }
        } catch (Exception $e) {
            return redirect()->route('test-results.swimming-pools.index', ['id' => 0])->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            if ($application = SwimmingPoolsApplications::with('payment', 'testResults')
                ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
                ->find($id)
            ) {
                if (!empty($application->payment)) {
                    $is_view = 1;
                    $system_operation_type_id = 3;
                    $app_type_id = 5;
                    return view('test_center.swimming_pool.edit', compact('application', 'is_view', 'system_operation_type_id', 'app_type_id'));
                } else {
                    throw new Exception("There is no payment for this swimming pool so it cannot be processed");
                }
            } else {
                throw new Exception('This test results entry does not exist or does not belong to your facility.');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            if ($application = SwimmingPoolsApplications::with('payment', 'testResults')->find($id)) {
                if (!empty($application->payment)) {
                    $system_operation_type_id = 3;
                    $app_type_id = 5;
                    return view('test_center.swimming_pool.edit', compact('application', 'system_operation_type_id', 'app_type_id'));
                } else {
                    throw new Exception("There is no payment for this swimming pool so it cannot be processed");
                }
            } else {
                throw new Exception('This test results entry does not exist.');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
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
        $results_edit = $request->validate([
            'staff_contact' => 'required',
            'test_date' => 'date|required',
            'test_location' => 'required',
            'critical_score' => 'required|numeric|min:0|max:100',
            'overall_score' => 'required|numeric|min:0|max:100',
            'comments' => 'nullable',
            'edit_reason' => 'required'
        ]);
        try {
            if ($old_results = TestResult::find($id)) {
                $edit_reason = $results_edit['edit_reason'];
                unset($results_edit['edit_reason']);
                if ($swimming_pool = SwimmingPoolsApplications::with('testResults')->find($old_results->application_id)) {
                    if ($swimming_pool->sign_off_status != '1') {
                        if (!empty($differences = array_diff($results_edit, TestResult::select('staff_contact', 'test_date', 'test_location', 'critical_score', 'overall_score', 'comments')->find($id)->toArray()))) {
                            DB::beginTransaction();
                            if ($transaction = EditTransactions::create([
                                'application_type_id' => 5,
                                'table_id' => $id,
                                'system_operation_type_id' => 3,
                                'edit_type_id' => 1,
                                'user_id' => auth()->user()->id,
                                'facility_id' => auth()->user()->facility_id,
                                'reason' => $edit_reason
                            ])) {
                                foreach ($differences as $key => $edit) {
                                    if (!EditTransactionsChangedColumns::create([
                                        'edit_transaction_id' => $transaction->id,
                                        'column_name' => $key,
                                        'old_value' => $old_results->toArray()[$key],
                                        'new_value' => $results_edit[$key]
                                    ])) {
                                        throw new Exception("Error updating test result. Error recording the fields changed.");
                                    }
                                }
                                if ($old_results->update($results_edit)) {
                                    DB::commit();
                                    return redirect()->route('test-results.swimming-pools.view', ['id' => $swimming_pool->id])->with('success', 'Swimming Pool Test Results for ' . $swimming_pool->firstname . ' ' . $swimming_pool->lastname . ':' . $swimming_pool->id . ' was updated successfully');
                                } else {
                                    throw new Exception("Error updating test results for swimming pool. Unable to update results.");
                                }
                            } else {
                                throw new Exception('Test Results were not updated. Error adding transaction');
                            }
                        } else {
                            throw new Exception("No field was updated in your entry.");
                        }
                    } else {
                        throw new Exception("This application has already been signed off. It cannot be edited");
                    }
                } else {
                    throw new Exception('This swimming pool application does not exist');
                }
            } else {
                throw new Exception('This Test Result does not exist.');
            }
        } catch (Exception $e) {
            DB::rollBack();
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
            if ($result = TestResult::where('facility_id', auth()->user()->facility_id)
                ->where('application_type_id', 5)
                ->with('swimmingPool')
                ->find($id)
            ) {
                if (!empty($result->swimmingPool)) {
                    if ($result->swimmingPool?->sign_off_status != '1') {
                        DB::beginTransaction();
                        if (EditTransactions::create([
                            'application_type_id' => 5,
                            'table_id' => $id,
                            'system_operation_type_id' => 3,
                            'edit_type_id' => 2,
                            'user_id' => auth()->user()->id,
                            'facility_id' => auth()->user()->facility_id,
                            'reason' => $request->data['reason']
                        ])) {
                            if ($result->update(['deleted_at' => date('Y-m-d H:i:s')])) {
                                DB::commit();
                                return [
                                    'success',
                                    'Test Result for Swimming Pool ' . $result->swimmingPool?->firstname . ' ' . $result->swimmingPool?->lastname . ':' . $result->swimmingPool?->id . ' has been deleted successfully.'
                                ];
                            }
                        } else {
                            throw new Exception("Error deleting test result. Unable to initiate transaction.");
                        }
                    } else {
                        throw new Exception("Swimming Pool has already been signed off. This test result cannot be deleted.");
                    }
                } else {
                    throw new Exception('Swimming Pool associated to this test result does not exist.');
                }
            } else {
                throw new Exception("This test results does not exist or does not belong your facility.");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
