<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use App\Models\EstablishmentClinics;
use App\Models\ExamDates;
use App\Models\HealthInterview;
use App\Models\Payments;
use App\Models\PermitApplication;
use App\Models\PermitCategory;
use App\Models\Renewals;
use App\Models\SignOff;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Storage;

// use Faker\Provider\ar_EG\Payment;

class PermitApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $permit_array = [];
        $id = $request->route('id');
        $today = date_format(new Datetime(), "Y-m-d");
        $yesterday = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
        $last_week = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        $thirty_days = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        $last_ninety_days = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");

        $filterTimeline = "";

        $i = 0;
        if ($id == "0") {
            $filterTimeline = $today;
            $permit_applications = PermitApplication::with('permitCategory', 'payment', 'user')
                ->where('created_at', '>', $today)
                ->whereRelation('user', 'facility_id', '=', Auth()->user()->facility_id)
                ->get();
            return view('food_handlers_permit.index', compact('permit_applications'));
        } else if ($id == "1") {
            $filterTimeline = $yesterday;
        } else if ($id == "7") {
            $filterTimeline = $last_week;
        } else if ($id == "30") {
            $filterTimeline = $thirty_days;
        } else if ($id == "90") {
            $filterTimeline = $last_ninety_days;
        }

        $permit_applications = PermitApplication::with('permitCategory', 'payment', 'user')
            ->whereBetween('created_at', [$filterTimeline, $today])
            ->whereRelation('user', 'facility_id', '=', Auth()->user()->facility_id)
            ->get();

        return view('food_handlers_permit.index', compact('permit_applications'));
    }

    public function customFilterApplications(Request $request)
    {
        $i = 0;
        date_default_timezone_set('Etc/GMT+5');
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'interval' => 'nullable|numeric|max:6'
        ]);

        $permit_array = [];

        $permit_applications = PermitApplication::with('permitCategory', 'payment', 'user')
            ->whereBetween('created_at', [$timeline['starting_date'], $timeline['ending_date'] . " 23:59:59"])
            ->whereRelation('user', 'facility_id', '=', Auth()->user()->facility_id)
            ->get();

        return view('food_handlers_permit.index', compact('permit_applications'));
    }

    public function viewApplication(Request $request)
    {
        $application_id = $request->route('id');
        $permit_application = PermitApplication::with('permitCategory', 'payment', 'user', 'establishmentClinics', 'signOffs')
            ->find($application_id);

            //dd($permit_application);

            $sign_off_user = SignOff::with('user')->where('application_id',$permit_application->id)->get();
           

            //dd($sign_off_user);

        $appointments = Appointments::with('examDate.examSites')
            ->where('facility_id', auth()->user()->facility_id)
            ->where('permit_application_id', $application_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $appointment_available = [];

        foreach (ExamDates::with('examSites', 'permitCategory')
            ->where('facility_id', auth()->user()->facility_id)
            ->where('application_type_id', 1)
            ->get() as $appointment) {
            $appointment_available[$appointment->id] = strtoupper($appointment->permitCategory?->name) . ' - ' . strtoupper($appointment->exam_day) . ' - ' . strtoupper($appointment->exam_start_time) . ' - ' . strtoupper($appointment->examSites?->name);
        }

        return view('food_handlers_permit.view', compact('permit_application', 'appointments', 'appointment_available','sign_off_user'));
    }

    public function editView(Request $request)
    {
        $application_id = $request->route('id');
        $permit_application = PermitApplication::with('permitCategory', 'payment', 'user', 'establishmentClinics', 'signOffs')
            ->find($application_id);

        $appointments = Appointments::with('examDate.examSites')
            ->where('facility_id', auth()->user()->facility_id)
            ->where('permit_application_id', $application_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $appointment_available = [];

        foreach (ExamDates::with('examSites', 'permitCategory')
            ->where('facility_id', auth()->user()->facility_id)
            ->where('application_type_id', 1)
            ->get() as $appointment) {
            $appointment_available[$appointment->id] = strtoupper($appointment->permitCategory?->name) . ' - ' . strtoupper($appointment->exam_day) . ' - ' . strtoupper($appointment->exam_start_time) . ' - ' . strtoupper($appointment->examSites?->name);
        }

        $edit_mode = 1;

        return view('food_handlers_permit.view', compact('permit_application', 'appointments', 'appointment_available', 'edit_mode'));
    }

    public function editApplication(Request $request)
    {
        $edits = $request->validate([
            'firstname' => "required",
            'middlename' => "nullable",
            'lastname' => "required",
            'address' => 'required',
            'id' => 'required',
            'date_of_birth' => 'required',
            'gender' => 'required',
            'cell_phone' => 'nullable|regex:/^[0-9]{1}\({1}[0-9]{3}\)[0-9]{3}\-[0-9]{4}+$/',
            'home_phone' => 'nullable|regex:/^[0-9]{1}\({1}[0-9]{3}\)[0-9]{3}\-[0-9]{4}+$/',
            'work_phone' => 'nullable|regex:/^[0-9]{1}\({1}[0-9]{3}\)[0-9]{3}\-[0-9]{4}+$/',
            'trn' => 'nullable|regex:/^[0-9]{3}\-[0-9]{3}\-[0-9]{3}+$/',
            'email' => 'nullable',
            'permit_no' => 'required',
            'photo_upload' => 'nullable'
        ]);

        $permit_application = PermitApplication::find($edits['id']);

        if ($request->file('photo_upload')) {
            if ($permit_application->photo_upload) {
                Storage::disk('public')->move($permit_application->photo_upload, '/photo_uploads/archives/' . explode('/', $permit_application->photo_upload)[1]);
            }

            $path = $request->file('photo_upload')->storeAs('photo_uploads', $permit_application->permit_no . '.' . $request->photo_upload->extension(), 'public');
            $photo_upload = $path;
        } else {
            $photo_upload = $permit_application->photo_upload;
        }

        //dd($path);

        $update_permit_application = PermitApplication::where('id', $edits["id"])->update([
            'firstname' => $edits["firstname"],
            'middlename' => $edits["middlename"],
            'lastname' => $edits["lastname"],
            'address' => $edits["address"],
            'date_of_birth' => $edits["date_of_birth"],
            'gender' => $edits["gender"],
            'cell_phone' => $edits["cell_phone"],
            'home_phone' => $edits["home_phone"],
            'work_phone' => $edits["work_phone"],
            'trn' => $edits["trn"],
            'email' => $edits["email"],
            'photo_upload' => $photo_upload
        ]);

        if ($update_permit_application > 0) {
            return redirect()->route('permit.index', ['id' => 0])->with(['success' => 'Applicant ' . $edits["firstname"] . ' ' . $edits["lastname"] . ' has be updated successfully']);
        } else {
            return redirect()->route('permit.index', ['id' => 0])->with(['error' => 'Error updating record or nothing to update']);
        }
    }

    public function editPermitAppointment(Request $request)
    {
        try {
            if (Appointments::find($request->data["appointment_id"])) {
                if (Appointments::find($request->data["appointment_id"])->update(
                    ['appointment_date' => $request->data["appointment_date"], 'exam_date_id' => $request->data["exam_date_id"]]
                )) {
                    return "success";
                } else {
                    throw new Exception("Problem updating record");
                }
            } else {
                throw new Exception("Appointment does not exist");
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function newApplication()
    {
        $categories = PermitCategory::all();
        $appointments_available = ExamDates::where('facility_id', auth()->user()->facility_id)
            ->where('application_type_id', 1)
            ->get();

        //dd($appointments_available);
        return view('food_handlers_permit.create', compact('categories', 'appointments_available'));
    }

    public function renewal(Request $request)
    {
        $categories = PermitCategory::all();
        $appointments_available = ExamDates::where('facility_id', auth()->user()->facility_id)
            ->where('application_type_id', 1)
            ->get();

        $application = PermitApplication::find($request->route('id'));

        return view('food_handlers_permit.renew', compact('categories', 'appointments_available', 'application'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $permit_application = $request->validate([
            'permit_category_id' => 'required',
            'firstname' => "required",
            'middlename' => "nullable",
            'lastname' => "required",
            'address' => 'required',
            'date_of_birth' => 'required',
            'gender' => 'required',
            'permit_type' => 'required',
            'no_of_years' => 'required_if:permit_type,=,student',
            'cell_phone' => 'nullable',
            'home_phone' => 'nullable',
            'work_phone' => 'nullable',
            'occupation' => 'nullable',
            'employer' => 'nullable',
            'employer_address' => 'nullable',
            'email' => 'nullable|email',
            'trn' => 'nullable',
            'applied_before' => 'required',
            'granted' => 'required_if:applied_before,=,1',
            'reason' => 'nullable',
            'photo_upload' => 'nullable',
            'exam_date' => 'required',
            'exam_session' => 'required',
            'application_date' => 'required',
        ]);

        if ($request->establishment_clinic_id) {
            $permit_application['establishment_clinic_id'] = $request->establishment_clinic_id;
        }

        function generatePermitNo()
        {
            do {
                $abbr = DB::table('facilities')
                    ->select('abbr')
                    ->where('id', auth()->user()->facility_id)
                    ->first()->abbr;

                $digit_limit = 4;
                $current_date = date("my");
                $random_digits = str_pad(rand(0, pow(10, $digit_limit) - 1), $digit_limit, '0', STR_PAD_LEFT);
                $permit_no = $abbr . $random_digits . $current_date;

                $permit_no_exist = PermitApplication::where('permit_no', $permit_no)->first();
            } while (!empty($permit_no_exist));
            return $permit_no;
        }

        $permit_application['user_id'] = Auth()->user()->id;
        $permit_application['permit_no'] = generatePermitNo();
        if ($request->file('photo_upload')) {
            $path = $request->file('photo_upload')->storeAs('photo_uploads', $permit_application['permit_no'] . '.' . $request->photo_upload->extension(), 'public');
            $permit_application['photo_upload'] = $path;
        } else {
            $permit_application['photo_upload'] = "";
        }

        $new_permit_application = PermitApplication::create($permit_application);

        if ($new_permit_application) {
            if ($request->establishment_clinic_id) {
                $est_clinic = EstablishmentClinics::withCount('permits')
                    ->where('id', $request->establishment_clinic_id)
                    ->first();

                $clinic_payment = Payments::where('application_id', $request->establishment_clinic_id)->where('application_type_id', 4)->first();
                Payments::create(
                    [
                        'application_type_id' => 1,
                        'application_id' => $new_permit_application->id,
                        'facility_id' => auth()->user()->facility_id,
                        'receipt_no' => $clinic_payment->receipt_no,
                        'amount_paid' => 0,
                        'total_cost' => 0,
                        'change_amt' => 0.0,
                        'cashier_user_id' => $clinic_payment->cashier_user_id
                    ]
                );
            } else {
                $appointment['appointment_date'] = $request->exam_date;
                $appointment['facility_id'] = Auth()->user()->facility_id;
                $appointment['permit_application_id'] = DB::table('permit_applications')->where('permit_no', $permit_application['permit_no'])->first()->id;
                $appointment['exam_date_id'] = $request->exam_session;
                $new_appointment = Appointments::create($appointment);
                if (!$new_appointment) {
                    return redirect()->route('permit.index', ['id' => 0])->with('error', 'Appointment was not created successfully');
                }
            }

            if (empty($request->establishment_clinic_id) || $est_clinic->permits_count == $est_clinic->no_of_employees) {
                return redirect()->route('permit.index', ['id' => 0])->with('success', 'Application has been processed successfully. The Application ID is: ' . $new_permit_application->id . '');
            } else {
                return redirect()
                    ->route('food-handlers-clinic.permit.application', ['clinic_app_id' => $request->establishment_clinic_id])
                    ->with('success', 'The application was entered successfully. The Application ID is: ' . $new_permit_application->id);
            }
        } else {
            return redirect()->route('permit.index', ['id' => 0])->with(['error' => 'Application was not created successfully']);
        }
    }

    public function storeRenewal(Request $request)
    {
        $permit_application = $request->validate([
            'permit_category_id' => 'required',
            'firstname' => "required",
            'middlename' => "nullable",
            'lastname' => "required",
            'address' => 'required',
            'date_of_birth' => 'required',
            'gender' => 'required',
            'permit_type' => 'required',
            'no_of_years' => 'required_if:permit_type,=,student',
            'cell_phone' => 'nullable',
            'home_phone' => 'nullable',
            'work_phone' => 'nullable',
            'occupation' => 'nullable',
            'employer' => 'nullable',
            'employer_address' => 'nullable',
            'email' => 'nullable|email',
            'trn' => 'nullable',
            'applied_before' => 'required',
            'granted' => 'required_if:applied_before,=,1',
            'reason' => 'nullable',
            'photo_upload' => 'nullable',
            'exam_date' => 'required',
            'exam_session' => 'required',
            'application_date' => 'required',
        ]);

        $old_permit = PermitApplication::find($request->old_application_id);
        DB::beginTransaction();
        $permit_application['permit_no'] = $old_permit->permit_no;

        $permit_application['user_id'] = Auth()->user()->id;
        if ($request->file('photo_upload')) {
            $path = $request->file('photo_upload')->storeAs('photo_uploads', $permit_application['permit_no'] . '.' . $request->photo_upload->extension(), 'public');
            $permit_application['photo_upload'] = $path;
        } else {
            $permit_application['photo_upload'] = $old_permit->photo_upload;
        }

        if (PermitApplication::create($permit_application)) {
            HealthInterview::where('permit_application_id', $request->old_application_id)->update([
                'deleted_at' => new DateTime()
            ]);

            TestResult::where('application_id', $request->old_application_id)->where('application_type_id', 1)->update([
                'deleted_at' => new DateTime()
            ]);

            Appointments::where('permit_application_id', $request->old_application_id)->update([
                'deleted_at' => new DateTime()
            ]);

            $new_application = PermitApplication::where('permit_no', $old_permit->permit_no)->orderBy('created_at', 'DESC')->first();

            if (Appointments::create(
                [
                    'facility_id' => auth()->user()->facility_id,
                    'permit_application_id' => $new_application->id,
                    'appointment_date' => $permit_application['exam_date'],
                    'exam_date_id' => $permit_application['exam_session']
                ]
            )) {
                Renewals::create([
                    'new_application_id' => $new_application->id,
                    'application_type_id' => 1,
                    'old_application_id' => $old_permit->id,
                ]);

                $old_permit->update([
                    'deleted_at' => new DateTime()
                ]);
            }
        }
        DB::commit();
        return redirect()->route('permit.index', ['id' => 0])->with('success', 'Renewal has been completed successfully. The Application Id is' . $new_application->id);
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
        $permit_application = PermitApplication::find($id);
        //dd($permit_application);

        return redirect()->route('dashboard.dashboard')->with('success',$permit_application);
    }
}
