<?php

namespace App\Http\Controllers;

use App\Models\ApplicationType;
use App\Models\Appointment;
use App\Models\BarbershopHairSalons;
use App\Models\EstablishmentApplications;
use App\Models\EstablishmentClinics;
use App\Models\Facility;
use App\Models\HealthCertApplications;
use App\Models\PaymentCancellationRequests;
use App\Models\Payments;
use App\Models\PermitApplication;
use App\Models\SwimmingPoolsApplications;
use App\Models\TouristEstablishments;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use Illuminate\Support\Facades\Validator;
use Exception;

class PaymentController extends Controller
{
    public function index()
    {

        // $today = date('Y-m-d');
        // //Set Faculty ID
        // //Disappear when paid
        // $permit_applications = DB::table('permit_applications')->selectRaw('id as app_number, concat(firstname, " ", lastname) as name, permit_no,trn')->where('created_at', '>', $today)->get();
        // $json_applications = json_encode($permit_applications);

        // return view('payments.applications', compact('json_applications'));
    }

    public function filterProcessedPayments(Request $request)
    {
        $applications = [];
        date_default_timezone_set('Etc/GMT+5');
        $today = date_format(new Datetime(), "Y-m-d");
        $yesterday = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
        $last_week = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        $thirty_days = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        $last_ninety_days = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");

        $filterTimeline = "";
        $id = $request->route('id');

        if ($id == "0") {
            $filterTimeline = $today;
            $payments = DB::table('payments')
                ->join('application_types', 'application_types.id', '=', 'payments.application_type_id')
                ->join('payment_cancellation_requests', 'payment_cancellation_requests.payment_id', '=', 'payments.id', 'left outer')
                ->selectRaw('application_types.name as name, payments.application_id, payments.receipt_no, payments.total_cost, payments.amount_paid, payments.change_amt, payments.id as payment_id, payments.created_at, payment_cancellation_requests.id as cancellation_id, payment_cancellation_requests.approved as cancellation_approved_status')
                ->where('payments.created_at', '>', $today)
                ->where('payments.facility_id', Auth()->user()->facility_id)
                ->where('payments.deleted_at', NULL)
                ->get();

            $payments_info = json_encode($payments);
            return view('payments.index', compact('payments_info'));
        } else if ($id == "1") {
            $filterTimeline = $yesterday;
        } else if ($id == "7") {
            $filterTimeline = $last_week;
        } else if ($id == "30") {
            $filterTimeline = $thirty_days;
        } else if ($id == "90") {
            $filterTimeline = $last_ninety_days;
        }

        $payments = DB::table('payments')
            ->join('application_types', 'application_types.id', '=', 'payments.application_type_id')
            ->join('payment_cancellation_requests', 'payment_cancellation_requests.payment_id', '=', 'payments.id', 'left outer')
            ->selectRaw('application_types.name as name, payments.application_id, payments.receipt_no, payments.total_cost, payments.amount_paid, payments.change_amt, payments.id as payment_id, payments.created_at, payment_cancellation_requests.id as cancellation_id, payment_cancellation_requests.approved as cancellation_approved_status')
            ->whereBetween('payments.created_at', [$filterTimeline, $today])
            ->where('payments.facility_id', Auth()->user()->facility_id)
            ->where('payments.deleted_at', NULL)
            ->get();

        $payments_info = json_encode($payments);

        return view('payments.index', compact('payments_info'));
    }

