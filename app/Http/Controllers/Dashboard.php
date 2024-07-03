<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use App\Models\BarbershopHairSalons;
use App\Models\EstablishmentApplications;
use App\Models\HealthInterview;
use App\Models\Payments;

use App\Models\PermitApplication;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class Dashboard extends Controller
{
    public function index()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $month = Carbon::now()->format('F');
        $now = Carbon::now();
        $year = Carbon::now()->year;
        $startofYear = Carbon::now()->startOfYear();
        $userId = auth()->user()->id;

        $query = function ($model, $startOfMonth, $endOfMonth, $userId) {
            try {
                return $model::where('user_id', $userId)
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->count();
            } catch (\Exception $e) {
                Log::error('Error fetching applications: ' . $e->getMessage());
                return 0;
            }
        };

        $permitApplicationCount = $query(PermitApplication::class, $startOfMonth, $endOfMonth, $userId);
        $permitApplicationCountYTD = $query(PermitApplication::class, $startofYear, $now, $userId);
        $foodestApplicationCount = $query(EstablishmentApplications::class, $startOfMonth, $endOfMonth, $userId);
        $barbercosmApplicationCount = $query(BarbershopHairSalons::class, $startOfMonth, $endOfMonth, $userId);
        $paymentCount = $query(Payments::class, $startOfMonth, $endOfMonth, $userId);


        // $applications = HealthInterview::with('permitApplication.permitCategory', 'permitApplication.establishmentClinics', 'permitApplication.testResults', 'permitApplication.travelHistory', 'healthInterviewSymptom.symptoms', 'permitApplication.appointment.examDate.examSites')
        //     ->where('facility_id', auth()->user()->facility_id)
        //     ->whereRelation('permitApplication.appointment', 'appointment_date', '2024-05-16')
        //     ->whereRelation('permitApplication.appointment.examDate.examSites', 'id', '6')
        //     ->doesntHave('permitApplication.establishmentClinics')
        //     ->has('permitApplication.testResults')
        //     ->with(['permitApplication' => function ($query) {
        //         $query->orderBy('lastname');
        //     }])
        //     ->get();
        // dd($applications);




        return view(
            'dashboard.dashboard',
            compact(
                'permitApplicationCount',
                'foodestApplicationCount',
                'barbercosmApplicationCount',
                'permitApplicationCountYTD',
                'paymentCount',
                'month',
                'year'
            )
        );
    }
}
