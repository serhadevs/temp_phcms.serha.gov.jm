<?php

namespace App\Http\Controllers;

use App\Models\EditTransactions;
use App\Models\EditTransactionsChangedColumns;
use App\Models\TestResult;
use App\Models\TouristEstablishments;
use App\Models\User;
use Illuminate\Http\Request;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;

class TouristEstTestResultController extends Controller
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
        } else if ($id == "180") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-180 days"), "Y-m-d");
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
        if (auth()->user()->default_filter_id != "") {
            $id = auth()->user()->default_filter_id;
        }

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
        } else if ($id == "180") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-180 days"), "Y-m-d");
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
        $application = TouristEstablishments::with('testResults')
            ->find($id);
        $is_view = 1;
        $system_operation_type_id = 3;
        $app_type_id = 6;

        return view('test_center.tourist_est.edit', compact('application', 'is_view', 'system_operation_type_id', 'app_type_id'));
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
        $system_operation_type_id = 3;
        $app_type_id = 6;

        return view('test_center.tourist_est.edit', compact('application', 'system_operation_type_id', 'app_type_id'));
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
            'test_location' => 'required',
            'edit_reason' => 'required'
        ]);

        try {
            if ($results = TestResult::with('touristEstablishment')
                ->where('application_type_id', 6)
                ->where('facility_id', auth()->user()->facility_id)
                ->find($id)
            ) {
                if (!empty($results->touristEstablishment)) {
                    if ($results->touristEstablishment?->sign_off_status != '1') {
                        $edit_reason = $tourist_est_results['edit_reason'];
                        unset($tourist_est_results['edit_reason']);
                        if (!empty($differences = array_diff_assoc($tourist_est_results, TestResult::select('staff_contact', 'test_date', 'overall_score', 'critical_score', 'comments', 'test_location')->find($id)->toArray()))) {
                            DB::beginTransaction();
                            if ($edit_transaction = EditTransactions::create([
                                'application_type_id' => 6,
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
                                        'new_value' => $tourist_est_results[$key]
                                    ])) {
                                        throw new Exception("Error updating test results. Unable to record changed fields.");
                                    }
                                }
                                if ($results->update($tourist_est_results)) {
                                    DB::commit();
                                    return redirect()->route('test-results.tourist-establishments.view', ['id' => $results->touristEstablishment?->id])->with('success', 'Test Results for Tourist Establishment ' . $results->touristEstablishment?->establishment_name . ':' . $results->touristEstablishment?->id . ' has been updated successfully.');
                                } else {
                                    throw new Exception("Error updating test results. Unable to update record.");
                                }
                            } else {
                                throw new Exception("Error updating test result. Unable to initiate transaction.");
                            }
                        } else {
                            throw new Exception("No fields were changed. Nothing was updated.");
                        }
                    } else {
                        throw new Exception("This application has already been signed off. Results cannot be edited.");
                    }
                } else {
                    throw new Exception("The is no tourist establishment application that is associated to these results");
                }
            } else {
                throw new Exception("These results do not exist.");
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
            if ($results = TestResult::with('touristEstablishment')
                ->where('application_type_id', 6)
                ->where('facility_id', auth()->user()->facility_id)
                ->find($id)
            ) {
                if (!empty($results->touristEstablishment)) {
                    if ($results->sign_off_status != '1') {
                        DB::beginTransaction();
                        if (EditTransactions::create([
                            'application_type_id' => 6,
                            'table_id' => $id,
                            'system_operation_type_id' => 3,
                            'edit_type_id' => 2,
                            'user_id' => auth()->user()->id,
                            'facility_id' => auth()->user()->facility_id,
                            'reason' => $request->data['reason']
                        ])) {
                            if ($results->update(['deleted_at' => new DateTime()])) {
                                DB::commit();
                                return [
                                    'success',
                                    'Tourist Establishment ' . $results->touristEstablishment?->establishment_name . ':' . $results->touristEstablishment?->id . 'has been deleted successfully'
                                ];
                            } else {
                                throw new Exception("Error deleting test results. Unable to delete record.");
                            }
                        } else {
                            throw new Exception("Error deleting test results. Unable to initiate transaction");
                        }
                    } else {
                        throw new Exception("This application has already been signed off. Results cannot be edited");
                    }
                } else {
                    throw new Exception("The tourist establishment application for these results does not exist.");
                }
            } else {
                throw new Exception("This test result either does not exist or does not belong to your facility.");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