    public function customFilterProcessedPayments(Request $request)
    {
        date_default_timezone_set('Etc/GMT+5');
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'interval' => 'nullable|numeric|max:6'
        ]);

        $payments = DB::table('payments')
            ->join('application_types', 'application_types.id', '=', 'payments.application_type_id')
            ->join('payment_cancellation_requests', 'payment_cancellation_requests.payment_id', '=', 'payments.id', 'left outer')
            ->selectRaw('application_types.name as name, payments.application_id, payments.receipt_no, payments.total_cost, payments.amount_paid, payments.change_amt, payments.id as payment_id, payments.created_at, payment_cancellation_requests.id as cancellation_id, payment_cancellation_requests.approved as cancellation_approved_status')
            ->whereBetween('payments.created_at', [$timeline['starting_date'], $timeline['ending_date']])
            ->where('payments.facility_id', Auth()->user()->facility_id)
            ->where('payments.deleted_at', NULL)
            ->get();

        $payments_info = json_encode($payments);

        return view('payments.index', compact('payments_info'));
    }

    public function detApplicationType($id)
    {
        $application_type = DB::table('application_types')
            ->join('prices', 'prices.application_type_id', '=', 'application_types.id')
            ->where('application_types.id', $id)
            ->selectRaw('application_types.id, application_types.name, prices.price')
            ->get();
        //dd($application_type[0]);
        return $application_type[0];
    }

    public function convertToArray($permit_applications)
    {
        $applications = [];
        $i = 0;
        foreach ($permit_applications as $application) {
            $applications[$i]["app_number"] = $application->app_number;
            $applications[$i]["name"] = $application->name;
            $applications[$i]["permit_no"] = $application->permit_no;
            $applications[$i]["trn"] = $application->trn;
            $applications[$i]["price"] = $application->permit_type == "regular" ? $this->detApplicationType(1)->price : ($application->permit_type == "student" ? $this->detApplicationType(8)->price : ($application->permit_type == "teacher" ? $this->detApplicationType(9)->price : ""));
            $applications[$i]["app_type"] = $application->permit_type == "regular" ? $this->detApplicationType(1)->name : ($application->permit_type == "student" ? $this->detApplicationType(8)->name : ($application->permit_type == "teacher" ? $this->detApplicationType(9)->name : ""));
            $applications[$i]["app_type_id"] = $application->permit_type == "regular" ? $this->detApplicationType(1)->id : ($application->permit_type == "student" ? $this->detApplicationType(8)->id : ($application->permit_type == "teacher" ? $this->detApplicationType(9)->id : ""));
            $i++;
        }

        return json_encode($applications);
    }


    public function filterOutstandingPayments(Request $request)
    {
        //Union with all the types of applications

        $applications = [];
        date_default_timezone_set('Etc/GMT+5');
        $today = date_format(new Datetime(), "Y-m-d");
        $yesterday = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
        $last_week = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        $thirty_days = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        $last_ninety_days = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");

        $filterTimeline = "";
        $id = $request->route('id');

        if ($id == "0") {
            $filterTimeline = $today;
            $permit_applications = DB::table('permit_applications')
                ->join('users', 'users.id', '=', 'permit_applications.user_id')
                ->join('payments', 'payments.application_id', '=', 'permit_applications.id', 'left outer')
                ->selectRaw('permit_applications.id as app_number, concat(permit_applications.firstname, " ", permit_applications.lastname) as name, permit_applications.permit_no, permit_applications.trn, permit_applications.permit_type, users.firstname')
                ->where('users.facility_id', auth()->user()->facility_id)
                ->whereNull('payments.id')
                ->where('permit_applications.created_at', '>', $today)
                ->get();

            $json_applications = $this->convertToArray($permit_applications);
            return view('payments.applications', compact('json_applications'));
        } else if ($id == "1") {
            $filterTimeline = $yesterday;
        } else if ($id == "7") {
            $filterTimeline = $last_week;
        } else if ($id == "30") {
            $filterTimeline = $thirty_days;
        } else if ($id == "90") {
            $filterTimeline = $last_ninety_days;
        }

        $permit_applications = DB::table('permit_applications')
            ->join('users', 'users.id', '=', 'permit_applications.user_id')
            ->join('payments', 'payments.application_id', '=', 'permit_applications.id', 'left outer')
            ->selectRaw('permit_applications.id as app_number, concat(permit_applications.firstname, " ", permit_applications.lastname) as name, permit_applications.permit_no, permit_applications.trn, permit_applications.permit_type, users.firstname')
            ->where('users.facility_id', auth()->user()->facility_id)
            ->whereNull('payments.id')
            ->whereBetween('permit_applications.created_at', [$filterTimeline, $today])
            ->where('permit_applications.deleted_at', NULL)
            ->get();

        $json_applications = $this->convertToArray($permit_applications);
        return view('payments.applications', compact('json_applications'));
    }



    public function customFilterOutstandingPayments(Request $request)
    {
        date_default_timezone_set('Etc/GMT+5');
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'interval' => 'nullable|numeric|max:6'
        ]);

        $permit_applications = DB::table('permit_applications')
            ->join('users', 'users.id', '=', 'permit_applications.user_id')
            ->join('payments', 'payments.application_id', '=', 'permit_applications.id', 'left outer')
            ->selectRaw('permit_applications.id as app_number, concat(permit_applications.firstname, " ", permit_applications.lastname) as name, permit_applications.permit_no, permit_applications.trn, permit_applications.permit_type, users.firstname')
            ->where('users.facility_id', auth()->user()->facility_id)
            ->whereNull('payments.id')
            ->where('permit_applications.deleted_at', NULL)
            ->whereBetween('permit_applications.created_at', [$timeline['starting_date'], $timeline['ending_date']])->get();

        $json_applications = $this->convertToArray($permit_applications);

        return view('payments.applications', compact('json_applications'));
    }

    public function create()
    {
        $application_types = DB::table('application_types')
            ->join('prices', 'prices.application_type_id', '=', 'application_types.id')
            ->select()
            ->get();
        return view('payments.create', compact('application_types'));
    }

    public function createFromApplication(Request $request)
    {
        $app_id = $request->route('app_id');
        $app_type = $request->route('app_t_id');
        // dd($app_id, $app_type);

        $application_types = DB::table('application_types')
            ->join('prices', 'prices.application_type_id', '=', 'application_types.id')
            ->select()
            ->get();
        return view('payments.create', compact('application_types', 'app_id', 'app_type'));
    }

    public function printReceipt(Request $request)
    {
        $payment_id = $request->route('id');
        $payment = Payments::find($payment_id);

        // dd($payment);
        if ($payment->application_type_id == 1 || $payment->application_type_id == 8 || $payment->application_type_id == 9) {
            $receipt_info['app_type'] = ApplicationType::find($payment->application_type_id)->name;
            $application = PermitApplication::with('permitCategory')->find($payment->application_id);
            $receipt_info['applicant_name'] = $application->firstname . " " . $application->lastname;
            $receipt_info['permit_category'] = $application->permitCategory?->name;
            $appointment = DB::table('appointments')
                ->join('exam_dates', 'exam_dates.id', '=', 'appointments.exam_date_id')
                ->join('exam_sites', 'exam_sites.id', '=', 'exam_dates.exam_site_id')
                ->where('appointments.facility_id', auth()->user()->facility_id)
                ->where('appointments.permit_application_id', $payment->application_id)
                ->where('exam_dates.application_type_id', 1)
                ->orderBy('appointments.created_at', 'desc')
                ->first();

            // dd($appointment);
            if ($appointment) {
                $receipt_info['appointment_date'] = date_format(new DateTime($appointment->appointment_date), 'M-d-Y') . " " . $appointment->exam_start_time;
                $receipt_info['exam_site'] = $appointment->name;
            } else if ($application->establishment_clinic_id) {
                $establishment_clinic = DB::table('establishment_clinics')
                    ->where('id', $application->establishment_clinic_id)
                    ->get();
                $receipt_info['appointment_data'] = date_format(new DateTime($establishment_clinic?->proposed_date), "Y-m-d") . " " . $establishment_clinic->proposed_time;
                $receipt_info['exam_site'] = $establishment_clinic->name . " " . $establishment_clinic->address;
            }
        }

        $receipt_info['application_no'] = $payment->application_id;
        $receipt_info['payment_date_time'] = $payment->created_at->format('M-d-Y h:i:s a');
        $receipt_info['facility'] = Facility::find($payment->facility_id)->name;
        $receipt_info['receipt_no'] = $payment->receipt_no;
        $receipt_info['total_cost'] = $payment->total_cost;
        $receipt_info['amount_paid'] = $payment->amount_paid;
        $receipt_info['change_amt'] = $payment->change_amt;
        $cashier = User::find($payment->cashier_user_id);
        $receipt_info['cashier'] = $cashier->firstname[0] . ". " . $cashier->lastname;

        return view('payments.receipt', compact('receipt_info'));
    }

    public function registerNewPayment(Request $request)
    {
        // date_default_timezone_set('Etc/GMT+5');
        //Write API
        //Limit input to numbers regex
        //Pattern 
        //Success Message => Done
        //Check if application id exists => Done
        //Check if receipt number exists => New Schema for this
        $new_payment = $request->validate(
            [
                'application_type_id' => 'required',
                'application_id' => 'required',
                'amount_paid' => 'required',
                'total_cost' => 'required',
                'change_amt' => 'required|numeric|min:0'
            ]
        );
        $app_id = $new_payment['application_id'];
        $app_type = $new_payment['application_type_id'];

        $id_exists = $app_type == 1 ? PermitApplication::find($app_id) : ($app_type == 2 ? HealthCertApplications::find($app_id) : ($app_type == 3 ? EstablishmentApplications::find($app_id) : ($app_type == 4 ? EstablishmentClinics::find($app_id) : ($app_type == 5 ? SwimmingPoolsApplications::find($app_id) : ($app_type == 6 ? TouristEstablishments::find($app_id) : ($app_type == 7 ? BarbershopHairSalons::find($app_id) : ($app_type == 8 ? PermitApplication::find($app_id) : PermitApplication::find($app_id))))))));

        $app_paid = Payments::where('application_type_id', $app_type)->where('application_id', $app_id)->get();
        // dd($app_paid);
        if (!$id_exists) {
            return redirect()->back()->with(['error' => 'This application number does not exist in the system.']);
        }

        if (!$app_paid->isEmpty()) {
            return redirect()->back()->with(['error' => 'This application has already been paid for.']);
        }

        //Another Check for facility id.-

        $new_payment['facility_id'] = Auth()->user()->facility_id;
        $new_payment['cashier_user_id'] = Auth()->user()->id;
        $new_payment['receipt_no'] = rand(1000000, 9999999);
        $register_new_payment = Payments::create($new_payment);

        if (!$register_new_payment) {
        }

        // dd($register_new_payment->getOriginal()["id"]);

        //Email Reciept 
        
        return redirect()->route('payment.receipt.print', ['id' => $register_new_payment->getOriginal()["id"]])->with(['success' => 'Payment has been process successfully. The receipt number is ' . $new_payment["receipt_no"] . '']);
    }

    public function searchApplication(Request $request)
    {
        $application_id = $request->route('app_id');
        $application_type_id = $request->route('app_t_id');

        if ($application_type_id == 1 || $application_type_id == 8 || $application_type_id == 9) {
            $output = "";
            $results = DB::table('permit_applications')
                ->join('permit_categories', 'permit_categories.id', '=', 'permit_applications.permit_category_id')
                ->where('permit_applications.id', $application_id)
                // ->where('permit_applications.deleted_at', '=', NULL)
                // ->where('permit_applications.sign_off_status', '=', 1)
                ->get();

            $appointments = DB::table('appointments')
                ->where('appointments.permit_application_id', '=', $application_id)
                ->join('exam_dates', 'appointments.exam_date_id', '=', 'exam_dates.id')
                ->join('exam_sites', 'exam_dates.exam_site_id', '=', 'exam_sites.id')
                ->get();

            if ($results->isEmpty()) {
                $output = '<h4>Application Information</h4><p class="text-danger">Data Not found</p>';
                echo $output;
            } else {
                $output = "<h4>Application Info</h4>";
                foreach ($results as $result) {
                    $output .= "<p>Application Type: Food Handler Permit</p>";
                    $output .= "<p>Permit Category: " . $result->name . "</p>";
                    $output .= "<p>First Name: " . $result->firstname . "</p>";
                    $output .= "<p>Last Name: " . $result->lastname . "</p>";
                    $output .= "<p>Gender: " . $result->gender . "</p>";
                    $output .= "<p>Date of Birth :" . $result->date_of_birth . "</p>";
                    $output .= "<p>Address: " . $result->address . "</p>";
                    foreach ($appointments as $appointment) {
                        $app_date = $appointment->appointment_date;
                        $app_location_name = $appointment->name;
                    }
                    $output .= "<p>Appointment Date: " . $app_date . "<p>";
                    $output .= "<p>Appointment Location: " . $app_location_name . "<p>";
                    $output .= $this->paymentStatus($application_id, $application_type_id);
                }
                echo $output;
            }
        } else if ($application_type_id == 2) {
            $output = "";
            $results = DB::table('health_cert_applications')
                ->where('id', '=', $application_id)
                ->get();

            if ($results->isEmpty()) {
                $output = '<h4>Application Information</h4><p class="text-danger">Data Not found</p>';
                echo $output;
            } else {
                $output = $output = "<h4>Application Info</h4>";
                foreach ($results as $result) {
                    $output .= "<p>Application Type; Barbers & Cosmet. etc.</p>";
                    $output .= "<p>First Name: " . $result->firstname . "</p>";
                    $output .= "<p>Last Name: " . $result->lastname . "</p>";
                    $output .= "<p>Gender: " . $result->sex . "</p>";
                    $output .= "<p>Date of Birth :" . $result->date_of_birth . "</p>";
                    $output .= "<p>Address: " . $result->address . "</p>";
                    $output .= $this->paymentStatus($application_id, $application_type_id);
                }
                echo $output;
            }
        } else if ($application_type_id == 3) {
            $output = "";
            $results = DB::table('establishment_applications')
                ->join('establishment_categories', 'establishment_categories.id', '=', 'establishment_applications.establishment_category_id')
                ->where('establishment_applications.id', '=', $application_id)
                ->get();

            if ($results->isEmpty()) {
                $output = '<h4>Application Information</h4><p class="text-danger">Data Not found</p>';
                echo $output;
            } else {
                $output = $output = "<h4>Application Info</h4>";
                foreach ($results as $result) {
                    $output .= "<p>Application Type : $result->establishment_name</p>";
                    $output .= "<p>Establishment Name : $result->establishment_name</p>";
                    $output .= "<p>Establishment Address : $result->establishment_address</p>";
                    $output .= "<p>Food Type : $result->food_type</p>";
                    $output .= "<p>Establishment Category: $result->name</p>";
                    $output .= "<p>Operator Names: </p>";
                    $output .= $this->paymentStatus($application_id, $application_type_id);
                }
                echo $output;
            }
        } else if ($application_type_id == 4) {
            $output = "";
            $results = DB::table('establishment_clinics')
                ->where('id', '=', $application_id)
                ->get();

            if ($results->isEmpty()) {
                $output = '<h4>Application Information</h4><p class="text-danger">Data Not found</p>';
                echo $output;
            } else {
                $output = $output = "<h4>Application Info</h4>";
                foreach ($results as $result) {
                    $output .= "<p>Application Type: Food Handler Clinic</p>";
                    $output .= "<p>Name: $result->name</p>";
                    $output .= "<p>Address: $result->address</p>";
                    $output .= "<p>Contact Person: $result->contact_person</p>";
                    $output .= "<p>Number of Employees: $result->no_of_employees</p>";
                    $output .= "<p>Proposed Date & Time: $result->proposed_date $result->proposed_time</p>";
                    $output .= $this->paymentStatus($application_id, $application_type_id);
                }
                echo $output;
            }
        } else if ($application_type_id == 5) {
            $output = "";
            $results = DB::table('swimming_pools_applications')
                ->where('id', '=', $application_id)
                ->get();

            if ($results->isEmpty()) {
                $output = '<h4>Application Information</h4><p class="text-danger">Data Not found</p>';
                echo $output;
            } else {
                $output = $output = "<h4>Application Info</h4>";
                foreach ($results as $result) {
                    $output .= "<p>Application Type: Swimming Pools</p>";
                    $output .= "<p>First Name: $result->firstname</p>";
                    $output .= "<p>Last Name: $result->lastname</p>";
                    $output .= "<p>Pool Address: $result->swimming_pool_address</p>";
                    $output .= $this->paymentStatus($application_id , $application_type_id);
                }
                echo $output;
            }
        } else  if ($application_type_id == 6) {
            $output = "";
            $results = DB::table('tourist_establishments')
                ->where('id', '=', $application_id)
                ->get();

            if ($results->isEmpty()) {
                $output = '<h4>Application Information</h4><p class="text-danger">Data Not found</p>';
                echo $output;
            } else {
                $output = "<h4>Application Info</h4>";
                foreach ($results as $result) {
                    $output .= "<p>Application Type: Touist Establishments</p>";
                    $output .= "<p>Est Name: $result->establishment_name</p>";
                    $output .= "<p>Officer First Name: $result->officer_firstname</p>";
                    $output .= "<p>Officer Last Name: $result->officer_lastname</p>";
                    $output .= "<p>Est Address: $result->establishment_address</p>";
                    $output .= $this->paymentStatus($application_id, $application_type_id);
                }
                echo $output;
            }
        }
    }

    public function paymentStatus(int $app_id, $app_type_id)
    {
        $payment = DB::table('payments')
            ->where('application_id', '=', $app_id)
            ->where('application_type_id', $app_type_id)
            ->get();
        $payment->isEmpty() ? $alert_type = "success" : $alert_type = "danger";
        $payment->isEmpty() ? $alert_text = "Payment Outstanding" : $alert_text = "Already Paid";
        return '<div class="alert alert-' . $alert_type . '"><b>Payment Status: ' . $alert_text . '</b></div>';
    }

    public function outstandingCancellations()
    {
        $paymentCancellations = PaymentCancellationRequests::with('payment.applicationType', 'requester')
            ->where('facility_id', auth()->user()->facility_id)
            ->where('approved', NULL)
            ->get();
        return view('payments.cancellation_requests.outstanding_approvals', compact('paymentCancellations'));
    }

    public function requestCancelPayment(Request $request)
    {
        $payment_cancellation = $request->data;
        try {
            if (Payments::find($payment_cancellation["payment_id"])) {
                if (PaymentCancellationRequests::where('payment_id', $payment_cancellation["payment_id"])->get()) {
                    $payment_cancellation["requester_user_id"] = Auth()->user()->id;
                    $payment_cancellation["facility_id"] = auth()->user()->facility_id;
                    if (PaymentCancellationRequests::create($payment_cancellation)) {
                        return "success";
                    }
                } else {
                    throw new Exception('A requests has already been made to cancel' . $request["payment_id"]);
                }
            } else {
                throw new Exception('No payment is associated with this id or it has already been deleted.');
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function approveCancelPaymentRequest(Request $request)
    {
        try {
            if (PaymentCancellationRequests::find($request->data["cancellation_id"])) {
                if ($request->data["approval_stat"] == "1") {
                    Payments::find(PaymentCancellationRequests::find($request->data["cancellation_id"])->payment_id)->update(["deleted_at" => new DateTime()]);
                }
                PaymentCancellationRequests::find($request->data["cancellation_id"])->update(["approved" => $request->data["approval_stat"], "approver_user_id" => auth()->user()->id]);
                return "success";
            } else {
                throw new Exception("Cancellation request does not exists");
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
