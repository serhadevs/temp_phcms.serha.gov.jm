<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointments;
use App\Models\EstablishmentApplications;
use App\Models\EstablishmentClinics;
use App\Models\HealthCertApplications;
use App\Models\TestResult;
use App\Models\Payments;
use App\Models\PermitApplication;
use App\Models\Renewals;
use App\Models\SignOff;
use App\Models\SwimmingPoolsApplications;
use App\Models\TouristEstablishments;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SummaryReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('reports.summaryreport.index');
    }

    public function show(Request $request)
    {
        $timeline = $request->validate([
            'starting_date' => 'required|date',
            'ending_date' => 'required|date'
        ]);

        $foodHandlers = $this->foodhandlerSummary($timeline['starting_date'], $timeline['ending_date']);
        $barberCosmet = $this->barberCosmetSummary($timeline['starting_date'], $timeline['ending_date']);
        $foodEstablishments = $this->foodEstablishmentSummary($timeline['starting_date'], $timeline['ending_date']);
        $swimmingPools = $this->swimmingPoolSummary($timeline['starting_date'], $timeline['ending_date']);
        $touristEstablishments = $this->touristEstablishmentSummary($timeline['starting_date'], $timeline['ending_date']);
        $foodClinics = $this->foodHandlerClinicSummary($timeline['starting_date'], $timeline['ending_date']);

        $starting_date = $timeline['starting_date'];
        $ending_date = $timeline['ending_date'];

        return view('reports.summaryreport.report', compact('foodHandlers', 'barberCosmet', 'foodEstablishments', 'swimmingPools', 'touristEstablishments', 'foodClinics', 'starting_date', 'ending_date'));
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

    private function foodhandlerSummary($starting_date, $ending_date)
    {
        $count = PermitApplication::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('application_date', [$starting_date, $ending_date])
            ->count();

        $noSignOffs = SignOff::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('sign_off_date', [$starting_date, $ending_date])
            ->where('application_type_id', 1)
            ->count();

        //Where in might be wrong
        $noRenewals = Renewals::join('permit_applications as pa', 'pa.id', 'new_application_id')
            ->whereBetween('application_date', [$starting_date, $ending_date])
            ->where('application_type_id', 1)
            ->whereIn('pa.user_id', User::facilityUsers()->pluck('id')->flatten())
            ->count();

        $cats = PermitApplication::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('application_date', [$starting_date, $ending_date])
            ->groupBy('permit_category_id')
            ->select('permit_category_id', DB::raw('count(*) as total'))
            ->get();

        $sum_foodHandlers = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
            ->where('facility_id', auth()->user()->facility_id)
            ->where('application_type_id', 1)
            ->select('total_cost')
            ->get();

        $sum_foodHandlers = $sum_foodHandlers->sum('total_cost');

        $max = 0;
        $min = 0;
        $maxCat = "None Found";
        $minCat = "none Found";

        if (!$cats->isEmpty()) {
            $max = $cats->max('total');
            $maxCat = $cats->where('total', $max)->first()->permitCategory->name;
            $min = $cats->min('total');
            $minCat = $cats->where('total', $min)->first()->permitCategory->name;
        }

        $noTrainingSessions = Appointments::where('facility_id', auth()->user()->facility_id)
            ->whereBetween('appointment_date', [$starting_date, $ending_date])
            ->count();

        $data = array($count, $count - $noRenewals, $noRenewals, $noSignOffs, $max . '-' . $maxCat, $min . '-' . $minCat, $noTrainingSessions, $sum_foodHandlers);
        return $data;
    }

    private function barberCosmetSummary($starting_date, $ending_date)
    {
        $count = HealthCertApplications::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('application_date', [$starting_date, $ending_date])
            ->count();

        $noSignOffs = SignOff::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('sign_off_date', [$starting_date, $ending_date])
            ->where('application_type_id', 2)
            ->count();

        $noRenewals = Renewals::join('health_cert_applications as ha', 'ha.id', 'new_application_id')
            ->whereBetween('application_date', [$starting_date, $ending_date])
            ->where('application_type_id', 2)
            ->whereIn('ha.user_id', User::facilityUsers()->pluck('id')->flatten())
            ->count();


        $sum_barberCosmet = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
            ->where('facility_id', auth()->user()->facility_id)
            ->where('application_type_id', 2)
            ->selectRaw('total_cost')
            ->get();

        $sum_barberCosmet = $sum_barberCosmet->sum('total_cost');

        $data = array(
            $count,
            $count - $noRenewals,
            $noRenewals,
            $noSignOffs,
            'N/A',
            'N/A',
            'N/A',
            $sum_barberCosmet
        );

        return $data;
    }

    private function foodEstablishmentSummary($starting_date, $ending_date)
    {
        $count = EstablishmentApplications::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('application_date', [$starting_date, $ending_date])
            ->count();

        $noSignOffs = SignOff::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('sign_off_date', [$starting_date, $ending_date])
            ->where('application_type_id', 3)
            ->count();

        $noRenewals = Renewals::join('establishment_applications as ea', 'ea.id', 'new_application_id')
            ->whereBetween('application_date', [$starting_date, $ending_date])
            ->whereIn('ea.user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereNotNull('ea.id')
            ->where('application_type_id', 3)
            ->where('ea.deleted_at', null)
            ->count();

        $cats = EstablishmentApplications::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->groupBy('establishment_category_id')
            ->whereBetween('application_date', [$starting_date, $ending_date])
            ->select('establishment_category_id', DB::raw('count(*) as total'))
            ->get();

        $sum_foodEstablishment = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
            ->where('facility_id', auth()->user()->facility_id)
            ->where('application_type_id', 3)
            ->selectRaw('total_cost')
            ->get();

        $sum_foodEstablishment = $sum_foodEstablishment->sum('total_cost');

        $max = 0;
        $min = 0;
        $maxCat = "None Found";
        $minCat = "none Found";

        if (!$cats->isEmpty()) {
            $max = $cats->max('total');
            $maxCat = $cats->where('total', $max)->first()->establishmentCategory?->name;
            $min = $cats->min('total');
            $minCat = $cats->where('total', $min)->first()->establishmentCategory?->name;
        }

        $data = array(
            $count,
            $count - $noRenewals,
            $noRenewals,
            $noSignOffs,
            $max . '-' . $maxCat,
            $min . '-' . $minCat,
            'N/A',
            $sum_foodEstablishment
        );

        return $data;
    }

    private function swimmingPoolSummary($starting_date, $ending_date)
    {
        $count = SwimmingPoolsApplications::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('application_date', [$starting_date, $ending_date])
            ->count();

        $noSignOffs = SignOff::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('sign_off_date', [$starting_date, $ending_date])
            ->where('application_type_id', 5)
            ->count();

        $noRenewals = Renewals::join('swimming_pools_applications as sa', 'sa.id', 'new_application_id')
            ->whereBetween('application_date', [$starting_date, $ending_date])
            ->whereIn('sa.user_id', User::facilityUsers()->pluck('id')->flatten())
            ->where('application_type_id', 5)
            ->where('sa.deleted_at', null)
            ->count();


        $sum_swimmingPool = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
            ->where('facility_id', auth()->user()->facility_id)
            ->where('application_type_id', 5)
            ->selectRaw('total_cost')
            ->get();

        $sum_swimmingPool = $sum_swimmingPool->sum('total_cost');

        return array(
            $count,
            $count - $noRenewals,
            $noRenewals,
            $noSignOffs,
            'N/A',
            'N/A',
            'N/A',
            $sum_swimmingPool
        );
    }

    private function touristEstablishmentSummary($starting_date, $ending_date)
    {
        $count = TouristEstablishments::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('application_date', [$starting_date, $ending_date])
            ->count();

        $noSignOffs = SignOff::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('sign_off_date', [$starting_date, $ending_date])
            ->where('application_type_id', 6)
            ->count();

        $noRenewals = Renewals::join('tourist_establishments as ta', 'ta.id', 'new_application_id')
            ->whereBetween('application_date', [$starting_date, $ending_date])
            ->whereIn('ta.user_id', User::facilityUsers()->pluck('id')->flatten())
            ->where('application_type_id', 6)
            ->where('ta.deleted_at', null)
            ->count();

        $sum_touristEstablishments = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
            ->where('facility_id', auth()->user()->facility_id)
            ->where('application_type_id', 6)
            ->selectRaw('total_cost')
            ->get();

        // $persons_trained = TestResult::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
        // ->where('facility_id', auth()->user()->facility_id)
        // ->where('application_type_id', 6)->count();
        $noTrainingSessions = Appointments::where('facility_id', auth()->user()->facility_id)
            ->whereBetween('appointment_date', [$starting_date, $ending_date])
            ->count();

        //dd($persons_trained);

        $sum_touristEstablishments = $sum_touristEstablishments->sum('total_cost');

        return array(
            $count,
            $count - $noRenewals,
            $noRenewals,
            $noSignOffs,
            'N/A',
            'N/A',
            $noTrainingSessions,
            $sum_touristEstablishments
        );
    }

    private function foodHandlerClinicSummary($starting_date, $ending_date)
    {
        $count = EstablishmentClinics::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('application_date', [$starting_date, $ending_date])
            ->count();

        $noSignOffs = 0;

        $noRenewals = Renewals::join('establishment_clinics as eca', 'eca.id', 'new_application_id')
            ->whereBetween('application_date', [$starting_date, $ending_date])
            ->whereIn('eca.user_id', User::facilityUsers()->pluck('id')->flatten())
            ->where('application_type_id', 4)
            ->where('eca.deleted_at', null)
            ->count();

        $sum_foodClinics = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
            ->where('facility_id', auth()->user()->facility_id)
            ->where('application_type_id', 4)
            ->selectRaw('total_cost')
            ->get();

        $sum_foodClinics = $sum_foodClinics->sum('total_cost');

        return array(
            $count,
            $count - $noRenewals,
            $noRenewals,
            $noSignOffs,
            'N/A',
            'N/A',
            'N/A',
            $sum_foodClinics
        );
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
