<?php

namespace App\Http\Controllers;

use App\Models\EstablishmentApplications;
use App\Models\EstablishmentCategories;
use App\Models\FoodEstablishment;
use App\Models\FoodEstablishmentOperators;
use App\Models\PermitApplication;
use App\Models\Renewals;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use DateTime;
use Dflydev\DotAccessData\Data;
use Exception;

class FoodEstablishmentController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->route('id');
        $today = date_format(new Datetime(), "Y-m-d");
        $yesterday = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
        $last_week = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        $thirty_days = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        $last_ninety_days = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");

        $filterTimeline = "";
        if ($id == "0") {
            $filterTimeline = $today;
            $food_establishments = EstablishmentApplications::with('establishmentCategory', 'user', 'payment', 'operators')
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->where('created_at', '>', $filterTimeline)
                ->get();
            return view('establishments.index', compact('food_establishments'));
        } else if ($id == "1") {
            $filterTimeline = $yesterday;
        } else if ($id == "7") {
            $filterTimeline = $last_week;
        } else if ($id == "30") {
            $filterTimeline = $thirty_days;
        } else if ($id == "90") {
            $filterTimeline = $last_ninety_days;
        }

        $food_establishments = EstablishmentApplications::with('establishmentCategory', 'user', 'payment', 'operators')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->whereBetween('created_at', [$filterTimeline, $today])
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

        $food_establishments = EstablishmentApplications::with('establishmentCategory', 'user', 'payment', 'operators')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->whereBetween('created_at', [$timeline["starting_date"], $timeline["ending_date"]])
            ->get();

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
                'deleted_at' => new DateTime()
            ]);

            return redirect()->route('food-establishment.filter', 0)->with('success', 'Food Establishment application for renewal has been entered successfully. The Est App ID : ' . $est_application_id);
        }
    }

    public function view(Request $request)
    {
        $est_application = EstablishmentApplications::with('operators')->find($request->route('id'));
        $establishment_categories = EstablishmentCategories::all();
        $enableEditFeature = "0";
        //dd($est_application);
        return view('establishments.view', compact('est_application', 'establishment_categories', 'enableEditFeature'));
    }

    public function renewal(Request $request)
    {
        $application = EstablishmentApplications::with('operators')->find($request->route('id'));
        $establishment_categories = EstablishmentCategories::all();

        return view('establishments.renew', compact('establishment_categories', 'application'));
    }

    public function editOperators(Request $request)
    {
        try {
            if (FoodEstablishmentOperators::find($request->data["operator_id"])) {
                if (FoodEstablishmentOperators::find($request->data["operator_id"])->update(
                    ['name_of_operator' => $request->data["name_of_operator"]]
                )) {
                    return "success";
                } else {
                    throw new Exception("Error updating operator.");
                }
            } else {
                throw new Exception("Operator not found");
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteOperator(Request $request)
    {
        try {
            if (count(FoodEstablishmentOperators::where('establishment_application_id', $request->data["est_app_id"])->get()) == 1) {
                throw new Exception("Cannot delete operator if only one exist.");
            } else {
                if (FoodEstablishmentOperators::where('id', $request->data["operator_id"])->update(['deleted_at' => new DateTime()])) {
                    return "success";
                } else {
                    throw new Exception("Error deleting operator");
                }
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getEdit(Request $request)
    {
        $est_application = EstablishmentApplications::with('operators')->find($request->route('id'));
        $establishment_categories = EstablishmentCategories::all();
        $enableEditFeature = "1";
        return view('establishments.view', compact('est_application', 'establishment_categories', 'enableEditFeature'));
    }

    public function edit(Request $request)
    {
        $food_est_application = $request->validate([
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
            'closure_date' => 'required_if:current_est_closed,1|required_if:prev_est_closed,1'
        ]);

        if (EstablishmentApplications::find($request->application_id)->update($food_est_application)) {
            return redirect()->route('food-establishment.filter', 0)->with('success', 'Food Establishment has been successfully updated.');
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
                ->where('id',23603)
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
        }

       
            $inspections = EstablishmentApplications::with('testResults','user')
            ->whereRelation('user','facility_id',auth()->user()->facility_id)
            ->where('created_at', '>', [$filterTimeline, $today])
            ->get();

        return view('establishments.inspections', compact('inspections'));
    }

    


}
