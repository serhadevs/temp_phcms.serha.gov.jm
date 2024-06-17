<?php

namespace App\Http\Controllers;

use App\Http\Requests\NumberApplicationsByCategory;
use App\Models\ApplicationType;
use App\Models\EstablishmentApplications;
use App\Models\EstablishmentCategories;
use App\Models\EstablishmentClinics;
use App\Models\ExamDates;
use App\Models\HealthCertApplications;
use App\Models\PermitApplication;
use App\Models\PermitCategory;
use App\Models\SwimmingPoolsApplications;
use App\Models\TouristEstablishments;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Exception;
use Illuminate\Database\QueryException;

class ReportController extends Controller
{
    public function index()
    {

        //Get all applications types 

        $application_type = ApplicationType::all();
        $establishmentCategories = EstablishmentCategories::all();
        $foodHandlersCategories = PermitCategory::all();
        $examDate = ExamDates::all();
        //dd($foodHandlersCategories);
        return view(
            'reports.generalreport.index',
            compact(
                'application_type',
                'establishmentCategories',
                'foodHandlersCategories',
                'examDate'
            )
        );
    }

    public function generalReport(Request $request)
    {
        $criteria = $request->validate([
            "starting_date" => "required|date",
            "ending_date" => "required|date",
            "type" => "required",
            "interval" => 'required|numeric|min:0|max:6'
        ]);

        $criteria['ending_date'] = $criteria['ending_date'] . ' 23:59:59';
        
        $application_type = $criteria['type'];
        $is_general_report = true;

        try {
            switch ($criteria['type']) {
                case '1':
                    $permit_category_id = $request->permit_category;
                    $applications = PermitApplication::with('permitCategory', 'payment', 'user', 'establishmentClinics', 'appointment.examDate.examSites', 'signOffs')
                        ->whereBetween('application_date', [$criteria['starting_date'], $criteria['ending_date']])
                        ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
                        ->when(
                            $permit_category_id,
                            function ($query, string $permit_category_id) {
                                $query->where('permit_category_id', $permit_category_id);
                            }
                        )->get();
                    break;
                case 2:
                    $applications = HealthCertApplications::with('user', 'appointment.examDate.examSites')
                        ->whereBetween('application_date', [$criteria['starting_date'], $criteria['ending_date']])
                        ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
                        ->get();
                    break;
                case 3:
                    $establishment_category_id = $request->est_category;
                    $critical_score = $request->critical_score;
                    $visit_purpose = $request->visit_purpose;
                    // dd($visit_purpose);
                    $applications = EstablishmentApplications::with('establishmentCategory', 'user', 'payment', 'operators', 'testResults')
                        ->whereBetween('application_date', [$criteria['starting_date'], $criteria['ending_date']])
                        ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
                        ->when(
                            $establishment_category_id,
                            function ($query, string $establishment_category_id) {
                                $query->where('establishment_category_id', $establishment_category_id);
                            }
                        )->when(
                            $critical_score,
                            function ($query, string $critical_score) {
                                switch ($critical_score) {
                                    case "less":
                                        $query->whereRelation('testResults', 'critical_score', '<', 59);
                                        break;
                                    case "equal":
                                        $query->whereRelation('testResults', 'critical_score', 59);
                                        break;
                                    case "greater":
                                        $query->whereRelation('testResults', 'critical_score', '>', 59);
                                        break;
                                }
                            }
                        )->when(
                            $visit_purpose,
                            function ($query, string $visit_purpose) {
                                $query->whereRelation('testResults', 'visit_purpose', '=', $visit_purpose);
                            }
                        )->get();
                    break;
                case 4:
                    $applications = EstablishmentClinics::with('payment', 'user')->withCount('permits')
                        ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
                        ->whereBetween('application_date', [$criteria['starting_date'], $criteria['ending_date']])
                        ->get();
                    break;
                case 5:
                    $applications = SwimmingPoolsApplications::with('payment')
                        ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
                        ->whereBetween('application_date', [$criteria['starting_date'], $criteria['ending_date']])
                        ->get();
                    break;
                case 6:
                    $applications =  TouristEstablishments::with('payments', 'managers', 'services')
                        ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
                        ->whereBetween('application_date', [$criteria['starting_date'], $criteria['ending_date']])
                        ->get();
                    break;
            }
            return view('reports.generalreport.report', compact('applications', 'application_type', 'is_general_report'));
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function numberApplicationsByCategory(){
        return view('reports.establishments.index'); 
    }

    public function numberApplicationsByCategoryShow(NumberApplicationsByCategory $request){
       
        $incomingFields = $request->validated();
        $counts = [];
        $permitcategorysArray = PermitCategory::pluck('id')->toArray();
        $establishmentcategorysArray = EstablishmentCategories::pluck('id')->toArray();
        

        try {
            if ($incomingFields['module'] == '1') {
                $query = PermitApplication::whereBetween('created_at', [$incomingFields['starting_date'], $incomingFields['ending_date']])
                    ->whereIn('user_id', User::facilityUsers()->pluck('id'))
                    ->with('permitCategory')
                    ->get();
        
                foreach ($permitcategorysArray as $categoryId) {
                    $count = $query->where('permit_category_id', $categoryId)->count();
                    $category_name = PermitCategory::where('id', $categoryId)->first();
                    $counts[$categoryId] = ['count' => $count, 'category_name' => $category_name->name];
                }
            } elseif ($incomingFields['module'] == '2') {
                $query = EstablishmentApplications::whereBetween('created_at', [$incomingFields['starting_date'], $incomingFields['ending_date']])
                    ->whereIn('user_id', User::facilityUsers()->pluck('id'))
                    ->with('establishmentCategory')
                    ->get();
        
                foreach ($establishmentcategorysArray as $categoryId) {
                    $count = $query->where('establishment_category_id', $categoryId)->count();
                    $category_name = EstablishmentCategories::where('id', $categoryId)->first();
                    $counts[$categoryId] = ['count' => $count, 'category_name' => $category_name->name];
                }
            }
               
        } catch (Exception $e) {
                return redirect()->with('error','Unable to fullfil request' . $e);
        } catch (QueryException $e){
            return redirect()->with('error','There was an issue with you query' . $e);
        }

        $start_date = $incomingFields['starting_date'];
        $end_date = $incomingFields['ending_date'];
       
        
        return view('reports.establishments.view',['counts'=> $counts,'start_date'=>$start_date,'end_date'=>$end_date]);
    }

    public function numberOnsiteApplications(){
        return view('reports.onsite.index'); 
    }

    public function numberOnsiteApplicationsShow(NumberApplicationsByCategory $request){

        $incomingFields = $request->validated();

        $start_date = $incomingFields['starting_date'];
        $end_date = $incomingFields['ending_date'];

        if($incomingFields['module'] == '1'){
            $food_clinics = EstablishmentClinics::with('payment','signOff')->whereBetween('created_at',[$incomingFields['starting_date'],$incomingFields['ending_date']])->whereIn('user_id', User::facilityUsers()->pluck('id'))
            ->count();
            $module = 1;

        }else{
            $food_clinics = EstablishmentClinics::with('payment','signOff')->whereBetween('created_at',[$incomingFields['starting_date'],$incomingFields['ending_date']])
            ->whereIn('user_id', User::facilityUsers()->pluck('id'))
            ->get(); 

            $module = 2;
        }
        
        //dd($onsite);

        return view('reports.onsite.view',compact('food_clinics','start_date','end_date','module'));
    }
}
