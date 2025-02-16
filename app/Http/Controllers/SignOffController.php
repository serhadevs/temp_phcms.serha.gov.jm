<?php

namespace App\Http\Controllers;

use App\Models\ApplicationType;
use App\Models\Downloads;
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
use App\Models\ZippedApplications;
use DateTime;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

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
                    ->whereRelation('permitApplication', 'photo_upload', '<>', NULL)
                    ->whereRelation('permitApplication', 'photo_upload', '<>', '0')
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
                    ->whereRelation('permitApplication', 'photo_upload', '<>', NULL)
                    ->whereRelation('permitApplication', 'photo_upload', '<>', '0')
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
            $applications = SwimmingPoolsApplications::with('testResults', 'payment')
                ->has('payment')
                ->has('testResults')
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
        // $app_type_id = 
        // $request->data["appTypeId"];
        $counter = 0;
        try {
            DB::beginTransaction();
            foreach ($request->data["selected_items"] as $item) {
                if ($request->data["appTypeId"] == "1") {
                    $application = PermitApplication::with('healthInterviews')
                        ->has('healthInterviews')
                        ->find($item);
                } elseif ($request->data["appTypeId"] == "2") {
                    $application = HealthCertApplications::with('healthInterviews')
                        ->has('healthInterviews')
                        ->find($item);
                } elseif ($request->data["appTypeId"] == "3") {
                    $application = EstablishmentApplications::find($item);
                } elseif ($request->data["appTypeId"] == "5") {
                    $application = SwimmingPoolsApplications::find($item);
                } else if ($request->data["appTypeId"] == "6") {
                    $application = TouristEstablishments::find($item);
                }

                if ($application) {
                    $exam_date = TestResult::where('application_id', $item)
                        ->where('application_type_id', $request->data["appTypeId"])
                        ->first();

                    if ($request->data["appTypeId"] == "1" || $request->data["appTypeId"] == "2") {
                        $application->healthInterviews->update(['sign_off_status' => TRUE]);
                    }

                    // if ($app_type_id == "1" || $app_type_id == "2") {
                    //     if ($app_type_id == 1) {
                    //         $health_interview = HealthInterview::where("permit_application_id", $item)->first();
                    //     } else {
                    //         $health_interview = HealthInterview::where("health_cert_application_id", $item)->first();
                    //     }
                    //     if ($health_interview) {
                    //         HealthInterview::find($health_interview->id)->update(['sign_off_status' => TRUE]);
                    //     } else {
                    //         throw new Exception("No health interview has been entered for Application No:" . $item);
                    //     }
                    // }

                    $application->update(['sign_off_status' => TRUE]);

                    // $sign_off_exists = SignOff::where('application_id', $item)->where('application_type_id', $app_type_id)->first();

                    if (!SignOff::where('application_id', $item)->where('application_type_id', $request->data["appTypeId"])->first()) {
                        $expiry_date = "";
                        // $sign_off = [];
                        // $sign_off["is_granted"] = TRUE;
                        // $sign_off["permit_no"] = $application->permit_no;
                        // $sign_off["sign_off_date"] = date("Y/m/d");
                        // $sign_off["user_id"] = Auth()->user()->id;
                        // $sign_off["application_type_id"] = $app_type_id;
                        // $sign_off["application_id"] = $item;

                        if ($request->data["appTypeId"] == 1) {
                            if ($application->permit_type == "student") {
                                $expiry_date = date_format(date_modify(date_create($exam_date->test_date), "+{$application->no_of_years} years"), "Y-m-d");
                            } else {
                                $expiry_date = date_format(date_modify(date_create($exam_date->test_date), "+1 years"), "Y-m-d");
                            }
                        } else {
                            $expiry_date = date_format(date_modify(date_create($exam_date->test_date), "+1 years"), "Y-m-d");
                        }

                        SignOff::create([
                            'is_granted' => TRUE,
                            'permit_no' => $application->permit_no,
                            'sign_off_date' => date("Y/m/d"),
                            'user_id' => Auth()->user()->id,
                            'application_type_id' => $request->data["appTypeId"],
                            'application_id' => $item,
                            'expiry_date' => $expiry_date
                        ]);
                    }
                    $counter++;
                }
            }
            DB::commit();
            // if ($request->data["appTypeId"] == 1) {
            //     if ($this->permitJob($request->data["selected_items"])) {
            //         return [
            //             "success",
            //             $counter . " Applications of " . count($request->data['selected_items']) . " were successfully signed off."
            //         ];
            //     } else {
            //         throw new Exception("Error zipping files");
            //     }
            // } else {
            return [
                "success",
                $counter . " Applications of " . count($request->data['selected_items']) . " were successfully signed off."
            ];
            // }
        } catch (Exception $e) {
            DB::rollBack();
            //Return error message to view
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

    public function permitJob($permitIds)
    {
        $permit_applications = PermitApplication::with('permitCategory', 'payment', 'appointment.examDate.examSites', 'user', 'establishmentClinics', 'testResults', 'signOffs', 'zippedApplication')
            ->where('photo_upload', '<>', NULL)
            ->where('photo_upload', '<>', '0')
            ->has('signOffs')
            ->doesntHave('zippedApplication')
            ->has('payment')
            ->whereIn('id', $permitIds)
            ->has('testResults')
            ->get();

        $grouped_by_facility = $permit_applications->groupBy('user.facility_id');

        $rand_string = explode('.', time() / rand(10000, 99999))[0];

        foreach ($grouped_by_facility as $key => $facility_permit) {
            if ($key == 1) {
                try {
                    $sch_per_date = $facility_permit->groupBy(function ($facility_permit) {
                        if ($facility_permit->establishment_clinic_id == NULL) {
                            return $facility_permit->appointment[0]?->appointment_date;
                        } else {
                            return $facility_permit->establishmentClinics?->proposed_date;
                        }
                    });

                    foreach ($sch_per_date as $key => $sch_permit) {
                        $content = "";
                        $counter = 0;

                        $zip = new ZipArchive();
                        $download_url = "downloads/archives/" . "STC-" . $key . "_" . $rand_string . '.zip';

                        $create_download = Downloads::create([
                            'application_type_id' => 1,
                            'application_amount' => 0,
                            'category' => 'Food Handlers Permit',
                            'download_url' => $download_url
                        ]);

                        if ($zip->open(storage_path('app/public/' . $download_url), ZipArchive::CREATE)) {
                            DB::beginTransaction();
                            foreach ($sch_permit as $index) {
                                $ext = pathinfo(storage_path() . $index->photo_upload, PATHINFO_EXTENSION);
                                $photo_exists = Storage::disk('public')->exists("photo_uploads/" . $index->permit_no . "." . $ext);
                                if ($photo_exists) {
                                    $file = glob(storage_path('app/public/' . $index->photo_upload));
                                    $zip->addFile($file[0], basename($file[0]));
                                    $content = $content . strtoupper(substr($index->permit_no, 0, -2)) . "\t"
                                        . strtoupper($index->lastname . "\t" . strtoupper($index->firstname)) . "\t"
                                        . "S1" . "\t"
                                        . "SCHD" . "\t"
                                        . strtoupper($index->permitCategory?->name) . "\t"
                                        . Carbon::parse($key)->format('m/d/Y') . "\t"
                                        . Carbon::parse($index->signOffs?->expiry_date)->format('m/d/Y')
                                        . "\t" . strtoupper($index->permit_no) . '.' . $ext . "\t"
                                        . "DR. " . strtoupper($index->signOffs?->user?->firstname) . " "
                                        . strtoupper($index->signOffs?->user?->lastname) . ".wmf"
                                        . ($index->permitCategory?->name == "Tourist Establishments Foodhandlers" ? "\t" . $index->permit_type . "TRUE\t" : "\t" . strtoupper($index->permit_type) . "\t") . "STC-" . explode('-', $index->signOffs?->sign_off_date)[0] . "-" . "\r\n";

                                    if (str_contains($content, $index->permit_no)) {
                                        ZippedApplications::create([
                                            'application_type_id' => '1',
                                            'application_id' => $index->id,
                                            'download_id' => $create_download->id
                                        ]);
                                        $counter++;
                                    }
                                }
                            }
                            $create_download->update(['application_amount' => $counter]);
                            DB::commit();

                            if ($content != "") {
                                $zip->addFromString("STC" . "-" . $key . "-Food_Handler_Permits.txt", $content);
                            }
                        }
                        $zip->close();

                        if (empty($content)) {
                            foreach (ZippedApplications::where('download_id', $create_download->id) as $zippedApp) {
                                $zippedApp->update(['deleted_at' => \Carbon\Carbon::now()->toDateTimeString()]);
                            }
                            $create_download->update(["deleted_at" => \Carbon\Carbon::now()->toDateTimeString()]);
                        }
                    }
                } catch (Exception $e) {
                    return $e->getMessage();
                }
            } else if ($key == 2) {
                try {
                    $stt_per_date = $facility_permit->groupBy(function ($facility_permit) {
                        if ($facility_permit->establishment_clinic_id == NULL) {
                            return $facility_permit->appointment[0]?->appointment_date;
                        } else {
                            return $facility_permit->establishmentClinics?->proposed_date;
                        }
                    });

                    foreach ($stt_per_date as $key => $stt_permit) {
                        $content = "";
                        $counter = 0;

                        $zip = new ZipArchive();
                        $download_url = "downloads/archives/" . "STT-" . $key . "_" . $rand_string . ".zip";

                        $create_download = Downloads::create([
                            'application_type_id' => 1,
                            'application_amount' => 0,
                            'category' => 'Food Handlers Permit',
                            'download_url' => $download_url
                        ]);

                        if ($zip->open(storage_path('app/public/' . $download_url), ZipArchive::CREATE)) {
                            DB::beginTransaction();
                            foreach ($stt_permit as $index) {
                                $ext = pathinfo(storage_path() . $index->photo_upload, PATHINFO_EXTENSION);
                                $photo_exists = Storage::disk('public')->exists("photo_uploads/" . $index->permit_no . "." . $ext);
                                if ($photo_exists) {
                                    $file = glob(storage_path('app/public/' . $index->photo_upload));
                                    $zip->addFile($file[0], basename($file[0]));
                                    $content = $content . strtoupper(substr($index->permit_no, 0, -2)) . "\t"
                                        . strtoupper($index->lastname . "\t" . strtoupper($index->firstname)) . "\t"
                                        . "S1" . "\t"
                                        . "STHD" . "\t"
                                        . strtoupper($index->permitCategory?->name) . "\t"
                                        . Carbon::parse($key)->format('m/d/Y') . "\t"
                                        . Carbon::parse($index->signOffs?->expiry_date)->format('m/d/Y')
                                        . "\t" . strtoupper($index->permit_no) . '.' . $ext . "\t"
                                        . "DR. " . strtoupper($index->signOffs?->user?->firstname) . " "
                                        . strtoupper($index->signOffs?->user?->lastname) . ".wmf"
                                        . ($index->permitCategory?->name == "Tourist Establishments Foodhandlers" ? "\t" . $index->permit_type . "TRUE\t" : "\t" . strtoupper($index->permit_type) . "\t") . "STT-" . explode('-', $index->signOffs?->sign_off_date)[0] . "-" . "\r\n";

                                    if (str_contains($content, $index->permit_no)) {
                                        ZippedApplications::create([
                                            'application_type_id' => '1',
                                            'application_id' => $index->id,
                                            'download_id' => $create_download->id
                                        ]);
                                        $counter++;
                                    }
                                }
                            }
                            $create_download->update(['application_amount' => $counter]);
                            DB::commit();
                            if ($content != "") {
                                $zip->addFromString("STT" . "-" . $key . "-Food_Handler_Permits.txt", $content);
                            }
                        }
                        $zip->close();

                        if (empty($content)) {
                            foreach (ZippedApplications::where('download_id', $create_download->id) as $zippedApp) {
                                $zippedApp->update(['deleted_at' => \Carbon\Carbon::now()->toDateTimeString()]);
                            }
                            $create_download->update(["deleted_at" => \Carbon\Carbon::now()->toDateTimeString()]);
                        }
                    }
                } catch (Exception $e) {
                    return $e->getMessage();
                }
            } else if ($key == 3) {
                try {
                    $ksa_per_date = $facility_permit->groupBy(function ($facility_permit) {
                        if ($facility_permit->establishment_clinic_id == NULL) {
                            return $facility_permit->appointment[0]?->appointment_date;
                        } else {
                            return $facility_permit->establishmentClinics?->proposed_date;
                        }
                    });

                    foreach ($ksa_per_date as $key => $ksa_permit) {
                        $content = "";
                        $counter = 0;

                        $download_url = "downloads/archives/" . "KSA-" . $key . "_" . $rand_string . '.zip';

                        $create_download = Downloads::create([
                            'application_type_id' => 1,
                            'application_amount' => 0,
                            'category' => 'Food Handlers Permit',
                            'download_url' => $download_url
                        ]);

                        $zip = new ZipArchive();

                        if ($zip->open(storage_path('app/public/' . $download_url), ZipArchive::CREATE)) {
                            DB::beginTransaction();
                            foreach ($ksa_permit as $index) {
                                $ext = pathinfo(storage_path() . $index->photo_upload, PATHINFO_EXTENSION);
                                $photo_exists = Storage::disk('public')->exists("photo_uploads/" . $index->permit_no . "." . $ext);
                                if ($photo_exists) {
                                    $file = glob(storage_path('app/public/' . $index->photo_upload));
                                    $zip->addFile($file[0], basename($file[0]));
                                    $content = $content . strtoupper(substr($index->permit_no, 0, -2)) . "\t"
                                        . strtoupper($index->lastname . "\t" . strtoupper($index->firstname)) . "\t"
                                        . "S1" . "\t"
                                        . "KSAHD" . "\t"
                                        . strtoupper($index->permitCategory?->name) . "\t"
                                        . Carbon::parse($key)->format('m/d/Y') . "\t"
                                        . Carbon::parse($index->signOffs?->expiry_date)->format('m/d/Y')
                                        . "\t" . strtoupper($index->permit_no) . '.' . $ext . "\t"
                                        . "DR. " . strtoupper($index->signOffs?->user?->firstname) . " "
                                        . strtoupper($index->signOffs?->user?->lastname) . ".wmf"
                                        . ($index->permitCategory?->name == "Tourist Establishments Foodhandlers" ? "\t" . $index->permit_type . "TRUE\t" : "\t" . strtoupper($index->permit_type) . "\t") . "KSA-" . explode('-', $index->signOffs?->sign_off_date)[0] . "-" . "\r\n";

                                    if (str_contains($content, $index->permit_no)) {
                                        ZippedApplications::create([
                                            'application_type_id' => '1',
                                            'application_id' => $index->id,
                                            'download_id' => $create_download->id
                                        ]);
                                        $counter++;
                                    }
                                }
                            }
                            $create_download->update(['application_amount' => $counter]);
                            DB::commit();

                            if ($content != "") {
                                $zip->addFromString("KSA" . "-" . $key . "-Food_Handler_Permits.txt", $content);
                            }
                        }
                        $zip->close();

                        if (empty($content)) {
                            foreach (ZippedApplications::where('download_id', $create_download->id) as $zippedApp) {
                                $zippedApp->update(['deleted_at' => \Carbon\Carbon::now()->toDateTimeString()]);
                            }
                            $create_download->update(["deleted_at" => \Carbon\Carbon::now()->toDateTimeString()]);
                        }
                    }
                } catch (Exception $e) {
                    return $e->getMessage();
                }
            }
        }
        return true;
    }

    public function printClinicPermits($clinic_id)
    {
        try {
            $counter = 0;
            $rand_string = explode('.', time() / rand(10000, 99999))[0];
            $permits = PermitApplication::with('signOffs.user', 'user', 'establishmentClinics')
                ->has('signOffs')
                ->where('establishment_clinic_id', $clinic_id)
                ->get();

            $content = "";
            $key = $permits->first()?->establishmentClinics?->proposed_date;
            $zip = new ZipArchive();
            $download_url = "downloads/archives/" . "KSA-" . $key . "_" . $rand_string . '.zip';

            $create_download = Downloads::create([
                'application_type_id' => 1,
                'application_amount' => 0,
                'category' => 'Food Handlers Permit',
                'download_url' => $download_url
            ]);

            if ($zip->open(storage_path('app/public/downloads/archives/' . "KSA-" . $key . "_" . $rand_string . '.zip'), ZipArchive::CREATE)) {
                foreach ($permits as $index) {
                    $ext = explode(".", $index->photo_upload)[1];
                    $file = glob(storage_path('app/public/' . $index->photo_upload));
                    $zip->addFile($file[0], basename($file[0]));

                    $content = $content . strtoupper(substr($index->permit_no, 0, -2)) . "\t"
                        . strtoupper($index->lastname . "\t" . strtoupper($index->firstname)) . "\t"
                        . "S1" . "\t"
                        . "SCHD" . "\t"
                        . strtoupper($index->permitCategory?->name) . "\t"
                        . Carbon::parse($key)->format('m/d/Y') . "\t"
                        . Carbon::parse($index->signOffs?->expiry_date)->format('m/d/Y')
                        . "\t" . strtoupper($index->permit_no) . '.' . $ext . "\t"
                        . "DR. " . strtoupper($index->signOffs?->user?->firstname) . " "
                        . strtoupper($index->signOffs?->user?->lastname) . ".wmf"
                        . ($index->permitCategory?->name == "Tourist Establishments Foodhandlers" ? "\t" . $index->permit_type . "TRUE\t" : "\t" . strtoupper($index->permit_type) . "\t") . "KSA-" . explode('-', $index->signOffs?->sign_off_date)[0] . "-" . "\r\n";

                    ZippedApplications::create([
                        'application_type_id' => '1',
                        'application_id' => $index->id,
                        'download_id' => $create_download->id
                    ]);
                    $counter++;
                }
                $zip->addFromString("KSA" . "-" . $key . "-Food_Handler_Permits.txt", $content);
            }
            $zip->close();
            $create_download->update(['application_amount' => $counter]);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
