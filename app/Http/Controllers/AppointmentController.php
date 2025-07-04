<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use App\Models\ExamDates;
use App\Models\ExamSites;
use App\Models\PermitApplication;
use App\Models\PermitCategory;
use Carbon\Carbon;
use DateTime;
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


        return view('appointments.index', compact('exam_dates', 'permit_categories'));
    }

    public function filterExamDates($id, $day)
    {

        try {
            $weekDays = ['sun', 'mon', 'tue', 'wed', 'thur', 'fri', 'sat'];
            $exam_dates = ExamDates::with('permitCategory', 'examSites')
                ->where('permit_category_id', $id)
                ->where('facility_id', auth()->user()->facility_id)
                ->where('exam_day', $weekDays[$day])
                ->get();

            if ($exam_dates->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'There is no exam date for the category'
                ], 404);
            }

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

    //Get Available Date Based Permit Category and Location
    // public function getAvailableDate(Request $request)
    // {
    //     $last_booked_appointment = Appointments::with('examDate')
    //         ->whereRelation('examDate', 'exam_site_id', $request->data(['exam_site_id']))
    //         ->where('permit_category_id', $request->data(['permit_category_id']))
    //         ->orderby('created_at', 'desc')
    //         ->first();

    //     if ($last_booked_appointment->appointment_date > date("Y-m-d")) {
    //         $number_appointments = Appointments::with('examDate')
    //             ->where('appointment_date', $last_booked_appointment->appointment_date)
    //             ->whereRelation('examDate', 'permit_category_id', $request->data(['permit_category_id']))
    //             ->whereRelation('examDate', 'exam_site_id', $request->data(['exam_site_id']))
    //             ->count();

    //         if ($number_appointments < ExamDates::find($last_booked_appointment)->capacity) {
    //             return [
    //                 'appointment_date' => $last_booked_appointment->appointment_date,
    //                 'exam_date_id' => $last_booked_appointment->exam_date_id
    //             ];
    //         } else {
    //             $exam_date_id = $this->getExamDateId($request->data(['permit_category_id']), $request->data(['exam_site_id']), $last_booked_appointment->exam_date_id);
    //             return [
    //                 'exam_date_id'=>$exam_date_id,
    //                 'appointment_date'=>$this->getAppointmentDateBasedOnExamDate($exam_date_id)
    //             ];
    //         }
    //     } else {
    //         //find the next available date
    //         // $day = ;
    //         if(array_search($day, ['Monday', 'Tuesday', 'Wednesday','Friday']) !== false){

    //         }
    //     }
    // }

    // public function getExamDateId($permit_category_id, $exam_site_id, $latest_appointment_exam_date_id)
    // {
    //     $allPossibleExamDates = ExamDates::where('permit_category_id', $permit_category_id)
    //         ->where('exam_site_id', $exam_site_id)
    //         ->orderByRaw("
    //             CASE WHEN exam_day = 'mon' then 1
    //             WHEN exam_day = 'tue' then 2
    //             WHEN exam_day = 'wed' then 3
    //             WHEN exam_day = 'thur' then 4
    //             WHEN exam_day = 'fri' then 5
    //             WHEN exam_day = 'sat' then 6
    //             else 7 end
    //         ")
    //         ->orderByRaw("
    //             STR_TO_DATE(exam_start_time, '%l:%i %p')
    //         ")
    //         ->get()
    //         ->pluck('id')
    //         ->flatten();

    //     $index = array_search($latest_appointment_exam_date_id, $allPossibleExamDates);

    //     if (($index + 1) < count($allPossibleExamDates)) {
    //         return $allPossibleExamDates[($index + 1)];
    //     } else {
    //         return $allPossibleExamDates[0];
    //     }
    // }

    // public function getAppointmentDateBasedOnExamDate($exam_date_id)
    // {
    //     $exam_day = ExamDates::find($exam_date_id)->exam_day;

    //     switch ($exam_day) {
    //         case 'mon':
    //             return ((new DateTime())->modify('next monday'))->format('Y-m-d');
    //             break;
    //         case 'tue':
    //             return ((new DateTime())->modify('next tuesday'))->format('Y-m-d');
    //             break;
    //         case 'wed':
    //             return ((new DateTime())->modify('next wednesday'))->format('Y-m-d');
    //             break;
    //         case 'thur':
    //             return ((new DateTime())->modify('next thursday'))->format('Y-m-d');
    //             break;
    //         case 'fri':
    //             return ((new DateTime())->modify('next friday'))->format('Y-m-d');
    //             break;
    //         default:
    //             return 'error';
    //             break;
    //     }
    // }

    public function getBookedDates(Request $request)
    {
        //Also ensure you get the days that actually have a exam day to it
        //Monday, Tuesday, Wednesday, Thursday
        try {
            $excluded_dates = [];
            $i = 0;
            $appointments = Appointments::with('examDate')
                ->whereRelation('examDate', 'permit_category_id', $request->data['permit_category_id'])
                ->where('appointment_date', '>=', date('Y-m-d'))
                ->whereRelation('examDate', 'exam_site_id', $request->data['exam_site_id'])
                // ->where('rescheduled', '<>', 1)
                ->get()
                ->groupBy('appointment_date');

            foreach ($appointments as $appointment) {
                if ($appointment->count() >= $appointment->sum(function ($appointment) {
                    return $appointment->examDate?->capacity;
                })) {
                    $excluded_dates[$i] = $appointment[0]->appointment_date;
                    $i++;
                }
            }
            return response()->json([
                'excluded_dates'=>$excluded_dates,
                'status'=>200
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status'=>400
            ]);
        }
    }

    public function show(Request $request)
    {
        // Validate incoming request fields
        $incomingFields = $request->validate([
            "app_date" => "required|date",
            "permit_category" => "required",
            "exam_date" => "required"
        ]);

        //dd($incomingFields);

        $app_date = $incomingFields['app_date'];
        $permit_category = $incomingFields['permit_category'];
        $exam_site = $incomingFields['exam_date'];

        try {
            // Fetch appointments for the provided date
            $appointments = Appointments::with('permitCategory', 'examSitesId', 'signOff', 'signOff.user:id,firstname,lastname')
                ->join('exam_dates', 'exam_dates.id', '=', 'appointments.exam_date_id')
                ->where('appointment_date', $app_date)
                ->where('permit_category_id', $permit_category)
                ->where('appointments.facility_id', auth()->user()->facility_id)
                ->select('appointments.*', 'exam_dates.*')
                ->whereNull('exam_dates.deleted_at')
                ->where('exam_date_id', $exam_site)

                ->get();

            //dd($appointments);
            // Check if the results are empty
            if ($appointments->isEmpty()) {
                return redirect()->back()->with('error', 'No appointments found for the selected date.');
            }
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'There was an issue with the query: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }

        // Return the view with the appointments data
        return view('appointments.view', ['appointments' => $appointments, 'app_date' => $app_date]);
    }
}
