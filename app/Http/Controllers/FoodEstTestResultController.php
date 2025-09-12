<?php

namespace App\Http\Controllers;

use App\Models\EditTransactions;
use App\Models\EditTransactionsChangedColumns;
use App\Models\EstablishmentApplications;
use App\Models\TestResult;
use App\Models\User;
use Illuminate\Http\Request;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;

class FoodEstTestResultController extends Controller
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
        $app_type_id = 3;

        if ($id == "0") {
            $filterTimeline = $today;
        } else if ($id == "1") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
            $applications = EstablishmentApplications::with('establishmentCategory', 'testResults', 'user', 'operators')
                ->has('testResults')
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->whereRelation('testResults', 'created_at', '>', $filterTimeline)
                ->whereRelation('testResults', 'created_at', '<', $today)
                ->get();
            return view('test_center.food_est.index', compact('applications', 'app_type_id'));
        } else if ($id == "7") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        } else if ($id == "30") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        } else if ($id == "90") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        } else if ($id == "180") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-180 days"), "Y-m-d");
        }

        $applications = EstablishmentApplications::with('establishmentCategory', 'testResults', 'user', 'operators')
            ->has('testResults')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->whereRelation('testResults', 'created_at', '>', $filterTimeline)
            ->get();
            //dd($applications);
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

        $applications = EstablishmentApplications::with('establishmentCategory', 'testResults', 'user', 'operators')
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
            'test_location' => 'required',
            'number_employees' => 'required|integer',
            'number_emp_permits' => 'required|integer'
        ]);

        //dd($food_est);

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
    public function outstanding($id)
    {
        if (auth()->user()->default_filter_id != "") {
            $id = auth()->user()->default_filter_id;
        }

        $today = date_format(new Datetime(), "Y-m-d");
        $filterTimeline = "";
        $app_type_id = 3;

        if ($id == "0") {
            $filterTimeline = $today;
        } else if ($id == "1") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
            $applications = EstablishmentApplications::with('establishmentCategory', 'testResults', 'operators', 'user', 'payment')
                ->has('payment')
                ->doesntHave('testResults')
                ->whereBetween('created_at', [$filterTimeline, $today])
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->get();

            return view('test_center.food_est.outstanding', compact('applications', 'app_type_id'));
        } else if ($id == "7") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        } else if ($id == "30") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        } else if ($id == "90") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        } else if ($id == "180") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-180 days"), "Y-m-d");
        }

        $applications = EstablishmentApplications::with('establishmentCategory', 'testResults', 'operators', 'user', 'payment')
            ->has('payment')
            ->doesntHave('testResults')
            ->where('created_at', '>', $filterTimeline)
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

        $applications = EstablishmentApplications::with('establishmentCategory', 'testResults', 'operators', 'user', 'payment')
            ->doesntHave('testResults')
            ->has('payment')
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
        $application = EstablishmentApplications::with('establishmentCategory')
            ->find($id);

        $app_type_id = '3';
        $system_operation_type_id = 3;

        $result = TestResult::with('editTransactions')->where('application_id', $id)
            ->whereRelation('editTransactions', 'system_operation_type_id', 3)
            ->where('application_type_id', 3)
            ->first();

        return view('test_center.food_est.edit', compact('application', 'result', 'app_type_id', 'system_operation_type_id'));
    }

    public function show($id)
    {
        $application = EstablishmentApplications::with('establishmentCategory')
            ->find($id);

        $app_type_id = '3';
        $system_operation_type_id = 3;

        $is_view = 1;

        $result = TestResult::where('application_id', $id)
            ->where('application_type_id', 3)
            ->first();

        return view('test_center.food_est.edit', compact('application', 'result', 'app_type_id', 'is_view', 'system_operation_type_id'));
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
        $updated_est_results = $request->validate([
            'visit_purpose' => 'required',
            'staff_contact' => 'required',
            'test_date' => 'required',
            'overall_score' => 'required|numeric|min:0|max:100',
            'critical_score' => 'required|numeric|min:0|max:100',
            'comments' => 'nullable',
            'test_location' => 'required',
            'edit_reason' => 'required',
            'number_employees' => 'required|integer',
            'number_emp_permits' => 'required|integer'
        ]);

        try {
            if ($old_results = TestResult::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
                ->find($id)
            ) {
                if ($application = EstablishmentApplications::find($old_results->application_id)) {
                    // if ($application->sign_off_status != '1') {
                    $edit_reason = $updated_est_results['edit_reason'];
                    unset($updated_est_results['edit_reason']);
                    if (!empty($differences = array_diff_assoc($updated_est_results, TestResult::select('visit_purpose', 'staff_contact', 'test_date', 'overall_score', 'critical_score', 'comments', 'test_location')->find($id)->toArray()))) {
                        DB::beginTransaction();
                        if ($edit_transaction = EditTransactions::create([
                            'application_type_id' => 3,
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
                                    'old_value' => $old_results->toArray()[$key],
                                    'new_value' => $updated_est_results[$key]
                                ])) {
                                    throw new Exception("Error updating test results. Unable to record field changed.");
                                }
                            }
                            if ($old_results->update($updated_est_results)) {
                                DB::commit();
                                return redirect()->route('test-results.food-est.view', ['id' => $application->id])->with('success', 'Test Results for ' . $application->establishment_name . ':' . $application->id . ' has been updated successfully');
                            } else {
                                throw new Exception("Error updating test results. Unable to updating record.");
                            }
                        } else {
                            throw new Exception("Error updating test results. Unable to initiate transaction.");
                        }
                    } else {
                        throw new Exception("No fields were changed. Nothing to update");
                    }
                    // } else {
                    //     throw new Exception("The food establishment application was already signed off. These results cannot be edited.");
                    // }
                } else {
                    throw new Exception("The food establishment application for this test result does not exist");
                }
            } else {
                throw new Exception("This Test Result does not exist or does not belong to your facility.");
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
            if ($result = TestResult::with('establishmentApplication')
                ->where('facility_id', auth()->user()->facility_id)
                ->where('application_type_id', 3)
                ->find($id)
            ) {
                if (!empty($result->establishmentApplication)) {
                    if ($result->establishmentApplication?->sign_off_status != '1') {
                        DB::beginTransaction();
                        if (EditTransactions::create([
                            'application_type_id' => 3,
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
                                    'Test Results for ' . $result->establishmentApplication?->establishment_name . ':' . $result->establishmentApplication?->id . ' have been deleted successfully.'
                                ];
                            } else {
                                throw new Exception("Error deleting result. Unable to delete record.");
                            }
                        } else {
                            throw new Exception("Error deleting test result. Unable to initiate transaction.");
                        }
                    } else {
                        throw new Exception("The application for this test result has already been signed off. Test Result cannot be deleted.");
                    }
                } else {
                    throw new Exception("The application for this test result does not exist.");
                }
            } else {
                throw new Exception("These test results either does not exist or is not a part of your facility.");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
