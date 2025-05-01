<?php

namespace App\Http\Controllers;

use App\Models\EditTransactions;
use App\Models\EditTransactionsChangedColumns;
use App\Models\EstablishmentApplications;
use App\Models\Facility;
use App\Models\Renewals;
use App\Models\TestResult;
use App\Models\TouristEstablishments;
use App\Models\TouristEstManagers;
use App\Models\TouristEstServices;
use App\Models\User;
use Illuminate\Http\Request;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;

class TouristEstApplicationsController extends Controller
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

        // dd($id);

        if ($id === "0") {
            $filterTimeline = $today;
        } else if ($id == "1") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
            $applications = TouristEstablishments::with('payments', 'managers', 'services')
                ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
                ->whereBetween('created_at', [$filterTimeline, $today])
                ->get();
            return view('tourist_est.index', compact('applications'));
        } else if ($id == "7") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        } else if ($id == "30") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        } else if ($id == "90") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        } else if ($id == "180") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-180 days"), "Y-m-d");
        } else if ($id == '00') {
            $applications = TouristEstablishments::with('payments', 'managers', 'services')
                ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
                ->where('created_at', '>', "2022-01-01")
                ->get();

            return view('tourist_est.index', compact('applications'));
        }

        $applications = TouristEstablishments::with('payments', 'managers', 'services')
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->where('created_at', '>', $filterTimeline)
            ->get();

        return view('tourist_est.index', compact('applications'));
    }

    public function customIndex(Request $request)
    {
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'interval' => 'nullable|numeric|max:6'
        ]);

        $applications = TouristEstablishments::with('payments', 'managers', 'services')
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('created_at', [$timeline['starting_date'], $timeline['ending_date'] . " 23:59:59"])
            ->get();

        return view('tourist_est.index', compact('applications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tourist_est.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tourist_est = $request->validate([
            'establishment_name' => 'required',
            'establishment_address' => 'required',
            'bed_capacity' => 'numeric|required',
            'is_eating_establishment' => 'required',
            'eating_establishment_description' => 'nullable',
            'establishment_state' => 'required',
            'officer_firstname' => 'nullable',
            'officer_lastname' => 'nullable',
            'authorized_officer_statement' => 'nullable',
            'statement_date' => 'nullable|date',
            'application_date' => 'required|date',
            'firstname.*' => 'required_with:firstname.0|required_with:lastname.0|required_with:nationality.0',
            'lastname.*' => 'required_with:firstname.0|required_with:lastname.0|required_with:nationality.0',
            'nationality.*' => 'required_with:firstname.0|required_with:lastname.0|required_with:nationality.0',
            'services.*' => 'required_with:services.0'
        ]);

        $tourist_est['permit_no'] = $this->generateTouristPermitNo();
        $tourist_est['user_id'] = auth()->user()->id;

        if ($created_tourist_est = TouristEstablishments::create($tourist_est)) {
            if (!empty($request->firstname)) {
                $i = 0;
                foreach ($request->firstname as $item) {
                    if ($item) {
                        TouristEstManagers::create([
                            'tourist_establishment_id' => $created_tourist_est->id,
                            'firstname' => $item,
                            'lastname' => $request->lastname[$i],
                            'post_held' => $request->post_held[$i],
                            'qualifications' => $request->qualifications[$i],
                            'nationality' => $request->nationality[$i]
                        ]);
                    }
                    $i++;
                }
            }

            if (!empty($request->services)) {
                foreach ($request->services as $name) {
                    if ($name) {
                        TouristEstServices::create(
                            [
                                'tourist_establishment_id' => $created_tourist_est->id,
                                'name' => $name
                            ]

                        );
                    }
                }
            }

            return redirect()->route('tourist-establishments.index.filter', ['id' => 0])->with('success', 'Tourist Establishment Application was added successfully. The Application ID is ' . $created_tourist_est->id);
        }

        return redirect()->route('tourist-establishments.index.filter', ['id' => 0])->with('error', 'Error creating processing tourist establishment application');
    }

    public function generateTouristPermitNo()
    {
        //Generate permit no.
        do {
            $abbr = Facility::where('id', auth()->user()->facility_id)
                ->first()
                ->abbr;
            $digits_limit = 4;
            $current_date = date("my");
            $random_digits = str_pad(rand(0, pow(10, $digits_limit) - 1), $digits_limit, '0', STR_PAD_LEFT);
            $permit_no = $abbr . $random_digits . $current_date;

            $permit_no_exist = TouristEstablishments::where('permit_no', $permit_no)->first();
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
        $application = TouristEstablishments::with('managers.editTransactions', 'services', 'user')->find($id);

        $system_operation_type_id = 10;

        $not_modal = 1;

        return view('tourist_est.view', compact('application', 'not_modal', 'system_operation_type_id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $application = TouristEstablishments::with('managers', 'services', 'user')->find($id);
        $system_operation_type_id = 10;

        $not_modal = 1;
        $edit_mode = 1;

        return view('tourist_est.view', compact('application', 'not_modal', 'edit_mode', 'system_operation_type_id'));
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
        $update_tourist_est = $request->validate([
            'establishment_name' => 'required',
            'establishment_address' => 'required',
            'bed_capacity' => 'numeric|required',
            'is_eating_establishment' => 'required',
            'eating_establishment_description' => 'nullable',
            'establishment_state' => 'required',
            'officer_firstname' => 'nullable',
            'officer_lastname' => 'nullable',
            'authorized_officer_statement' => 'nullable',
            'statement_date' => 'nullable|date',
            'edit_reason' => 'required'
        ]);

        try {
            if ($application = TouristEstablishments::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())->find($id)) {
                if ($application->sign_off_status != '1') {
                    $edit_reason = $update_tourist_est['edit_reason'];
                    unset($update_tourist_est['edit_reason']);
                    if (!empty($differences = array_diff_assoc($update_tourist_est, TouristEstablishments::select('establishment_name', 'establishment_address', 'bed_capacity', 'is_eating_establishment', 'eating_establishment_description', 'establishment_state', 'officer_firstname', 'officer_lastname', 'authorized_officer_statement', 'statement_date')->find($id)->toArray()))) {
                        DB::beginTransaction();
                        if ($edit_transaction = EditTransactions::create([
                            'application_type_id' => 6,
                            'table_id' => $application->id,
                            'system_operation_type_id' => 1,
                            'edit_type_id' => 1,
                            'user_id' => auth()->user()->id,
                            'facility_id' => auth()->user()->facility_id,
                            'reason' => $edit_reason
                        ])) {
                            foreach ($differences as $key => $value) {
                                if (!EditTransactionsChangedColumns::create([
                                    'edit_transaction_id' => $edit_transaction->id,
                                    'column_name' => $key,
                                    'old_value' => $application->toArray()[$key],
                                    'new_value' => $update_tourist_est[$key]
                                ])) {
                                    throw new Exception("Error updating application. Unable to record the fields changed.");
                                }
                            }
                            if ($application->update($update_tourist_est)) {
                                DB::commit();
                                return redirect()->route('tourist-establishments.view', ['id' => $application->id])->with('success', 'Tourist Establishment Application for ' . $application->establishment_name . ':' . $application->id . ' has been updated successfully.');
                            } else {
                                throw new Exception("Error updating application. Unable to update application");
                            }
                        } else {
                            throw new Exception("Error updating application. Error initiating transaction.");
                        }
                    } else {
                        throw new Exception("None of the fields were changed. Update was not completed.");
                    }
                } else {
                    throw new Exception("This tourist establishment application has already been signed off. It cannot be edited.");
                }
            } else {
                throw new Exception("This tourist establishment application does not exist or does not belong to your facility.");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $tourist_est_id
     * @return \Illuminate\Http\Response
     */
    public function createManager($tourist_est_id)
    {
        $establishment_name = TouristEstablishments::find($tourist_est_id)->establishment_name;

        return view('tourist_est.create_mangers', compact('establishment_name', 'tourist_est_id'));
    }

    public function storeManager(Request $request, $id)
    {
        $tourist_est_managers = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'post_held' => 'nullable',
            'qualifications' => 'nullable',
            'nationality' => 'required',
            'edit_reason' => 'required'
        ]);

        try {
            if ($est_application = TouristEstablishments::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())->find($id)) {
                if ($est_application->sign_off_status != '1') {
                    DB::beginTransaction();
                    $edit_reason = $tourist_est_managers['edit_reason'];
                    unset($tourist_est_managers['edit_reason']);
                    $tourist_est_managers['tourist_establishment_id'] = $id;
                    if ($new_manager = TouristEstManagers::create($tourist_est_managers)) {
                        if (EditTransactions::create([
                            'application_type_id' => 6,
                            'table_id' => $new_manager->id,
                            'system_operation_type_id' => 10,
                            'edit_type_id' => 3,
                            'user_id' => auth()->user()->id,
                            'facility_id' => auth()->user()->facility_id,
                            'reason' => $edit_reason
                        ])) {
                            DB::commit();
                            return redirect()->route('tourist-establishments.view', ['id' => $est_application->id])->with('success', 'Manager has been added to ' . $est_application->establishment_name . ' successfully.');
                        } else {
                            throw new Exception("Error storing new manager. Unable to initiate transaction.");
                        }
                    } else {
                        throw new Exception("Error creating new manager. Unable to store manager.");
                    }
                } else {
                    throw new Exception("This application has already been signed off. Manager cannot be added to application.");
                }
            } else {
                throw new Exception("This application does not exist or does not belong to your facility.");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }


        // $tourist_est = TouristEstablishments::find($request->tourist_est_id);

        // $tourist_est_managers['tourist_establishment_id'] = $request->tourist_est_id;

        // if (TouristEstManagers::create($tourist_est_managers)) {
        //     return redirect()->route('tourist-establishments.index.filter', ['id' => 0])->with('success', 'Manager has been added to ' . $tourist_est->establishment_name . ' successfully.');
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editManager($id)
    {
        $manager = TouristEstManagers::find($id);
        $establishment = TouristEstablishments::find($manager->tourist_establishment_id);

        return view('tourist_est.edit_managers', compact('manager', 'establishment'));
    }

    public function updateManager(Request $request, $id)
    {
        $tourist_est_manager_update = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'post_held' => 'nullable',
            'qualifications' => 'nullable',
            'nationality' => 'required',
            'edit_reason' => 'required'
        ]);

        try {
            if ($manager = TouristEstManagers::find($id)) {
                if ($application = TouristEstablishments::with('user')
                    ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                    ->find($manager->tourist_establishment_id)
                ) {
                    if ($application->sign_off_status != '1') {
                        $edit_reason = $tourist_est_manager_update['edit_reason'];
                        unset($tourist_est_manager_update['edit_reason']);
                        if (!empty($differences = array_diff_assoc($tourist_est_manager_update, TouristEstManagers::select('firstname', 'lastname', 'post_held', 'qualifications', 'nationality')->find($id)->toArray()))) {
                            DB::beginTransaction();
                            if ($edit_transaction = EditTransactions::create([
                                'application_type_id' => 6,
                                'table_id' => $id,
                                'system_operation_type_id' => 10,
                                'edit_type_id' => 1,
                                'user_id' => auth()->user()->id,
                                'facility_id' => auth()->user()->facility_id,
                                'reason' => $edit_reason
                            ])) {
                                foreach ($differences as $key => $value) {
                                    if (!EditTransactionsChangedColumns::create([
                                        'edit_transaction_id' => $edit_transaction->id,
                                        'column_name' => $key,
                                        'old_value' => $manager->toArray()[$key],
                                        'new_value' => $tourist_est_manager_update[$key]
                                    ])) {
                                        throw new Exception("Error updating manager. Unable to initiate transaction.");
                                    }
                                }
                                if ($manager->update($tourist_est_manager_update)) {
                                    DB::commit();
                                    return redirect()->route('tourist-establishments.view', ['id' => $application->id])->with('success', 'Manager has been updated for ' . $application->establishment_name . ' successfully.');
                                } else {
                                    throw new Exception("Error updating manager. Unable to record update.");
                                }
                            } else {
                                throw new Exception("Error updating manager. Unable to initiate transaction.");
                            }
                        } else {
                            throw new Exception("None of the fields were changed. Nothing was updated");
                        }
                    } else {
                        throw new Exception("This application has already been signed off. Manager can not be edited.");
                    }
                } else {
                    throw new Exception("This application either does not exist or does not belong to your facility.");
                }
            } else {
                throw new Exception("This tourist establishment manager does not exist.");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

        // $tourist_est_manager = TouristEstManagers::find($request->manager_id);
        // $establishment_name = TouristEstablishments::find($tourist_est_manager->tourist_establishment_id)->establishment_name;

        // if ($tourist_est_manager->update($tourist_est_manager_update)) {
        //     return redirect()->route('tourist-establishments.index.filter', ['id' => 0])->with('success', $tourist_est_manager->firstname . ' ' . $tourist_est_manager->lastname . ' of Tourist Establishment ' . $establishment_name . ' has been updated successfully.');

        //     return view('tourist_est.edit_managers', compact('manager', 'establishment_name'));
        // }
    }

    public function updateService(Request $request, $id)
    {
        try {
            if ($service = TouristEstServices::find($id)) {
                if ($application = TouristEstablishments::with('user')
                    ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                    ->find($service->tourist_establishment_id)
                ) {
                    if ($application->sign_off_status != '1') {
                        if ($request->data['name'] != $service->name) {
                            DB::beginTransaction();
                            if ($edit_transaction = EditTransactions::create([
                                'application_type_id' => 6,
                                'table_id' => $service->id,
                                'system_operation_type_id' => 11,
                                'edit_type_id' => 1,
                                'user_id' => auth()->user()->id,
                                'facility_id' => auth()->user()->facility_id,
                                'reason' => $request->data['edit_reason']
                            ])) {
                                if (EditTransactionsChangedColumns::create([
                                    'edit_transaction_id' => $edit_transaction->id,
                                    'column_name' => 'name',
                                    'old_value' => $service->name,
                                    'new_value' => $request->data['name']
                                ])) {
                                    if ($service->update(['name' => $request->data['name']])) {
                                        DB::commit();
                                        return [
                                            'success',
                                            'Service ' . $request->data['name'] . ' of tourist establishment ' . $application->establishment_name . ' has been updated successfully'
                                        ];
                                    } else {
                                        throw new Exception("Error updating service. Unable to update service record.");
                                    }
                                } else {
                                    throw new Exception("Error editing service. Unable to record field changed");
                                }
                            } else {
                                throw new Exception("Error editing service. Unable to initiate transaction.");
                            }
                        } else {
                            throw new Exception("Service was not changed. There is nothing to be updated.");
                        }
                    } else {
                        throw new Exception("Tourist Establishment has already been signed off. Service cannot be updated.");
                    }
                } else {
                    throw new Exception("This tourist establishment either does not exist or does not belong to your facility.");
                }
            } else {
                throw new Exception("This service does not exist.");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
        try {
            if (TouristEstServices::find($request->data['id'])->update([
                'name' => $request->data['name']
            ])) {
                return 'success';
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteService(Request $request, $id)
    {
        try {
            if ($service = TouristEstServices::find($id)) {
                if ($application = TouristEstablishments::with('user')
                    ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                    ->find($service->tourist_establishment_id)
                ) {
                    if ($application->sign_off_status != '1') {
                        DB::beginTransaction();
                        if (EditTransactions::create([
                            'application_type_id' => 6,
                            'table_id' => $id,
                            'system_operation_type_id' => 11,
                            'edit_type_id' => 2,
                            'user_id' => auth()->user()->id,
                            'facility_id' => auth()->user()->facility_id,
                            'reason' => $request->data['edit_reason']
                        ])) {
                            if ($service->update(['deleted_at' => date('Y-m-d H:i:s')])) {
                                DB::commit();
                                return [
                                    'success',
                                    'Service ' . $service->name . 'has been deleted from tourist establishment ' . $application->establishment_name . ':' . $application->id . '.'
                                ];
                            } else {
                                throw new Exception(("Error deleting service. Unable to delete record"));
                            }
                        } else {
                            throw new Exception("Error deleting service. Unable to initiate transaction.");
                        }
                    } else {
                        throw new Exception('The tourist establishment application has already been signed off. Service cannot be deleted.');
                    }
                } else {
                    throw new Exception("The tourist establishment that this service belongs to either does not exist or is not a part of your facility.");
                }
            } else {
                throw new Exception("This service does not exist.");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function deleteManager(Request $request, $id)
    {
        try {
            if ($manager = TouristEstManagers::find($id)) {
                if ($application = TouristEstablishments::with('user')
                    ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                    ->find($manager->tourist_establishment_id)
                ) {
                    if ($application->sign_off_status != '1') {
                        DB::beginTransaction();
                        if (EditTransactions::create([
                            'application_type_id' => 6,
                            'table_id' => $manager->id,
                            'system_operation_type_id' => 10,
                            'edit_type_id' => 2,
                            'user_id' => auth()->user()->id,
                            'facility_id' => auth()->user()->facility_id,
                            'reason' => $request->data['edit_reason']
                        ])) {
                            if ($manager->update(['deleted_at' => date('Y-m-d H:i:s')])) {
                                DB::commit();
                                return [
                                    'success',
                                    'Manager ' . $manager->firstname . ' ' . $manager->lastname . ' for ' . $application->establishment_name . ' has been deleted successfully.'
                                ];
                            } else {
                                throw new Exception("Error deleting manager. Unable to delete record.");
                            }
                        } else {
                            throw new Exception("Error deleting manager. Unable to initiate transaction.");
                        }
                    } else {
                        throw new Exception("This tourist establishment application has already been signed off. This manager cannot be deleted.");
                    }
                } else {
                    throw new Exception("This tourist establishment either does not exist or isn't a part of your facility.");
                }
            } else {
                throw new Exception("This tourist establishment manager does not exist.");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function storeService(Request $request)
    {
        try {
            if ($application = TouristEstablishments::with('user')
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->find($request->data['tourist_est_id'])
            ) {
                if ($application->sign_off_status != '1') {
                    DB::beginTransaction();
                    if ($tourist_service = TouristEstServices::create([
                        'tourist_establishment_id' => $request->data['tourist_est_id'],
                        'name' => $request->data['name']
                    ])) {
                        if (EditTransactions::create([
                            'application_type_id' => 6,
                            'table_id' => $tourist_service->id,
                            'system_operation_type_id' => 11,
                            'edit_type_id' => 3,
                            'user_id' => auth()->user()->id,
                            'facility_id' => auth()->user()->facility_id,
                            'reason' => $request->data['edit_reason']
                        ])) {
                            DB::commit();
                            return [
                                'success',
                                'Service ' . $request->data['name'] . ' has been added to tourist establishment ' . $application->establishment_name . ':' . $application->id . '.'
                            ];
                        } else {
                            throw new Exception("Error adding service. Unable to initiate transaction.");
                        }
                    } else {
                        throw new Exception("Error adding new service. Unable to store record.");
                    }
                } else {
                    throw new Exception("This tourist establishment application has already been signed off. Service cannot be added");
                }
            } else {
                throw new Exception("This tourist establishment application either does not exist or doesn't belong to your facility.");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
        try {
            if (TouristEstServices::create([
                'tourist_establishment_id' => $request->data['tourist_est_id'],
                'name' => $request->data['name']
            ])) {
                return 'success';
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function renewal($id)
    {
        $application = TouristEstablishments::with('services', 'managers')->find($id);

        return view('tourist_est.renew', compact('application'));
    }

    public function renew(Request $request, $id)
    {
        $tourist_est = $request->validate([
            'establishment_name' => 'required',
            'establishment_address' => 'required',
            'bed_capacity' => 'numeric|required',
            'is_eating_establishment' => 'required',
            'eating_establishment_description' => 'nullable',
            'establishment_state' => 'required',
            'officer_firstname' => 'nullable',
            'officer_lastname' => 'nullable',
            'authorized_officer_statement' => 'nullable',
            'statement_date' => 'nullable|date',
            'application_date' => 'required|date',
            'firstname.*' => 'required_with:firstname.0|required_with:lastname.0|required_with:nationality.0',
            'lastname.*' => 'required_with:firstname.0|required_with:lastname.0|required_with:nationality.0',
            'nationality.*' => 'required_with:firstname.0|required_with:lastname.0|required_with:nationality.0',
            'services.*' => 'required_with:services.0'
        ]);

        $old_application = TouristEstablishments::find($id);

        $tourist_est['permit_no'] = $old_application->permit_no;
        $tourist_est['user_id'] = auth()->user()->id;

        if ($new_tourist_est = TouristEstablishments::create($tourist_est)) {
            foreach ($old_application->managers as $manager) {
                TouristEstManagers::find($manager->id)->update([
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);
            }

            foreach ($old_application->services as $service) {
                TouristEstServices::find($service->id)->update([
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);
            }

            if (!empty($request->firstname)) {
                $i = 0;
                foreach ($request->firstname as $item) {
                    if ($item) {
                        TouristEstManagers::create([
                            'tourist_establishment_id' => $new_tourist_est->id,
                            'firstname' => $item,
                            'lastname' => $request->lastname[$i],
                            'post_held' => $request->post_held[$i],
                            'qualifications' => $request->qualifications[$i],
                            'nationality' => $request->nationality[$i]
                        ]);
                    }
                    $i++;
                }
            }

            if (!empty($request->services)) {
                foreach ($request->services as $name) {
                    if ($name) {
                        TouristEstServices::create(
                            [
                                'tourist_establishment_id' => $new_tourist_est->id,
                                'name' => $name
                            ]

                        );
                    }
                }
            }

            if ($old_test_results = TestResult::where('application_id', $id)->where('application_type_id', 6)->first()) {
                $old_test_results->update([
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);
            }

            if (Renewals::create(
                [
                    'new_application_id' => $new_tourist_est->id,
                    'application_type_id' => 6,
                    'old_application_id' => $old_application->id

                ]
            )) {
                if ($old_application->update(['deleted_at' => date('Y-m-d H:i:s')])) {
                    return redirect()->route('tourist-establishments.index.filter', ['id' => 0])->with('success', 'Tourist Establishment ' . $new_tourist_est->establishment_name . ' has been renewed successfully. The Application ID is: ' . $new_tourist_est->id . '.');
                }
            }
        }
        return redirect()->route('tourist-establishments.index.filter', ['id' => 0])->with('error', 'Error processing renewal for Tourist Establishment ' . $old_application->establishment_name . '.');
    }

    public function destroy(Request $request, $id)
    {
        try {
            if ($application = TouristEstablishments::with('testResults', 'managers', 'services')->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())->find($id)) {
                if ($application->sign_off_status != '1') {
                    DB::beginTransaction();
                    if (EditTransactions::create([
                        'application_type_id' => 6,
                        'table_id' => $application->id,
                        'system_operation_type_id' => 2,
                        'edit_type_id' => 2,
                        'user_id' => auth()->user()->id,
                        'facility_id' => auth()->user()->facility_id,
                        'reason' => $request->data['reason']
                    ])) {
                        if (!empty($application->testResults)) {
                            if (!TestResult::find($application->testResults?->id)->update(['deleted_at' => date('Y-m-d H:i:s')])) {
                                throw new Exception("Error deleting application. Unable to delete test results");
                            }
                        }

                        if (!empty($application->managers->first())) {
                            foreach ($application->managers as $manager) {
                                if (!TouristEstManagers::find($manager->id)->update(['deleted_at' => date('Y-m-d H:i:s')])) {
                                    throw new Exception("Error deleting application. Unable to delete manger.");
                                }
                            }
                        }

                        if (!empty($application->services->first())) {
                            foreach ($application->services as $service) {
                                if (!TouristEstServices::find($service->id)->update(['deleted_at' => date('Y-m-d H:i:s')])) {
                                    throw new Exception("Error deleting application. Unable to delete service");
                                }
                            }
                        }

                        if ($application->update(['deleted_at' => date('Y-m-d H:i:s')])) {
                            DB::commit();
                            return [
                                'success',
                                'Tourist Establishment Application for ' . $application->establishment_name . ':' . $application->id . ' has been deleted successfully.'
                            ];
                        }
                    } else {
                        throw new Exception("Error deleting application. Error initiating transaction.");
                    }
                } else {
                    throw new Exception("This application has already been signed off. This application cannot be edited.");
                }
            } else {
                throw new Exception("This establishment does not exist or does not belong to your facility.");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
