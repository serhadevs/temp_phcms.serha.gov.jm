<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use App\Models\BarbershopHairSalons;
use App\Models\EstablishmentApplications;
use App\Models\HealthInterview;
use App\Models\Payments;

use App\Models\PermitApplication;
use App\Models\Renewals;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class Dashboard extends Controller
{
    public function index(Request $request)
{
    $days = $request->route('days'); 
    $now = Carbon::now(); 
    switch ($days) {
        case 0:
            $expiryDays = $now;
            break;
        case 30:
            $expiryDays = $now->copy()->addDays(30);
            break;
        case 60:
            $expiryDays = $now->copy()->addDays(60);
            break;
        case 90:
            $expiryDays = $now->copy()->addDays(90);
            break;
        default:
            $expiryDays = $now; 
            break;
    }
    $userId = auth()->user()->id;
    $facilityId = auth()->user()->facility_id;

    // Date-related variables
    $startOfMonth = $now->copy()->startOfMonth();
    $endOfMonth = $now->copy()->endOfMonth();
    $startOfYear = $now->copy()->startOfYear();
    $year = $now->year;
    $month = $now->format('F');

    // Query Helper for Applications Count
    $query = function ($model, $startDate, $endDate, $userId) {
        try {
            return $model::where('user_id', $userId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
        } catch (\Exception $e) {
            Log::error('Error fetching applications: ' . $e->getMessage());
            return 0;
        }
    };


    // Fetch Expiry Count
    try {
        
        $expiryestCount = EstablishmentApplications::with('payment')
        ->join('sign_offs', 'sign_offs.application_id', '=', 'establishment_applications.id')
        ->whereIn('establishment_applications.user_id', User::facilityUserId()->pluck('id'))
        ->whereBetween('sign_offs.expiry_date', isset($expiryDays) && $expiryDays != $now ? [$now, $expiryDays] : [$now])->get();

    } catch (\Throwable $e) {
        Log::error('Error fetching expiry count: ' . $e->getMessage());
        $expiryestCount = 0;
    }

    // Return JSON response if called via AJAX
    if ($request->ajax()) {
        return response()->json([
            'status' => 'success',
            'expiry_count' => $expiryestCount,
        ]);
    }

    // Fetch additional counts for dashboard display
    $permitApplicationCount = $query(PermitApplication::class, $startOfMonth, $endOfMonth, $userId);
    $permitApplicationCountYTD = $query(PermitApplication::class, $startOfYear, $now, $userId);
    $foodestApplicationCount = $query(EstablishmentApplications::class, $startOfMonth, $endOfMonth, $userId);
    $foodestApplicationCountYTD = $query(EstablishmentApplications::class, $startOfYear, $now, $userId);
    $barbercosmApplicationCount = $query(BarbershopHairSalons::class, $startOfMonth, $endOfMonth, $userId);
    $barbercosmApplicationCountYTD = $query(BarbershopHairSalons::class, $startOfYear, $now, $userId);
    $paymentCount = $query(Payments::class, $startOfMonth, $endOfMonth, $userId);

    // Return the dashboard view
    return view('dashboard.dashboard', compact(
        'permitApplicationCount',
        'foodestApplicationCount',
        'barbercosmApplicationCount',
        'permitApplicationCountYTD',
        'foodestApplicationCountYTD',
        'barbercosmApplicationCountYTD',
        'paymentCount',
        'expiryestCount',
        'month',
        'year'
    ));
}

}
