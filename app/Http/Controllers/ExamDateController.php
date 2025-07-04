<?php

namespace App\Http\Controllers;

use App\Models\ApplicationType;
use App\Models\Appointments;
use App\Models\ExamDates;
use App\Models\ExamSites;
use App\Models\PermitCategory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Exception;

class ExamDateController extends Controller
{
    public function index()
    {

        if (in_array(auth()->user()->role_id, [1])) {
            $exam_dates = ExamDates::with('permitCategory', 'facility', 'application_type', 'examSites')->get();
        } else {
            $exam_dates = ExamDates::with('permitCategory', 'facility', 'application_type', 'examSites')->where('facility_id', Auth::user()->facility_id)->get();
        };

        $exam_days = collect(['mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 'thur' => 'Thursday', 'fri' => 'Friday', 'sat' => 'Saturday', 'sun' => 'Sunday']);
        //dd($exam_dates);
        return view('admin.examdates', compact('exam_dates', 'exam_days'));
    }

    public function filter($id)
    {
        //Find Facility in Database
        $exam_dates = ExamDates::with('permitCategory', 'facility', 'application_type', 'examSites')->where('facility_id', $id)->get();
        $exam_days = collect(['mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 'thur' => 'Thursday', 'fri' => 'Friday', 'sat' => 'Saturday', 'sun' => 'Sunday']);
        return view('admin.examdates', compact('exam_dates', 'exam_days'));
    }

    public function create()
    {
        $applicationArray = [1, 2];
        $application_types = ApplicationType::whereIn('id', $applicationArray)->get();
        $permitcategories = PermitCategory::all();
        if (in_array(auth()->user()->role_id, [1])) {
            $exam_sites = ExamSites::all();
        } else {
            $exam_sites = ExamSites::with('facility')->where('facility_id', auth()->user()->facility_id)->orderBy('facility_id', 'desc')->get();
        }

        //dd($exam_sites);
        return view('admin.create', compact('application_types', 'exam_sites', 'permitcategories'));
    }

    public function store(Request $request)
    {

        $exam_date = $request->validate([
            'application_type_id' => 'required',
            'permit_category_id' => 'null',
            'exam_site_id' => 'required',
            'exam_day' => 'required',
            'exam_start_time' => 'required'
        ]);

        $exam_date['facility_id'] = auth()->user()->facility_id;

        try {

            $exists = ExamDates::where([
                ['application_type_id', '=', $exam_date['application_type_id']],
                ['permit_category_id', '=', $exam_date['permit_category_id']],
                ['exam_site_id', '=', $exam_date['exam_site_id']],
                ['exam_day', '=', $exam_date['exam_day']],
                ['exam_start_time', '=', $exam_date['exam_start_time']],
                ['facility_id', '=', $exam_date['facility_id']],
            ])->exists();

            if ($exists) {
                // If an exact match is found, return with an error message
                return redirect()->route('examdates')->with('error', 'An exam date with the same details already exists.');
            }

            $newdate = ExamDates::create($exam_date);

            if (!$newdate) {
                return redirect()->route('examsites')->with('error', 'Unable to add the exam date');
            }

            return redirect()->route('examdates')->with('success', 'Exam Date was succesfully added');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.dashboard')->with('error', 'Exception Error: ' . $e);
        } catch (QueryException $e) {
            return redirect()->route('dashboard.dashboard')->with('error', 'Query Exception Error: ' . $e);
        }
    }

    public function edit($id)
    {

        $applicationArray = [1, 2];
        $application_types = ApplicationType::whereIn('id', $applicationArray)->get();
        $permitcategories = PermitCategory::all();
        $exam_days = collect(['mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 'thur' => 'Thursday', 'fri' => 'Friday', 'sat' => 'Saturday', 'sun' => 'Sunday']);
        if (in_array(auth()->user()->role_id, [1])) {
            $exam_sites = ExamSites::all();
        } else {
            $exam_sites = ExamSites::with('facility')->where('facility_id', auth()->user()->facility_id)->orderBy('facility_id', 'desc')->get();
        }
        $exam_date = ExamDates::with('permitCategory', 'facility', 'application_type', 'examSites')->where('id', $id)->first();

        //dd($exam_date);

        return view('admin.edit', compact('exam_date', 'application_types', 'permitcategories', 'exam_sites', 'exam_days'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'application_type_id' => 'required|integer|exists:application_types,id',
            'permit_category_id' => 'required|integer|exists:permit_categories,id',
            'exam_site_id' => 'required|integer|exists:exam_sites,id',
            'exam_day' => 'required|string|in:mon,tue,wed,thur,fri,sat,sun',
            'exam_start_time' => 'required',
        ]);

        try {
            // Retrieve the existing exam date record
            $exam_date = ExamDates::findOrFail($id);

            // Update the exam date with validated data
            $exam_date->application_type_id = $validatedData['application_type_id'];
            $exam_date->permit_category_id = $validatedData['permit_category_id'];
            $exam_date->exam_site_id = $validatedData['exam_site_id'];
            $exam_date->exam_day = $validatedData['exam_day'];
            $exam_date->exam_start_time = $validatedData['exam_start_time'];

            // Save the updated record
            $exam_date->save();

            // Redirect back with a success message
            return redirect()->route('examdates')
                ->with('success', 'Exam date updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('examdates')->with('error', 'Exception Error: ' . $e);
        } catch (QueryException $e) {
            return redirect()->route('examdates')->with('error', 'Query Exception Error: ' . $e);
        }
    }

    //get examSites based on permit category id
    public function getExamSite(Request $request)
    {
        try {
            $exam_sites = ExamSites::where('facility_id', auth()->user()->facility_id)
                ->whereRelation('examDates', 'permit_category_id', $request->data['permit_category_id'])
                ->has('examDates')
                ->select('id', 'name')
                ->get();
            return response()->json([
                'exam_sites' => $exam_sites,
                'status' => 200
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
                'status' => 400
            ]);
        }
    }

    public function getPossibleTime(Request $request)
    {
        try {
            $excluded_exam_dates = [];
            $day = "";
            $i = 0;
            $day = date('D', strtotime($request->data['appointment_date'])) != "Thu"
                ? strtolower(date('D', strtotime($request->data['appointment_date'])))
                : "thur";

            $exam_dates = Appointments::with('examDate')
                ->whereRelation('examDate', 'permit_category_id', $request->data['permit_category_id'])
                ->whereRelation('examDate', 'exam_site_id', $request->data['exam_site_id'])
                ->where('appointment_date', $request->data['appointment_date'])
                ->get()
                ->groupBy('exam_date_id');

            foreach ($exam_dates as $exam_date) {
                if ($exam_date->count() >= $exam_date[0]->examDate?->capacity) {
                    $excluded_exam_dates[$i] = $exam_date[0]->exam_date_id;
                }
            }

            $available_times = ExamDates::whereNotIn('id', $excluded_exam_dates)
                ->where('permit_category_id', $request->data['permit_category_id'])
                ->where('exam_site_id', $request->data['exam_site_id'])
                ->where('exam_day', $day)
                ->select('exam_start_time')
                ->orderByRaw("STR_TO_DATE(exam_start_time, '%l:%i %p')")
                ->get();

            return response()->json([
                'available_times' => $available_times,
                'status' => 200
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
                'status' => 400
            ]);
        }
    }
}
