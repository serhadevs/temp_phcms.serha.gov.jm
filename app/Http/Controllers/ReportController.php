<?php

namespace App\Http\Controllers;

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
            "type" => "required"
        ]);
        $application_type = $criteria['type'];
        $is_general_report = true;

        // dd($criteria);

        try {
            switch ($criteria['type']) {
                case '1':
                    $permit_category_id = $request->permit_category;
                    $applications = PermitApplication::with('permitCategory', 'payment', 'user', 'establishmentClinics', 'appointment.examDate.examSites')
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
                    $food_establishments = EstablishmentApplications::with('establishmentCategory', 'user', 'payment', 'operators', 'testResults')
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
                    $food_clinics = EstablishmentClinics::with('payment', 'user')->withCount('permits')
                        ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
                        ->whereBetween('application_date', [$criteria['starting_date'], $criteria['ending_date']])
                        ->get();
                    break;
                case 5:
                    $applications = SwimmingPoolsApplications::with('payment')
                        ->where('user_id', User::facilityUsers()->pluck('id')->flatten())
                        ->whereBetween('application_date', [$criteria['starting_date'], $criteria['ending_date']])
                        ->get();
                    break;
                case 6:
                    $applications =  TouristEstablishments::with('payments', 'managers', 'services')
                        ->where('user_id', User::facilityUsers()->pluck('id')->flatten())
                        ->whereBetween('application_date', [$criteria['starting_date'], $criteria['ending_date']])
                        ->get();
                    break;
            }
            return view('reports.generalreport.report', compact('applications', 'application_type', 'is_general_report'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
