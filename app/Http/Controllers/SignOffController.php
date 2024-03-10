<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ApplicationType;
use App\Models\EstablishmentApplications;
use App\Models\ExamSites;
use App\Models\HealthCertApplications;
use App\Models\HealthInterview;
use App\Models\PermitApplication;
use App\Models\PermitCategory;
use App\Models\SignOff;
use App\Models\SwimmingPoolsApplications;
use App\Models\TestResult;
use App\Models\TouristEstablishments;
use Illuminate\Support\Facades\DB;
use DateTime;
use Exception;

class SignOffController extends Controller
{

    public function index()
    {
        $excludedIds = [4, 7];
        $application_type = ApplicationType::whereNotIn('id', $excludedIds)->get();

        return view('signoffs.index', compact('application_type'));
    }

    public function create(Request $request, $id)
    {
        $id = $request->id;
        $exam_sites = DB::table('exam_sites')->whereNull('deleted_at')->where('facility_id', auth()->user()->facility_id)->get();
        return view('signoffs.create', compact('exam_sites', 'id'));
    }

    public function fetchApplications(Request $request)
    {
        $sign_off_params = $request->validate([
            'app_type_id'=>'required',
            'exam_date'=>'required_if:app_type_id,1,2',
            'clinic_mode'=>'required_if:app_type_id,1',
            'exam_site'=>'required_if:clinic_mode,regular|required_if:app_type_id,2',
            'date_of_inspection'=>'required_if:app_type_id,3,5,6'
        ]);
        $app_type_id = $request->route('id');
        $exam_date = Carbon::parse($request->exam_date)->format('Y-m-d');
        $exam_site = $request->exam_site;
        $date_of_inspection = Carbon::parse($request->date_of_inspection)->format('Y-m-d');
        $clinic_mode = $request->clinic_mode;

        if ($app_type_id == 1) {
            if ($clinic_mode == "onsite") {
                $applications = DB::table('health_interviews')
                    ->where('health_interviews.facility_id', Auth()->user()->facility_id)
                    ->where('health_interviews.deleted_at', '=', null)
                    ->join('permit_applications', 'health_interviews.permit_application_id', '=', 'permit_applications.id')
                    ->join('permit_categories', 'permit_categories.id', '=', 'permit_applications.permit_category_id')
                    ->join('establishment_clinics', function ($join) use ($exam_date) {
                        $join->on('establishment_clinics.id', '=', 'permit_applications.establishment_clinic_id')
                            ->where('establishment_clinics.proposed_date', '=', $exam_date);
                    })
                    ->leftJoin('test_results', function ($join) {
                        $join->on('health_interviews.permit_application_id', '=', 'test_results.application_id')
                            ->where('test_results.deleted_at', '=', null)
                            ->where('test_results.application_type_id', '=', 1);
                    })
                    ->leftJoin('health_interview_symptom', 'health_interviews.id', '=', 'health_interview_symptom.health_interview_id')
                    ->leftJoin('symptoms', 'symptoms.id', '=', 'health_interview_symptom.symptom_id')
                    ->leftJoin('travel_history', function ($join) {
                        $join->on('travel_history.permit_application_id', '=', 'health_interviews.permit_application_id')
                            ->where('travel_history.deleted_at', '=', null);
                    })
                    ->select(
                        'establishment_clinics.proposed_date',
                        'establishment_clinics.name as est_name',
                        'establishment_clinics.address as est_address',
                        'permit_applications.permit_no',
                        'permit_applications.firstname as permit_firstname',
                        'permit_applications.id as permit_id',
                        'permit_applications.middlename as permit_middlename',
                        'permit_applications.lastname as permit_lastname',
                        'permit_applications.address as permit_address',
                        'permit_applications.photo_upload',
                        'permit_applications.date_of_birth',
                        'permit_applications.gender as permit_gender',
                        'permit_applications.sign_off_status',
                        'permit_categories.name as permit_category',
                        DB::raw('group_concat("  ", travel_history.destination, " - " ,travel_history.travel_date) as travel_history'),
                        'test_results.overall_score',
                        'test_results.critical_score',
                        'health_interviews.*',
                        'health_interviews.id as interview_id',
                        DB::raw('group_concat("  ", symptoms.name) as symptoms')
                    )
                    ->where('permit_applications.deleted_at', NULL)
                    ->groupBy('health_interviews.id', 'test_results.id', 'travel_history.id', 'establishment_clinics.id')
                    ->orderBy('establishment_clinics.name')
                    ->orderBy('permit_applications.sign_off_status')
                    ->get();
            } else if ($clinic_mode == "regular") {
                $applications = DB::table('health_interviews')
                    ->where('health_interviews.facility_id', Auth()->user()->facility_id)
                    ->where('health_interviews.deleted_at', '=', null)
                    ->join('permit_applications', function ($join) {
                        $join->on('health_interviews.permit_application_id', '=', 'permit_applications.id')
                            ->where('permit_applications.establishment_clinic_id', '=', null);
                    })
                    ->join('permit_categories', 'permit_categories.id', '=', 'permit_applications.permit_category_id')
                    ->join('appointments', function ($join) use ($exam_date) {
                        $join->on('appointments.permit_application_id', '=', 'permit_applications.id')
                            ->where('appointments.deleted_at', '=', null)
                            ->where('appointments.appointment_date', '=', $exam_date);
                    })
                    ->join('exam_dates', 'exam_dates.id', '=', 'appointments.exam_date_id')
                    ->join('exam_sites', function ($join) use ($exam_site) {
                        $join->on('exam_sites.id', '=', 'exam_dates.exam_site_id')
                            ->where('exam_sites.id', '=', $exam_site);
                    })
                    ->leftJoin('test_results', function ($join) {
                        $join->on('test_results.application_id', '=', 'health_interviews.permit_application_id')
                            ->where('test_results.deleted_at', '=', null)
                            ->where('test_results.application_type_id', '=', 1);
                    })
                    ->leftJoin('health_interview_symptom', 'health_interview_symptom.health_interview_id', '=', 'health_interviews.id')
                    ->leftJoin('symptoms', 'symptoms.id', '=', 'health_interview_symptom.symptom_id')
                    ->leftJoin('travel_history', function ($join) {
                        $join->on('travel_history.permit_application_id', '=', 'health_interviews.permit_application_id')
                            ->where('travel_history.deleted_at', '=', null);
                    })
                    ->select(
                        'permit_applications.permit_no',
                        'exam_sites.name as exam_site',
                        'appointments.appointment_date',
                        'permit_applications.firstname as permit_firstname',
                        'permit_applications.id as permit_id',
                        'permit_applications.middlename as permit_middlename',
                        'permit_applications.lastname as permit_lastname',
                        'permit_applications.address as permit_address',
                        'permit_applications.date_of_birth',
                        'permit_applications.gender as permit_gender',
                        'permit_applications.sign_off_status',
                        'permit_applications.photo_upload',
                        'permit_categories.name as permit_category',
                        DB::raw('group_concat("  ", travel_history.destination, " - " ,travel_history.travel_date) as travel_history'),
                        'test_results.overall_score',
                        'test_results.critical_score',
                        'health_interviews.*',
                        'health_interviews.id as interview_id',
                        DB::raw('group_concat("  ", symptoms.name) as symptoms')
                    )
                    ->where('permit_applications.deleted_at', NULL)
                    ->groupBy('health_interviews.id', 'test_results.id', 'travel_history.id', 'appointments.id')
                    ->orderBy('permit_applications.sign_off_status')
                    ->get();
            }
        } elseif ($app_type_id == 2) {
            $applications = DB::table('health_interviews')
                ->where('health_interviews.facility_id', Auth()->user()->facility_id)
                ->where('health_interviews.deleted_at', '=', null)
                ->join('health_cert_applications', function ($join) {
                    $join->on('health_interviews.health_cert_application_id', '=', 'health_cert_applications.id');
                })
                ->join('appointments', function ($join) use ($exam_date) {
                    $join->on('appointments.health_cert_application_id', '=', 'health_cert_applications.id')
                        ->where('appointments.deleted_at', '=', null)
                        ->where('appointments.appointment_date', '=', $exam_date);
                })
                ->join('exam_dates', 'exam_dates.id', '=', 'appointments.exam_date_id')
                ->join('exam_sites', function ($join) use ($exam_site) {
                    $join->on('exam_sites.id', '=', 'exam_dates.exam_site_id')
                        ->where('exam_sites.id', '=', $exam_site);
                })
                ->leftJoin('test_results', function ($join) {
                    $join->on('test_results.application_id', '=', 'health_interviews.health_cert_application_id')
                        ->where('test_results.deleted_at', '=', null)
                        ->where('test_results.application_type_id', '=', 2);
                })
                ->leftJoin('health_interview_symptom', 'health_interview_symptom.health_interview_id', '=', 'health_interviews.id')
                ->leftJoin('symptoms', 'symptoms.id', '=', 'health_interview_symptom.symptom_id')
                ->leftJoin('travel_history', function ($join) {
                    $join->on('travel_history.health_cert_application_id', '=', 'health_interviews.health_cert_application_id')
                        ->where('travel_history.deleted_at', '=', null);
                })
                ->select(
                    'exam_sites.name as exam_site',
                    'appointments.appointment_date',
                    'health_cert_applications.id as health_cert_id',
                    'health_cert_applications.firstname as health_cert_firstname',
                    'health_cert_applications.middlename as health_cert_middlename',
                    'health_cert_applications.lastname as health_cert_lastname',
                    'health_cert_applications.address as health_cert_address',
                    'health_cert_applications.permit_no',
                    'health_cert_applications.sex as health_cert_gender',
                    'health_cert_applications.sign_off_status',
                    'health_cert_applications.date_of_birth',
                    DB::raw('group_concat("  ", travel_history.destination, " - " ,travel_history.travel_date) as travel_history'),
                    'test_results.overall_score',
                    'health_interviews.*',
                    'health_interviews.id as interview_id',
                    DB::raw('group_concat("  ", symptoms.name) as symptoms')
                )
                ->groupBy('health_interviews.id', 'test_results.id', 'travel_history.id', 'appointments.id')
                ->get();
        } elseif ($app_type_id == 3) {
            $applications = DB::table('establishment_applications')
                ->where('establishment_applications.deleted_at', '=', null)
                ->join('food_est_operators', 'food_est_operators.establishment_application_id', '=', 'establishment_applications.id')
                ->join('establishment_categories', 'establishment_applications.establishment_category_id', '=', 'establishment_categories.id')
                ->join('test_results', function ($join) use ($date_of_inspection) {
                    $join->on('test_results.application_id', '=', 'establishment_applications.id')
                        ->where('test_results.application_type_id', '=', 3)
                        ->where('test_results.facility_id', '=', Auth()->user()->facility_id)
                        ->where('test_results.deleted_at', '=', null)
                        ->where('test_results.test_date', '=', $date_of_inspection);
                })
                ->select(
                    'establishment_categories.name as est_category',
                    'test_results.visit_purpose',
                    'test_results.test_date as inspection_date',
                    'test_results.staff_contact',
                    'establishment_applications.id as est_id',
                    'establishment_applications.*',
                    'establishment_applications.id as permit_id',
                    'establishment_applications.establishment_name as est_name',
                    'establishment_applications.establishment_address as address',
                    'test_results.overall_score',
                    'test_results.comments',
                    'test_results.critical_score',
                    DB::raw('group_concat("  ", food_est_operators.name_of_operator) as operators')
                )
                ->groupBy('establishment_applications.id')
                ->get();
        } elseif ($app_type_id == 5) {
            $applications = DB::table('swimming_pools_applications')
                ->join('test_results', function ($join) use ($date_of_inspection) {
                    $join->on('test_results.application_id', '=', 'swimming_pools_applications.id')
                        ->where('test_results.application_type_id', '=', 5)
                        ->where('test_results.deleted_at', '=', null)
                        ->where('test_results.facility_id', '=', Auth()->user()->facility_id)
                        ->where('test_results.test_date', '=', $date_of_inspection);
                })
                ->select('test_results.test_date', 'test_results.test_location', 'test_results.comments', 'test_results.staff_contact', 'test_results.overall_score', 'test_results.critical_score', 'swimming_pools_applications.id as pool_id', 'swimming_pools_applications.*')
                ->get();
        } elseif ($app_type_id == 6) {
            $applications = DB::table('tourist_establishments')
                ->join('test_results', function ($join) use ($date_of_inspection) {
                    $join->on('test_results.application_id', '=', 'tourist_establishments.id')
                        ->where('test_results.application_type_id', '=', 6)
                        ->where('test_results.deleted_at', '=', null)
                        ->where('test_results.facility_id', '=', Auth()->user()->facility_id)
                        ->where('test_results.test_date', '=', $date_of_inspection);
                })
                ->join('tourist_establishment_services', 'tourist_establishment_services.tourist_establishment_id', '=', 'tourist_establishments.id')
                ->select(
                    'test_results.test_date',
                    'test_results.test_location',
                    'test_results.comments',
                    'test_results.staff_contact',
                    'test_results.overall_score',
                    'test_results.critical_score',
                    'test_results.test_date',
                    'tourist_establishments.id as tourist_est_id',
                    'tourist_establishments.*',
                    DB::raw('group_concat("  ", tourist_establishment_services.name) as services')
                )
                ->groupBy('tourist_establishments.id', 'test_results.id')
                ->get();
        }
        return view('signoffs.view', compact('applications', 'app_type_id'));
    }

