<?php

namespace App\Http\Controllers;

use App\Models\EstablishmentApplications;
use App\Models\TestResult;
use Illuminate\Http\Request;

class FoodEstTestResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $applications = EstablishmentApplications::with('establishmentCategory', 'testResults')
            ->has('testResults')
            ->where('created_at', '>', '2024-01-01')
            ->get();

        $app_type_id = 3;

        return view('test_center.food_est.index', compact('applications', 'app_type_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $application = EstablishmentApplications::with('establishmentCategory')
            ->find($request->route('id'));

        $app_type_id = '3';

        return view('test_center.food_est.create', compact('application', 'app_type_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $food_est = $request->validate([
            'staff_contact' => 'required',
            'test_date' => 'required',
            'overall_score' => 'required|is_numeric|min:0|max:100',
            'critical_score' => 'is_numeric|min:0|max:100',
            'comments' => 'nullable',
            'application_id' => 'required'
        ]);

        $food_est["application_type_id"] = 3;
        $food_est["user_id"] = auth()->user()->id;
        $food_est["facility_id"] = auth()->user()->facility_id;

        if (TestResult::create($food_est)) {
            return redirect()->route('food_est.create')->with('success', 'Food Establishment Test Results have been entered successfully.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function outstanding()
    {
        $applications = EstablishmentApplications::with('establishmentCategory', 'testResults', 'operators')
            ->doesntHave('testResults')
            ->where('created_at', '>', '2024-01-01')
            ->get();

        $app_type_id = 3;

        return view('test_center.food_est.outstanding', compact('applications', 'app_type_id'));
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
