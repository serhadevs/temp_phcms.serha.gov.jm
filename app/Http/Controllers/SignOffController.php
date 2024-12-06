<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ApplicationType;
use App\Models\EditTransactions;
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
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use DateTime;
use Exception;

class SignOffController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('checkRole:1,5,7'); // Only allow users with role_id 1 to access this controller
    // }

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
            'app_type_id' => 'required',
            'exam_date' => 'required_if:app_type_id,1,2',
            'clinic_mode' => 'required_if:app_type_id,1',
            'exam_site' => 'required_if:clinic_mode,regular|required_if:app_type_id,2',
            'date_of_inspection' => 'required_if:app_type_id,3,5,6'
        ]);
        $app_type_id = $request->route('id');
        $exam_date = Carbon::parse($request->exam_date)->format('Y-m-d');
        $exam_site = $request->exam_site;
        $date_of_inspection = Carbon::parse($request->date_of_inspection)->format('Y-m-d');
        $clinic_mode = $request->clinic_mode;

        if ($app_type_id == 1) {
            if ($clinic_mode == "onsite") {
                $applications = HealthInterview::with('permitApplication.permitCategory', 'permitApplication.establishmentClinics', 'permitApplication.testResults', 'permitApplication.travelHistory', 'healthInterviewSymptom.symptoms', 'permitApplication.payment')
                    ->where('facility_id', auth()->user()->facility_id)
                    ->whereRelation('permitApplication.establishmentClinics', 'proposed_date', $exam_date)
                    ->has('permitApplication.testResults')
                    ->has('permitApplication.payment')
                    ->with(['permitApplication' => function ($query) {
                        $query->orderBy('lastname');
                    }])
                    ->get();
            } else if ($clinic_mode == "regular") {
                $applications = HealthInterview::with('permitApplication.permitCategory', 'permitApplication.establishmentClinics', 'permitApplication.testResults', 'permitApplication.travelHistory', 'healthInterviewSymptom.symptoms', 'permitApplication.appointment.examDate.examSites', 'permitApplication.payment')
                    ->where('facility_id', auth()->user()->facility_id)
                    ->whereRelation('permitApplication.appointment', 'appointment_date', $exam_date)
                    ->whereRelation('permitApplication.appointment.examDate.examSites', 'id', $exam_site)
                    ->doesntHave('permitApplication.establishmentClinics')
                    ->has('permitApplication.testResults')
                    ->has('permitApplication.payment')
                    ->with(['permitApplication' => function ($query) {
                        $query->orderBy('lastname');
                    }])
                    ->get();
            }
        } elseif ($app_type_id == 2) {
            $applications = HealthInterview::with('healthCertApplication.appointment.examDate.examSites', 'healthCertApplication.testResults', 'healthInterviewSymptom.symptoms', 'healthCertApplication.travelHistory', 'healthCertApplication.payment')
                ->whereRelation('healthCertApplication.appointment', 'appointment_date', $exam_date)
                ->whereRelation('healthCertApplication.appointment.examDate.examSites', 'id', $exam_site)
                ->has('healthCertApplication.testResults')
                ->has('healthCertApplication.payment')
                ->where('facility_id', auth()->user()->facility_id)
                ->orderBy('sign_off_status')
                ->get();
        } elseif ($app_type_id == 3) {
            $applications = EstablishmentApplications::with('operators', 'establishmentCategory', 'testResults', 'payment')
                ->has('testResults')
                ->has('payment')
                ->whereRelation('testResults', 'test_date', $date_of_inspection)
                ->whereRelation('testResults', 'facility_id', auth()->user()->facility_id)
                ->get();
        } elseif ($app_type_id == 5) {
            // $applications = DB::table('swimming_pools_applications')
            //     ->join('test_results', function ($join) use ($date_of_inspection) {
            //         $join->on('test_results.application_id', '=', 'swimming_pools_applications.id')
            //             ->where('test_results.application_type_id', '=', 5)
            //             ->where('test_results.deleted_at', '=', null)
            //             ->where('test_results.facility_id', '=', Auth()->user()->facility_id)
            //             ->where('test_results.test_date', '=', $date_of_inspection);
            //     })
            //     ->select('test_results.test_date', 'test_results.test_location', 'test_results.comments', 'test_results.staff_contact', 'test_results.overall_score', 'test_results.critical_score', 'swimming_pools_applications.id as pool_id', 'swimming_pools_applications.*')
            //     ->get();
            $applications = SwimmingPoolsApplications::with('testResults', 'payment')
                ->has('payment')
                ->whereRelation('testResults', 'test_date', $date_of_inspection)
                ->whereRelation('testResults', 'facility_id', auth()->user()->facility_id)
                ->get();
        } elseif ($app_type_id == 6) {
            $applications = TouristEstablishments::with('testResults', 'services', 'payments')
                ->has('payments')
                ->has('testResults')
                ->whereRelation('testResults', 'facility_id', auth()->user()->facility_id)
                ->whereRelation('testResults', 'test_date', $date_of_inspection)
                ->get();
        }
        return view('signoffs.view', compact('applications', 'app_type_id'));
    }

    public function approve(Request $request)
    {
        $app_type_id = $request->data["appTypeId"];
        DB::beginTransaction();
        try {
            foreach ($request->data["selected_items"] as $item) {
                if ($app_type_id == "1") {
                    $application = PermitApplication::with('healthInterviews')->find($item);
                } elseif ($app_type_id == "2") {
                    $application = HealthCertApplications::find($item);
                } elseif ($app_type_id == "3") {
                    $application = EstablishmentApplications::find($item);
                } elseif ($app_type_id == "5") {
                    $application = SwimmingPoolsApplications::find($item);
                } else if ($app_type_id == "6") {
                    $application = TouristEstablishments::find($item);
                }
                $exam_date = TestResult::where('application_id', '=', $item)->where('application_type_id', '=', $app_type_id)->first();
                if ($app_type_id == "1" || $app_type_id == "2") {
                    if ($app_type_id == 1) {
                        $health_interview = HealthInterview::where("permit_application_id", $item)->first();
                    } else {
                        $health_interview = HealthInterview::where("health_cert_application_id", $item)->first();
                    }
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
            DB::commit();
            return "success";
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }


    public function viewSignOffs()
    {

        try {

            $applications = SignOff::join('establishment_applications', 'sign_offs.application_id', '=', 'establishment_applications.id')
                ->where('sign_offs.application_type_id', '=', 3)
                ->where('sign_offs.created_at', '>', '2024-01-01')
                ->join('users', 'users.id', '=', 'sign_offs.user_id')
                ->orderBy('sign_offs.sign_off_date', 'desc')
                ->where('users.facility_id', auth()->user()->facility_id)
                ->get();

            // dd($applications);

            return view('signoffs.signsoff', compact('applications'));
        } catch (Exception $e) {
            return redirect()->with('error', 'Unknown error occured', $e->getMessage());
        } catch (QueryException $e) {
            return redirect()->with('error', 'Unable to fetch data from the database!', $e->getMessage());
        }
    }

    public function requestSignoffReversal(Request $request)
    {
        try {
            if ($application = $request->data['app_type'] == 1 ?
                PermitApplication::with('zippedApplication', 'signOffs')->find($request->data['application_id'])
                : EstablishmentApplications::with('zippedApplication', 'signOff')->find($request->data['application_id'])
            ) {
                if ($application->sign_off_status == 1) {
                    //Ensure two requests cannot be logged
                    if (empty($application->zippedApplication)) {
                        DB::beginTransaction();
                        if (EditTransactions::create([
                            'application_type_id' => $request->data['app_type'],
                            'table_id' => $request->data['app_type'] == 1 ? $application->signOffs->id : $application->signOff->id,
                            'system_operation_type_id' => 4,
                            'edit_type_id' => 2,
                            'user_id' => auth()->user()->id,
                            'facility_id' => auth()->user()->facility_id,
                            'reason' => $request->data['reason']
                        ])) {
                            DB::commit();
                            return [
                                "success",
                                "Request for reversal of sign off has been submitted successfully"
                            ];
                        }
                    } else {
                        throw new Exception("This permit has already been prepared for printing");
                    }
                } else {
                    throw new Exception("This permit application has not been signed off");
                }
            } else {
                throw new Exception("This permit application does not exist");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function viewReversalRequests()
    {
        $requests = EditTransactions::with('signOffs', 'user')
            ->where('system_operation_type_id', 4)
            ->where('facility_id', auth()->user()->facility_id)
            ->where('approved', NULL)
            ->get();

        return view('signoffs.reverse_signoffs', compact('requests'));
    }

    public function approveReversal($id)
    {
        try {
            if ($edit = EditTransactions::find($id)) {
                if ($sign_off = SignOff::find($edit->table_id)) {
                    if ($application = $sign_off->application_type_id == 1 ?
                        PermitApplication::find($sign_off->application_id) :
                        EstablishmentApplications::find($sign_off->application_id)
                    ) {
                        DB::beginTransaction();
                        if ($edit->update(['approved' => 1])) {
                            if ($application->update(['sign_off_status' => NULL])) {
                                if ($sign_off->update(['deleted_at' => new DateTime()])) {
                                    DB::commit();
                                    return [
                                        "success",
                                        "Reversal of Sign Off has been approved and completed"
                                    ];
                                } else {
                                    throw new Exception("Unable to approve. Error updating sign off record");
                                }
                            } else {
                                throw new Exception("Unable to approve. Error updating application");
                            }
                        } else {
                            throw new Exception("Unable to approve. Error updating transaction");
                        }
                    } else {
                        throw new Exception("This application does not exist");
                    }
                } else {
                    throw new Exception("This Sign off does not exist");
                }
            } else {
                throw new Exception("This request for this ID does not exist");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
