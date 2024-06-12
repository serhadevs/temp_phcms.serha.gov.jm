<?php

namespace App\Http\Controllers;

use App\Models\EstablishmentApplications;
use App\Models\EstablishmentClinics;
use App\Models\FoodEstablishmentOperators;
use App\Models\HealthCertApplications;
use App\Models\Payments;
use App\Models\PermitApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class AdvanceSearchController extends Controller
{
    public function index()
    {
    }

    public function create()
    {
        $establishment_clinics = EstablishmentClinics::with('user')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->orderBy('name', 'asc')
            ->get();

        $food_establishments = EstablishmentApplications::with('user')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->orderBy('establishment_name', 'asc')
            ->get();

        $operators = FoodEstablishmentOperators::with('foodEstablishment.user')
            ->whereRelation('foodEstablishment.user', 'facility_id', auth()->user()->facility_id)
            ->orderBy('name_of_operator', 'asc')
            ->select('name_of_operator')
            ->get();

        return view('advancesearch.create', compact('establishment_clinics', 'food_establishments', 'operators'));
    }

    public function show(Request $request)
    {
        $module = $request->validate([
            'module' => 'required',
        ]);
        try {
            if ($module['module'] == '1') {
                if (!$request->firstname && !$request->lastname && !$request->application_number && !$request->establishment_clinic_name) {
                    return redirect()->route('advance-search')->with('error', 'At least one field has to be entered for search.');
                }
                $firstname = $request->firstname;
                $lastname = $request->lastname;
                $id = $request->application_number;
                $est_clinic_name = $request->establishment_clinic_name;
                $permit_applications = PermitApplication::with('user', 'establishmentClinics', 'signOffs')
                    ->when(
                        $firstname,
                        function ($query, string $firstname) {
                            $query->where('firstname', 'like', "%" . $firstname . "%");
                        }
                    )->when(
                        $lastname,
                        function ($query, $lastname) {
                            $query->where('lastname', 'like', "%" . $lastname . "%");
                        }
                    )->when(
                        $id,
                        function ($query, $id) {
                            $query->where('id', $id);
                        }
                    )->when(
                        $est_clinic_name,
                        function ($query, $est_clinic_name) {
                            $query->whereRelation('establishmentClinics', 'name', 'like', "%" . $est_clinic_name . "%");
                        }
                    )
                    ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                    ->get();
                $module = 1;

                return view('advancesearch.view', compact('permit_applications', 'module'));
            } else if ($module['module'] == '2') {
                if (!$request->establishment_clinic_name && !$request->application_number) {
                    return redirect()->route('advance-search')->with('error', 'At least one field has to be entered for search.');
                }
                $id = $request->application_number;
                $est_clinic_name = $request->establishment_clinic_name;
                $food_clinics = EstablishmentClinics::with('payment', 'user')->withCount('permits')
                    ->when($est_clinic_name, function ($query, string $est_clinic_name) {
                        $query->where('name', 'like', "%" . $est_clinic_name . "%");
                    })->when($id, function ($query, string $id) {
                        $query->where('id', $id);
                    })->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                    ->get();
                $module = 2;

                return view('advancesearch.view', compact('food_clinics', 'module'));
            } else if ($module['module'] == '3') {
                if ($request->app_type == "1") {
                    if (!$request->firstname && !$request->lastname && !$request->application_number) {
                        return redirect()->route('advance-search')->with('error', 'At least one field has to be entered for search.');
                    }
                    $module = 3;
                    $app_type_id = 1;
                    $firstname = $request->firstname;
                    $lastname = $request->lastname;
                    $id = $request->application_number;

                    $test_results = PermitApplication::with('permitCategory', 'testResults', 'user')
                        ->whereRelation('user', 'facility_id', Auth()->user()->facility_id)
                        ->when($firstname, function ($query, string $firstname) {
                            $query->where('firstname', 'like', '%' . $firstname . '%');
                        })->when($lastname, function ($query, string $lastname) {
                            $query->where('lastname', 'like', '%' . $lastname . '%');
                        })->when($id, function ($query, string $id) {
                            $query->where('id', $id);
                        })
                        ->get();

                    return view('advancesearch.view', compact('test_results', 'module', 'app_type_id'));
                } else if ($request->app_type == "2") {
                    if (!$request->food_est_name && !$request->application_number) {
                        return redirect()->route('advance-search')->with('error', 'At least one field has to be entered for search.');
                    }
                    $app_type_id = 3;
                    $module = 3;
                    $est_name = $request->food_est_name;
                    $application_id = $request->application_number;

                    $applications = EstablishmentApplications::with('establishmentCategory', 'testResults', 'user', 'signOff')
                        ->when($est_name, function ($query, string $est_name) {
                            $query->where('establishment_name', 'like', '%' . $est_name . '%');
                        })
                        ->when($application_id, function ($query, string $application_id) {
                            $query->where('id', '=', $application_id);
                        })
                        ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                        ->get();

                    return view('advancesearch.view', compact('applications', 'module', 'app_type_id'));
                }
            } else if ($module['module'] == 4) {
                if (!$request->firstname && !$request->lastname && !$request->application_number) {
                    return redirect()->route('advance-search')->with('error', 'At least one field has to be entered for search.');
                }
                $firstname = $request->firstname;
                $lastname = $request->lastname;
                $id = $request->application_number;
                $module = 4;
                if ($request->app_type == 1) {
                    $app_type_id = 1;
                    $applications = PermitApplication::with('healthInterviews.healthInterviewSymptom.symptoms', 'user', 'payment', 'signOffs')
                        ->has('payment')
                        ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                        ->when($firstname, function ($query, string $firstname) {
                            $query->where('firstname', 'like', '%' . $firstname . '%');
                        })->when($lastname, function ($query, string $lastname) {
                            $query->where('lastname', 'like', '%' . $lastname . '%');
                        })->when($id, function ($query, string $id) {
                            $query->where('id', $id);
                        })->orderBy('created_at', 'desc')
                        ->get();
                    return view('advancesearch.view', compact('applications', 'module', 'app_type_id'));
                } else if ($request->app_type == 3) {
                    $app_type_id = 2;
                    $applications = HealthCertApplications::with('healthInterviews.healthInterviewSymptom.symptoms', 'user', 'payment')
                        ->has('payment')
                        ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                        ->when($firstname, function ($query, string $firstname) {
                            $query->where('firstname', 'like', '%' . $firstname . '%');
                        })->when($lastname, function ($query, string $lastname) {
                            $query->where('lastname', 'like', '%' . $lastname . '%');
                        })->when($id, function ($query, string $id) {
                            $query->where('id', $id);
                        })->orderBy('created_at', 'desc')
                        ->get();
                    return view('advancesearch.view', compact('applications', 'module', 'app_type_id'));
                }
            } else if ($module['module'] == '5') {
                if (!$request->receipt_no && !$request->application_number) {
                    return redirect()->route('advance-search')->with('error', 'At least one field has to be entered for search.');
                }
                $application_id = $request->application_number;
                $receipt_no = $request->receipt_no;

                $payments_info = Payments::with('applicationType', 'paymentCancellation', 'cashier')
                    ->when($application_id, function ($query, string $application_id) {
                        $query->where('application_id', $application_id);
                    })
                    ->when($receipt_no, function ($query, string $receipt_no) {
                        $query->where('receipt_no', $receipt_no);
                    })
                    ->where('facility_id', auth()->user()->facility_id)
                    ->get();

                $module = 5;
                return view('advancesearch.view', compact('payments_info', 'module'));
            } else if ($module['module'] == '6') {
                if (!$request->application_number && !$request->food_est_name && !$request->operator_name) {
                    return redirect()->route('advance-search')->with('error', 'At least one field has to be entered for search.');
                }

                $id = $request->application_number;
                $est_name = $request->food_est_name;
                $operator_name = $request->operator_name;
                $food_establishments = EstablishmentApplications::with('user', 'operators')
                    ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                    ->when($id, function ($query, string $id) {
                        $query->where('id', $id);
                    })->when($est_name, function ($query, string $est_name) {
                        $query->where('establishment_name', 'like', '%' . $est_name . '%');
                    })
                    ->when($operator_name, function ($query, string $operator_name) {
                        $query->whereRelation('operators', 'name_of_operator', 'like', '%' . $operator_name . '%');
                    })->get();
                $module = 6;

                return view('advancesearch.view', compact('food_establishments', 'module'));
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }
}
