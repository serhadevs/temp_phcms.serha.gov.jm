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
use OpenAI\Laravel\Facades\OpenAI;

class ReportController extends Controller
{
    public function index()
    {

        //Get all applications types 

        $application_type = ApplicationType::all();
        $establishmentCategories = EstablishmentCategories::withTrashed()->get();
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
            // "interval" => 'required|numeric|min:0|max:6'
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
                    $zone = $request->zone;


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
                        )->when(
                            $zone,
                            function ($query, string $zone) {
                                $query->where('zone', $zone);
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
        $establishmentcategorysArray = EstablishmentCategories::withTrashed()->pluck('id')->toArray();


        try {
            if ($incomingFields['module'] == '1') {
                $query = PermitApplication::whereBetween('application_date', [$incomingFields['starting_date'], $incomingFields['ending_date']])
                    ->whereIn('user_id', User::facilityUsers()->pluck('id'))
                    ->with('permitCategory')
                    ->get();

                foreach ($permitcategorysArray as $categoryId) {
                    $count = $query->where('permit_category_id', $categoryId)->count();
                    $category_name = PermitCategory::where('id', $categoryId)->first();
                    $counts[$categoryId] = ['count' => $count, 'category_name' => $category_name->name];
                }
            } elseif ($incomingFields['module'] == '2') {
                $query = EstablishmentApplications::whereBetween('application_date', [$incomingFields['starting_date'], $incomingFields['ending_date']])
                    ->whereIn('user_id', User::facilityUsers()->pluck('id'))
                    ->with('establishmentCategory')
                    ->get();

                foreach ($establishmentcategorysArray as $categoryId) {
                    $count = $query->where('establishment_category_id', $categoryId)->count();
                    $category_name = EstablishmentCategories::withTrashed()->where('id', $categoryId)->first();
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
            $food_clinics = EstablishmentClinics::with('payment', 'signOff')->whereBetween('application_date', [$incomingFields['starting_date'], $incomingFields['ending_date']])->whereIn('user_id', User::facilityUsers()->pluck('id'))
                ->count();
            $module = 1;
        } else {
            $food_clinics = EstablishmentClinics::with('payment', 'signOff')->whereBetween('application_date', [$incomingFields['starting_date'], $incomingFields['ending_date']])
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

    public function countCategoriesByZone()
    {
        return view('reports.categorybyzonecount.index');
    }

    public function viewCountCategoriesByZone(Request $request)
    {

        $incomingFields = $request->validate([
            'starting_date' => 'required|date',
            'ending_date' => 'required|date',
            'zone' => 'required'
        ]);

        $counts = [];
        $categoriesArray = EstablishmentCategories::withTrashed()->pluck('id')->toArray();
        $start_date = $incomingFields['starting_date'];
        $end_date = $incomingFields['ending_date'];
        $zone = $incomingFields['zone'];

        $query = EstablishmentApplications::whereBetween('created_at', [$start_date, $end_date])->whereIn('user_id', User::facilityUsers()->pluck('id'))->with('establishmentCategory')->where('zone', $zone)->get();

        //dd($query);
        foreach ($categoriesArray as $categoryId) {
            $count = $query->where('establishment_category_id', $categoryId)->count();
            $category_name = EstablishmentCategories::withTrashed()->where('id', $categoryId)->first();
            $counts[$categoryId] = ['count' => $count, 'category_name' => $category_name->name];
        }

        return view('reports.categorybyzonecount.view', compact('start_date', 'end_date', 'counts', 'zone'));
    }

    public function generateReport()
    {
        // Fetch data from database

        $start_date = '2025-01-01';
        $end_date = '2025-02-01';
        $data = DB::table('permit_applications')
            ->select('firstname', 'lastname', 'application_date')
            ->orderBy('application_date', 'desc')
            ->whereBetween('application_date', [$start_date, $end_date])
            ->get();

        // Format data for AI prompt
        $formattedData = $this->formatDataForAI($data);

        // Generate report using OpenAI
        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a data analyst generating a report. Analyze the following data and provide insights.'
                ],
                [
                    'role' => 'user',
                    'content' => "Provide a count of permit applications between 01-01-2025 and 01-02-2025: \n\n" . $formattedData
                ]
            ],
            'temperature' => 0.7,
            'max_tokens' => 1000
        ]);

        $report = $response->choices[0]->message->content;

        dd($report);
    }

    private function formatDataForAI($data)
    {
        $output = "Data Analysis:\n\n";

        // Total users
        $output .= "Total Applications: " . count($data) . "\n\n";

        // Monthly signups
        $monthlySignups = $data->groupBy(function ($date) {
            return \Carbon\Carbon::parse($date->application_date)->format('Y-m');
        });

        $output .= "Monthly Applications:\n";
        foreach ($monthlySignups as $month => $users) {
            $output .= "$month: " . count($users) . " users\n";
        }

        return $output;
    }

    //All Establishments by Zone Report 

    public function allEstablishmentsByZone()
    {
        return view('reports.establishments.estbyzone');
    }

    public function viewAllEstablishmentsByZone(Request $request)
    {
        // Validate input
        $incomingFields = $request->validate([
            'zone' => 'required'
        ]);

        // Start building query
        $query = EstablishmentApplications::with('establishmentCategory', 'user', 'operators', 'signOff', 'testResults', 'establishmentCategory')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id);

        // Apply zone filter if not 7
        if ($incomingFields['zone'] != 7) {
            $query->where('zone', $incomingFields['zone']);
        }

        // Limit results after filtering
        $establishments = $query->get();

        //dd($establishments);

        return view('reports.establishments.viewest', compact('establishments'));
    }

    public function downloadsTest()
    {
        $downloads = Downloads::withCount('zippedApplications')
            ->whereBetween('created_at', ['2024-01-30', '2024-06-31'])
            ->where('application_type_id', 3)
            ->get();

        // dd($downloads);

        return view('reports.download_test.index', compact('downloads'));
    }
}
