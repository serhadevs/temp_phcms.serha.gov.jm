<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Mail\ForgetPasswordMail;
use Illuminate\Support\Facades\Log;
use App\Mail\SendPermitApplicationMail;
use App\Models\User;
use App\Http\Requests\PermitApplicationRequest;
use App\Models\Appointments;
use App\Models\EditTransactions;
use App\Models\EditTransactionsChangedColumns;
use App\Models\EstablishmentClinics;
use App\Models\ExamDates;
use App\Models\HealthInterview;
use App\Models\HealthInterviewSymptom;
use App\Models\Payments;
use App\Models\PermitApplication;
use App\Models\PermitCategory;
use App\Models\Renewals;
use App\Models\TestResult;
use App\Models\TravelHistory;
use DateTime;
use Exception;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Jobs\SendPermitApplicationEmailJob;
use App\Mail\SendTestEmailConfig;
use App\Http\Controllers\EmailController;
use App\Models\ExamSites;
use App\Notifications\SignOff;
use Illuminate\Support\Facades\Notification;
use App\Models\Messages;
use Illuminate\Support\Facades\URL;
use Barryvdh\DomPDF\Facade\Pdf;

// use Faker\Provider\ar_EG\Payment;

class PermitApplicationController extends Controller
{

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
            $permit_applications = PermitApplication::with('permitCategory', 'payment', 'user', 'establishmentClinics', 'appointment.examDate.examSites', 'signOffs')
                ->whereBetween('created_at', [$filterTimeline, $today])
                ->whereRelation('user', 'facility_id', '=', Auth()->user()->facility_id)
                ->get();
            return view('food_handlers_permit.index', compact('permit_applications'));
        } else if ($id == "7") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        } else if ($id == "30") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        } else if ($id == "90") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        } else if ($id == "180") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-180 days"), "Y-m-d");
        }

        $permit_applications = PermitApplication::with('permitCategory', 'payment', 'user', 'establishmentClinics', 'appointment.examDate.examSites', 'signOffs')
            ->where('created_at', '>', $filterTimeline)
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

        $permit_applications = PermitApplication::with('permitCategory', 'payment', 'user', 'establishmentClinics', 'appointment.examDate.examSites', 'signOffs')
            ->whereBetween('created_at', [$timeline['starting_date'], $timeline['ending_date'] . " 23:59:59"])
            ->whereRelation('user', 'facility_id', '=', Auth()->user()->facility_id)
            ->get();

        return view('food_handlers_permit.index', compact('permit_applications'));
    }

    public function viewApplication(Request $request)
    {
        $application_id = $request->route('id');
        $permit_application = PermitApplication::with('permitCategory', 'payment', 'user', 'establishmentClinics', 'signOffs', 'testResults', 'healthInterviews.healthInterviewSymptom.symptoms', 'appointment.editTransactions', 'messages', 'messages.user', 'printedcard', 'collected_cards', 'signOffs.user:id,firstname,lastname')
            ->find($application_id);
        //dd($permit_application);

        //dd($permit_application);

        $categories = PermitCategory::all();

        $appointments = Appointments::with('examDate.examSites')
            ->where('facility_id', auth()->user()->facility_id)
            ->where('permit_application_id', $application_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $appointment_available = [];
        $app_type_id = 1;
        $system_operation_type_id = 6;

        foreach (
            ExamDates::with('examSites', 'permitCategory')
                ->where('facility_id', auth()->user()->facility_id)
                ->where('application_type_id', 1)
                ->where('permit_category_id', $permit_application->permit_category_id)
                ->get() as $appointment
        ) {
            $appointment_available[$appointment->id] = strtoupper($appointment->permitCategory?->name) . ' - ' . strtoupper($appointment->exam_day) . ' - ' . strtoupper($appointment->exam_start_time) . ' - ' . strtoupper($appointment->examSites?->name);
        }

        // if($permit_application->printedcard && $permit_application->printedcard?->created_at){
        //     $tdbetwappandprint = Carbon::parse($permit_application->application_date)->diffInDays(\Carbon\Carbon::parse($permit_application->printedcard?->created_at));
        // }else{
        //     $tdbetwappandprint = 0;
        // }


        // dd($tdbetwappandprint);

        return view('food_handlers_permit.view', compact('permit_application', 'appointments', 'appointment_available', 'categories', 'app_type_id', 'system_operation_type_id'));
    }

    public function editView(Request $request)
    {
        $application_id = $request->route('id');
        $permit_application = PermitApplication::with('permitCategory', 'payment', 'user', 'establishmentClinics', 'signOffs', 'testResults', 'healthInterviews.healthInterviewSymptom.symptoms', 'appointment.editTransactions')
            ->find($application_id);

        $categories = PermitCategory::all();
        $app_type_id = 1;
        $system_operation_type_id = 6;

        $appointments = Appointments::with('examDate.examSites')
            ->where('facility_id', auth()->user()->facility_id)
            ->where('permit_application_id', $application_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $appointment_available = [];

        foreach (
            ExamDates::with('examSites', 'permitCategory')
                ->where('facility_id', auth()->user()->facility_id)
                ->where('application_type_id', 1)
                ->where('permit_category_id', $permit_application->permit_category_id)
                ->get() as $appointment
        ) {
            $appointment_available[$appointment->id] = strtoupper($appointment->permitCategory?->name) . ' - ' . strtoupper($appointment->exam_day) . ' - ' . strtoupper($appointment->exam_start_time) . ' - ' . strtoupper($appointment->examSites?->name);
        }

        $edit_mode = 1;

        return view('food_handlers_permit.view', compact('permit_application', 'appointments', 'appointment_available', 'edit_mode', 'categories', 'app_type_id', 'system_operation_type_id'));
    }

    public function updateApplication(Request $request, $id)
    {
        $edits = $request->validate([
            'firstname' => "required",
            'middlename' => "nullable",
            'lastname' => "required",
            'address' => 'required',
            'date_of_birth' => 'required',
            'gender' => 'required',
            'cell_phone' => 'nullable|regex:/^[0-9]{1}\({1}[0-9]{3}\)[0-9]{3}\-[0-9]{4}+$/',
            'home_phone' => 'nullable|regex:/^[0-9]{1}\({1}[0-9]{3}\)[0-9]{3}\-[0-9]{4}+$/',
            'work_phone' => 'nullable|regex:/^[0-9]{1}\({1}[0-9]{3}\)[0-9]{3}\-[0-9]{4}+$/',
            'trn' => 'nullable|regex:/^[0-9]{3}\-[0-9]{3}\-[0-9]{3}+$/',
            'email' => 'nullable',
            'permit_no' => 'required',
            'photo_upload' => 'nullable',
            'permit_category_id' => 'required',
            'permit_type' => 'required',
            'edit_reason' => 'required',
            'no_of_years' => 'required_if:permit_type,student'
        ]);
        try {
            if ($permit = PermitApplication::with('signOffs', 'user')
                ->whereRelation('user', 'facility_id', '=', auth()->user()->facility_id)
                ->find($id)
            ) {
                if (empty($permit->signOffs)) {
                    unset($edits['edit_reason']);
                    // if ($request->file('photo_upload')) {
                    //     if ($permit->photo_upload) {
                    //         Storage::disk('public')->move($permit->photo_upload, '/photo_uploads/archives/' . explode('/', $permit->photo_upload)[1]);
                    //     }
                    //     $path = $request->file('photo_upload')->storeAs('photo_uploads', $permit->permit_no . '.' . $request->photo_upload->extension(), 'public');
                    //     $edits['photo_upload'] = $path;
                    // } else {
                    //     
                    // }
                    if ($request->file('photo_upload')) {
                        if ($permit->photo_upload) {
                            $old_image_name = explode('.', explode('/', $permit->photo_upload)[1]);
                            $new_image_name = $old_image_name[0] . '_' . time() . '_' . $permit->id . '.' . $old_image_name[1];
                            Storage::disk('public')->move($permit->photo_upload, '/photo_uploads/archives/' . $new_image_name);
                        }
                        $path = $request->file('photo_upload')->storeAs('photo_uploads', $permit['permit_no'] . '.' . $request->photo_upload->extension(), 'public');
                        $edits['photo_upload'] = $path;

                        if ($edits['photo_upload'] == '0') {
                            $file = $request->file('photo_upload');
                            $edits['photo_upload'] = 'photo_uploads/' . $edits['permit_no'] . '.' . $file->extension();
                        }
                    } else {
                        $edits['photo_upload'] = $permit->photo_upload;
                    }

                    $permit_array_used_compare = PermitApplication::where('id', $id)->select('firstname', 'middlename', 'lastname', 'address', 'date_of_birth', 'gender', 'cell_phone', 'home_phone', 'work_phone', 'trn', 'email', 'permit_no', 'photo_upload', 'permit_category_id', 'permit_type', 'no_of_years')->first()->toArray();

                    if (isset($new_image_name)) {
                        $permit_array_used_compare['photo_upload'] = 'photo_uploads/archives/' . $new_image_name;
                    }

                    if (!empty($differences = array_diff_assoc($edits, $permit_array_used_compare))) {
                        DB::beginTransaction();
                        if ($edit_transaction = EditTransactions::create([
                            'application_type_id' => 1,
                            'table_id' => $id,
                            'system_operation_type_id' => 1,
                            'edit_type_id' => 1,
                            'user_id' => auth()->user()->id,
                            'facility_id' => auth()->user()->facility_id,
                            'reason' => $request->edit_reason
                        ])) {
                            foreach ($differences as $key => $difference) {
                                EditTransactionsChangedColumns::create([
                                    'edit_transaction_id' => $edit_transaction->id,
                                    'column_name' => $key,
                                    'old_value' => $key == 'permit_category_id' ? PermitCategory::find($permit?->permit_category_id)->name : ($key == 'photo_upload' ? $permit_array_used_compare[$key] : $permit->toArray()[$key]),
                                    'new_value' => $key == 'permit_category_id' ? PermitCategory::find($edits['permit_category_id'])->name : $edits[$key]
                                ]);
                            }
                            if ($permit->update($edits)) {
                                DB::commit();
                                if (str_contains($request->previous_url, 'food-handlers-clinics')) {
                                    return redirect()->route('food-handlers-clinics.view', ['id' => explode("view/", $request->previous_url)[1]])->with(['success' => 'Applicant ' . $edits["firstname"] . ' ' . $edits["lastname"] . ':' . $permit->id . ' has be updated successfully']);
                                }
                                return redirect()->route('permit.application.view', ['id' => $permit->id])->with(['success' => 'Applicant ' . $edits["firstname"] . ' ' . $edits["lastname"] . ':' . $permit->id . ' has be updated successfully']);
                            } else {
                                throw new Exception("Error updating Food Handlers Application. Unable to update Permit Application.");
                            }
                        } else {
                            throw new Exception("Error updating Food Handlers Permit. Unable to create Edit Transaction.");
                        }
                    } else {
                        throw new Exception('You did not edit any field on this application.');
                    }
                } else {
                    throw new Exception('This Food Handlers Application has already been signed off. It cannot be updated.');
                }
            } else {
                throw new Exception('This Food Handlers Application does not exist or does not belong to your facility.');
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updatePermitAppointment(Request $request, $id)
    {
        try {
            if ($appointment = Appointments::find($id)) {
                if ($application = PermitApplication::with('user')
                    ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                    ->find($appointment->permit_application_id)
                ) {
                    if ($application->sign_off_status != '1') {
                        if ($appointment->exam_date_id != $request->data['exam_date_id'] || $appointment->appointment_date != $request->data['appointment_date']) {
                            DB::beginTransaction();
                            if ($edit_transaction = EditTransactions::create([
                                'application_type_id' => 1,
                                'table_id' => $id,
                                'system_operation_type_id' => 6,
                                'edit_type_id' => 1,
                                'user_id' => auth()->user()->id,
                                'facility_id' => auth()->user()->facility_id,
                                'reason' => $request->data['edit_reason']
                            ])) {
                                if ($appointment->exam_date_id != $request->data['exam_date_id']) {
                                    if (!EditTransactionsChangedColumns::create([
                                        'edit_transaction_id' => $edit_transaction->id,
                                        'column_name' => "exam_date_id",
                                        'old_value' => ExamDates::withTrashed()->find($appointment->exam_date_id)?->exam_day . ' ' . ExamDates::withTrashed()->find($appointment->exam_date_id)?->exam_start_time . ' - ' . ExamSites::withTrashed()->find(ExamDates::withTrashed()->find($appointment->exam_date_id)->exam_site_id)?->name,
                                        'new_value' => ExamDates::find($request->data['exam_date_id'])?->exam_day . ' ' . ExamDates::find($request->data['exam_date_id'])?->exam_start_time . ' - ' . ExamSites::find(ExamDates::find($request->data['exam_date_id'])->exam_site_id)?->name
                                    ])) {
                                        throw new Exception("Error updating appointment. Unable to record field changed.");
                                    }
                                }
                                if ($appointment->appointment_date != $request->data['appointment_date']) {
                                    if (!EditTransactionsChangedColumns::create([
                                        'edit_transaction_id' => $edit_transaction->id,
                                        'column_name' => "appointment_date",
                                        'old_value' => $appointment->appointment_date,
                                        'new_value' => $request->data['appointment_date']
                                    ])) {
                                        throw new Exception("Error updating appointment. Unable to record field changed.");
                                    }
                                }
                                if ($appointment->update(
                                    [
                                        'appointment_date' => $request->data["appointment_date"],
                                        'exam_date_id' => $request->data["exam_date_id"]
                                    ]
                                )) {
                                    DB::commit();
                                    return [
                                        'success',
                                        'Appointment for ' . $application->firstname . ' ' . $application->lastname . ':' . $application->id . ' has been updated successfully.'
                                    ];
                                } else {
                                    throw new Exception("Error updating appointment. Unable to update record.");
                                }
                            } else {
                                throw new Exception("Error updating appointment. Unable to initiate transaction");
                            }
                        } else {
                            throw new Exception("None of the values were changed. Nothing to update");
                        }
                    } else {
                        throw new Exception("Permit Application has already been signed off. Appointment cannot be signed off.");
                    }
                } else {
                    throw new Exception("The application for this appointment either does not exist or does not belong to your facility.");
                }
            } else {
                throw new Exception("This appointment does not exist.");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
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
        $appointments_available = ExamDates::join('exam_sites', 'exam_dates.exam_site_id', '=', 'exam_sites.id')
            ->join('permit_categories', 'permit_categories.id', '=', 'exam_dates.permit_category_id')
            ->where('exam_dates.facility_id', auth()->user()->facility_id)
            ->select('exam_dates.id', 'permit_category_id', 'exam_day', 'exam_start_time', 'exam_sites.name as site_name', 'permit_categories.name as category_name')
            // ->orderByRaw('DAY(exam_dates.exam_day)')
            ->where('application_type_id', 1)
            ->get();

        //dd($appointments_available[0]);
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
    public function store(PermitApplicationRequest $request)
    {

        $user = User::where('role_id', 1)->get();
        $permit_application = $request->validated();

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

            if ($permit_application['photo_upload'] == '0') {
                $file = $request->file('photo_upload');
                $permit_application['photo_upload'] = 'photo_uploads/' . $permit_application['permit_no'] . '.' . $file->extension();
            }
        } else {
            $permit_application['photo_upload'] = "";
        }

        //Checks if there is an application that exists already to prevent duplicates
        $exists = PermitApplication::where([
            ['firstname', '=', $permit_application['firstname']],
            ['lastname', '=', $permit_application['lastname']],
            ['date_of_birth', '=', $permit_application['date_of_birth']],
            ['cell_phone', '=', $permit_application['cell_phone']],
        ])
            ->where('created_at', '>', date_format(new DateTime(), 'Y-m-d'))
            ->exists();

        //dd($exists);

        if ($exists) {
            // If an exact match is found, return with an error message
            return redirect()->route('dashboard.dashboard')->with('error', 'An applicant exists with the same details.');
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
                //dd($new_appointment);
                if (!$new_appointment) {
                    return redirect()->route('permit.index', ['id' => 0])->with('error', 'Appointment was not created successfully');
                }
            }

            //Appointment email function. 
            $sendAppointmentMail = new Services();


            if (empty($request->establishment_clinic_id) || $est_clinic->permits_count == $est_clinic->no_of_employees) {
                $sendAppointmentMail->sendAppointmentEmail($new_permit_application);
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

    public function storeRenewal(PermitApplicationRequest $request)
    {
        $permit_application = $request->validated();

        $old_permit = PermitApplication::find($request->old_application_id);
        DB::beginTransaction();
        $permit_application['permit_no'] = $old_permit->permit_no;

        $permit_application['user_id'] = Auth()->user()->id;
        if ($request->file('photo_upload')) {
            if ($old_permit->photo_upload) {
                $old_image_name = explode('.', explode('/', $old_permit->photo_upload)[1]);
                $new_image_name = $old_image_name[0] . '_' . time() . '_' . $old_permit->id . '.' . $old_image_name[1];
                Storage::disk('public')->move($old_permit->photo_upload, '/photo_uploads/archives/' . $new_image_name);
            }
            $path = $request->file('photo_upload')->storeAs('photo_uploads', $permit_application['permit_no'] . '.' . $request->photo_upload->extension(), 'public');
            $permit_application['photo_upload'] = $path;

            if ($permit_application['photo_upload'] == '0') {
                $file = $request->file('photo_upload');
                $permit_application['photo_upload'] = 'photo_uploads/' . $permit_application['permit_no'] . '.' . $file->extension();
            }
        } else {
            $permit_application['photo_upload'] = $old_permit->photo_upload;
        }
        // if ($request->file('photo_upload')) {
        //     $path = $request->file('photo_upload')->storeAs('photo_uploads', $permit_application['permit_no'] . '.' . $request->photo_upload->extension(), 'public');
        //     $permit_application['photo_upload'] = $path;
        // } else {
        //     $permit_application['photo_upload'] = $old_permit->photo_upload;
        // }

        if (PermitApplication::create($permit_application)) {
            $now = Carbon::now()->toDateTimeString();
            HealthInterview::where('permit_application_id', $request->old_application_id)->update([
                'deleted_at' => $now
            ]);

            TestResult::where('application_id', $request->old_application_id)->where('application_type_id', 1)->update([
                'deleted_at' => $now
            ]);

            Appointments::where('permit_application_id', $request->old_application_id)->update([
                'deleted_at' => $now
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
                    'deleted_at' => $now
                ]);
            }
        }
        DB::commit();
        return redirect()->route('permit.index', ['id' => 0])->with('success', 'Renewal has been completed successfully. The Application Id is' . $new_application->id);
    }

    public function addNewAppointment(Request $request)
    {
        try {
            if (Appointments::create([
                'appointment_date' => $request->data['appointment_date'],
                'facility_id' => auth()->user()->facility_id,
                'permit_application_id' => $request->data['permit_application_id'],
                'exam_date_id' => $request->data['exam_date_id'],
            ])) {
                return [
                    "success",
                    "Appointment has been added for " . $request->appointment_date
                ];
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function printInspection($id)
    // {
    //     try {
    //         //Get the inspection to be printed 
    //         $inspection = Inspection::where('entry_number_inspections', $id)
    //             ->join("establishment_master", "establishment_master.establishment_id", "=", "sanitized_inspections.establishment_id")
    //             ->where('sanitized_inspections.entry_number_inspections', $id)->firstOrFail();

    //         //Pass the inspection details to the view for the pdf
    //         $inspectionData = [
    //             'inspection' => $inspection
    //         ];

    //         $pdf = PDF::loadView('inspections.inspectionPDF', $inspectionData);

    //         // Check if PDF is successfully generated
    //         if (!$pdf) {
    //             return view('inspections.view')->with('error', 'Unable to generate PDF for ' . $inspection->establishment_name);
    //         }

    //         // Add the establishment name and the id to the pdf before it is downloaded
    //         $fileName = strval($inspection->establishment_name) . '_' . strval($inspection->establishment_id) . '.pdf';

    //         // Show the pdf in the browser
    //         return $pdf->stream($fileName);
    //     } catch (ModelNotFoundException $e) {
    //         // Handle the case when the inspection is not found
    //         return view('inspections.view')->with('error', 'Inspection not found for ID ' . $id);
    //     } catch (\Exception $e) {
    //         // Handle other exceptions
    //         return view('inspections.view')->with('error', 'An error occurred: ' . $e->getMessage());
    //     }
    // }




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
    public function destroy(Request $request, $id)
    {
        try {
            if ($permit = PermitApplication::with('payment', 'appointment', 'testResults', 'healthInterviews.healthInterviewSymptom', 'travelHistory')
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->find($id)
            ) {
                if ($permit->sign_off_status != '1') {
                    DB::beginTransaction();
                    if (EditTransactions::create([
                        'application_type_id' => 1,
                        'table_id' => $id,
                        'system_operation_type_id' => 1,
                        'edit_type_id' => 2,
                        'user_id' => auth()->user()->id,
                        'facility_id' => auth()->user()->facility_id,
                        'reason' => $request->data['reason']
                    ])) {
                        if (!empty($permit->appointment->first())) {
                            if (!Appointments::where('permit_application_id', $id)->first()->update(['deleted_at' => date('Y-m-d H:i:s')])) {
                                throw new Exception("Delete Operation failed. Unable to delete appointment created for this application.");
                            }
                        }
                        if (!empty($permit->testResults)) {
                            if (!TestResult::find($permit->testResults?->id)->update(['deleted_at' => date('Y-m-d H:i:s')])) {
                                throw new Exception("Delete operation failed. System was unable to delete Test Results");
                            }
                        }
                        if (!empty($permit->healthInterviews)) {
                            foreach ($permit->healthInterviews?->healthInterviewSymptom as $sym) {
                                if (!HealthInterviewSymptom::find($sym->id)->update(['deleted_at' => date('Y-m-d H:i:s')])) {
                                    throw new Exception("Delete operation failed. Unable to delete symptom added in health interview");
                                }
                            }
                            if (!HealthInterview::find($permit->healthInterviews?->id)->update(['deleted_at' => date('Y-m-d H:i:s')])) {
                                throw new Exception('Delete operation failed. Unable to delete health interviews.');
                            }
                        }
                        if (!empty($permit->travelHistory)) {
                            foreach ($permit->travelHistory as $travel) {
                                if (!TravelHistory::find($travel->id)->update(['deleted_at' => date('Y-m-d H:i:s')])) {
                                    throw new Exception('Delete operation failed. Unable to delete travel history');
                                }
                            }
                        }
                        //Add delete for messages
                        if ($permit->update(['deleted_at' => date('Y-m-d H:i:s')])) {
                            DB::commit();
                            return [
                                'success',
                                'Permit Application for ' . $permit->firstname . ' ' . $permit->lastname . ':' . $permit->id . ' has been deleted successfully.'
                            ];
                        } else {
                            throw new Exception("Delete Operation Failed. Failed to delete application.");
                        }
                    } else {
                        throw new Exception('Delete operation failed. Failed to create transaction');
                    }
                } else {
                    throw new Exception('This application has already been signed off. It therefore cannot be deleted');
                }
            } else {
                throw new Exception("This permit application does not exist or does not belong to your facility.");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function printApplication($id)
    {
        try {
            $permit_application = PermitApplication::with([
                'permitCategory',
                'payment',
                'user',
                'establishmentClinics',
                'signOffs',
                'testResults',
                'healthInterviews.healthInterviewSymptom.symptoms',
                'appointment.editTransactions'
            ])
                ->findOrFail($id);

            //dd($permit_application);

            // Convert the object to an array that can be passed to the view
            $imagePath = storage_path('app/public/' . $permit_application->photo_upload);
            $imageData = base64_encode(file_get_contents($imagePath));
            $imageSrc = 'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . $imageData;
            $pdf = Pdf::loadView('pdf.form', ['permit_application' => $permit_application],['imageSrc' => $imageSrc]);

            $filename = $permit_application->firstname . '_' . $permit_application->lastname . '_' . $permit_application->id . '.pdf';
            return $pdf->stream($filename);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unable to generate PDF: ' . $e->getMessage());
        }
    }
}
