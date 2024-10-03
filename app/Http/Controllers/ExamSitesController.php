<?php

namespace App\Http\Controllers;

use App\Models\ExamSites;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class ExamSitesController extends Controller
{
    public function index(){
        if(in_array(Auth::user()->role_id,[1])){
            $exam_sites = ExamSites::with('facility')->get();
        }else{
            $exam_sites = ExamSites::with('facility')->where('facility_id', Auth::user()->facility_id)->get();
        }
        
        //dd($exam_sites);
        return view('examsites.index',compact('exam_sites'));
    }

    public function create(){
        
    }

    public function edit(){

    }

    public function filter($id){
        //Find Facility in Database
        $exam_sites = ExamSites::with('facility')->where('facility_id',$id)->get();
        return view('examsites.index',compact('exam_sites'));
    }


}
