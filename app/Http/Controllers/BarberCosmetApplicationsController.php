<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use App\Models\ExamDates;
use App\Models\HealthCertApplications;
use App\Models\HealthInterview;
use App\Models\Renewals;
use App\Models\TestResult;
use App\Models\User;
use Illuminate\Http\Request;
use DateTime;

class BarberCosmetApplicationsController extends Controller
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
        $filterTimeline = "";

        if ($id == "0") {
            $applications = HealthCertApplications::with('user', 'appointment.examDate.examSites')
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->where('created_at', '>', $today)
                ->get();

            return view('barbercosmet.index', compact('applications'));
        } else if ($id == "1") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
        } else if ($id == "7") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        } else if ($id == "30") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        } else if ($id == "90") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        }

        $applications = HealthCertApplications::with('user', 'appointment.examDate.examSites')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->whereBetween('created_at', [$filterTimeline, $today])
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
        return view('barbercosmet.create', compact('exam_sessions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $health_cert_app = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'middlename' => 'nullable',
            'date_of_birth' => 'required|date',
            'sex' => 'required',
            'email' => 'nullable|email',
            'trn' => 'nullable|regex:/^[0-9]{3}\-[0-9]{3}\-[0-9]{3}+$/',
            'occupation' => 'nullable',
            'employer_address' => 'nullable',
            'employer' => 'nullable',
            'granted' => 'required_if:applied_before,1',
            'appointment_date' => 'required',
            'telephone' => 'required|regex:/^\+1+\(+[0-9]{3}+\)+[0-9]{3}+\-+[0-9]{4}+$/',
            'applied_before' => 'required',
            'reason' => 'required_if:granted,0|max:255',
            'exam_date_id' => 'required',
            'application_date' => 'required',
            'address' => 'required'
        ]);

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

        return view('barbercosmet.view', compact('application', 'exam_sessions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editApplicant(Request $request)
    {
        $applicant_info = $request->validate([
            'firstname' => 'required',
            'middlename' => 'nullable',
            'lastname' => 'required',
            'address' => 'required',
            'date_of_birth' => 'required|date',
            'sex' => 'required',
            'telephone' => 'required',
            'email' => 'nullable|email'
        ]);

        $application = HealthCertApplications::find($request->id);

        if ($application->update($applicant_info)) {
            return redirect()->route('barber-cosmet.index', ['id' => 0])->with('success', 'Applicant Information for ' . $application->firstname . ' ' . $application->lastname . ' has been updated successfully.');
        }

        return redirect()->route('barber-cosmet.index', ['id' => 0])->with('error', 'Employment and Application Information was not updated');
    }

    public function edit(Request $request)
    {
        $application = HealthCertApplications::with('payment', 'appointment.examDate.examSites', 'user')->find($request->route('id'));
        $exam_sessions = ExamDates::with('examSites')
            ->where('application_type_id', 2)
            ->where('facility_id', auth()->user()->facility_id)
            ->get();

        $edit_mode = 1;

        return view('barbercosmet.view', compact('application', 'exam_sessions', 'edit_mode'));
    }

    public function editEmp(Request $request)
    {
        $application_info = $request->validate([
            'occupation' => 'nullable',
            'employer' => 'nullable',
            'employer_address' => 'nullable',
            'applied_before' => 'required',
            'granted' => 'required_if:applied_before,1',
            'reason' => 'required_if:granted,0|max:255'
        ]);

        $application = HealthCertApplications::find($request->id);

        if ($application->update($application_info)) {
            return redirect()->route('barber-cosmet.index', ['id' => 0])->with('success', 'Employment and Application Information for ' . $application->firstname . ' ' . $application->lastname . ' has been updated successfully.');
        }

        return redirect()->route('barber-cosmet.index', ['id' => 0])->with('error', 'Employment and Application Information was not updated');
    }

    public function editAppointment(Request $request)
    {
        $appointment_info = $request->validate([
            'appointment_date' => 'required',
            'exam_date_id' => 'required'
        ]);

        $application = HealthCertApplications::find($request->id);

        if (Appointments::where('health_cert_application_id', $request->id)
            ->orderBy('created_at', 'desc')
            ->first()
            ->update($appointment_info)
        ) {
            return redirect()->route('barber-cosmet.index', ['id' => 0])->with('success', 'Appointment information for ' . $application->firstname . ' ' . $application->lastname . ' has been updated successfully.');
        }

        return redirect()->route('barber-cosmet.index', ['id' => 0])->with('error', 'Appointment information was not updated.');
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
    public function destroy($id)
    {
        //
    }
}
