<?php

namespace App\Http\Controllers;

use App\Models\PermitApplication;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{

    //Route: /
    public function index(){
        $permits = PermitApplication::where('facility_id',Auth::user()->facility_id)->get();
        dd($permits);
        return view('testcenterapplications.index', compact('permits'));
    }
}
