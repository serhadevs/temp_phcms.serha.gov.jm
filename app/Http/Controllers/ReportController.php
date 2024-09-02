<?php

namespace App\Http\Controllers;

use App\Http\Requests\NumberApplicationsByCategory;
use App\Models\ApplicationType;
use App\Models\Downloads;
use App\Models\EditTransactions;
use App\Models\EstablishmentApplications;
use App\Models\EstablishmentCategories;
use App\Models\EstablishmentClinics;
use App\Models\ExamDates;
use App\Models\HealthCertApplications;
use App\Models\Payments;
use App\Models\PermitApplication;
use App\Models\PermitCategory;
use App\Models\SignOff;
use App\Models\SwimmingPoolsApplications;
use App\Models\TestResult;
use App\Models\TouristEstablishments;
use App\Models\User;
use App\Models\ZippedApplications;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use League\Flysystem\ZipArchive\ZipArchiveAdapter;

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

    public function numberApplicationsByCategory()
    {
        return view('reports.establishments.index');
    }

    public function numberApplicationsByCategoryShow(NumberApplicationsByCategory $request)
    {

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
            return redirect()->with('error', 'Unable to fullfil request' . $e);
        } catch (QueryException $e) {
            return redirect()->with('error', 'There was an issue with you query' . $e);
        }

        $start_date = $incomingFields['starting_date'];
        $end_date = $incomingFields['ending_date'];


        return view('reports.establishments.view', ['counts' => $counts, 'start_date' => $start_date, 'end_date' => $end_date]);
    }

    public function numberOnsiteApplications()
    {
        return view('reports.onsite.index');
    }

    public function numberOnsiteApplicationsShow(NumberApplicationsByCategory $request)
    {

        $incomingFields = $request->validated();

        $start_date = $incomingFields['starting_date'];
        $end_date = $incomingFields['ending_date'];

        if ($incomingFields['module'] == '1') {
            $food_clinics = EstablishmentClinics::with('payment', 'signOff')->whereBetween('created_at', [$incomingFields['starting_date'], $incomingFields['ending_date']])->whereIn('user_id', User::facilityUsers()->pluck('id'))
                ->count();
            $module = 1;
        } else {
            $food_clinics = EstablishmentClinics::with('payment', 'signOff')->whereBetween('created_at', [$incomingFields['starting_date'], $incomingFields['ending_date']])
                ->whereIn('user_id', User::facilityUsers()->pluck('id'))
                ->get();

            $module = 2;
        }

        //dd($onsite);

        return view('reports.onsite.view', compact('food_clinics', 'start_date', 'end_date', 'module'));
    }

    public function numberSignOffs()
    {
        $application_types = ApplicationType::all();
        return view('reports.signoffs.index', compact('application_types'));
    }

    public function numberSignOffsShow(Request $request)
    {

        $incomingFields = $request->validate([
            'starting_date' => 'required|date',
            'ending_date' => 'required|date'
        ]);

        $counts = [];
        $applicationTypesArray = ApplicationType::pluck('id')->toArray();
        $start_date = $incomingFields['starting_date'];
        $end_date = $incomingFields['ending_date'];

        try {
            $query = SignOff::whereBetween('sign_off_date', [$incomingFields['starting_date'], $incomingFields['ending_date']])->whereIn('user_id', User::facilityUsers()->pluck('id'))->with('application_type')->get();


            if (!$query) {
                return redirect()->with('error', 'There is no data for the signoff');
            }

            foreach ($applicationTypesArray as $categoryId) {
                $count = $query->where('application_type_id', $categoryId)->count();
                $category_name = ApplicationType::where('id', $categoryId)->first();
                $counts[$categoryId] = ['count' => $count, 'category_name' => $category_name->name];
            }
        } catch (Exception $e) {
            return redirect()->with('error', 'Unable to fullfil request' . $e);
        } catch (QueryException $e) {
            return redirect()->with('error', 'There was an issue with you query' . $e);
        }

        return view('reports.signoffs.view', ['counts' => $counts, 'start_date' => $start_date, 'end_date' => $end_date]);
    }

    public function backLogReport()
    {

        $counts = [];
        $applicationTypesArray = ApplicationType::pluck('id')->toArray();
        $backlog = Payments::with('applicationType')->whereNotNull('manual_receipt_date')->get();
        foreach ($applicationTypesArray as $categoryId) {
            $count = $backlog->where('application_type_id', $categoryId)->where('facility_id', auth()->user()->facility_id)->count();
            $category_name = ApplicationType::where('id', $categoryId)->first();
            $counts[$categoryId] = ['count' => $count, 'category_name' => $category_name->name];
        }
        return view('reports.backlog.index', ['counts' => $counts]);
    }

    public function newTouristEstablishmentReport(NumberApplicationsByCategory $request) {}

    //Transaction Report
    public function transactionReportIndex()
    {
        $application_types = ApplicationType::all();

        return view('reports.transactions.index', compact('application_types'));
    }

    public function generateTransactionReport(Request $request)
    {
        $criteria = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'application_type_id' => 'required',
            'interval' => 'nullable|numeric|max:6'
        ]);

        $application_type_id = $criteria['application_type_id'] == 'All' ? '' : $criteria['application_type_id'];

        $transactions = EditTransactions::with('applicationType', 'editType', 'user', 'systemOperationType', 'changedColumns')
            ->when(
                $application_type_id,
                function ($query, $application_type_id) {
                    $query->where('application_type_id', $application_type_id);
                }
            )
            ->where('created_at', '>', $criteria['start_date'])
            ->where('created_at', '<', $criteria['end_date'] . ' 23:59:59')
            ->where('facility_id', auth()->user()->facility_id)
            ->get();

        return view('reports.transactions.report', compact('transactions'));
    }

    public function printedCardsIndex()
    {
        $est_clinics = EstablishmentClinics::with('user')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->get();

        $food_ests = EstablishmentApplications::with('user')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->get();
        return view('reports.printed_cards.index', compact('est_clinics', 'food_ests'));
    }

    public function generatePrintedCards(Request $request)
    {
        $criteria = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'interval' => 'nullable|numeric|max:6',
            'application_type_id' => 'required',
            'establishment_clinic_name' => 'nullable',
            'food_establishment_name' => 'nullable',
            'test_date' => 'nullable|date'
        ]);

        $est_clinic = $criteria['establishment_clinic_name'];
        $food_est = $criteria['food_establishment_name'];
        $app_type_id = $criteria['application_type_id'];
        $test_date = $criteria['test_date'];

        if ($criteria['application_type_id'] == 1) {
            $printed_cards = PermitApplication::with('testResults', 'permitCategory', 'establishmentClinics', 'zippedApplication.download')
                ->has('zippedApplication.download')
                ->whereRelation('zippedApplication.download', 'download_date', '>', $criteria['start_date'])
                ->whereRelation('zippedApplication.download', 'download_date', '<', $criteria['end_date'] . ' 23:59:59')
                ->when($est_clinic, function ($query, $est_clinic) {
                    $query->whereRelation('establishmentClinics', 'name', 'like', "%" . $est_clinic . "%");
                })
                ->when($test_date, function ($query, $test_date) {
                    $query->whereRelation('testResults', 'test_date', $test_date);
                })
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->get();
        } else {
            $printed_cards = EstablishmentApplications::with('testResults', 'establishmentCategory', 'zippedApplication.download', 'user')
                ->has('zippedApplication.download')
                ->whereRelation('zippedApplication.download', 'download_date', '>', $criteria['start_date'])
                ->whereRelation('zippedApplication.download', 'download_date', '<', $criteria['end_date'] . ' 23:59:59')
                ->when($food_est, function ($query, $food_est) {
                    $query->where('establishment_name', 'like', "%" . $food_est . "%");
                })
                ->when($test_date, function ($query, $test_date) {
                    $query->whereRelation('testResults', 'test_date', $test_date);
                })
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->get();
        }

        return view('reports.printed_cards.report', compact('printed_cards', 'app_type_id'));
    }

    public function createInspectionsReport()
    {
        $application_types = ApplicationType::all();

        return view('reports.inspections.create', compact('application_types'));
    }

    public function generateInspectionsReport(Request $request)
    {
        $criteria = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            // 'interval' => 'nullable|numeric|max:6',
            'application_type_id' => 'nullable'
        ]);

        $app_type = $criteria['application_type_id'] == 'All' ? '' : $criteria['application_type_id'];

        $inspections = TestResult::select('staff_contact', DB::raw('count(*) as total'))
            ->groupBy('staff_contact')
            ->whereBetween('test_date', [$criteria['start_date'], $criteria['end_date'] . ' 23:59:59'])
            ->where('facility_id', auth()->user()->facility_id)
            ->when($app_type, function ($query, $app_type) {
                $query->where('application_type_id', $app_type);
            })
            ->pluck('staff_contact', 'total');

        return view('reports.inspections.report', compact('inspections'));
    }

    // public function productivityReportCreate(){
    //     return view('reports.productivity.index');
    // }

    // public function productivityReport(Request $request) {
    //     // Validate the incoming request fields
    //     $incomingFields = $request->validate([
    //         'starting_date' => 'required|date',
    //         'ending_date' => 'required|date'
    //     ]);

    //     // Ensure the dates are formatted correctly
    //     $start_date = $incomingFields['starting_date'] . ' 17:00:00';
    //     $end_date = $incomingFields['ending_date'] . ' 21:00:00';

    //     // Get the facility user IDs once
    //     $facilityUserIds = User::facilityUsers()->pluck('id')->flatten();

    //     // Retrieve the permit applications and group by user
    //     $permits = PermitApplication::with('user')
    //         ->whereBetween('created_at', [$start_date, $end_date])
    //         ->whereIn('user_id', $facilityUserIds)
    //         ->get()
    //         ->groupBy('user_id');

    //         dd($permits);

    //     // Retrieve the establishment applications and group by user
    //     $establishments = EstablishmentApplications::with('user')
    //         ->whereBetween('created_at', [$start_date, $end_date])
    //         ->whereIn('user_id', $facilityUserIds)
    //         ->get()
    //         ->groupBy('user_id');

    //     // Retrieve the test results and group by user
    //     $tests = TestResult::with('user')
    //         ->whereBetween('created_at', [$start_date, $end_date])
    //         ->whereIn('user_id', $facilityUserIds)
    //         ->get()
    //         ->groupBy('user_id');

    //     // Count the number of applications for each user
    //     $permitCounts = $permits->map(function ($items, $userId) {
    //         return ['user' => $items->first()->user, 'count' => $items->count()];
    //     });

    //     $establishmentCounts = $establishments->map(function ($items, $userId) {
    //         return ['user' => $items->first()->user, 'count' => $items->count()];
    //     });

    //     $testCounts = $tests->map(function ($items, $userId) {
    //         return ['user' => $items->first()->user, 'count' => $items->count()];
    //     });

    //     return view('reports.productivity.view', compact('permitCounts', 'establishmentCounts', 'testCounts', 'start_date', 'end_date'));
    // }

}
