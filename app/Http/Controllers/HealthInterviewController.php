<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use App\Models\EditTransactions;
use App\Models\EditTransactionsChangedColumns;
use App\Models\EstablishmentClinics;
use App\Models\HealthCertApplications;
use App\Models\HealthInterview;
use App\Models\HealthInterviewSymptom;
use App\Models\PermitApplication;
use App\Models\Symptoms;
use App\Models\TestResult;
use App\Models\TravelHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use Exception;

class HealthInterviewController extends Controller
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

        $now = new DateTime();

        $today = date_format(new Datetime(), "Y-m-d");
        $filterTimeline = "";

        if ($id == "0") {
            $filterTimeline = $today;
        } else if ($id == "1") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
            $health_interviews = HealthInterview::with('healthInterviewSymptom.symptoms', 'permitApplication.appointment.examDate.examSites', 'healthCertApplication.appointment.examDate.examSites', 'permitApplication.establishmentClinics', 'user')
                ->whereBetween('created_at', [$filterTimeline, $today])
                ->where('facility_id', auth()->user()->facility_id)
                ->get();
                //dd($health_interviews);
            return view('test_center.health_interviews.index', compact('health_interviews'));
        } else if ($id == "7") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        } else if ($id == "30") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        } else if ($id == "90") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        } else if ($id == "180") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-180 days"), "Y-m-d");
        }

        $health_interviews = HealthInterview::with('healthInterviewSymptom.symptoms', 'permitApplication.appointment.examDate.examSites', 'healthCertApplication.appointment.examDate.examSites', 'permitApplication.establishmentClinics', 'user')
            ->where('created_at', '>', $filterTimeline)
            ->where('facility_id', auth()->user()->facility_id)
            ->get();

            //dd($health_interviews);
            
        if(!$health_interviews){
            return view('dashboard.dashboard')->with('error', 'Error with interviews');
        }
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

        $health_interviews = HealthInterview::with('healthInterviewSymptom.symptoms', 'permitApplication.appointment.examDate.examSites', 'healthCertApplication.appointment.examDate.examSites', 'permitApplication.establishmentClinics', 'user')
            ->whereBetween('created_at', [$timeline["starting_date"], $end_date])
            ->where('facility_id', auth()->user()->facility_id)
            ->get();
        return view('test_center.health_interviews.index', compact('health_interviews'));
    }

    public function outstandingApplications($app_type_id, $filter_id)
    {
        // $id = $request->route('filter_id');
        // $app_type_id = $request->route('app_type_id');
        if (auth()->user()->default_filter_id != "") {
            $id = auth()->user()->default_filter_id;
        } else {
            $id = $filter_id;
        }
        $today = date_format(new Datetime(), "Y-m-d");
        $filterTimeline = "";

        if ($id == "0") {
            $filterTimeline = $today;
        } else if ($id == "1") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
            if ($app_type_id == "1") {
                $applications = PermitApplication::with('permitCategory', 'appointment.examDate.examSites', 'establishmentClinics', 'payment', 'user', 'healthInterviews')
                    ->whereBetween('created_at', [$filterTimeline, $today])
                    ->has('payment')
                    ->doesntHave('healthInterviews')
                    ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                    ->get();
            } else if ($app_type_id == '2') {
                $applications = HealthCertApplications::with('appointment.examDate.examSites', 'payment', 'user', 'healthInterviews')
                    ->whereBetween('created_at', [$filterTimeline, $today])
                    ->has('payment')
                    ->doesntHave('healthInterviews')
                    ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                    ->get();
            }

            return view('test_center.health_interviews.outstanding_applications', compact('applications', 'app_type_id'));
        } else if ($id == "7") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        } else if ($id == "30") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        } else if ($id == "90") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        } else if ($id == "180") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-180 days"), "Y-m-d");
        }

        if ($app_type_id == "1") {
            $applications = PermitApplication::with('permitCategory', 'appointment.examDate.examSites', 'establishmentClinics', 'payment', 'user', 'healthInterviews')
                ->where('created_at', '>', $filterTimeline)
                ->has('payment')
                ->doesntHave('healthInterviews')
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->get();
        } else if ($app_type_id == '2') {
            $applications = HealthCertApplications::with('appointment.examDate.examSites', 'payment', 'user', 'healthInterviews')
                ->where('created_at', '>', $filterTimeline)
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
            'overall_score' => 'required_if:test_results_exist,0|numeric|min:0|max:100',
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
        try {
            if ($interview = HealthInterview::find($id)) {
                if ($interview->permit_application_id != "") {
                    if (!$application = PermitApplication::with('healthInterviews.healthInterviewSymptom.symptoms', 'healthInterviews.editTransactions', 'healthInterviews.symptomsWithTrashed.editTransactions', 'travelHistory.editTransactions')->find($interview->permit_application_id)) {
                        throw new Exception("Permit Application associated with this health interview does not exist.");
                    }
                } else if ($interview->health_cert_application_id != "") {
                    if (!$application = HealthCertApplications::with('healthInterviews.healthInterviewSymptom.symptoms', 'healthInterviews.editTransactions', 'healthInterviews.symptomsWithTrashed.editTransactions', 'travelHistory.editTransactions')->find($interview->health_cert_application_id)) {
                        throw new Exception("Health Certification Application this is associated with this health interview does not exist.");
                    }
                }
                $app_type_id = 0;
                $system_operation_type_id = 2;
                return view('test_center.health_interviews.view', compact('application', 'app_type_id', 'system_operation_type_id'));
            } else {
                throw new Exception("This health interview does not exist");
            }
        } catch (Exception $e) {
            return redirect()->route('health-interview.index', ['id' => 0])->with('error', $e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            if ($interview = HealthInterview::find($id)) {
                if ($interview->sign_off_status !== 1) {
                    if ($application = $interview->permit_application_id == "" ? HealthCertApplications::with('healthInterviews.healthInterviewSymptom.symptoms', 'healthInterviews.editTransactions', 'healthInterviews.healthInterviewSymptom.editTransactions', 'travelHistory')->find($interview->health_cert_application_id) : PermitApplication::with('healthInterviews.healthInterviewSymptom.symptoms', 'healthInterviews.editTransactions', 'healthInterviews.healthInterviewSymptom.editTransactions', 'travelHistory.editTransactions')->find($interview->permit_application_id)) {
                        $app_type_id = 0;
                        $system_operation_type_id = 2;
                        $edit_mode = 1;
                        return view('test_center.health_interviews.view', compact('application', 'app_type_id', 'system_operation_type_id', 'edit_mode'));
                    } else {
                        throw new Exception('The application linked to the health interview does not exist. It cannot be edited.');
                    }
                } else {
                    throw new Exception("The application linked to this health interview has already been signed off. This health interview cannot be edited.");
                }
            } else {
                throw new Exception("This health interview does not exist.");
            }
        } catch (Exception $e) {
            return redirect()->route('health-interview.index', ['id' => 0])->with('error', $e->getMessage());
        }
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
        $updated_interview = $request->validate([
            'literate' => 'required|numeric',
            'typhoid' => 'required',
            'lived_abroad' => 'required',
            'lived_abroad_location' => 'required_if:lived_abroad, 1',
            'lived_abroad_date' => 'required_if:lived_abroad, 1',
            'travel_abroad' => 'required',
            'whitlow' => 'required',
            'hands_condition' => 'required',
            'fingernails_condition' => 'required',
            'teeth_condition' => 'required',
            'tests_recommended' => 'nullable',
            'tests_results' => 'nullable',
            'doctor_name' => 'nullable',
            'doctor_address' => 'nullable',
            'doctor_tele' => 'nullable',
            'edit_reason' => 'required'
        ]);
        try {
            if ($old_interview = HealthInterview::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
                ->find($id)
            ) {
                if ($old_interview->sign_off_status != '1') {
                    if ($application = $old_interview->permit_application_id == '' ? HealthCertApplications::find($old_interview->health_cert_application_id) : PermitApplication::find($old_interview->permit_application_id)) {
                        $reason = $updated_interview['edit_reason'];
                        unset($updated_interview['edit_reason']);
                        if (!empty($differences = array_diff_assoc(HealthInterview::select('typhoid', 'literate', 'lived_abroad', 'lived_abroad_location', 'whitlow', 'hands_condition', 'fingernails_condition', 'teeth_condition', 'lived_abroad_date', 'travel_abroad', 'tests_recommended', 'tests_results', 'doctor_name', 'doctor_address', 'doctor_tele')->find($id)->toArray(), $updated_interview))) {
                            DB::beginTransaction();
                            if ($edit_transaction = EditTransactions::create([
                                'application_type_id' => $old_interview->permit_application_id == '' ? 2 : 1,
                                'table_id' => $id,
                                'system_operation_type_id' => 2,
                                'edit_type_id' => 1,
                                'user_id' => auth()->user()->id,
                                'facility_id' => auth()->user()->facility_id,
                                'reason' => $reason
                            ])) {
                                foreach ($differences as $key => $edit) {
                                    EditTransactionsChangedColumns::create([
                                        'edit_transaction_id' => $edit_transaction->id,
                                        'column_name' => $key,
                                        'old_value' => $key == 'literate' ? ($old_interview->toArray()[$key] == '1' ? 'YES' : 'NO') : ($key == 'typhoid' ? ($old_interview->toArray()[$key] == '1' ? 'YES' : 'NO') : ($key == 'lived_abroad' ? ($old_interview->toArray()[$key] == '1' ? 'YES' : 'NO') : ($key == 'travel_abroad' ? ($old_interview->toArray()[$key] == '1' ? 'YES' : 'NO') : $old_interview->toArray()[$key]))),
                                        'new_value' => $key == 'literate' ? ($updated_interview[$key] == '1' ? 'YES' : 'NO') : ($key == 'typhoid' ? ($updated_interview[$key] == '1' ? 'YES' : 'NO') : ($key == 'lived_abroad' ? ($updated_interview[$key] == '1' ? 'YES' : 'NO') : ($key == 'travel_abroad' ? ($updated_interview[$key] == '1' ? 'YES' : 'NO') : $updated_interview[$key])))
                                    ]);
                                }
                                if ($old_interview->update($updated_interview)) {
                                    DB::commit();
                                    return redirect()->route('health-interview.view', ['id' => $old_interview->id])->with('success', 'Health Interview for ' . $application->firstname . ' ' . $application->lastname . ' has been updated successfully.');
                                }
                            } else {
                                throw new Exception('Unable to update health interview. Could not created transaction.');
                            }
                        } else {
                            throw new Exception("Nothing was changed in this health interview.");
                        }
                    } else {
                        throw new Exception("Application linked to this interview does not exist");
                    }
                } else {
                    throw new Exception("This application has already been signed off. It cannot be updated.");
                }
            } else {
                throw new Exception('This Health Interview does not exist or does not belong to your facility.');
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('health-interview.view', ['id' => $old_interview->id])->with('error', $e->getMessage());
        }
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
            if ($interview = HealthInterview::with('permitApplication', 'healthCertApplication')
                ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
                ->find($id)
            ) {
                if ($interview->sign_off_status != '1') {
                    DB::beginTransaction();
                    if (EditTransactions::create([
                        'application_type_id' => $interview->permit_application_id == '' ? 2 : 1,
                        'table_id' => $id,
                        'system_operation_type_id' => 2,
                        'edit_type_id' => 2,
                        'user_id' => auth()->user()->id,
                        'facility_id' => auth()->user()->facility_id,
                        'reason' => $request->data['reason']
                    ])) {
                        if ($interview->update(['deleted_at' => new DateTime()])) {
                            //Delete all symptoms and travel history
                            DB::commit();
                            return [
                                'success',
                                $interview->permit_application_id != '' ? ('Health Interview for ' . $interview->permitApplication?->firstname . ' ' . $interview->permitApplication?->lastname . ':' . $interview->permit_application_id . ' has been deleted successfully.') : ('Health Interview for ' . $interview->healthCertApplication?->firstname . ' ' . $interview->healthCertApplication?->lastname . ':' . $interview->health_cert_application_id . ' has been deleted successfully.')
                            ];
                        } else {
                            throw new Exception("Unable to delete health interview. Unable to update interview.");
                        }
                    } else {
                        throw new Exception("Unable to delete health interview. Unable to create transaction.");
                    }
                } else {
                    throw new Exception("The application associated to this health interview has already be signed off. It cannot be deleted.");
                }
            } else {
                throw new Exception("This interview does not exist or does not belong to your facility. It cannot be deleted");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function destroySymptom(Request $request, $id)
    {
        try {
            if ($interview_sym = HealthInterviewSymptom::find($id)) {
                if ($interview = HealthInterview::find($interview_sym->health_interview_id)) {
                    if ($interview->sign_off_status != '1') {
                        DB::beginTransaction();
                        if (EditTransactions::create([
                            'application_type_id' => HealthInterview::find($interview_sym->health_interview_id) ? (HealthInterview::find($interview_sym->health_interview_id)->permit_application_id == "" ? 2 : 1) : 0,
                            'table_id' => $id,
                            'system_operation_type_id' => 7,
                            'edit_type_id' => 2,
                            'user_id' => auth()->user()->id,
                            'facility_id' => auth()->user()->facility_id,
                            'reason' => $request->data['reason']
                        ])) {
                            if ($interview_sym->update(['deleted_at' => new DateTime()])) {
                                DB::commit();
                                return [
                                    'success',
                                    'Health Symptoms have been deleted successfully.'
                                ];
                            } else {
                                throw new Exception('This health interview symptoms has not be deleted. Unable to delete health interview symptoms.');
                            }
                        } else {
                            throw new Exception("This health interview symptom has not been deleted. Unable to create transactions");
                        }
                    } else {
                        new Exception("This application has already been signed off.");
                    }
                } else {
                    throw new Exception("This interview does not exist.");
                }
            } else {
                throw new Exception("This health interview symptom does not exist.");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function addTravelHistory(Request $request, $id)
    {
        try {
            if ($request->data['destination'] && $request->data['travel_date']) {
                if ($application = $request->data['application_type'] == '1' ? PermitApplication::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())->find($id) : HealthCertApplications::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())->find($id)) {
                    if ($application->sign_off_status != '1') {
                        DB::beginTransaction();
                        if ($travel_history = $request->data['application_type'] == 1 ?
                            TravelHistory::create([
                                'permit_application_id' => $id,
                                'destination' => $request->data['destination'],
                                'travel_date' => $request->data['travel_date']
                            ]) : TravelHistory::create([
                                'health_cert_application_id' => $id,
                                'destination' => $request->data['destination'],
                                'travel_date' => $request->data['travel_date']
                            ])
                        ) {
                            if ($edit_transaction = EditTransactions::create([
                                'application_type_id' => $request->data["application_type"],
                                'table_id' => $travel_history->id,
                                'system_operation_type_id' => 8,
                                'edit_type_id' => 3,
                                'user_id' => auth()->user()->id,
                                'facility_id' => auth()->user()->facility_id,
                                'reason' => $request->data['edit_reason']
                            ])) {
                                DB::commit();
                                return 'success';
                            } else {
                                throw new Exception("Error storing new travel history. Edit Transaction was not created successfully.");
                            }
                        } else {
                            throw new Exception("Travel History was not added successfully. Error storing data.");
                        }
                    } else {
                        throw new Exception("This application has already signed off. It cannot be edited");
                    }
                } else {
                    throw new Exception("This application was either deleted or doesn't exist");
                }
            } else {
                throw new Exception("Required fields have not been entered.");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function destroyTravelHistory(Request $request, $id)
    {
        try {
            if ($trip = TravelHistory::find($id)) {
                if ($interview = $trip->health_cert_application_id == "" ? HealthInterview::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
                    ->where('permit_application_id', $trip->permit_application_id)->first() : HealthInterview::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())->where('health_cert_application_id', $trip->health_cert_application_id)->first()
                ) {
                    if ($interview->sign_off_status != '1') {
                        DB::beginTransaction();
                        if (EditTransactions::create([
                            'application_type_id' => $trip->permit_application_id == '' ? 2 : 1,
                            'table_id' => $id,
                            'system_operation_type_id' => 8,
                            'edit_type_id' => 2,
                            'user_id' => auth()->user()->id,
                            'facility_id' => auth()->user()->facility_id,
                            'reason' => $request->data['reason']
                        ])) {
                            if ($trip->update(['deleted_at' => new DateTime()])) {
                                DB::commit();
                                return [
                                    'success',
                                    'Travel History has been deleted successfully.'
                                ];
                            } else {
                                throw new Exception("Travel History was not deleted. Unable to delete symptom record.");
                            }
                        } else {
                            throw new Exception('Travel History was not deleted. Unable to create transaction.');
                        }
                    } else {
                        throw new Exception("This interview has already been signed off. Travel history cannot be destroyed");
                    }
                } else {
                    throw new Exception("This interview does not exist or does not belong to your facility.");
                }
            } else {
                throw new Exception('This travel history record does not exist.');
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
