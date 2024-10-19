<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use App\Models\ExamDates;
use App\Models\ExamSites;
use App\Models\PermitApplication;
use App\Models\PermitCategory;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{

    public function index()
    {
        $exam_dates = ExamDates::with('permitCategory')->where('facility_id', auth()->user()->facility_id)
            ->where('application_type_id', 1)
            ->with(['permitCategory' => function ($query) {
                $query->orderBy('name', 'asc');
            }])
            ->get();

            $permit_categories = PermitCategory::all();

            //dd($permit_categories);

        return view('appointments.index', compact('exam_dates','permit_categories'));
    }

    public function filterExamDates($id){

            $exam_dates = ExamDates::with('permitCategory','examSites')->where('permit_category_id',$id)
            ->where('facility_id',auth()->user()->facility_id)->get();

            return response()->json([
                'success' => true,
                'message' => 'Exam Dates Got',
                'data' => $exam_dates
            ], 200);  // 200 OK

    }

    public function show(Request $request)
    {
        $incomingFields = $request->validate([
            "app_date" => "required|date",
            "exam_date" => "required|exists:exam_dates,id",
            
        ]);

        $yesterday = Carbon::now()->subDay()->toDateString();

        //dd($yesterday);

        try {
            $appointments = Appointments::with('applications.permitCategory', 'examDate','examSites')
                ->where('appointment_date', $incomingFields['app_date'])
                ->whereRelation('examDate','id',$incomingFields['exam_date'])
                ->get();

                //dd($appointments);
            } catch (QueryException $e) {
                return redirect()->back()->with('error', 'There is an issue with the query: ' . $e->getMessage());
            } catch (Exception $e) {
                return redirect()->back()->with('error', 'Unknown error occurred: ' . $e->getMessage());
            }

            if ($appointments->isEmpty()) {
                return redirect()->back()->with('error', 'Unable to find appointments for that date');
            }

        return view('appointments.view', compact('appointments'));
    }
}
