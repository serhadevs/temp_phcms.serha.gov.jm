<?php

namespace App\Http\Controllers;

use App\Models\ApplicationType;
use App\Models\EstablishmentCategories;
use App\Models\ExamDates;
use App\Models\PermitApplication;
use App\Models\PermitCategory;
use App\Models\SwimmingPoolsApplications;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReportController extends Controller
{
    public function index(){

        //Get all applications types 

        $application_type = ApplicationType::all();
        $establishmentCategories = EstablishmentCategories::all();
        $foodHandlersCategories = PermitCategory::pluck('name')->prepend('All')->toArray();
        $examDate = ExamDates::all();
        //dd($foodHandlersCategories);
        return view('reports.generalreport.index',
        compact('application_type',
                'establishmentCategories',
                'foodHandlersCategories',
                'examDate'
    ));
    }

    public function generalReport(Request $request){

        $report = $request->validate([
            "starting_date" => "required|date",
            "ending_date" => "required|date",
            "type" => "required"
        ]);

        // dd($report);

        $startDate = Carbon::parse($report['starting_date']);
        $endDate = Carbon::parse($report['ending_date']);
        $type = $report['type'];

        //dd($type);
        try{
            $application = ApplicationType::find($type);
            if($application->id === 5){
                $data = SwimmingPoolsApplications::whereBetween('application_date',[$startDate,$endDate])
                ->where('user_id',auth()->user()->facility_id)->get();
            }
            
           

            return view('reports.generalreport.report',compact('data'));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

}
