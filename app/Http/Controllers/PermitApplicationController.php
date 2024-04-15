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
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use Exception;
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
        // $start_date = "2023-10-12";
        // $end_date = new DateTime();

        $i = 0;
        if ($id == "0") {
            $filterTimeline = $today;

            $all_permit_applications = PermitApplication::with('permitCategory', 'payment', 'user')->where('created_at', '>', $today)->whereRelation('user', 'facility_id', '=', Auth()->user()->facility_id)->get();
            foreach ($all_permit_applications as $permit_app) {
                $permit_array[$i]["id"] = $permit_app->id;
                $permit_array[$i]["permit_no"] = $permit_app->permit_no;
                $permit_array[$i]["firstname"] = $permit_app->firstname;
                $permit_array[$i]["lastname"] = $permit_app->lastname;
                $permit_array[$i]["category"] = $permit_app->permitCategory?->name;
                $permit_array[$i]["payment_status"] = $permit_app->payment?->id;
                $permit_array[$i]["permit_type"] = $permit_app->permit_type;
                $permit_array[$i]["sign_off_status"] = $permit_app->sign_off_status;
                $permit_array[$i]["trn"] = $permit_app->trn;
                $permit_array[$i]["granted"] = $permit_app->granted;
                $i++;
            }
            $json_applications = json_encode($permit_array);
            return view('food_handlers_permit.index', compact('json_applications'));
        } else if ($id == "1") {
            $filterTimeline = $yesterday;
        } else if ($id == "7") {
            $filterTimeline = $last_week;
        } else if ($id == "30") {
            $filterTimeline = $thirty_days;
        } else if ($id == "90") {
            $filterTimeline = $last_ninety_days;
        }

        $all_permit_applications = PermitApplication::with('permitCategory', 'payment', 'user')->whereBetween('created_at', [$filterTimeline, $today])->whereRelation('user', 'facility_id', '=', Auth()->user()->facility_id)->get();

        foreach ($all_permit_applications as $permit_app) {
            $permit_array[$i]["id"] = $permit_app->id;
            $permit_array[$i]["permit_no"] = $permit_app->permit_no;
            $permit_array[$i]["firstname"] = $permit_app->firstname;
            $permit_array[$i]["lastname"] = $permit_app->lastname;
            $permit_array[$i]["category"] = $permit_app->permitCategory?->name;
            $permit_array[$i]["payment_status"] = $permit_app->payment?->id;
            $permit_array[$i]["permit_type"] = $permit_app->permit_type;
            $permit_array[$i]["sign_off_status"] = $permit_app->sign_off_status;
            $permit_array[$i]["trn"] = $permit_app->trn;
            $permit_array[$i]["granted"] = $permit_app->granted;
            $i++;
        }

        $json_applications = json_encode($permit_array);
        return view('food_handlers_permit.index', compact('json_applications'));
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

        $all_permit_applications = PermitApplication::with('permitCategory', 'payment', 'user')->whereBetween('created_at', [$timeline['starting_date'], $timeline['ending_date']])->whereRelation('user', 'facility_id', '=', Auth()->user()->facility_id)->get();

        foreach ($all_permit_applications as $permit_app) {
            $permit_array[$i]["id"] = $permit_app->id;
            $permit_array[$i]["permit_no"] = $permit_app->permit_no;
            $permit_array[$i]["firstname"] = $permit_app->firstname;
            $permit_array[$i]["lastname"] = $permit_app->lastname;
            $permit_array[$i]["category"] = $permit_app->permitCategory?->name;
            $permit_array[$i]["payment_status"] = $permit_app->payment?->id;
            $permit_array[$i]["permit_type"] = $permit_app->permit_type;
            $permit_array[$i]["sign_off_status"] = $permit_app->sign_off_status;
            $permit_array[$i]["trn"] = $permit_app->trn;
            $permit_array[$i]["granted"] = $permit_app->granted;
            $i++;
        }

        $json_applications = json_encode($permit_array);
        return view('food_handlers_permit.index', compact('json_applications'));
    }

    public function viewApplication(Request $request)
    {
        $application_id = $request->route('id');
        $applicant_info = PermitApplication::with('permitCategory', 'payment', 'user', 'establishmentClinics')->find($application_id);
        $permit_application["firstname"] = $applicant_info->firstname;
        $permit_application["middlename"] =  $applicant_info->middlename;
        $permit_application["permit_no"] = $applicant_info->permit_no;
        $permit_application["lastname"] =  $applicant_info->lastname;
        $permit_application["date_of_birth"] =  $applicant_info->date_of_birth;
        $permit_application["gender"] =  $applicant_info->gender;
        $permit_application["address"] =  $applicant_info->address;
        $permit_application["cell_phone"] =  $applicant_info->cell_phone;
        $permit_application["home_phone"] =  $applicant_info->home_phone;
        $permit_application["work_phone"] =  $applicant_info->work_phone;
        $permit_application["trn"] =  $applicant_info->trn;
        $permit_application["email"] =  $applicant_info->email;
        $permit_application["id"] =  $applicant_info->id;
        $permit_application["permit_type"] =  $applicant_info->permit_type;
        $permit_application["permit_category"] =  $applicant_info->permitCategory?->name;
        $permit_application["expiration_date"] =  $applicant_info->no_of_years;
        $permit_application["granted"] =  $applicant_info->granted;
        $permit_application["sign_off_status"] =  $applicant_info->sign_off_status;
        $permit_application["reason"] =  $applicant_info->reason;
        $permit_application["applied_before"] =  $applicant_info->applied_before;
        $permit_application["payment_status"] =  $applicant_info->payment?->id;
        $permit_application["establishment"] =  $applicant_info->establishmentClinics?->name;
        $permit_application["added_by"] =  $applicant_info->user?->firstname . ' ' . $applicant_info->user?->lastname;
        $permit_application["created_at"] = $applicant_info->created_at;
        $permit_application["photo_upload"] = $applicant_info->photo_upload;

        $appointments = DB::table('appointments')
            ->join('exam_dates', 'exam_dates.id', '=', 'appointments.exam_date_id')
            ->join('exam_sites', 'exam_sites.id', '=', 'exam_dates.exam_site_id')
            ->selectRaw('appointments.id as appointment_id, appointments.appointment_date, exam_sites.name as appointment_location, exam_dates.exam_start_time as appointment_time, exam_dates.id as exam_date_id')
            ->where('appointments.facility_id', auth()->user()->facility_id)
            ->where('appointments.permit_application_id', $application_id)
            ->where('exam_dates.application_type_id', 1)
            ->orderBy('appointments.created_at', 'desc')
            ->get();

        $json_appointments = json_encode($appointments);

        $json_application = json_encode($permit_application);

        $appointment_available = [];

        foreach (ExamDates::with('examSites', 'permitCategory')
            ->where('facility_id', auth()->user()->facility_id)
            ->where('application_type_id', 1)
            ->get() as $appointment) {
            $appointment_available[$appointment->id] = strtoupper($appointment->permitCategory?->name) . ' - ' . strtoupper($appointment->exam_day) . ' - ' . strtoupper($appointment->exam_start_time) . ' - ' . strtoupper($appointment->examSites?->name);
        }

        return view('food_handlers_permit.view', compact('json_application', 'json_appointments', 'appointment_available'));
    }

    public function editView(Request $request)
    {
        $application_id = $request->route('id');
        $applicant_info = PermitApplication::with('permitCategory', 'payment', 'user', 'establishmentClinics')->find($application_id);
        $permit_application["firstname"] = $applicant_info->firstname;
        $permit_application["middlename"] =  $applicant_info->middlename;
        $permit_application["permit_no"] = $applicant_info->permit_no;
        $permit_application["lastname"] =  $applicant_info->lastname;
        $permit_application["date_of_birth"] =  $applicant_info->date_of_birth;
        $permit_application["gender"] =  $applicant_info->gender;
        $permit_application["address"] =  $applicant_info->address;
        $permit_application["cell_phone"] =  $applicant_info->cell_phone;
        $permit_application["home_phone"] =  $applicant_info->home_phone;
        $permit_application["work_phone"] =  $applicant_info->work_phone;
        $permit_application["trn"] =  $applicant_info->trn;
        $permit_application["email"] =  $applicant_info->email;
        $permit_application["id"] =  $applicant_info->id;
        $permit_application["permit_type"] =  $applicant_info->permit_type;
        $permit_application["permit_category"] =  $applicant_info->permitCategory?->name;
        $permit_application["expiration_date"] =  $applicant_info->no_of_years;
        $permit_application["granted"] =  $applicant_info->granted;
        $permit_application["sign_off_status"] =  $applicant_info->sign_off_status;
        $permit_application["reason"] =  $applicant_info->reason;
        $permit_application["applied_before"] =  $applicant_info->applied_before;
        $permit_application["payment_status"] =  $applicant_info->payment?->id;
        $permit_application["establishment"] =  $applicant_info->establishmentClinics?->name;
        $permit_application["added_by"] =  $applicant_info->user?->firstname . ' ' . $applicant_info->user?->lastname;
        $permit_application["created_at"] = $applicant_info->created_at;
        $permit_application["photo_upload"] = $applicant_info->photo_upload;

        $appointments = DB::table('appointments')
            ->join('exam_dates', 'exam_dates.id', '=', 'appointments.exam_date_id')
            ->join('exam_sites', 'exam_sites.id', '=', 'exam_dates.exam_site_id')
            ->selectRaw('appointments.id as appointment_id, appointments.appointment_date, exam_sites.name as appointment_location, exam_dates.exam_start_time as appointment_time, exam_dates.id as exam_date_id')
            ->where('appointments.facility_id', auth()->user()->facility_id)
            ->where('appointments.permit_application_id', $application_id)
            ->where('exam_dates.application_type_id', 1)
            ->orderBy('appointments.created_at', 'desc')
            ->get();

        $json_appointments = json_encode($appointments);

        $json_application = json_encode($permit_application);

        $appointment_available = [];

        foreach (ExamDates::with('examSites', 'permitCategory')
            ->where('facility_id', auth()->user()->facility_id)
            ->where('application_type_id', 1)
            ->get() as $appointment) {
            $appointment_available[$appointment->id] = strtoupper($appointment->permitCategory?->name) . ' - ' . strtoupper($appointment->exam_day) . ' - ' . strtoupper($appointment->exam_start_time) . ' - ' . strtoupper($appointment->examSites?->name);
        }

        $edit_mode = 1;

        return view('food_handlers_permit.view', compact('json_application', 'json_appointments', 'appointment_available', 'edit_mode'));
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
        $permit_application->firstname = $edits["firstname"];
        $permit_application->middlename = $edits["middlename"];
        $permit_application->lastname = $edits["lastname"];
        $permit_application->address = $edits["address"];
        $permit_application->date_of_birth = $edits["date_of_birth"];
        $permit_application->gender = $edits["gender"];
        $permit_application->cell_phone = $edits["cell_phone"];
        $permit_application->home_phone = $edits["home_phone"];
        $permit_application->work_phone = $edits["work_phone"];
        $permit_application->trn = $edits["trn"];
        $permit_application->email = $edits["email"];

        if ($request->file('photo_upload')) {
            $path = $request->file('photo_upload')->storeAs('photo_uploads', $edits['permit_no'] . '.' . $request->photo_upload->extension(), 'public');
            $permit_application->photo_upload = $path;
        }

        $update_permit_application = PermitApplication::where('id', $edits["id"])->update($permit_application->getAttributes());

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
                        'facility_id' => auth()->user()->id,
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
            return redirect()->route('dashboard.dashboard')->with(['error' => 'Application was not created successfully']);
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
        //
    }
}
