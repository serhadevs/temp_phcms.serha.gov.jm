<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use App\Models\EstablishmentClinics;
use App\Models\HealthCertApplications;
use App\Models\HealthInterview;
use App\Models\HealthInterviewSymptom;
use App\Models\PermitApplication;
use App\Models\Symptoms;
use App\Models\TestResult;
use App\Models\TravelHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;

class HealthInterviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id = $request->route('id');
        $today = date_format(new Datetime(), "Y-m-d");
        $tonight = new DateTime($today . " 23:59:59");
        $yesterday = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
        $last_week = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        $thirty_days = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        $last_ninety_days = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");

        $filterTimeline = "";

        if ($id == "0") {
            $filterTimeline = $today;
            $health_interviews = HealthInterview::with('healthInterviewSymptom.symptoms', 'permitApplication.appointment', 'healthCertApplication')
                ->whereBetween('created_at', [$filterTimeline, $tonight])
                ->where('facility_id', auth()->user()->facility_id)
                ->get();
            return view('test_center.health_interviews.index', compact('health_interviews'));
        } else if ($id == "1") {
            $filterTimeline = $yesterday;
        } else if ($id == "7") {
            $filterTimeline = $last_week;
        } else if ($id == "30") {
            $filterTimeline = $thirty_days;
        } else if ($id == "90") {
            $filterTimeline = $last_ninety_days;
        }

        $health_interviews = HealthInterview::with('healthInterviewSymptom.symptoms', 'permitApplication.appointment', 'healthCertApplication')
            ->whereBetween('created_at', [$filterTimeline, $today])
            ->where('facility_id', auth()->user()->facility_id)
            ->get();
        return view('test_center.health_interviews.index', compact('health_interviews'));
    }

    public function customFilterIndex(Request $request)
    {
        date_default_timezone_set('Etc/GMT+5');
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'interval' => 'nullable|numeric|max:6'
        ]);

        $end_date = new DateTime($timeline["ending_date"] . " 23:59:59");

        $health_interviews = HealthInterview::with('healthInterviewSymptom.symptoms', 'permitApplication.appointment', 'healthCertApplication')
            ->whereBetween('created_at', [$timeline["starting_date"], $end_date])
            ->where('facility_id', auth()->user()->facility_id)
            ->get();
        return view('test_center.health_interviews.index', compact('health_interviews'));
    }

    public function outstandingApplications(Request $request)
    {
        $id = $request->route('filter_id');
        $app_type_id = $request->route('app_type_id');
        $today = date_format(new Datetime(), "Y-m-d");
        $tonight = new DateTime($today . " 23:59:59");
        $yesterday = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
        $last_week = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        $thirty_days = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        $last_ninety_days = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");

        $filterTimeline = "";

        if ($id == "0") {
            $filterTimeline = $today;
            if ($request->route('app_type_id') == "1") {
                $applications = PermitApplication::with('permitCategory', 'appointment.examDate.examSites', 'establishmentClinics', 'payment', 'user', 'healthInterviews')
                    ->whereBetween('created_at', [$filterTimeline, $tonight])
                    ->has('payment')
                    ->doesntHave('healthInterviews')
                    ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                    ->get();
            } else if ($request->route('app_type_id') == '2') {
                $applications = HealthCertApplications::with('appointment.examDate.examSites', 'payment', 'user', 'healthInterviews')
                    ->whereBetween('created_at', [$filterTimeline, $tonight])
                    ->has('payment')
                    ->doesntHave('healthInterviews')
                    ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                    ->get();
            }

            return view('test_center.health_interviews.outstanding_applications', compact('applications', 'app_type_id'));
        } else if ($id == "1") {
            $filterTimeline = $yesterday;
        } else if ($id == "7") {
            $filterTimeline = $last_week;
        } else if ($id == "30") {
            $filterTimeline = $thirty_days;
        } else if ($id == "90") {
            $filterTimeline = $last_ninety_days;
        }

        if ($request->route('app_type_id') == "1") {
            $applications = PermitApplication::with('permitCategory', 'appointment.examDate.examSites', 'establishmentClinics', 'payment', 'user', 'healthInterviews')
                ->whereBetween('created_at', [$filterTimeline, $today])
                ->has('payment')
                ->doesntHave('healthInterviews')
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->get();
        } else if ($request->route('app_type_id') == '2') {
            $applications = HealthCertApplications::with('appointment.examDate.examSites', 'payment', 'user', 'healthInterviews')
                ->whereBetween('created_at', [$filterTimeline, $today])
                ->has('payment')
                ->doesntHave('healthInterviews')
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->get();
        }

        return view('test_center.health_interviews.outstanding_applications', compact('applications', 'app_type_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $app_id = $request->route('app_id');
        $app_type_id = $request->route('app_type_id');
        $symptoms = Symptoms::all();
        $test_info = [];

        if ($app_type_id == 1) {
            $application = PermitApplication::find($app_id);
            $test_result = TestResult::select("*")
                ->where('application_id', $app_id)
                ->where('application_type_id', $app_type_id)
                ->orderBy('created_at', 'desc')
                ->first();
            if (empty($test_result) && $application->establishment_clinic_id == "") {
                $test_info = Appointments::with('examDate.examSites')->where('permit_application_id', $application->id)->orderBy('created_at', 'desc')->first();
            } else if (empty($test_result) && $application->establishment_clinic_id != "") {
                $test_info = EstablishmentClinics::select("*")->where('id', $application->establishment_clinic_id)->orderBy('created_at', 'desc')->first();
            }
        } else {
            $application = HealthCertApplications::find($app_id);
        }

        // dd($test_info);
        return view('test_center.health_interviews.create', compact('application', 'symptoms', 'app_type_id', 'test_info'));
    }

    public function customFilterOutstanding(Request $request)
    {
        date_default_timezone_set('Etc/GMT+5');
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'interval' => 'nullable|numeric|max:6',
            'app_type_id' => 'required'
        ]);

        $app_type_id = $timeline["app_type_id"];
        $end_date = new DateTime($timeline["ending_date"] . " 23:59:59");

        if ($timeline["app_type_id"] == "1") {
            $applications = PermitApplication::with('permitCategory', 'appointment.examDate.examSites', 'establishmentClinics', 'payment', 'user', 'healthInterviews')
                ->whereBetween('created_at', [$timeline["starting_date"], $end_date])
                ->has('payment')
                ->doesntHave('healthInterviews')
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->get();
        } else if ($timeline["app_type_id"] == '2') {
            $applications = HealthCertApplications::with('appointment.examDate.examSites', 'payment', 'user', 'healthInterviews')
                ->whereBetween('created_at', [$timeline["starting_date"], $end_date])
                ->has('payment')
                ->doesntHave('healthInterviews')
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->get();
        }

        return view('test_center.health_interviews.outstanding_applications', compact('applications', 'app_type_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $health_interview = $request->validate([
            'test_results_exist' => 'required',
            'test_location' => 'required_if:test_results_exist,0',
            'test_date' => 'required_if:test_results_exist,0',
            'overall_score' => 'required_if:test_results_exist,0',
            'staff_contact' => 'required_if:test_results_exist,0',
            'destination.0' => 'required_if:travel_abroad,1',
            'travel_date.0' => 'required_if:travel_abroad,1',
            'comments' => 'nullable',
            'literate' => 'required',
            'typhoid' => 'required',
            'lived_abroad' => 'required',
            'lived_abroad_location' => 'required_if:lived_abroad, 1',
            'lived_abroad_date' => 'required_if:lived_abroad, 1',
            'travel_abroad' => 'required',
            'destination' => 'array|required_if:travel_abroad,1',
            'travel_date' => 'required_if:travel_abroad,1',
            'whitlow' => 'required',
            'hands_condition' => 'required',
            'fingernails_condition' => 'required',
            'teeth_condition' => 'required',
            'tests_recommended' => 'nullable',
            'tests_results' => 'nullable',
            'doctor_name' => 'nullable',
            'doctor_address' => 'nullable',
            'doctor_tele' => 'nullable'
        ]);

        if ($request->app_type_id == "1") {
            $health_interview["permit_application_id"] = $request->application_id;
        } else if ($request->app_type_id == "2") {
            $health_interview["health_cert_application_id"] = $request->application_id;
        }

        $health_interview["user_id"] = Auth()->user()->id;
        $health_interview["facility_id"] = Auth()->user()->facility_id;

        if (HealthInterview::create($health_interview)) {
            if ($request->symptoms) {
                if (count($request->symptoms) > 0) {
                    if ($request->app_type_id == "1") {
                        $health_interview_id = DB::table('health_interviews')
                            ->select('id')
                            ->where('permit_application_id', $request->application_id)
                            ->get();
                    } else {
                        $health_interview_id = DB::table('health_interviews')->select('id')->where('health_cert_application_id', $request->application_id)->get();
                    }
                    for ($i = 0; $i < count($request->symptoms); $i++) {
                        HealthInterviewSymptom::create(['symptom_id' => $request->symptoms[$i], 'health_interview_id' => $health_interview_id[0]->id]);
                    }
                }
            }

            if ($health_interview["travel_abroad"] == "1") {
                for ($a = 0; $a < 2; $a++) {
                    if ($request->destination[$a] || $request->travel_date[$a]) {
                        if ($request->app_type_id == "1") {
                            TravelHistory::create([
                                'permit_application_id' => $request->application_id,
                                'destination' => $request->destination[$a] ? $request->destination[$a] : NULL,
                                'travel_date' => $request->travel_date[$a] ? $request->travel_date[$a] : NULL
                            ]);
                        }
                    }
                }
            }

            //Requires testing
            if ($health_interview["test_results_exist"] == "0") {
                if (!TestResult::create(
                    [
                        'application_id' => $request->application_id,
                        'application_type_id' => '1',
                        'test_location' => $health_interview["test_location"],
                        'staff_contact' => $health_interview["staff_contact"],
                        'test_date' => $health_interview["test_date"],
                        'comments' => $health_interview["comments"],
                        'overall_score' => $health_interview["overall_score"],
                        'user_id' => Auth()->user()->id,
                        'facility_id' => auth()->user()->facility_id
                    ]
                )) {
                    return redirect()->route('health-interview.index', ['id' => 0])->with('error', 'Error processing health interview');
                }
            }
            return redirect()->route('health-interview.index', ['id' => 0])->with('success', 'Health Interview has been processed successfully.');
        } else {
            return redirect()->route('health-interview.index', ['id' => 0])->with('error', 'Error processing health interview');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
