<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Jobs\SendPaymentReceiptEmail;
use App\Mail\PaymentEmail;
use App\Mail\TestingMail;
use App\Models\ApplicationType;
use App\Models\Appointment;
use App\Models\Messages;
use App\Models\BarbershopHairSalons;
use App\Models\EditTransactions;
use App\Models\EstablishmentApplications;
use App\Models\EstablishmentClinics;
use App\Models\Facility;
use App\Models\FoodEstablishmentOperators;
use App\Models\HealthCertApplications;
use App\Models\PaymentCancellationRequests;
use App\Models\Payments;
use App\Models\PaymentTypeFacilities;
use App\Models\PaymentTypes;
use App\Models\PermitApplication;
use App\Models\Prices;
use App\Models\Renewals;
use App\Models\SwimmingPoolsApplications;
use App\Models\TouristEstablishments;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Faker\Provider\ar_EG\Payment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

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

            $swim_pool_applications = SwimmingPoolsApplications::with('payment', 'user')
                ->selectRaw('"5" as application_type_id, "' . $application_type->where('id', 5)->first()->name . '" as app_type, swimming_pools_applications.id as app_number, concat(swimming_pools_applications.firstname, " ", swimming_pools_applications.lastname) as name, swimming_pools_applications.permit_no,"" as trn, "" as permit_type, ' . $prices->where('application_type_id', 5)->first()->price . '')
                ->doesntHave('payment')
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->whereBetween('created_at', [$filterTimeline, $today]);

            $tourist_est_applications = TouristEstablishments::with('payments', 'user')
                ->selectRaw('"6" as application_type_id, "' . $application_type->where('id', 6)->first()->name . '" as app_type, tourist_establishments.id as app_number, tourist_establishments.establishment_name as name, tourist_establishments.permit_no,"" as trn, "" as permit_type, ' . $prices->where('application_type_id', 6)->first()->price . '')
                ->doesntHave('payments')
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->whereBetween('created_at', [$filterTimeline, $today]);

            $applications = $permit_applications
                ->union($est_applications)
                ->union($clinic_application)
                ->union($health_cert_applications)
                ->union($swim_pool_applications)
                ->union($tourist_est_applications)
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
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->where('created_at', '>', $filterTimeline);

        $clinic_application = EstablishmentClinics::with('payment', 'user')
            ->selectRaw('"4" as application_type_id, "' . $application_type->where('id', 4)->first()->name . '" as app_type, establishment_clinics.id as app_number, establishment_clinics.name, "" as permit_no,"" as trn, "" as permit_type, ' . $prices->where('application_type_id', 4)->first()->price . '')
            ->doesntHave('payment')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->where('created_at', '>', $filterTimeline);

        $swim_pool_applications = SwimmingPoolsApplications::with('payment', 'user')
            ->selectRaw('"5" as application_type_id, "' . $application_type->where('id', 5)->first()->name . '" as app_type, swimming_pools_applications.id as app_number, concat(swimming_pools_applications.firstname, " ", swimming_pools_applications.lastname) as name, swimming_pools_applications.permit_no,"" as trn, "" as permit_type, ' . $prices->where('application_type_id', 5)->first()->price . '')
            ->doesntHave('payment')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->where('created_at', '>', $filterTimeline);

        $tourist_est_applications = TouristEstablishments::with('payments', 'user')
            ->selectRaw('"6" as application_type_id, "' . $application_type->where('id', 6)->first()->name . '" as app_type, tourist_establishments.id as app_number, tourist_establishments.establishment_name as name, tourist_establishments.permit_no,"" as trn, "" as permit_type, ' . $prices->where('application_type_id', 6)->first()->price . '')
            ->doesntHave('payments')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->where('created_at', '>', $filterTimeline);

        $applications = $permit_applications
            ->union($est_applications)
            ->union($clinic_application)
            ->union($health_cert_applications)
            ->union($swim_pool_applications)
            ->union($tourist_est_applications)
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
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->whereBetween('created_at', [$timeline['starting_date'], $timeline['ending_date']]);

        $swim_pool_applications = SwimmingPoolsApplications::with('payment', 'user')
            ->selectRaw('"5" as application_type_id, "' . $application_type->where('id', 5)->first()->name . '" as app_type, swimming_pools_applications.id as app_number, concat(swimming_pools_applications.firstname, " ", swimming_pools_applications.lastname) as name, swimming_pools_applications.permit_no,"" as trn, "" as permit_type, ' . $prices->where('application_type_id', 5)->first()->price . '')
            ->doesntHave('payment')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->whereBetween('created_at', [$timeline['starting_date'], $timeline['ending_date']]);

        $tourist_est_applications = TouristEstablishments::with('payments', 'user')
            ->selectRaw('"6" as application_type_id, "' . $application_type->where('id', 6)->first()->name . '" as app_type, tourist_establishments.id as app_number, tourist_establishments.establishment_name as name, tourist_establishments.permit_no,"" as trn, "" as permit_type, ' . $prices->where('application_type_id', 6)->first()->price . '')
            ->doesntHave('payments')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->whereBetween('created_at', [$timeline['starting_date'], $timeline['ending_date']]);

        $applications = $permit_applications
            ->union($est_applications)
            ->union($clinic_application)
            ->union($health_cert_applications)
            ->union($swim_pool_applications)
            ->union($tourist_est_applications)
            ->get();

        return view('payments.applications', compact('applications'));
    }

    public function create()
    {
        $prices = Prices::join('application_types', 'prices.application_type_id', '=', 'application_types.id')
            ->selectRaw('if(prices.id = 7, "Food Handlers - Student", (if(prices.id=8 , "Food Handlers - Teacher Regular", (if(prices.id = 9 , "Food Handlers - Teacher - Early Childhood", application_types.name))))) as app_type_name, prices.application_type_id, prices.price, prices.id')
            ->get();

        $payment_types = PaymentTypes::with('paymentTypeFacilities')
            ->whereRelation('paymentTypeFacilities', 'facility_id', auth()->user()->facility_id)
            // ->whereRelation('paymentTypeFacilities', 'status', "<>", "0")
            ->get();
        // $payment_types = PaymentTypeFacilities::with('paymentType')
        //     ->where('facility_id', auth()->user()->facility_id)
        //     ->where('status', "<>", "0")
        //     ->get();
        //dd($payment_types);
        return view('payments.create', compact('prices', 'payment_types'));
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

        $payment_types = PaymentTypes::with('paymentTypeFacilities')
            ->whereRelation('paymentTypeFacilities', 'facility_id', auth()->user()->facility_id)
            // ->whereRelation('paymentTypeFacilities', 'status', "<>", "0")
            ->get();


        $prices = Prices::join('application_types', 'prices.application_type_id', '=', 'application_types.id')
            ->selectRaw('if(prices.id = 7, "Food Handlers - Student", (if(prices.id=8 , "Food Handlers - Teacher Regular", (if(prices.id = 9 , "Food Handlers - Teacher - Early Childhood", application_types.name))))) as app_type_name, prices.application_type_id, prices.price, prices.id')
            ->get();

        return view('payments.create', compact('prices', 'app_id', 'app_type', 'price_id', 'payment_types'));
    }

    public function applyClinicPermitPayment($clinic_id)
    {
        $est_payment = Payments::where('application_id', $clinic_id)
            ->where('application_type_id', 4)
            ->first();
        try {
            DB::beginTransaction();
            $permit_ids = [];
            $i = 0;
            foreach (
                PermitApplication::with('payment')
                    ->doesntHave('payment')
                    ->where('establishment_clinic_id', $clinic_id)
                    ->get() as $permit
            ) {
                Payments::create([
                    'application_type_id' => 1,
                    'application_id' => $permit->id,
                    'receipt_no' => $est_payment->receipt_no,
                    'facility_id' => $est_payment->facility_id,
                    'cashier_user_id' => $est_payment->cashier_user_id,
                    'amount_paid' => 0,
                    'total_cost' => 0,
                    'change_amt' => 0
                ]);
                $permit_ids[$i] = $permit->id;
                $i++;
            }
            DB::commit();
            return json_encode($permit_ids);
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function fixStanMarkSTT()
    {
        $applications = PermitApplication::with('payment')
            ->where('establishment_clinic_id', 2431)
            ->doesntHave('payment')
            ->get();

        $establishment_payment = Payments::where('application_type_id', 4)
            ->where('application_id', 2431)
            ->first();

        $ids = [];
        $i = 0;

        foreach ($applications as $app) {
            $ids[$i] = $app->id;
            Payments::create([
                'application_type_id' => 1,
                'application_id' => $app->id,
                'receipt_no' => $establishment_payment->receipt_no,
                'facility_id' => $establishment_payment->facility_id,
                'cashier_user_id' => $establishment_payment->cashier_user_id,
                'amount_paid' => 0,
                'total_cost' => 0,
                'change_amt' => 0
            ]);
            $i++;
        }
        dd($ids);
    }

    public function printReceipt(Request $request)
    {
        $payment_id = $request->route('id');
        $payment = Payments::with('paymentType')->find($payment_id);

        // dd($payment);
        if ($payment->application_type_id == 1) {
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
        } else if ($payment->application_type_id == 4) {
            $receipt_info['applicant_name'] = EstablishmentClinics::find($payment->application_id)?->name;
        } else if ($payment->application_type_id == 2) {
            $health_certif = HealthCertApplications::find($payment->application_id);
            $receipt_info['applicant_name'] = $health_certif->firstname . " " . $health_certif->lastname;
        } else if ($payment->application_type_id == 6) {
            $receipt_info['applicant_name'] = TouristEstablishments::find($payment->application_id)->establishment_name;
        } else if ($payment->application_type_id == 5) {
            $receipt_info['applicant_name'] = SwimmingPoolsApplications::find($payment->application_id)->firstname . ' ' . SwimmingPoolsApplications::find($payment->application_id)->lastname;
        }

        $receipt_info['app_type'] = ApplicationType::find($payment->application_type_id)?->name;

        $receipt_info['application_no'] = $payment->application_id;
        $receipt_info['payment_date_time'] = $payment->created_at->format('M-d-Y h:i:s a');
        $receipt_info['facility'] = Facility::find($payment->facility_id)->name;
        $receipt_info['receipt_no'] = $payment->receipt_no;
        $receipt_info['total_cost'] = $payment->total_cost;
        $receipt_info['amount_paid'] = $payment->amount_paid;
        $receipt_info['change_amt'] = $payment->change_amt;
        $receipt_info['payment_type'] = $payment->paymentType?->name;
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

        //dd($new_payment);

        $app_id = $new_payment['application_id'];

        $app_type = Prices::find($new_payment['price_id'])->application_type_id;

        $id_exists = $app_type == 1 ? PermitApplication::find($app_id) : ($app_type == 2 ? HealthCertApplications::find($app_id) : ($app_type == 3 ? EstablishmentApplications::find($app_id) : ($app_type == 4 ? EstablishmentClinics::find($app_id) : ($app_type == 5 ? SwimmingPoolsApplications::find($app_id) : ($app_type == 6 ? TouristEstablishments::find($app_id) : ($app_type == 7 ? BarbershopHairSalons::find($app_id) : ''))))));

        $app_paid = Payments::where('application_type_id', $app_type)->where('application_id', $app_id)->get();

        if (!$id_exists) {
            return redirect()->back()->with(['error' => 'This application number does not exist in the system.']);
        }

        if ($app_type == 4) {
            $application = EstablishmentClinics::with('editTransactions.changedColumns')->withCount('payment')->find($app_id);
            $due_payments = $application->due_payments == NULL ? 1 : $application->due_payments;
            if ($due_payments == $application->payment_count) {
                return redirect()->back()->with(['error' => 'This application has already been paid for.']);
            }

            if ($transaction = EditTransactions::with('changedColumns')
                ->where('table_id', $application->id)
                ->where('application_type_id', 4)
                ->where('system_operation_type_id', 1)
                ->where('approved', 1)
                ->orderBy('created_at', 'desc')
                ->first()
            ) {
                $application->update([
                    'no_of_employees' => $transaction->changedColumns->first()->new_value
                ]);
            }
        } else {
            if (!$app_paid->isEmpty()) {
                return redirect()->back()->with(['error' => 'This application has already been paid for.']);
            }
        }
        $new_payment['application_type_id'] = $app_type;
        $new_payment['facility_id'] = Auth()->user()->facility_id;
        $new_payment['cashier_user_id'] = Auth()->user()->id;
        $new_payment['receipt_no'] = rand(1000000, 9999999);
        $receipt_number = $new_payment['receipt_no'];
        $new_payment['wire_transfer_date'] = date($new_payment['wire_transfer_date']);

        // dd($new_payment);
        $register_new_payment = Payments::create($new_payment);

        $applicant = PermitApplication::where('id', $app_id)->first();
        $cashier = User::find($register_new_payment->cashier_user_id);
        $cashier_name = $cashier->firstname[0] . ". " . $cashier->lastname;

        //Send email to applicant for payment
        $sendEmail = new Services();
        $sendEmail->sendPaymentEmail($applicant, $register_new_payment, $cashier_name, $receipt_number);

        if ($app_type == 4) {
            if (Renewals::where('new_application_id', $new_payment['application_id'])
                ->where('application_type_id', 4)->first()
            ) {
                DB::beginTransaction();
                foreach (PermitApplication::where('establishment_clinic_id', $new_payment['application_id'])->get() as $permit) {
                    Payments::create([
                        'application_type_id' => 1,
                        'application_id' => $permit->id,
                        'facility_id' => auth()->user()->facility_id,
                        'receipt_no' => $new_payment['receipt_no'],
                        'amount_paid' => 0,
                        'total_cost' => 0,
                        'change_amt' => 0.0,
                        'cashier_user_id' => $new_payment['cashier_user_id']
                    ]);
                }
                DB::commit();
            }
        }

        if (!$register_new_payment) {
        }
        return redirect()->route('payment.receipt.print', ['id' => $register_new_payment->getOriginal()["id"]])->with(['success' => 'Payment has been process successfully. The receipt number is ' . $new_payment["receipt_no"] . '']);
    }

    public function registerOnlinePayment(Request $request)
    {
        $new_payment = $request->validate([
            'price_id' => 'required',
            'application_id' => 'required',
            'amount_paid' => 'required',
            'total_cost' => 'required',
            //'change_amt' => 'required|numeric|min:0',
            // 'manual_receipt_no' => 'required_if:is_backlog,1',
            // 'manual_receipt_date' => 'required_if:is_backlog,1',
            'payment_type_id' => 'required',
            'facility_id' => 'required',
            'cashier_user_id' => 'required',
            'application_type_id' => 'required',
        ]);
        $new_payment['receipt_no'] = rand(1000000, 9999999);
        $new_payment['change_amt'] = $new_payment['amount_paid'] - $new_payment['total_cost'];
        $receipt_number = $new_payment['receipt_no'];
        $register_new_payment = Payments::create($new_payment);
        $application_id = $register_new_payment->application_id;
        //dd($application_id);
        if ($register_new_payment) {
            return redirect()->route('permit.online.application.complete', ['id' => $application_id]);
        }
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
                ->join('users', 'permit_applications.user_id', '=', 'users.id')
                ->where('permit_applications.id', $application_id)
                ->where('permit_applications.deleted_at', '=', NULL)
                ->where('users.facility_id', auth()->user()->facility_id)
                ->select('permit_applications.*', 'permit_categories.name')
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
                ->join('users', 'health_cert_applications.user_id', '=', 'users.id')
                ->where('health_cert_applications.id', '=', $application_id)
                ->where('users.facility_id', auth()->user()->facility_id)
                ->where('health_cert_applications.deleted_at', '=', NULL)
                ->select('health_cert_applications.*')
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
                ->join('users', 'establishment_applications.user_id', '=', 'users.id')
                ->where('establishment_applications.id', '=', $application_id)
                ->where('users.facility_id', auth()->user()->facility_id)
                ->where('establishment_applications.deleted_at', '=', NULL)
                ->get();

            $operators = FoodEstablishmentOperators::where('establishment_application_id', $application_id)->get();

            if ($results->isEmpty()) {
                $output = '<h4>Application Information</h4><p class="text-danger">Data Not found</p>';
                echo $output;
            } else {
                $output = $output = "<h4>Application Info</h4>";
                foreach ($results as $result) {
                    // $output .= "<p>Application Type : $result->establishment_name</p>";
                    $output .= "<p>Establishment Name : $result->establishment_name</p>";
                    $output .= "<p>Establishment Address : $result->establishment_address</p>";
                    $output .= "<p>Food Type : $result->food_type</p>";
                    $output .= "<p>Establishment Category: $result->name</p>";
                    $output .= "<p>Operator Names:</p>";
                    $output .= "<ul>";
                    foreach ($operators as $operator) {
                        $output .= "<li>" . $operator->name_of_operator . "</li>";
                    }
                    $output .= "</ul>";

                    $output .= $this->paymentStatus($application_id, $application_type_id);
                }
                echo $output;
            }
        } else if ($application_type_id == 4) {
            $output = "";
            $results = DB::table('establishment_clinics')
                ->join('users', 'establishment_clinics.user_id', '=', 'users.id')
                ->where('establishment_clinics.id', '=', $application_id)
                ->where('users.facility_id', auth()->user()->facility_id)
                ->where('establishment_clinics.deleted_at', '=', NULL)
                ->select('establishment_clinics.*')
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
                ->join('users', 'swimming_pools_applications.user_id', '=', 'users.id')
                ->where('swimming_pools_applications.id', '=', $application_id)
                ->where('users.facility_id', auth()->user()->facility_id)
                ->where('swimming_pools_applications.deleted_at', '=', NULL)
                ->select('swimming_pools_applications.firstname', 'swimming_pools_applications.lastname', 'swimming_pools_applications.swimming_pool_address')
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
                ->join('users', 'tourist_establishments.user_id', '=', 'users.id')
                ->where('tourist_establishments.id', '=', $application_id)
                ->where('users.facility_id', auth()->user()->facility_id)
                ->where('tourist_establishments.deleted_at', '=', NULL)
                ->select('tourist_establishments.*')
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

    // public function fixDownloadsBasedOnPayment()
    // {
    //     $application_ids = [
    //         23443,
    //         22927,
    //         20879,
    //         22072,
    //         23280,
    //         22633,
    //         22825,
    //         23220,
    //         23507,
    //         23525,
    //         23519,
    //         23445,
    //         20232,
    //         23543,
    //         23542,
    //         17287,
    //         23584,
    //         23598,
    //         23596,
    //         23595,
    //         23561,
    //         23298,
    //         23537,
    //         23580,
    //         22062,
    //         22150,
    //         22813,
    //         23593,
    //         23560,
    //         20295,
    //         22954,
    //         23245,
    //         18927,
    //         22402,
    //         23085,
    //         23279,
    //         20570,
    //         22609,
    //         22625,
    //         23100,
    //         23559,
    //         23558,
    //         19499,
    //         23554,
    //         21124,
    //         23545,
    //         23576,
    //         23571,
    //         22626,
    //         20874,
    //         23091,
    //         23605,
    //         23591,
    //         23557,
    //         23556,
    //         23555,
    //         23575,
    //         23588,
    //         23552,
    //         23538,
    //         22826,
    //         23241,
    //         23656,
    //         23544,
    //         23597,
    //         23540,
    //         23077,
    //         23536,
    //         23582,
    //         23579,
    //         23565,
    //         22635,
    //         23581,
    //         23567,
    //         23563,
    //         22448,
    //         22430,
    //         23578,
    //         20773,
    //         22964,
    //         22852,
    //         23583,
    //         22806,
    //         23592,
    //         22536,
    //         22271,
    //         23009,
    //         23530,
    //         23610,
    //         22543,
    //         22929,
    //         22928,
    //         22321,
    //         22698,
    //         21424,
    //         23692,
    //         23698,
    //         22709,
    //         22503,
    //         22911,
    //         23683,
    //         23681,
    //         23696,
    //         23697,
    //         22231,
    //         22877,
    //         23007,
    //         22946,
    //         23125,
    //         23059,
    //         22290,
    //         23055,
    //         23057,
    //         22944,
    //         22220,
    //         23695,
    //         23694,
    //         23693,
    //         24274,
    //         23135,
    //         22823,
    //         22560,
    //         23422,
    //         23792,
    //         23769,
    //         24424,
    //         23763,
    //         24272,
    //         22071,
    //         22917,
    //         24320,
    //         23209,
    //         22592,
    //         22591,
    //         23376,
    //         24945,
    //         24338,
    //         23779,
    //         22874,
    //         24258,
    //         23346,
    //         23778,
    //         23777,
    //         21080,
    //         23791,
    //         24430,
    //         24429,
    //         23093,
    //         23790,
    //         22922,
    //         23082,
    //         24425,
    //         23415,
    //         24276,
    //         22432,
    //         23314,
    //         23797,
    //         23765,
    //         24280,
    //         24457,
    //         23876,
    //         23143,
    //         22851,
    //         23896,
    //         23859,
    //         23387,
    //         23891,
    //         23863,
    //         22890,
    //         23793,
    //         23717,
    //         23862,
    //         24288,
    //         23892,
    //         23979,
    //         24233,
    //         23383,
    //         22953,
    //         23378,
    //         23637,
    //         23648,
    //         23305,
    //         22769,
    //         24474,
    //         23269,
    //         23653,
    //         23652,
    //         24218,
    //         23651,
    //         24268,
    //         23485,
    //         23716,
    //         23744,
    //         23103,
    //         23099,
    //         23484,
    //         23481,
    //         23661,
    //         23492,
    //         24254,
    //         24873,
    //         23644,
    //         24342,
    //         22858,
    //         22860,
    //         24463,
    //         24336,
    //         21747,
    //         23430,
    //         23987,
    //         24456,
    //         24444,
    //         23380,
    //         23657,
    //         23647,
    //         22583,
    //         24357,
    //         24356,
    //         23746,
    //         24193,
    //         24467,
    //         24197,
    //         23879,
    //         23861,
    //         23871,
    //         24466,
    //         20713,
    //         24329,
    //         23371,
    //         23374,
    //         23375,
    //         23645,
    //         23271,
    //         24235,
    //         23715,
    //         23391,
    //         24295,
    //         23872,
    //         23137,
    //         20231,
    //         24943,
    //         23982,
    //         23991,
    //         23885,
    //         23994,
    //         23404,
    //         24674,
    //         24249,
    //         23856,
    //         24368,
    //         24199,
    //         23895,
    //         24232,
    //         24267,
    //         23128,
    //         23997,
    //         24290,
    //         24256,
    //         24260,
    //         24259,
    //         23776,
    //         22392,
    //         23735,
    //         24358,
    //         22638,
    //         20594,
    //         24299,
    //         23848,
    //         23438,
    //         24205,
    //         24297,
    //         23708,
    //         23795,
    //         24271,
    //         24039,
    //         24293,
    //         22590,
    //         22141,
    //         18235,
    //         24265,
    //         24182,
    //         23798,
    //         24284,
    //         23073,
    //         24523,
    //         23710,
    //         23873,
    //         23866,
    //         23699,
    //         24217,
    //         24221,
    //         23370,
    //         23482,
    //         24332,
    //         22639,
    //         24501,
    //         24008,
    //         24330,
    //         23386,
    //         24624,
    //         24353,
    //         21831,
    //         24491,
    //         23999,
    //         24000,
    //         25057,
    //         22130,
    //         22965,
    //         24230,
    //         24017,
    //         23476,
    //         23470,
    //         24334,
    //         22127,
    //         24325,
    //         24003,
    //         23641,
    //         23639,
    //         23640,
    //         25058,
    //         24348,
    //         24621,
    //         24341,
    //         23649,
    //         24493,
    //         21952,
    //         22956,
    //         25060,
    //         20897,
    //         20547,
    //         23749,
    //         23709,
    //         20173,
    //         24370,
    //         23747,
    //         24379,
    //         21944,
    //         24738,
    //         24648,
    //         24041,
    //         24376,
    //         23416,
    //         23206,
    //         23787,
    //         24553,
    //         23771,
    //         24549,
    //         24428,
    //         22972,
    //         23995,
    //         24554,
    //         24426,
    //         24471,
    //         23847,
    //         23977,
    //         24192,
    //         24191,
    //         22470,
    //         24768,
    //         24729,
    //         24202,
    //         24183,
    //         23857,
    //         23983,
    //         24735,
    //         24733,
    //         24734,
    //         24742,
    //         23884,
    //         24743,
    //         24769,
    //         23431,
    //         24755,
    //         22215,
    //         22217,
    //         22219,
    //         22363,
    //         22214,
    //         22514,
    //         22500,
    //         23350,
    //         22491,
    //         22527,
    //         22422,
    //         22518,
    //         22489,
    //         22627,
    //         22601,
    //         22762,
    //         22790,
    //         22800,
    //         23018,
    //         22974,
    //         23012,
    //         22562,
    //         23351
    //     ];

    //     $count = 0;
    //     $not_processed[] = 0;
    //     $not_processed_num = 0;

    //     foreach ($application_ids as $id) {
    //         if ($application = EstablishmentApplications::with('payment')
    //             ->doesntHave('payment')
    //             ->find($id)
    //         ) {
    //             Payments::create([
    //                 'application_type_id' => 3,
    //                 'application_id' => $id,
    //                 'receipt_no' => rand(1000000, 9999999),
    //                 'facility_id' => 1,
    //                 'cashier_user_id' => 133,
    //                 'amount_paid' => 0,
    //                 'total_cost' => 0,
    //                 'change_amt' => 0,
    //                 'manual_receipt_no' => "Change Needed",
    //                 'manual_receipt_date' => date('Y-m-d H:i:s')
    //             ]);
    //             $count++;
    //         } else {
    //             $not_processed_num++;
    //             $not_processed[$not_processed_num] = $id;
    //         }
    //     }

    //     dd($count, $not_processed);
    // }
    public function fixRobertsIssue()
    {
        try {
            $establishment_clinic = EstablishmentApplications::find(2398);
            $est_permits = PermitApplication::where('establishment_clinic_id', 2398)->get();
            // dd($est_permits);
            foreach ($est_permits as $permit) {
                Payments::create(
                    [
                        'application_type_id' => 1,
                        'application_id' => $permit->id,
                        'facility_id' => 2,
                        'receipt_no' => 6739761,
                        'amount_paid' => 0,
                        'total_cost' => 0,
                        'change_amt' => 0.0,
                        'cashier_user_id' => 96
                    ]
                );
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function fixLattyIssue()
    {
        try {
            $counter = 0;
            $establishment_clinic = EstablishmentApplications::find(2345);
            $est_permits = PermitApplication::with('payment')
                ->doesntHave('payment')
                ->where('establishment_clinic_id', 2435)
                ->get();

            // $string = "";
            // foreach ($est_permits as $permit) {
            //     $string .= "ID : " . $permit->id . " Name: " . $permit->firstname . ' ' . $permit->lastname . '<br>';
            // }
            // dd($string);

            foreach ($est_permits as $permit) {
                $counter++;
                Payments::create(
                    [
                        'application_type_id' => 1,
                        'application_id' => $permit->id,
                        'facility_id' => 3,
                        'receipt_no' => 5742700,
                        'amount_paid' => 0,
                        'total_cost' => 0,
                        'change_amt' => 0.0,
                        'cashier_user_id' => 124
                    ]
                );
            }
            return $counter;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function paymentStatus(int $app_id, $app_type_id)
    {
        if ($app_type_id == 4) {
            $application = EstablishmentClinics::withCount('payment')->find($app_id);
            $due_payments = $application->due_payments == NULL ? 1 : $application->due_payments;
            if ($due_payments != $application->payment_count) {
                $alert_type = "success";
                $alert_text = "Payment Outstanding";
            } else {
                $alert_type = "danger";
                $alert_text = "Already Paid";
            }
        } else {
            $payment = DB::table('payments')
                ->where('application_id', '=', $app_id)
                ->where('application_type_id', $app_type_id)
                ->where('deleted_at', '=', null)
                ->get();
            $payment->isEmpty() ? $alert_type = "success" : $alert_type = "danger";
            $payment->isEmpty() ? $alert_text = "Payment Outstanding" : $alert_text = "Already Paid";
        }
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
            if ($payment_cancellation_request = PaymentCancellationRequests::find($request->data["cancellation_id"])) {
                if ($payment_cancellation_request->requester_user_id == auth()->user()->id) {
                    throw new Exception("You cannot approve the same request that you  entered.");
                }
                if ($request->data["approval_stat"] == "1") {
                    Payments::find(PaymentCancellationRequests::find($request->data["cancellation_id"])->payment_id)->update(["deleted_at" => date('Y-m-d H:i:s')]);
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
