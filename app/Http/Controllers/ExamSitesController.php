<?php

namespace App\Http\Controllers;

use App\Models\ExamSites;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class ExamSitesController extends Controller
{
    public function index(){
        $exam_sites = ExamSites::where('facility_id', Auth::user()->facility_id)->get();
        //dd($exam_sites);
        return view('examsites.index',compact('exam_sites'));
    }

    public function create(){
        
    }

    public function edit(){
        
    }


}
