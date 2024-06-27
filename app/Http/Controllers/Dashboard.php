<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use App\Models\BarbershopHairSalons;
use App\Models\EstablishmentApplications;
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
        $year = Carbon::now()->year;
        $userId = auth()->user()->id;

        $query = function($model,$startOfMonth,$endOfMonth,$userId){
            try {
                return $model::where('user_id', $userId)
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->count();
            } catch (\Exception $e) {
                Log::error('Error fetching applications: ' . $e->getMessage());
                return 0;
            }

        };

       $permitApplicationCount = $query(PermitApplication::class,$startOfMonth,$endOfMonth,$userId);
       $foodestApplicationCount = $query(EstablishmentApplications::class,$startOfMonth,$endOfMonth,$userId);
       $barbercosmApplicationCount = $query(BarbershopHairSalons::class,$startOfMonth,$endOfMonth,$userId);
       $paymentCount = $query(Payments::class,$startOfMonth,$endOfMonth,$userId);

       
        return view('dashboard.dashboard', compact('permitApplicationCount','foodestApplicationCount','barbercosmApplicationCount','paymentCount','month','year'));
    }

   
    

    
}
