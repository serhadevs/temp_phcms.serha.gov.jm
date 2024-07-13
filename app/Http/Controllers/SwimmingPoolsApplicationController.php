<?php

namespace App\Http\Controllers;

use App\Models\EditTransactions;
use App\Models\EditTransactionsChangedColumns;
use App\Models\Renewals;
use App\Models\SwimmingPoolsApplications;
use App\Models\TestResult;
use App\Models\User;
use Illuminate\Http\Request;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;

class SwimmingPoolsApplicationController extends Controller
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
            $applications = SwimmingPoolsApplications::with('payment')
                ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
                ->whereBetween('created_at', [$filterTimeline, $today])
                ->get();
            return view('swimming_pools.index', compact('applications'));
        } else if ($id == "7") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        } else if ($id == "30") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        } else if ($id == "90") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        } else if ($id == "180") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-180 days"), "Y-m-d");
        }

        $applications = SwimmingPoolsApplications::with('payment')
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->where('created_at', '>', $filterTimeline)
            ->get();

        return view('swimming_pools.index', compact('applications'));
    }

    public function customIndex(Request $request)
    {
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'interval' => 'nullable|numeric|max:6'
        ]);

        $applications = SwimmingPoolsApplications::with('payment')
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('created_at', [$timeline['starting_date'], $timeline['ending_date'] . " 23:59:59"])
            ->get();

        return view('swimming_pools.index', compact('applications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('swimming_pools.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $swimming_pool = $request->validate([
            'firstname' => 'required',
            'middlename' => 'nullable',
            'lastname' => 'required',
            'swimming_pool_address' => 'required',
            'application_date' => 'required'
        ]);

        $swimming_pool['user_id'] = auth()->user()->id;
        $swimming_pool['permit_no'] = $this->generateSwimmingPoolPermitNo();

        if ($created_pool = SwimmingPoolsApplications::create($swimming_pool)) {
            return redirect()->route('swimming-pools.index.filter', ['id' => 0])->with('success', 'Swimming Pool has been entered successfully. The Application ID is ' . $created_pool->id);
        }

        return redirect()->route('swimming-pools.index.filter', ['id' => 0])->with('error', 'Error entering swimming pool application');
    }

    public function generateSwimmingPoolPermitNo()
    {
        //Generate permit no.
        do {
            $abbr = auth()->user()->facility->abbr;
            $digits_limit = 4;
            $current_date = date("my");
            $random_digits = str_pad(rand(0, pow(10, $digits_limit) - 1), $digits_limit, '0', STR_PAD_LEFT);
            $permit_no = $abbr . $random_digits . $current_date;

            $permit_no_exist = SwimmingPoolsApplications::where('permit_no', $permit_no)->first();
        } while (!empty($permit_no_exist));

        return $permit_no;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $application = SwimmingPoolsApplications::find($id);
        $is_edit = 0;

        return view('swimming_pools.edit', compact('application', 'is_edit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $application = SwimmingPoolsApplications::find($id);
        $is_edit = 1;

        return view('swimming_pools.edit', compact('application', 'is_edit'));
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
        $update_pool = $request->validate([
            'firstname' => 'required',
            'middlename' => 'required',
            'lastname' => 'required',
            'swimming_pool_address' => 'required',
            'edit_reason' => 'required'
        ]);

        try {
            if ($s_pool = SwimmingPoolsApplications::with('user')
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->find($id)
            ) {
                if ($s_pool->sign_off_status != '1') {
                    $edit_reason = $update_pool['edit_reason'];
                    unset($update_pool['edit_reason']);
                    if (!empty($differences = array_diff_assoc($update_pool, SwimmingPoolsApplications::select('firstname', 'middlename', 'lastname', 'swimming_pool_address')->find($id)->toArray()))) {
                        DB::beginTransaction();
                        if ($edit_trans = EditTransactions::create([
                            'application_type_id' => 5,
                            'table_id' => $s_pool->id,
                            'system_operation_type_id' => 1,
                            'edit_type_id' => 1,
                            'user_id' => auth()->user()->id,
                            'facility_id' => auth()->user()->facility_id,
                            'reason' => $edit_reason
                        ])) {
                            foreach ($differences as $key => $value) {
                                if (!EditTransactionsChangedColumns::create([
                                    'edit_transaction_id' => $edit_trans->id,
                                    'column_name' => $key,
                                    'old_value' => $s_pool->toArray()[$key],
                                    'new_value' => $update_pool[$key]
                                ])) {
                                    throw new Exception("Error updating Swimming Pool. Issue recording the changed columns");
                                }
                            }
                            if ($s_pool->update($update_pool)) {
                                DB::commit();
                                return redirect()->route('swimming-pools.view', ['id' => $s_pool->id])->with('success', 'Swimming Pool Application for ' . $s_pool->firstname . ' ' . $s_pool->lastname . ':' . $s_pool->id . ' has been updated successfully.');
                            } else {
                                throw new Exception("Error updating swimming pool.");
                            }
                        } else {
                            throw new Exception("Swimming Pool was not edited. Error initiating transaction");
                        }
                    } else {
                        throw new Exception("No fields were changed. Nothing was updated.");
                    }
                } else {
                    throw new Exception("This swimming pool has already been signed off. Application cannot be edited.");
                }
            } else {
                throw new Exception("This Swimming Pool does not exist or does not belong to your facility.");
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function renewal($id)
    {
        $application = SwimmingPoolsApplications::find($id);

        return view('swimming_pools.renew', compact('application'));
    }

    public function renew(Request $request, $id)
    {
        $swimming_pool = $request->validate([
            'firstname' => 'required',
            'middlename' => 'nullable',
            'lastname' => 'required',
            'swimming_pool_address' => 'required',
            'application_date' => 'required'
        ]);
        $old_application = SwimmingPoolsApplications::find($id);
        $swimming_pool['user_id'] = Auth()->user()->id;
        $swimming_pool['permit_no'] = $old_application->permit_no;

        if ($new_swimming_pool = SwimmingPoolsApplications::create($swimming_pool)) {
            if (Renewals::create([
                'new_application_id' => $new_swimming_pool->id,
                'old_application_id' => $old_application->id,
                'application_type_id' => 5
            ])) {
                TestResult::where('application_type_id', 5)->where('application_id', $id)->first()->update(['deleted_at' => new DateTime()]);
                if ($old_application->update(['deleted_at' => new DateTime()])) {
                    return redirect()->route('swimming-pools.index.filter', ['id' => 0])->with('success', 'Swimming Pool renewal application for ' . $new_swimming_pool->firstname . ' ' . $new_swimming_pool->lastname . ' has been entered successfully. The Application ID is ' . $new_swimming_pool->id);
                }
            }
        }

        return redirect()->route('swimming-pools.index.filter', ['id' => 0])->with('error', 'Error entering swimming pool application renewal.');
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
            if ($s_pool = SwimmingPoolsApplications::with('user')
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->find($id)
            ) {
                if ($s_pool->sign_off_status != '1') {
                    DB::beginTransaction();
                    if (EditTransactions::create([
                        'application_type_id' => 5,
                        'table_id' => $s_pool->id,
                        'system_operation_type_id' => 1,
                        'edit_type_id' => 2,
                        'user_id' => auth()->user()->id,
                        'facility_id' => auth()->user()->facility_id,
                        'reason' => $request->data['reason']
                    ])) {
                        if ($s_pool->update(['deleted_at' => new DateTime()])) {
                            DB::commit();
                            return [
                                'success',
                                'Swimming Pool Application for ' . $s_pool->firstname . ' ' . $s_pool->lastname . ':' . $s_pool->id . ' has been deleted successfully'
                            ];
                        } else {
                            throw new Exception('Error deleting swimming pool.');
                        }
                    } else {
                        throw new Exception('Error initiating transaction. This swimming pool was not deleted.');
                    }
                } else {
                    throw new Exception('This swimming pool has already been signed off. Application cannot be deleted.');
                }
            } else {
                throw new Exception("This swimming pool application does not exist or does not belong to your facility.");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
