<?php

namespace App\Http\Controllers;

use App\Models\EstablishmentApplications;
use App\Models\EstablishmentClinics;
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

        return view('advancesearch.create', compact('establishment_clinics', 'food_establishments'));
    }

    public function show(Request $request)
    {
        $module = $request->validate([
            'module' => 'required',
        ]);
        try {
            if ($module['module'] == '1') {
                if ($request->firstname == "" && $request->lastname == "" && $request->application_nu) {
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
                $id = $request->application_number;
                $est_clinic_name = $request->establishment_clinic_name;
                $food_clinics = EstablishmentClinics::with('payment', 'user')
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
                    
                } else if ($request->app_type == "2") {
                    $app_type_id = 3;
                    $module = 3;
                    $est_name = $request->food_est_name;
                    $application_id = $request->application_number;

                    $applications = EstablishmentApplications::with('establishmentCategory', 'testResults', 'user')
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
            } else if ($module['module'] == '5') {
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
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }

        // try {
        //     // $module = (int)$request->module;
        //     // $test_type = (int)$request->test_type;
        //     // $interview_type = (int)$request->interview_type;
        //     // $interview_app_id = (int)$request->interview_app_id;
        //     // $application_id = (int)$request->application_id;
        //     // $establishment_name = $request->establishment_name;
        //     // $firstname = $request->firstname;
        //     // $lastname = $request->lastname;
        //     // $app_id = (int)$request->app_id;
        //     // $food_establishment = $request->food_establishment;

        //     // dd($module,$firstname,$lastname);
        //     // if ($module == 1) {
        //     //     $permits = DB::table('permit_applications')->whereNull('permit_applications.deleted_at')
        //     //         ->whereNull('payments.deleted_at')
        //     //         ->whereIn('permit_applications.user_id', User::facilityUsers()
        //     //             ->pluck('id')->flatten())
        //     //         ->leftJoin('establishment_clinics', 'establishment_clinics.id', '=', 'permit_applications.establishment_clinic_id')
        //     //         ->leftJoin('permit_categories', 'permit_categories.id', '=', 'permit_applications.permit_category_id')
        //     //         ->leftJoin('appointments', function ($join) {
        //     //             $join->on('appointments.permit_application_id', '=', 'permit_applications.id')
        //     //                 ->where('appointments.facility_id', '=', auth()->user()->facility_id);
        //     //         })
        //     //         ->leftJoin('exam_dates', function ($join) {
        //     //             $join->on('exam_dates.id', '=', 'appointments.exam_date_id')
        //     //                 ->where('exam_dates.facility_id', '=', auth()->user()->facility_id);
        //     //         })
        //     //         ->leftJoin('exam_sites', function ($join) {
        //     //             $join->on('exam_sites.id', '=', 'exam_dates.exam_site_id')
        //     //                 ->where('exam_sites.facility_id', '=', auth()->user()->facility_id);
        //     //         })
        //     //         ->leftJoin('users', 'users.id', '=', 'permit_applications.user_id')
        //     //         ->leftJoin('payments', function ($join) {
        //     //             $join->on('permit_applications.id', '=', 'payments.application_id')
        //     //                 ->where('payments.application_type_id', '=', 1)
        //     //                 ->where('payments.deleted_at', '=', null)
        //     //                 ->where('payments.facility_id', '=', auth()->user()->facility_id);
        //     //         })
        //     //         ->leftJoin('sign_offs', function ($join) {
        //     //             $join->on('permit_applications.id', '=', 'sign_offs.application_id')
        //     //                 ->where('sign_offs.application_type_id', '=', 1)
        //     //                 ->where('sign_offs.deleted_at', '=', null);
        //     //         })
        //     //         ->select(
        //     //             'permit_applications.*',
        //     //             'permit_applications.created_at as permit_created_at',
        //     //             'permit_applications.id as permit_id',
        //     //             'permit_applications.firstname as permit_fname',
        //     //             'permit_applications.middlename as permit_mname',
        //     //             'permit_applications.lastname as permit_lname',
        //     //             'payments.receipt_no',
        //     //             'sign_offs.expiry_date',
        //     //             'users.firstname as added_by_fname',
        //     //             'users.lastname as added_by_lname',
        //     //             'exam_dates.exam_start_time',
        //     //             'appointments.appointment_date',
        //     //             'exam_sites.name as exam_site',
        //     //             'permit_categories.name as permit_category_name',
        //     //             'establishment_clinics.proposed_date',
        //     //             'establishment_clinics.address as clinic_address',
        //     //             'establishment_clinics.proposed_time',
        //     //             'establishment_clinics.name as est_clinic_name'
        //     //         )
        //     //         ->when($application_id, function ($query) use ($application_id) {
        //     //             return $query->where('permit_applications.id', '=', $application_id);
        //     //         })
        //     //         ->when($firstname, function ($query) use ($firstname) {
        //     //             return $query->where('permit_applications.firstname', 'like', $firstname . '%');
        //     //         })
        //     //         ->when($lastname, function ($query) use ($lastname) {
        //     //             return $query->where('permit_applications.lastname', 'like', '%' . $lastname . '%');
        //     //         })
        //     //         ->when($establishment_name, function ($query) use ($establishment_name) {
        //     //             return $query->where('establishment_clinics.name', '=', $establishment_name);
        //     //         })
        //     //         ->distinct()
        //     //         ->orderBy('permit_created_at', 'desc')
        //     //         ->get();
        //     //  } else if ($module == 2) {
        //     //     $onsite_id = $request->onsite_id;
        //     //     $establishment_no = $request->establishment_no;


        //     //     $permits = DB::table('establishment_clinics')
        //     //         ->join('payments', 'payments.application_id', '=', 'establishment_clinics.id')
        //     //         ->join('users', 'users.id', '=', 'establishment_clinics.user_id')
        //     //         ->whereIn('establishment_clinics.user_id', User::facilityUsers()
        //     //             ->pluck('id')->flatten())
        //     //         ->leftJoin('permit_applications', function ($join) {
        //     //             $join->on('establishment_clinics.id', '=', 'permit_applications.establishment_clinic_id')
        //     //                 ->where('permit_applications.deleted_at', '=', null);
        //     //         })
        //     //         ->where('payments.application_type_id', 4)
        //     //         ->where('permit_applications.deleted_at', '=', null)
        //     //         ->where('payments.facility_id', '=', auth()->user()->facility_id)
        //     //         ->select(
        //     //             'establishment_clinics.*',
        //     //             DB::raw('concat(" ",establishment_clinics.proposed_date,"  ",establishment_clinics.proposed_time) as proposed_date'),
        //     //             'users.firstname',
        //     //             'users.lastname',
        //     //             'payments.receipt_no as payment_status',
        //     //             //DB::raw('count(permit_applications.establishment_clinic_id) as permit_count ')
        //     //         )
        //     //         ->when($onsite_id, function ($query) use ($onsite_id) {
        //     //             return $query->where('establishment_clinics.id', '=', $onsite_id);
        //     //         })
        //     //         ->when($establishment_no, function ($query) use ($establishment_no) {
        //     //             return $query->where('establishment_clinics.name', '=', $establishment_no);
        //     //         })->latest()->get();

        //     //     // dd($permits);
        //     }

        //     //dd($permits);


        //     return view('advancesearch.view', compact('permits'));
        // } catch (\Exception $e) {
        //     return view('advancesearch.create')->with('error', "Unknown error" . $e);
        // }
    }
}
