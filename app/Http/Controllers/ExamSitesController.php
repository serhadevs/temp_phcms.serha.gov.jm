<?php

namespace App\Http\Controllers;

use App\Models\ExamSites;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ExamSitesController extends Controller
{
    public function index(){
        Log::channel('systemOperations')->info('Fetching exam site list', ['user_id' => auth()->user()->id]);
        if(in_array(Auth::user()->role_id,[1])){
            $exam_sites = ExamSites::with('facility')->get();
        }else{
            $exam_sites = ExamSites::with('facility')->where('facility_id', Auth::user()->facility_id)->get();
        }
        
        //dd($exam_sites);
        return view('examsites.index',compact('exam_sites'));
    }

    public function create(){
        Log::channel('systemOperations')->info('Loading exam site create form', ['user_id' => auth()->user()->id]);
        return view('examsites.create');
    }

    public function edit($id){
        Log::channel('systemOperations')->info('Loading exam site edit form', ['user_id' => auth()->user()->id, 'id' => $id]);
        $exam_site = ExamSites::with('facility')->where('id',$id)->first();
        //dd($exam_site);
        return view('admin.edit',compact('exam_site'));
    }

    public function filter($id){
        Log::channel('systemOperations')->info('Fetching exam site list', ['user_id' => auth()->user()->id, 'id' => $id]);
        //Find Facility in Database
        $exam_sites = ExamSites::with('facility')->where('facility_id',$id)->get();
        return view('examsites.index',compact('exam_sites'));
    }


}
