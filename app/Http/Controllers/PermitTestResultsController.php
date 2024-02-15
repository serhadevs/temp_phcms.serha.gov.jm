<?php

namespace App\Http\Controllers;

use App\Models\PermitCategory;
use App\Models\PermitTestResults;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PermitTestResultsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function permitResults(Request $request)
    {
        $permit_id = $request->route('id');
        $permit_applications = DB::table('permit_applications')
            ->where('permit_applications.id', $permit_id)
            ->get();

        $permit_appointments = DB::table('appointments')
            ->leftJoin('facilities', 'facilities.id', '=', 'appointments.facility_id')
            ->leftJoin('exam_dates', 'exam_dates.id', '=', 'appointments.exam_date_id')
            ->leftJoin('exam_sites', 'exam_dates.exam_site_id', '=', 'exam_sites.id')
            ->where('appointments.permit_application_id', $permit_id)
            ->orderBy('appointments.appointment_date', 'desc')
            ->first();
        // dd($permit_appointments);

        $permit_categories = PermitCategory::all();
        return view('test_center.food_handlers_permit.create', compact('permit_applications', 'permit_categories', 'permit_appointments'));
    }

    public function addPermitResults(Request $request)
    {
        $permit_results = $request->validate([
            'staff_contact' => 'required',
            'overall_score' => 'required|numeric|max:100|min:0'
        ]);

        $permit_results['application_type_id'] = $request->application_type_id;
        $permit_results['application_id'] = $request->application_id;
        $permit_results['test_location'] = $request->test_location;
        $permit_results['comments'] = $request->comments;
        $permit_results['user_id'] = Auth()->user()->id;
        $permit_results['test_date'] = $request->test_date;
        $permit_results['facility_id'] = Auth()->user()->facility_id;

        $new_permit_results = PermitTestResults::create($permit_results);

        if (!$new_permit_results) {
            return redirect()->route('dashboard')->with(['error' => 'Test Results could not be added']);
        }

        return redirect()->route('dashboard')->with(['success' => 'Test Results successfully added']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