    public function approve(Request $request)
    {
        $app_type_id = $request->data["appTypeId"];
        try {
            foreach ($request->data["selected_items"] as $item) {
                if ($app_type_id == "1") {
                    $application = PermitApplication::with('healthInterviews')->find($item);
                } elseif ($app_type_id == "2") {
                    $application = HealthCertApplications::find($item);
                } elseif ($app_type_id = "3") {
                    $application = EstablishmentApplications::find($item);
                } elseif ($app_type_id == "5") {
                    $application = SwimmingPoolsApplications::find($item);
                } else if ($app_type_id == "6") {
                    $application = TouristEstablishments::find($item);
                }
                $exam_date = TestResult::where('application_id', '=', $item)->where('application_type_id', '=', $app_type_id)->first();
                

                if ($app_type_id == "1" || $app_type_id == "2") {
                    $health_interview = HealthInterview::where("permit_application_id", $item)->first();
                    if ($health_interview) {
                        HealthInterview::find($health_interview->id)->update(['sign_off_status' => TRUE]);
                    } else {
                        throw new Exception("No health interview has been entered");
                    }
                }

                $application->update(['sign_off_status' => TRUE]);

                $sign_off_exists = SignOff::where('application_id', $item)->where('application_type_id', $app_type_id)->first();

                if (!$sign_off_exists) {
                    $sign_off = [];
                    $sign_off["is_granted"] = TRUE;
                    $sign_off["permit_no"] = $application->permit_no;
                    $sign_off["sign_off_date"] = date("Y/m/d");
                    $sign_off["user_id"] = Auth()->user()->id;
                    $sign_off["application_type_id"] = $app_type_id;
                    $sign_off["application_id"] = $item;

                    if ($application->permit_type == "student") {
                        $sign_off["expiry_date"] = date_format(date_modify(new DateTime($exam_date->test_date), "+{$application->no_of_years} years"), "Y-m-d");
                    } else {
                        $sign_off["expiry_date"] = date_format(date_modify(new DateTime($exam_date->test_date), "+1 years"), "Y-m-d");
                    }

                    SignOff::create($sign_off);
                }
            }
            return "success";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}