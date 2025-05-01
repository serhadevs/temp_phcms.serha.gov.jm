<?php

namespace App\Http\Controllers;

use App\Models\EditTransactions;
use App\Models\EditTransactionsChangedColumns;
use App\Models\EstablishmentApplications;
use App\Models\EstablishmentCategories;
use App\Models\FoodEstablishment;
use App\Models\FoodEstablishmentOperators;
use App\Models\PermitApplication;
use App\Models\Renewals;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Dflydev\DotAccessData\Data;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FoodEstablishmentController extends Controller
{
    public function index($id)
    {
        if (auth()->user()->default_filter_id != "") {
            //Allow latty to see food establishments up to the last 6 months
            $id = 180;
        }

        $today = date_format(new Datetime(), "Y-m-d");
        $filterTimeline = "";
        if ($id == "0") {
            $filterTimeline = $today;
        } else if ($id == "1") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
            $food_establishments = EstablishmentApplications::with('establishmentCategory', 'user', 'payment', 'operators', 'signOff', 'renewal')
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->whereBetween('created_at', [$filterTimeline, $today])
                ->get();

            return view('establishments.index', compact('food_establishments'));
        } else if ($id == "7") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        } else if ($id == "30") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        } else if ($id == "90") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        } else if ($id == "180") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-180 days"), "Y-m-d");
        }

        $food_establishments = EstablishmentApplications::with('establishmentCategory', 'user', 'payment', 'operators', 'signOff', 'renewal')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->where('created_at', '>', $filterTimeline)
            ->get();


        return view('establishments.index', compact('food_establishments'));
    }

    public function indexCustom(Request $request)
    {
        date_default_timezone_set('Etc/GMT+5');
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'interval' => 'nullable|numeric|max:6'
        ]);

        $timeline["ending_date"] = $timeline["ending_date"] . " 23:59:59";

        $food_establishments = EstablishmentApplications::with('establishmentCategory', 'user', 'payment', 'operators', 'signOff', 'renewal')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->whereBetween('created_at', [$timeline["starting_date"], $timeline["ending_date"]])
            ->get();

        //dd($food_establishments);

        return view('establishments.index', compact('food_establishments'));
    }

    public function create()
    {
        $establishment_categories = EstablishmentCategories::all();

        return view('establishments.create', compact('establishment_categories'));
    }

    public function generateEstPermitNo()
    {
        do {
            $abbr = DB::table('facilities')
                ->select('abbr')
                ->where('id', auth()->user()->facility_id)
                ->first()->abbr;

            $digits_limit = 4;
            $current_date = date("my");
            $random_digits = str_pad(rand(0, pow(10, $digits_limit) - 1), $digits_limit, '0', STR_PAD_LEFT);
            $permit_no = $abbr . $random_digits . $current_date;

            $permit_no_exist = EstablishmentApplications::where('permit_no', $permit_no)->first();
        } while (!empty($permit_no_exist));

        return $permit_no;
    }

    public function store(Request $request)
    {
        $food_est_application = $request->validate([
            'current_est_closed' => 'required',
            // 'new_est' => 'accepted:1',
            'prev_est_closed' => 'required',
            'telephone' => 'required|regex:/^\+1+\(+[0-9]{3}+\)+[0-9]{3}+\-+[0-9]{4}+$/',
            'alt_telephone' => 'nullable|regex:/^\+1+\(+[0-9]{3}+\)+[0-9]{3}+\-+[0-9]{4}+$/',
            'food_type' => 'required',
            'zone' => 'required|max:3',
            'establishment_name' => 'required',
            'establishment_category_id' => 'required',
            'establishment_address' => 'required',
            'establishment_operator.0' => 'required',
            'establishment_operator.1' => 'nullable',
            'establishment_operator.2' => 'nullable',
            'establishment_operator.3' => 'nullable',
            'application_date' => 'required|date',
            'trn' => 'nullable',
            'email' => 'nullable|email',
            'closure_date' => 'required_if:current_est_closed,1|required_if:prev_est_closed,1'
        ]);

        $food_est_application["permit_no"] = $this->generateEstPermitNo();
        $food_est_application["user_id"] = auth()->user()->id;



        if (EstablishmentApplications::create($food_est_application)) {
            $est_application_id = EstablishmentApplications::select('id')->where("permit_no", $food_est_application["permit_no"])->first()->id;
            for ($i = 0; $i < count($request->establishment_operator); $i++) {
                if ($request->establishment_operator[$i] != null && $request->establishment_operator[$i] != "null") {
                    FoodEstablishmentOperators::create(
                        [
                            'establishment_application_id' => $est_application_id,
                            'name_of_operator' => $request->establishment_operator[$i]
                        ]
                    );
                }
            }
            return redirect()->route('food-establishment.filter', 0)->with('success', 'Food Establishment has been added successfully. The Est App ID : ' . $est_application_id);
        }

        return redirect()->route('food-establishment.filter', 0)->with('error', 'There was an error processing your application');
    }

    public function storeRenewal(Request $request)
    {
        //Delete Test Results
        $food_est_application = $request->validate([
            'current_est_closed' => 'required',
            // 'new_est' => 'accepted:1',
            'prev_est_closed' => 'required',
            'telephone' => 'required|regex:/^\+1+\(+[0-9]{3}+\)+[0-9]{3}+\-+[0-9]{4}+$/',
            'alt_telephone' => 'nullable|regex:/^\+1+\(+[0-9]{3}+\)+[0-9]{3}+\-+[0-9]{4}+$/',
            'food_type' => 'required',
            'zone' => 'required',
            'establishment_name' => 'required',
            'establishment_category_id' => 'required',
            'establishment_address' => 'required',
            'establishment_operator.0' => 'required',
            'establishment_operator.1' => 'nullable',
            'establishment_operator.2' => 'nullable',
            'establishment_operator.3' => 'nullable',
            'application_date' => 'required|date',
            'trn' => 'nullable',
            'email' => 'nullable|email',
            'closure_date' => 'required_if:current_est_closed,1|required_if:prev_est_closed,1'
        ]);

        $old_application = EstablishmentApplications::find($request->old_application_id);

        $food_est_application['user_id'] = auth()->user()->id;
        $food_est_application['permit_no'] = $old_application->permit_no;

        if (EstablishmentApplications::create($food_est_application)) {
            $est_application_id = EstablishmentApplications::where('permit_no', $food_est_application['permit_no'])->orderBy('created_at', 'DESC')->first()->id;
            for ($i = 0; $i < count($request->establishment_operator); $i++) {
                if ($request->establishment_operator[$i] != null && $request->establishment_operator[$i] != "null") {
                    FoodEstablishmentOperators::create(
                        [
                            'establishment_application_id' => $est_application_id,
                            'name_of_operator' => $request->establishment_operator[$i]
                        ]
                    );
                }
            }

            Renewals::create([
                'new_application_id' => $est_application_id,
                'application_type_id' => 3,
                'old_application_id' => $old_application->id,
            ]);

            $old_application->update([
                'deleted_at' => date('Y-m-d H:i:s')
            ]);

            return redirect()->route('food-establishment.filter', 0)->with('success', 'Food Establishment application for renewal has been entered successfully. The Est App ID : ' . $est_application_id);
        }
    }

    public function view(Request $request)
    {
        $est_application = EstablishmentApplications::with('operators.editTransactions', 'zippedApplication', 'editTransactions','testResults','signOff','signOff.user:id,firstname,lastname')->find($request->route('id'));
        $establishment_categories = EstablishmentCategories::withTrashed()->get();
        $enableEditFeature = "0";
        $app_type_id = 3;
        $system_operation_type_id = 1;
        //dd($est_application);
        return view('establishments.view', compact('est_application', 'establishment_categories', 'enableEditFeature', 'app_type_id', 'system_operation_type_id'));
    }

    public function renewal(Request $request)
    {
        $application = EstablishmentApplications::with('operators')->find($request->route('id'));
        $establishment_categories = EstablishmentCategories::all();

        return view('establishments.renew', compact('establishment_categories', 'application'));
    }

    public function createOperator(Request $request)
    {
        try {
            if ($food_est = EstablishmentApplications::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
                ->find($request->data['food_establishment_id'])
            ) {
                if ($food_est->sign_off_status != '1') {
                    DB::beginTransaction();
                    if ($operator = FoodEstablishmentOperators::create([
                        'establishment_application_id' => $request->data['food_establishment_id'],
                        'name_of_operator' => $request->data['name_of_operator']
                    ])) {
                        if (EditTransactions::create([
                            'application_type_id' => 3,
                            'table_id' => $operator->id,
                            'system_operation_type_id' => 9,
                            'edit_type_id' => 3,
                            'user_id' => auth()->user()->id,
                            'facility_id' => auth()->user()->facility_id,
                            'reason' => $request->data['reason']
                        ])) {
                            DB::commit();
                            return 'success';
                        }
                    }
                } else {
                    throw new Exception('This food establishment has already been signed off. No operators can be added');
                }
            } else {
                throw new Exception('This Establishment does not exist or does not belong to your facility.');
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function editOperators(Request $request)
    {
        try {
            if ($operator = FoodEstablishmentOperators::find($request->data["operator_id"])) {
                if ($food_est = EstablishmentApplications::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
                    ->find($operator->establishment_application_id)
                ) {
                    if ($food_est->sign_off_status != '1') {
                        if ($request->data['name_of_operator'] != $operator->name_of_operator) {
                            DB::beginTransaction();
                            if ($edit_transaction = EditTransactions::create([
                                'application_type_id' => 3,
                                'table_id' => $operator->id,
                                'system_operation_type_id' => 9,
                                'edit_type_id' => 1,
                                'user_id' => auth()->user()->id,
                                'facility_id' => auth()->user()->facility_id,
                                'reason' => $request->data['reason']
                            ])) {
                                if (EditTransactionsChangedColumns::create([
                                    'edit_transaction_id' => $edit_transaction->id,
                                    'column_name' => 'name_of_operator',
                                    'old_value' => $operator->name_of_operator,
                                    'new_value' => $request->data['name_of_operator']
                                ])) {
                                    if ($operator->update(
                                        ['name_of_operator' => $request->data["name_of_operator"]]
                                    )) {
                                        DB::commit();
                                        return "success";
                                    } else {
                                        throw new Exception("Error updating operator.");
                                    }
                                } else {
                                    throw new Exception("Operator was not edited. Error recording column changed.");
                                }
                            } else {
                                throw new Exception("This operator was not edited. Error initiating transaction.");
                            }
                        } else {
                            throw new Exception("Name of Operator was not edited. Nothing was updated");
                        }
                    } else {
                        throw new Exception("This food establishment has already been signed off. Operator cannot be edited.");
                    }
                } else {
                    throw new Exception("Food Establishment Application for this operator does not exist or does not belong to your facility. Operator cannot be edited.");
                }
            } else {
                throw new Exception("Operator not found");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function deleteOperator(Request $request)
    {
        try {
            if ($food_est = EstablishmentApplications::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
                ->find($request->data['est_app_id'])
            ) {
                if ($food_est->sign_off_status != '1') {
                    if ($operator = FoodEstablishmentOperators::find($request->data['operator_id'])) {
                        if (count(FoodEstablishmentOperators::where('establishment_application_id', $request->data["est_app_id"])->get()) == 1) {
                            throw new Exception("Cannot delete operator if only one exist.");
                        } else {
                            DB::beginTransaction();
                            if (EditTransactions::create([
                                'application_type_id' => 3,
                                'table_id' => $operator->id,
                                'system_operation_type_id' => 9,
                                'edit_type_id' => 2,
                                'user_id' => auth()->user()->id,
                                'facility_id' => auth()->user()->facility_id,
                                'reason' => $request->data['reason']
                            ])) {
                                if ($operator->update(['deleted_at' => date('Y-m-d H:i:s')])) {
                                    DB::commit();
                                    return "success";
                                } else {
                                    throw new Exception("Error deleting operator");
                                }
                            }
                        }
                    } else {
                        throw new Exception("This operator does not exist. It cannot be deleted.");
                    }
                } else {
                    throw new Exception("This food establishment application has already been signed off. Operator cannot be deleted.");
                }
            } else {
                throw new Exception("The food establishment application for this operator does not exist or does not belong to your facility.");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function getEdit(Request $request)
    {
        $est_application = EstablishmentApplications::with('operators', 'editTransactions')->find($request->route('id'));
        $establishment_categories = EstablishmentCategories::withTrashed()->get();
        $app_type_id = 3;
        $system_operation_type_id = 1;
        $enableEditFeature = "1";
        return view('establishments.view', compact('est_application', 'establishment_categories', 'enableEditFeature', 'app_type_id', 'system_operation_type_id'));
    }

    public function edit(Request $request, $id)
    {
        $food_est_updated = $request->validate([
            'current_est_closed' => 'required',
            'prev_est_closed' => 'required',
            'telephone' => 'required|regex:/^\+1+\(+[0-9]{3}+\)+[0-9]{3}+\-+[0-9]{4}+$/',
            'alt_telephone' => 'nullable',
            'food_type' => 'required',
            'zone' => 'numeric|min:1|max:6|required',
            'establishment_name' => 'required',
            'establishment_category_id' => 'required',
            'establishment_address' => 'required',
            'trn' => 'nullable',
            'email' => 'nullable|email',
            'closure_date' => 'required_if:current_est_closed,1|required_if:prev_est_closed,1',
            'edit_reason' => 'required'
        ]);

        try {
            if ($food_est = EstablishmentApplications::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
                ->find($id)
            ) {
                if ($food_est->sign_off_status != '1') {
                    $edit_reason = $food_est_updated['edit_reason'];
                    unset($food_est_updated['edit_reason']);
                    if (!empty($differences = array_diff_assoc(
                        $food_est_updated,
                        EstablishmentApplications::select('current_est_closed', 'prev_est_closed', 'telephone', 'alt_telephone', 'food_type', 'zone', 'establishment_name', 'establishment_category_id', 'establishment_address', 'trn', 'email', 'closure_date')->find($id)->toArray()
                    ))) {
                        DB::beginTransaction();
                        if ($edit_transaction = EditTransactions::create([
                            'application_type_id' => 3,
                            'table_id' => $food_est->id,
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
                                    'old_value' => $food_est->toArray()[$key],
                                    'new_value' => $food_est_updated[$key]
                                ])) {
                                    throw new Exception("This food establishment could not be edited. Error recording fields changed.");
                                }
                            }
                            if ($food_est->update($food_est_updated)) {
                                DB::commit();
                                return redirect()->route('food-establishment.view', ['id' => $food_est->id])->with('success', 'Food establishment Application for ' . $food_est->name . ':' . $food_est->id . ' has been updated successfully.');
                            }
                        } else {
                            throw new Exception("This food establishment application could not be edited. Error processing edit transaction.");
                        }
                    } else {
                        throw new Exception("None of the fields were changed. This application was not edited.");
                    }
                } else {
                    throw new Exception("This food establishment has already been signed off. It cannot be edited.");
                }
            } else {
                throw new Exception("This food establishment does not exist or does nopt belong to your facility.");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            if ($food_est = EstablishmentApplications::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
                ->find($id)
            ) {
                if ($food_est->sign_off_status != '1') {
                    DB::beginTransaction();
                    if (EditTransactions::create([
                        'application_type_id' => 3,
                        'table_id' => $food_est->id,
                        'system_operation_type_id' => 1,
                        'edit_type_id' => 2,
                        'user_id' => auth()->user()->id,
                        'facility_id' => auth()->user()->facility_id,
                        'reason' => $request->data['reason']
                    ])) {
                        if ($food_est->update(['deleted_at' => date('Y-m-d H:i:s')])) {
                            DB::commit();
                            return ['success', 'Food Establishment ' . $food_est->establishment_name . ':' . $food_est->id . ' has been deleted successfully.'];
                        } else {
                            throw new Exception("This application was not deleted. Error updating application record.");
                        }
                    } else {
                        throw new Exception("This application could not be deleted. Error creating transaction.");
                    }
                } else {
                    throw new Exception("This application has already been signed off. It cannot be deleted.");
                }
            } else {
                throw new Exception('This application does not exist. This cannot be deleted.');
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function showInspections(Request $request)
    {

        $id = $request->route('id');
        $today = date_format(new Datetime(), "Y-m-d");
        $yesterday = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
        $last_week = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        $thirty_days = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        $last_ninety_days = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");

        //Find the establishments in the database by joining the establishment_applications and the test_results table

        $filterTimeline = "";
        if ($id == "0") {
            $filterTimeline = $today;
            $inspections = EstablishmentApplications::with('testResults', 'user')
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                // ->where('created_at', '>', $filterTimeline)
                ->where('id', 23603)
                ->get();

            //dd($inspections);
            return view('establishments.inspections', compact('inspections'));
        } else if ($id == "1") {
            $filterTimeline = $yesterday;
        } else if ($id == "7") {
            $filterTimeline = $last_week;
        } else if ($id == "30") {
            $filterTimeline = $thirty_days;
        } else if ($id == "90") {
            $filterTimeline = $last_ninety_days;
        } else if ($id == "180") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-180 days"), "Y-m-d");
        }


        $inspections = EstablishmentApplications::with('testResults', 'user')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->where('created_at', '>', [$filterTimeline, $today])
            ->get();

        return view('establishments.inspections', compact('inspections'));
    }

    public function foodEstablishmentCategories()
    {
        $categories = EstablishmentCategories::all();

        return view('establishments.categories.index', compact('categories'));
    }

    public function addEstCategory(Request $request)
    {
        try {
            DB::beginTransaction();
            if ($est_cat = EstablishmentCategories::create(['name' => $request->data['est_cat_name']])) {
                if (EditTransactions::create([
                    'application_type_id' => 3,
                    'table_id' => $est_cat->id,
                    'system_operation_type_id' => 12,
                    'edit_type_id' => 3,
                    'user_id' => auth()->user()->id,
                    'facility_id' => auth()->user()->facility_id,
                    'reason' => $request->data['reason']
                ])) {
                    DB::commit();
                    return [
                        'success',
                        'New Establishment Category: ' . $request->data['est_cat_name'] . ' has been created successfully.'
                    ];
                } else {
                    throw new Exception("Error creating establishment category. Unable to store transaction");
                }
            } else {
                throw new Exception("Error creating establishment category. Unable to store record.");
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $ex->getMessage();
        }
    }

    public function updateEstCategory(Request $request)
    {
        try {
            DB::beginTransaction();
            if ($estCategory = EstablishmentCategories::find($request->data['est_cat_id'])) {
                if ($edit_transaction = EditTransactions::create([
                    'application_type_id' => 3,
                    'table_id' => $request->data['est_cat_id'],
                    'system_operation_type_id' => 12,
                    'edit_type_id' => 1,
                    'user_id' => auth()->user()->id,
                    'facility_id' => auth()->user()->facility_id,
                    'reason' => $request->data['reason']
                ])) {
                    if (EditTransactionsChangedColumns::create([
                        'edit_transaction_id' => $edit_transaction->id,
                        'column_name' => 'name',
                        'old_value' => $estCategory->name,
                        'new_value' => $request->data['est_cat_updated']
                    ])) {
                        if ($estCategory->update(['name' => $request->data['est_cat_updated']])) {
                            DB::commit();
                            return [
                                'success',
                                'New Establishment Category: ' . $request->data['est_cat_updated'] . ' has been updated successfully'
                            ];
                        } else {
                            throw new Exception("Error updating establishment category. Unable to update record.");
                        }
                    } else {
                        throw new Exception("Error update establishment category. Unable to record changed field");
                    }
                } else {
                    throw new Exception("Error updating establishment category. Unable to store transaction");
                }
            } else {
                throw new Exception("Unable to update establishment category. This category does not exist.");
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $ex->getMessage();
        }
    }

    public function deleteEstCategory(Request $request)
    {
        try {
            DB::beginTransaction();
            if ($estCategory = EstablishmentCategories::find($request->data['est_cat_id'])) {
                if (EditTransactions::create([
                    'application_type_id' => 3,
                    'table_id' => $request->data['est_cat_id'],
                    'system_operation_type_id' => 12,
                    'edit_type_id' => 2,
                    'user_id' => auth()->user()->id,
                    'facility_id' => auth()->user()->facility_id,
                    'reason' => $request->data['reason']
                ])) {
                    if ($estCategory->update(['deleted_at' => date('Y-m-d H:i:s')])) {
                        DB::commit();
                        return [
                            'success',
                            'New Establishment Category: ' . $estCategory->name . ' has been deleted successfully'
                        ];
                    } else {
                        throw new Exception("Error deleting establishment category. Unable to delete record.");
                    }
                } else {
                    throw new Exception("Error deleting establishment category. Unable to store transaction");
                }
            } else {
                throw new Exception("Unable to delete establishment category. This category does not exist.");
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $ex->getMessage();
        }
    }

    public function expiredEstabtablishments($days){
        
        
        $now = Carbon::now(); 
        switch ($days) {
            case 0:
                $expiryDays = $now;
                break;
            case 30:
                $expiryDays = $now->copy()->addDays(30);
                break;
            case 60:
                $expiryDays = $now->copy()->addDays(60);
                break;
            case 90:
                $expiryDays = $now->copy()->addDays(90);
                break;
            default:
                $expiryDays = $now; 
                break;
        }
        
    
        // Fetch Expired Application
        try {
            
            $food_establishments = EstablishmentApplications::with('payment')
            ->join('sign_offs', 'sign_offs.application_id', '=', 'establishment_applications.id')
            ->whereIn('establishment_applications.user_id', User::facilityUserId()->pluck('id'))
            //->where('sign_offs.sign_off_status',1)
            ->whereBetween('sign_offs.expiry_date', isset($expiryDays) && $expiryDays != $now ? [$now, $expiryDays] : [$now])
            ->get();

            //dd($food_establishments);

            return view('establishments.expiredapplications.index',compact('food_establishments','days','now'));
        } catch (\Throwable $e) {
            Log::error('Error fetching expiry count: ' . $e->getMessage());
            $food_establishments = 0;
        }

        
        
    }

   
}
