<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ApplicationType;
use App\Models\ExamSites;
use App\Models\HealthInterview;
use App\Models\PermitCategory;

use Illuminate\Support\Facades\DB;

class SignOffController extends Controller
{

    public function index()
    {
        $excludedIds = [4, 7];
        $application_type = ApplicationType::whereNotIn('id', $excludedIds)->get();
        
        return view('signoffs.index',compact('application_type'));
    }

    public function create(Request $request,$id)
    {
       $id = $request->id;
        $exam_sites = DB::table('exam_sites')->whereNull('deleted_at')->where('facility_id', auth()->user()->facility_id)->get();
        return view('signoffs.create', compact('exam_sites','id'));
    }

    public function fetchApplications(Request $request, $id){
        $id = $request->id;
        $exam_date = Carbon::parse($request->exam_date)->format('Y-m-d');
        $exam_site = $request->exam_site;
        $date_of_inspection = Carbon::parse($request->date_of_inspection)->format('Y-m-d');
        $clinic_mode = $request->clinic_mode;

        $applications = HealthInterview::where('health_interviews.facility_id', auth()->user()->facility_id)
                    ->where('health_interviews.deleted_at', '=', null)
                    ->join('permit_applications', 'health_interviews.permit_application_id', '=', 'permit_applications.id')
                    ->join('permit_categories', 'permit_categories.id', '=', 'permit_applications.permit_category_id')
                    ->join('establishment_clinics', function ($join) use ($exam_date) {
                        $join->on('establishment_clinics.id', '=', 'permit_applications.establishment_clinic_id')
                            ->where('establishment_clinics.proposed_date', '=', $exam_date);
                    })->leftJoin('test_results', function ($join) use ($id){
                        $join->on( 'health_interviews.permit_application_id', '=', 'test_results.application_id')
                        ->where('test_results.deleted_at', '=', null)
                        ->where('test_results.application_type_id', '=', $id);
                    })
                    ->leftJoin('health_interview_symptom', 'health_interviews.id', '=', 'health_interview_symptom.health_interview_id')
                    ->leftJoin('symptoms', 'symptoms.id', '=', 'health_interview_symptom.symptom_id')
                    ->leftJoin('travel_history', function ($join) {
                        $join->on('travel_history.permit_application_id', '=', 'health_interviews.permit_application_id')
                        ->where('travel_history.deleted_at', '=', null);

                    })
                    ->select('establishment_clinics.proposed_date', 
                    'establishment_clinics.name as est_name', 
                    'establishment_clinics.address as est_address',
                     'permit_applications.permit_no', 
                     'permit_applications.firstname as permit_firstname', 
                     'permit_applications.id as permit_id',
                      'permit_applications.middlename as permit_middlename',
                       'permit_applications.lastname as permit_lastname',
                        'permit_applications.address as permit_address', 
                        'permit_applications.photo_upload', 
                        'permit_applications.date_of_birth', 
                        'permit_applications.gender as permit_gender',
                         'permit_applications.sign_off_status',
                        'permit_categories.name as permit_category',
                         DB::raw('group_concat("  ", travel_history.destination, " - " ,travel_history.travel_date) as travel_history'),
                        'test_results.overall_score', 'health_interviews.*', 'health_interviews.id as interview_id', DB::raw('group_concat("  ", symptoms.name) as symptoms'))
                    ->groupBy('health_interviews.id', 'test_results.id', 'travel_history.id', 'establishment_clinics.id')
                    ->orderBy('establishment_clinics.name', 'asc')
                    ->orderBy('permit_applications.sign_off_status', 'asc') 
                    ->get();

                    return view('signoffs.view', compact('applications', 'id'));
    
    }
}
