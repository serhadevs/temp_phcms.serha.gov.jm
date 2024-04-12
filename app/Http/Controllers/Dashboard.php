<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;


class Dashboard extends Controller
{
    public function index(){
        
        return view('dashboard.dashboard');
    }

    public function fetchAppointments(){
        $appointments = Appointments::with('applications')->where("appointment_date",date("Y-m-d"))
        ->whereIn("facility_id",User::facilityUsers()->pluck('id')->flatten())->limit(10)->get();
        //dd($appointments);
        return view('dashboard.dashboard', compact('appointments'));
    }
    

    
}
