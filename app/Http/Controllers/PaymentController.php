<?php

namespace App\Http\Controllers;

use App\Models\ApplicationType;
use App\Models\Appointment;
use App\Http\Requests\PaymentRequest;
use App\Models\BarbershopHairSalons;
use App\Models\EstablishmentApplications;
use App\Models\EstablishmentClinics;
use App\Models\Facility;
use App\Models\HealthCertApplications;
use App\Models\PaymentCancellationRequests;
use App\Models\Payments;
use App\Models\PermitApplication;
use App\Models\Prices;
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

    public function filterProcessedPayments($id)
    {
        if (auth()->user()->default_filter_id != "") {
            $id = auth()->user()->default_filter_id;
        }

        date_default_timezone_set('Etc/GMT+5');
        $today = date_format(new Datetime(), "Y-m-d");

        $filterTimeline = "";

        if ($id == "0") {
            $filterTimeline = $today;
        } else if ($id == "1") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
            $payments_info = Payments::with('applicationType', 'paymentCancellation', 'cashier')
                ->whereBetween('created_at', [$filterTimeline, $today])
                ->where('facility_id', auth()->user()->facility_id)
                ->where('deleted_at', NULL)
                ->get();

            return view('payments.index', compact('payments_info'));
        } else if ($id == "7") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        } else if ($id == "30") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        } else if ($id == "90") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        } else if ($id == "180") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-180 days"), "Y-m-d");
        }

        $payments_info = Payments::with('applicationType', 'paymentCancellation', 'cashier')
            ->where('created_at', '>', $filterTimeline)
            ->where('facility_id', auth()->user()->facility_id)
            ->where('deleted_at', NULL)
            ->get();

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

        $payments_info = Payments::with('applicationType', 'paymentCancellation', 'cashier')
            ->whereBetween('created_at', [$timeline['starting_date'], $timeline['ending_date'] . " 23:59:59"])
            ->where('facility_id', auth()->user()->facility_id)
            ->where('deleted_at', NULL)
            ->get();

        return view('payments.index', compact('payments_info'));
    }

    public function filterOutstandingPayments($id)
    {
        if (auth()->user()->default_filter_id != "") {
            $id = auth()->user()->default_filter_id;
        }
        $applications = [];
        date_default_timezone_set('Etc/GMT+5');
        $today = date_format(new Datetime(), "Y-m-d");

        $filterTimeline = "";
        $prices = Prices::all();
        $application_type = ApplicationType::all();

        if ($id == "0") {
            $filterTimeline = $today;
        } else if ($id == "1") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
            $permit_applications = PermitApplication::with('payment', 'user')
                ->selectRaw('"1" as application_type_id, concat("' . $application_type->where('id', 1)->first()->name . '", " - ", permit_applications.permit_type) as app_type, permit_applications.id as app_number, concat(permit_applications.firstname, " ", permit_applications.lastname) as name, permit_applications.permit_no, permit_applications.trn, permit_applications.permit_type, if(permit_applications.permit_type="regular", ' . $prices[0]->price . ', (if(permit_applications.permit_type="student", ' . $prices[6]->price . ',' . $prices[7]->price . '  ))) as price')
                ->doesntHave('payment')
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->whereBetween('created_at', [$filterTimeline, $today]);

            $est_applications = EstablishmentApplications::with('payment', 'user')
                ->selectRaw('"3" as application_type_id, "' . $application_type->where('id', 3)->first()->name . '" as app_type, establishment_applications.id as app_number, establishment_name as name, establishment_applications.permit_no, establishment_applications.trn, "" as permit_type, ' . $prices->where('application_type_id', 3)->first()->price . '')
                ->doesntHave('payment')
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->whereBetween('created_at', [$filterTimeline, $today]);

            $clinic_application = EstablishmentClinics::with('payment', 'user')
                ->selectRaw('"4" as application_type_id, "' . $application_type->where('id', 4)->first()->name . '" as app_type, establishment_clinics.id as app_number, establishment_clinics.name, "" as permit_no,"" as trn, "" as permit_type, ' . $prices->where('application_type_id', 4)->first()->price . '')
                ->doesntHave('payment')
                ->doesntHave('payment')
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->whereBetween('created_at', [$filterTimeline, $today]);

            $health_cert_applications = HealthCertApplications::with('payment', 'user')
                ->selectRaw('"2" as application_type_id, "' . $application_type->where('id', 2)->first()->name . '" as app_type, health_cert_applications.id as app_number, concat(health_cert_applications.firstname, " ", health_cert_applications.lastname) as name, health_cert_applications.permit_no, health_cert_applications.trn, "" as permit_type, ' . $prices->where('application_type_id', 2)->first()->price . '')
                ->doesntHave('payment')
                ->doesntHave('payment')
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->whereBetween('created_at', [$filterTimeline, $today]);

            $applications = $permit_applications
                ->union($est_applications)
                ->union($clinic_application)
                // ->union($health_cert_applications)
                ->get();
            return view('payments.applications', compact('applications'));
        } else if ($id == "7") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        } else if ($id == "30") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        } else if ($id == "90") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        } else if ($id == "180") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-180 days"), "Y-m-d");
        }

        $permit_applications = PermitApplication::with('payment', 'user')
            ->selectRaw('"1" as application_type_id, concat("' . $application_type->where('id', 1)->first()->name . '", " - ", permit_applications.permit_type) as app_type, permit_applications.id as app_number, concat(permit_applications.firstname, " ", permit_applications.lastname) as name, permit_applications.permit_no, permit_applications.trn, permit_applications.permit_type, if(permit_applications.permit_type="regular", ' . $prices[0]->price . ', (if(permit_applications.permit_type="student", ' . $prices[6]->price . ',' . $prices[7]->price . '  ))) as price')
            ->doesntHave('payment')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->where('created_at', '>', $filterTimeline);

        $est_applications = EstablishmentApplications::with('payment', 'user')
            ->selectRaw('"3" as application_type_id, "' . $application_type->where('id', 3)->first()->name . '" as app_type, establishment_applications.id as app_number, establishment_name as name, establishment_applications.permit_no, establishment_applications.trn, "" as permit_type, ' . $prices->where('application_type_id', 3)->first()->price . '')
            ->doesntHave('payment')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->where('created_at', '>', $filterTimeline);

        $health_cert_applications = HealthCertApplications::with('payment', 'user')
            ->selectRaw('"2" as application_type_id, "' . $application_type->where('id', 2)->first()->name . '" as app_type, health_cert_applications.id as app_number, concat(health_cert_applications.firstname, " ", health_cert_applications.lastname) as name, health_cert_applications.permit_no, health_cert_applications.trn, "" as permit_type, ' . $prices->where('application_type_id', 2)->first()->price . '')
            ->doesntHave('payment')
            ->doesntHave('payment')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->where('created_at', '>', $filterTimeline);

        $clinic_application = EstablishmentClinics::with('payment', 'user')
            ->selectRaw('"4" as application_type_id, "' . $application_type->where('id', 4)->first()->name . '" as app_type, establishment_clinics.id as app_number, establishment_clinics.name, "" as permit_no,"" as trn, "" as permit_type, ' . $prices->where('application_type_id', 4)->first()->price . '')
            ->doesntHave('payment')
            ->doesntHave('payment')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->where('created_at', '>', $filterTimeline);

        $applications = $permit_applications
            ->union($est_applications)
            ->union($clinic_application)
            // ->union($health_cert_applications)
            ->get();

        return view('payments.applications', compact('applications'));
    }

    public function customFilterOutstandingPayments(Request $request)
    {
        date_default_timezone_set('Etc/GMT+5');
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'interval' => 'nullable|numeric|max:6'
        ]);

        $prices = Prices::all();
        $application_type = ApplicationType::all();

        $timeline['ending_date'] = $timeline['ending_date'] . " 23:59:59";

        $permit_applications = PermitApplication::with('payment', 'user')
            ->selectRaw('"1" as application_type_id, concat("' . $application_type->where('id', 1)->first()->name . '", " - ", permit_applications.permit_type) as app_type, permit_applications.id as app_number, concat(permit_applications.firstname, " ", permit_applications.lastname) as name, permit_applications.permit_no, permit_applications.trn, permit_applications.permit_type, if(permit_applications.permit_type="regular", ' . $prices[0]->price . ', (if(permit_applications.permit_type="student", ' . $prices[6]->price . ',' . $prices[7]->price . '  ))) as price')
            ->doesntHave('payment')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->whereBetween('created_at', [$timeline['starting_date'], $timeline['ending_date']]);

        $health_cert_applications = HealthCertApplications::with('payment', 'user')
            ->selectRaw('"2" as application_type_id, "' . $application_type->where('id', 2)->first()->name . '" as app_type, health_cert_applications.id as app_number, concat(health_cert_applications.firstname, " ", health_cert_applications.lastname) as name, health_cert_applications.permit_no, health_cert_applications.trn, "" as permit_type, ' . $prices[2]->price . ' as  price')
            ->doesntHave('payment')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->whereBetween('created_at', [$timeline['starting_date'], $timeline['ending_date']]);

        $est_applications = EstablishmentApplications::with('payment', 'user')
            ->selectRaw('"3" as application_type_id, "' . $application_type->where('id', 3)->first()->name . '" as app_type, establishment_applications.id as app_number, establishment_name as name, establishment_applications.permit_no, establishment_applications.trn, "" as permit_type, ' . $prices->where('application_type_id', 3)->first()->price . '')
            ->doesntHave('payment')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->whereBetween('created_at', [$timeline['starting_date'], $timeline['ending_date']]);

        $clinic_application = EstablishmentClinics::with('payment', 'user')
            ->selectRaw('"4" as application_type_id, "' . $application_type->where('id', 4)->first()->name . '" as app_type, establishment_clinics.id as app_number, establishment_clinics.name, "" as permit_no,"" as trn, "" as permit_type, ' . $prices->where('application_type_id', 4)->first()->price . '')
            ->doesntHave('payment')
            ->doesntHave('payment')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->whereBetween('created_at', [$timeline['starting_date'], $timeline['ending_date']]);

        $applications = $permit_applications
            ->union($est_applications)
            ->union($clinic_application)
            // ->union($health_cert_applications)
            ->get();

        return view('payments.applications', compact('applications'));
    }

    public function create()
    {
        $prices = Prices::join('application_types', 'prices.application_type_id', '=', 'application_types.id')
            ->selectRaw('if(prices.id = 7, "Food Handlers - Student", (if(prices.id=8 , "Food Handlers - Teacher", application_types.name))) as app_type_name, prices.application_type_id, prices.price, prices.id')
            ->get();

        return view('payments.create', compact('prices'));
    }

    public function createFromApplication(Request $request)
    {
        $app_id = $request->route('app_id');
        $app_type = $request->route('app_t_id');
        $permit_type = "";

        if ($app_type == "1") {
            $permit_type = PermitApplication::find($app_id)->permit_type;
            if ($permit_type == "regular") {
                $price_id = 1;
            } else if ($permit_type == "student") {
                $price_id = 7;
            } else if ($permit_type == "teacher") {
                $price_id = 8;
            }
        } else {
            $price_id = Prices::where('application_type_id', $app_type)->first()->id;
        }

        $prices = Prices::join('application_types', 'prices.application_type_id', '=', 'application_types.id')
            ->selectRaw('if(prices.id = 7, "Food Handlers - Student", (if(prices.id=8 , "Food Handlers - Teacher", application_types.name))) as app_type_name, prices.application_type_id, prices.price, prices.id')
            ->get();

        return view('payments.create', compact('prices', 'app_id', 'app_type', 'price_id'));
    }

    public function printReceipt(Request $request)
    {
        $payment_id = $request->route('id');
        $payment = Payments::find($payment_id);

        // dd($payment);
        if ($payment->application_type_id == 1 || $payment->application_type_id == 8 || $payment->application_type_id == 9) {
            $receipt_info['app_type'] = ApplicationType::find($payment->application_type_id)->name;
            $application = PermitApplication::with('permitCategory')->find($payment->application_id);
            $receipt_info['applicant_name'] = $application?->firstname . " " . $application?->lastname;
            $receipt_info['permit_category'] = $application?->permitCategory?->name;
            $appointment = DB::table('appointments')
                ->join('exam_dates', 'exam_dates.id', '=', 'appointments.exam_date_id')
                ->join('exam_sites', 'exam_sites.id', '=', 'exam_dates.exam_site_id')
                ->where('appointments.facility_id', auth()->user()->facility_id)
                ->where('appointments.permit_application_id', $payment->application_id)
                ->where('exam_dates.application_type_id', 1)
                ->orderBy('appointments.created_at', 'desc')
                ->first();

            if ($appointment) {
                $receipt_info['appointment_date'] = date_format(new DateTime($appointment->appointment_date), 'M-d-Y') . " " . $appointment->exam_start_time;
                $receipt_info['exam_site'] = $appointment->name;
            } else if ($application?->establishment_clinic_id) {
                $establishment_clinic = DB::table('establishment_clinics')
                    ->where('id', $application->establishment_clinic_id)
                    ->first();
                $receipt_info['appointment_data'] = date_format(new DateTime($establishment_clinic?->proposed_date), "Y-m-d") . " " . $establishment_clinic->proposed_time;
                $receipt_info['exam_site'] = $establishment_clinic?->name . " " . $establishment_clinic?->address;
            }
        } else if ($payment->application_type_id == 3) {
            $receipt_info['applicant_name'] = EstablishmentApplications::find($payment->application_id)?->establishment_name;
            $receipt_info['app_type'] = ApplicationType::find($payment->application_type_id)?->name;
        } else if ($payment->application_type_id == 4) {
            $receipt_info['applicant_name'] = EstablishmentClinics::find($payment->application_id)?->name;
            $receipt_info['app_type'] = ApplicationType::find($payment->application_type_id)?->name;
        } else if ($payment->application_type_id == 2) {
            $health_certif = HealthCertApplications::find($payment->application_id);
            $receipt_info['applicant_name'] = $health_certif->firstname . " " . $health_certif->lastname;
            $receipt_info['app_type'] = ApplicationType::find($payment->application_type_id)?->name;
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

    public function registerNewPayment(PaymentRequest $request)
    {
        // date_default_timezone_set('Etc/GMT+5');
        //Write API
        //Limit input to numbers regex
        //Pattern 
        //Success Message => Done
        //Check if application id exists => Done
        //Check if receipt number exists => New Schema for this
        $new_payment = $request->validated();
            
        $app_id = $new_payment['application_id'];
        $app_type = Prices::find($new_payment['price_id'])->application_type_id;

        $id_exists = $app_type == 1 ? PermitApplication::find($app_id) : ($app_type == 2 ? HealthCertApplications::find($app_id) : ($app_type == 3 ? EstablishmentApplications::find($app_id) : ($app_type == 4 ? EstablishmentClinics::find($app_id) : ($app_type == 5 ? SwimmingPoolsApplications::find($app_id) : ($app_type == 6 ? TouristEstablishments::find($app_id) : ($app_type == 7 ? BarbershopHairSalons::find($app_id) : ''))))));

        $app_paid = Payments::where('application_type_id', $app_type)->where('application_id', $app_id)->get();

        if (!$id_exists) {
            return redirect()->back()->with(['error' => 'This application number does not exist in the system.']);
        }

        if (!$app_paid->isEmpty()) {
            return redirect()->back()->with(['error' => 'This application has already been paid for.']);
        }

        $new_payment['application_type_id'] = $app_type;
        $new_payment['facility_id'] = Auth()->user()->facility_id;
        $new_payment['cashier_user_id'] = Auth()->user()->id;
        $new_payment['receipt_no'] = rand(1000000, 9999999);
        $register_new_payment = Payments::create($new_payment);
       
        //Get the same information from the printReciept

        if (!$register_new_payment) {
        }
        return redirect()->route('payment.receipt.print', ['id' => $register_new_payment->getOriginal()["id"]])->with(['success' => 'Payment has been process successfully. The receipt number is ' . $new_payment["receipt_no"] . '']);
    }

    public function searchApplication(Request $request)
    {
        $application_id = $request->route('app_id');
        $price_id = $request->route('price_id');

        $application_type_id = Prices::find($price_id)->application_type_id;

        if ($application_type_id == 1) {
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
                    $output .= $this->paymentStatus($application_id, $application_type_id);
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
