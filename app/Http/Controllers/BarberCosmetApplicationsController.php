<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use App\Models\ExamDates;
use App\Http\Requests\HealthCertificateRequest;
use App\Models\EditTransactions;
use App\Models\EditTransactionsChangedColumns;
use App\Models\ExamSites;
use App\Models\HealthCertApplications;
use App\Models\HealthInterview;
use App\Models\HealthInterviewSymptom;
use App\Models\Renewals;
use App\Models\TestResult;
use App\Models\TravelHistory;
use App\Models\User;
use Illuminate\Http\Request;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;

class BarberCosmetApplicationsController extends Controller
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

        if ($id == "0") {
            $filterTimeline = $today;
        } else if ($id == "1") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
            $applications = HealthCertApplications::with('user', 'appointment.examDate.examSites')
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->whereBetween('created_at', [$filterTimeline, $today])
                ->get();
            return view('barbercosmet.index', compact('applications'));
        } else if ($id == "7") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        } else if ($id == "30") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        } else if ($id == "90") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        } else if ($id == "180") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-180 days"), "Y-m-d");
        }

        $applications = HealthCertApplications::with('user', 'appointment.examDate.examSites')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->where('created_at', '>', $filterTimeline)
            ->get();

        return view('barbercosmet.index', compact('applications'));
    }

    public function customIndex(Request $request)
    {
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'interval' => 'nullable|numeric|max:6'
        ]);

        $applications = HealthCertApplications::with('user', 'appointment.examDate.examSites')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->whereBetween('created_at', [$timeline['starting_date'], $timeline['ending_date'] . " 23:59:59"])
            ->get();

        return view('barbercosmet.index', compact('applications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $exam_sessions = ExamDates::with('examSites')
            ->where('application_type_id', 2)
            ->where('facility_id', auth()->user()->facility_id)
            ->get();
            //dd($exam_sessions);
        return view('barbercosmet.create', compact('exam_sessions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HealthCertificateRequest $request)
    {
        $health_cert_app = $request->validated();

        $health_cert_app['user_id'] = auth()->user()->id;
        $health_cert_app['permit_no'] = $this->generateHealthCertificatePermitNo();

        if ($cert_created = HealthCertApplications::create($health_cert_app)) {
            if (Appointments::create(
                [
                    'appointment_date' => $health_cert_app['appointment_date'],
                    'facility_id' => auth()->user()->facility_id,
                    'health_cert_application_id' => $cert_created->id,
                    'exam_date_id' => $health_cert_app['exam_date_id']
                ]
            )) {
                return redirect()->route('barber-cosmet.index', ['id' => 0])->with('success', 'Health Certificate Application has been processed successfully. The Application ID is: ' . $cert_created->id);
            }
        }

        return redirect()->route('barber-cosmet.index', ['id' => 0])->with('error', 'Error processing Health Certificate Application.');
    }

    public function generateHealthCertificatePermitNo()
    {
        //Generate permit no.
        do {
            $abbr = User::with('facility')->find(auth()->user()->id)?->facility?->abbr;
            $digits_limit = 4;
            $current_date = date("my");
            $random_digits = str_pad(rand(0, pow(10, $digits_limit) - 1), $digits_limit, '0', STR_PAD_LEFT);
            $permit_no = $abbr . $random_digits . $current_date;

            $permit_no_exist = HealthCertApplications::where('permit_no', $permit_no)->first();
        } while (!empty($permit_no_exist));

        return $permit_no;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $application = HealthCertApplications::with('payment', 'appointment.examDate.examSites', 'user')->find($request->route('id'));
        $exam_sessions = ExamDates::with('examSites')
            ->where('application_type_id', 2)
            ->where('facility_id', auth()->user()->facility_id)
            ->get();

        $system_operation_type_id = 6;

        return view('barbercosmet.view', compact('application', 'exam_sessions', 'system_operation_type_id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateApplicant(Request $request, $id)
    {
        $applicant_info = $request->validate([
            'firstname' => 'required',
            'middlename' => 'nullable',
            'lastname' => 'required',
            'address' => 'required',
            'date_of_birth' => 'required|date',
            'sex' => 'required',
            'telephone' => 'required',
            'email' => 'nullable|email',
            'edit_reason_one' => 'required'
        ]);

        try {
            if ($bar_application = HealthCertApplications::with('user')
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->find($id)
            ) {
                if ($bar_application->sign_off_status != '1') {
                    $edit_reason = $applicant_info['edit_reason_one'];
                    unset($applicant_info['edit_reason_one']);
                    if (!empty($differences = array_diff_assoc($applicant_info, HealthCertApplications::select('firstname', 'middlename', 'lastname', 'address', 'date_of_birth', 'sex', 'telephone', 'email')->find($id)->toArray()))) {
                        DB::beginTransaction();
                        if ($edit_transaction = EditTransactions::create([
                            'application_type_id' => 2,
                            'table_id' => $bar_application->id,
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
                                    'old_value' => $bar_application->toArray()[$key],
                                    'new_value' => $applicant_info[$key]
                                ])) {
                                    throw new Exception("Error updating application. Error recording fields changed.");
                                }
                            }
                            if ($bar_application->update($applicant_info)) {
                                DB::commit();
                                return redirect()->route('barber-cosmet.view', ['id' => $id])->with('success', 'Applicant Information for ' . $bar_application->firstname . ' ' . $bar_application->lastname . ' has been updated successfully.');
                            } else {
                                throw new Exception("Error updating application. Unable to update record.");
                            }
                        } else {
                            throw new Exception("Application was not updated. Error initiating transaction.");
                        }
                    } else {
                        throw new Exception("No fields were changed. Update was not processed");
                    }
                } else {
                    throw new Exception("This application has already been signed off. Application cannot be edited.");
                }
            } else {
                throw new Exception("This application does not exist or does not belong to your facility.");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('barber-cosmet.view', ['id' => $id])->with('error', $e->getMessage());
        }
    }

    public function edit(Request $request)
    {
        $application = HealthCertApplications::with('payment', 'appointment.examDate.examSites', 'user')->find($request->route('id'));
        $exam_sessions = ExamDates::with('examSites')
            ->where('application_type_id', 2)
            ->where('facility_id', auth()->user()->facility_id)
            ->get();

        $edit_mode = 1;
        $system_operation_type_id = 6;

        return view('barbercosmet.view', compact('application', 'exam_sessions', 'edit_mode', 'system_operation_type_id'));
    }

    public function updateEmp(Request $request, $id)
    {
        $updated_info = $request->validate([
            'occupation' => 'nullable',
            'employer' => 'nullable',
            'employer_address' => 'nullable',
            'applied_before' => 'required',
            'granted' => 'required_if:applied_before,1',
            'reason' => 'required_if:granted,0|max:255',
            'edit_reason_two' => 'required'
        ]);

        try {
            if ($bar_application = HealthCertApplications::with('user')
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->find($id)
            ) {
                if ($bar_application->sign_off_status != '1') {
                    $edit_reason = $updated_info['edit_reason_two'];
                    unset($updated_info['edit_reason_two']);
                    if (!empty($differences = array_diff_assoc($updated_info, HealthCertApplications::select('occupation', 'employer', 'employer_address', 'applied_before', 'granted', 'reason')->find($id)->toArray()))) {
                        DB::beginTransaction();
                        if ($edit_transaction = EditTransactions::create([
                            'application_type_id' => 2,
                            'table_id' => $bar_application->id,
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
                                    'old_value' => $bar_application->toArray()[$key],
                                    'new_value' => $updated_info[$key]
                                ])) {
                                    throw new Exception("Error updating application. Error recording fields changed.");
                                }
                            }
                            if ($bar_application->update($updated_info)) {
                                DB::commit();
                                return redirect()->route('barber-cosmet.view', ['id' => $id])->with('success', 'Employment and Application Information for ' . $bar_application->firstname . ' ' . $bar_application->lastname . ' has been updated successfully.');
                            } else {
                                throw new Exception("Error updating application. Unable to update record.");
                            }
                        } else {
                            throw new Exception("Application was not updated. Error initiating transaction.");
                        }
                    } else {
                        throw new Exception("No fields were changed. Update was not processed");
                    }
                } else {
                    throw new Exception("This application has already been signed off. Application cannot be edited.");
                }
            } else {
                throw new Exception("This application does not exist");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('barber-cosmet.view', ['id' => $id])->with('error', $e->getMessage());
        }

        // $application = HealthCertApplications::find($request->id);

        // if ($application->update($application_info)) {
        //     return redirect()->route('barber-cosmet.index', ['id' => 0])->with('success', 'Employment and Application Information for ' . $application->firstname . ' ' . $application->lastname . ' has been updated successfully.');
        // }

        // return redirect()->route('barber-cosmet.index', ['id' => 0])->with('error', 'Employment and Application Information was not updated');
    }

    public function updateAppointment(Request $request, $id)
    {
        $appointment_info = $request->validate([
            'appointment_date' => 'required',
            'exam_date_id' => 'required',
            'edit_reason_three' => 'required'
        ]);

        try {
            if ($appointment = Appointments::find($id)) {
                if ($bar_application = HealthCertApplications::with('user')
                    ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                    ->find($appointment->health_cert_application_id)
                ) {
                    if ($bar_application->sign_off_status != '1') {
                        $edit_reason = $appointment_info['edit_reason_three'];
                        unset($appointment_info['edit_reason_three']);
                        if (!empty($differences = array_diff_assoc($appointment_info, Appointments::select('appointment_date', 'exam_date_id')->find($id)->toArray()))) {
                            DB::beginTransaction();
                            if ($edit_transaction = EditTransactions::create([
                                'application_type_id' => 2,
                                'table_id' => $appointment->id,
                                'system_operation_type_id' => 6,
                                'edit_type_id' => 1,
                                'user_id' => auth()->user()->id,
                                'facility_id' => auth()->user()->facility_id,
                                'reason' => $edit_reason
                            ])) {
                                foreach ($differences as $key => $value) {
                                    if (!EditTransactionsChangedColumns::create([
                                        'edit_transaction_id' => $edit_transaction->id,
                                        'column_name' => $key,
                                        'old_value' => $key == 'exam_date_id' ? ExamDates::find($appointment->exam_date_id)->exam_day . ' ' . ExamDates::find($appointment->exam_date_id)->exam_start_time . ' - ' . ExamSites::find(ExamDates::find($appointment->exam_date_id)->exam_site_id)->name : $appointment->toArray()[$key],
                                        'new_value' => $key == 'exam_date_id' ? ExamDates::find($appointment_info['exam_date_id'])->exam_day . ' ' . ExamDates::find($appointment_info['exam_date_id'])->exam_start_time . ' - ' . ExamSites::find(ExamDates::find($appointment_info['exam_date_id'])->exam_site_id)->name : $appointment_info[$key]
                                    ])) {
                                        throw new Exception("Error processing update. Unable to record changed fields.");
                                    }
                                }
                                if ($appointment->update($appointment_info)) {
                                    DB::commit();
                                    return redirect()->route('barber-cosmet.view', ['id' => $bar_application->id])->with('success', 'Appointment information for ' . $bar_application->firstname . ' ' . $bar_application->lastname . ' has been updated successfully.');
                                }
                            } else {
                                throw new Exception("Error updating appointment. Error initiating transaction.");
                            }
                        } else {
                            throw new Exception("No fields were changed. Nothing was updated.");
                        }
                    } else {
                        throw new Exception("This application has already been signed off. Edits are not permitted.");
                    }
                } else {
                    throw new Exception("This application does exist.");
                }
            } else {
                throw new Exception("This appointment does not exist.");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function renewal($id)
    {
        $application = HealthCertApplications::find($id);

        $exam_sessions = ExamDates::with('examSites')
            ->where('application_type_id', 2)
            ->where('facility_id', auth()->user()->facility_id)
            ->get();

        return view('barbercosmet.renew', compact('application', 'exam_sessions'));
    }

    public function renew(Request $request, $id)
    {
        //Use destroy function in this
        $health_cert_app = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'middlename' => 'nullable',
            'date_of_birth' => 'required|date',
            'sex' => 'required',
            'email' => 'nullable|email',
            'trn' => 'nullable',
            'occupation' => 'nullable',
            'employer_address' => 'nullable',
            'employer' => 'nullable',
            'granted' => 'required_if:applied_before,1',
            'appointment_date' => 'required',
            'telephone' => 'required',
            'applied_before' => 'required',
            'reason' => 'required_if:granted,0|max:255',
            'exam_date_id' => 'required',
            'application_date' => 'required',
            'address' => 'required'
        ]);

        $old_application = HealthCertApplications::find($id);
        $health_cert_app['user_id'] = auth()->user()->id;
        $health_cert_app['permit_no'] = $old_application->permit_no;

        if ($new_application = HealthCertApplications::create($health_cert_app)) {
            if (Renewals::create([
                'old_application_id' => $old_application->id,
                'new_application_id' => $new_application->id,
                'application_type_id' => '2'
            ])) {
                if ($old_application->update(['deleted_at' => new DateTime()])) {
                    if (Appointments::create(
                        [
                            'appointment_date' => $health_cert_app['appointment_date'],
                            'facility_id' => auth()->user()->facility_id,
                            'health_cert_application_id' => $new_application->id,
                            'exam_date_id' => $health_cert_app['exam_date_id']
                        ]
                    )) {
                        if (Appointments::where('health_cert_application_id', $id)
                            ->orderBy('created_at', 'desc')
                            ->first()
                            ->update(['deleted_at' => new DateTime()])
                        ) {
                            if ($old_health_interview = HealthInterview::where('health_cert_application_id', $id)->first()) {
                                $old_health_interview->update(['deleted_at' => new DateTime()]);
                            }

                            if ($old_test_results = TestResult::where('application_id', $id)->where('application_type_id', 2)->first()) {
                                $old_test_results->update(['deleted_at' => new DateTime()]);
                            }
                            return redirect()->route('barber-cosmet.index', ['id' => 0])->with('success', 'Health Certificate Application has been renewed successfully. The New Application ID is: ' . $new_application->id);
                        }
                    }
                }
            }
        }
        return redirect()->route('barber-cosmet.index', ['id' => 0])->with('error', 'Error renewing application id: ' . $id);
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
            if ($application = HealthCertApplications::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
                ->find($id)
            ) {
                if ($application->sign_off_status != '1') {
                    //Delete Appointment
                    //Delete Test Result
                    //Delete Health Interview
                    //Delete Travel History
                    //Delete Health Interview Symptoms
                    DB::beginTransaction();
                    if (EditTransactions::create([
                        'application_type_id' => 2,
                        'table_id' => $application->id,
                        'system_operation_type_id' => 1,
                        'edit_type_id' => 2,
                        'user_id' => auth()->user()->id,
                        'facility_id' => auth()->user()->facility_id,
                        'reason' => $request->data['reason']
                    ])) {
                        if (!empty($application->appointment->first())) {
                            if (!Appointments::where('health_cert_application_id', $id)->first()->update(['deleted_at' => new DateTime()])) {
                                throw new Exception("Delete Operation failed. Unable to delete appointment created for this application.");
                            }
                        }
                        if (!empty($application->testResults)) {
                            if (!TestResult::find($application->testResults?->id)->update(['deleted_at' => new DateTime()])) {
                                throw new Exception("Delete operation failed. System was unable to delete Test Results");
                            }
                        }
                        if (!empty($application->healthInterviews)) {
                            foreach ($application->healthInterviews?->healthInterviewSymptom as $sym) {
                                if (!HealthInterviewSymptom::find($sym->id)->update(['deleted_at' => new DateTime()])) {
                                    throw new Exception("Delete operation failed. Unable to delete symptom added in health interview");
                                }
                            }
                            if (!HealthInterview::find($application->healthInterviews?->id)->update(['deleted_at' => new DateTime()])) {
                                throw new Exception('Delete operation failed. Unable to delete health interviews.');
                            }
                        }
                        if (!empty($application->travelHistory)) {
                            foreach ($application->travelHistory as $travel) {
                                if (!TravelHistory::find($travel->id)->update(['deleted_at' => new DateTime()])) {
                                    throw new Exception('Delete operation failed. Unable to delete travel history');
                                }
                            }
                        }
                        if ($application->update(['deleted_at' => new DateTime()])) {
                            DB::commit();
                            return [
                                'success',
                                "Barber/Cosmet application for " . $application->firstname . " " . $application->lastname . ":" . $application->id . " has been deleted successfully"
                            ];
                        } else {
                            throw new Exception("Error deleting application. Unable to processing delete.");
                        }
                    } else {
                        throw new Exception("Error deleting application. Unable to initiate transaction.");
                    }
                } else {
                    throw new Exception("This application has already been signed off. It can no longer be edited.");
                }
            } else {
                throw new Exception("This application does not exist or is not a part of your facility.");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
