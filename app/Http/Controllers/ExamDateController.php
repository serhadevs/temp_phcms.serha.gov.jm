<?php

namespace App\Http\Controllers;

use App\Models\ExamDates;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class ExamDateController extends Controller
{
    public function index(){
        
        if(in_array(auth()->user()->role_id,[1])){
            $exam_dates = ExamDates::with('permitCategory','facility','application_type','examSites')->get();
        }else{
            $exam_dates = ExamDates::with('permitCategory','facility','application_type','examSites')->where('facility_id', Auth::user()->facility_id)->get();
        };
       
        $exam_days = collect(['mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 'thur' => 'Thursday', 'fri' => 'Friday', 'sat' => 'Saturday', 'sun' => 'Sunday']);
        //dd($exam_dates);
        return view('admin.examdates',compact('exam_dates','exam_days'));
    }
}
