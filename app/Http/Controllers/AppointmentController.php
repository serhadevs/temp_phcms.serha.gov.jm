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


        return view('appointments.index', compact('exam_dates','permit_categories'));
    }

    public function filterExamDates($id,$day){

        try {
            $weekDays = ['sun','mon','tue','wed','thur','fri','sat'];
            $exam_dates = ExamDates::with('permitCategory', 'examSites')
                ->where('permit_category_id', $id)
                // ->where('facility_id',1)
                ->where('exam_day', $weekDays[$day])
                ->get();
        
            return response()->json([
                'success' => true,
                'message' => 'Exam Dates Fetched Successfully',
                'data' => $exam_dates
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching exam dates: ' . $e->getMessage()
            ], 500);
        }
        

    }

    public function show(Request $request)
    {
        $incomingFields = $request->validate([
            "app_date" => "required|date",
            // "exam_date" => "required|exists:exam_dates,id",
            
        ]);

           try {
            // $appointments = Appointments::with('applications.permitCategory', 'examDate','availableSites')
            //     ->where('appointment_date', $incomingFields['app_date'])
            //     ->where('facility_id',auth()->user()->facility_id)
            //     ->whereRelation('examDate','id',$incomingFields['exam_date'])
            //     ->get();

            $appointments = Appointments::join('exam_dates','exam_dates.id','=','appointments.exam_date_id')->where('appointment_date',$incomingFields['app_date']);

            dd($appointments);
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
