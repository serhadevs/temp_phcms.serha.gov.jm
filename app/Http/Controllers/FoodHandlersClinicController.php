<?php

namespace App\Http\Controllers;

use App\Models\EstablishmentClinics;
use App\Models\SignOff;
use Illuminate\Http\Request;
use DateTime;

class FoodHandlersClinicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id = $request->route('id');
        $today = date_format(new Datetime(), "Y-m-d");
        $tonight = new DateTime($today . " 23:59:59");
        $yesterday = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
        $last_week = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        $thirty_days = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        $last_ninety_days = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");

        $filterTimeline = "";

        if ($id == "0") {
            $filterTimeline = $today;
            $food_clinics = EstablishmentClinics::with('payment', 'user')->withCount('permits')
                ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
                ->where('created_at', '>', $filterTimeline)
                ->get();

            return view('food_handlers_clinic.index', compact('food_clinics'));
        } else if ($id == "1") {
            $filterTimeline = $yesterday;
        } else if ($id == "7") {
            $filterTimeline = $last_week;
        } else if ($id == "30") {
            $filterTimeline = $thirty_days;
        } else if ($id == "90") {
            $filterTimeline = $last_ninety_days;
        }

        $food_clinics = EstablishmentClinics::with('payment', 'user')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)->withCount('permits')
            ->whereBetween('created_at', [$filterTimeline, $today])
            ->get();

        return view('food_handlers_clinic.index', compact('food_clinics'));
    }

    public function customFilter(Request $request)
    {
        date_default_timezone_set('Etc/GMT+5');
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'interval' => 'nullable|numeric|max:6'
        ]);

        $timeline["ending_date"] = $timeline["ending_date"] . " 23:59:59";

        $food_clinics = EstablishmentClinics::with('payment', 'user')->withCount('permits')
            ->whereRelation('user', 'facility_id', auth()->user()->facility_id)
            ->whereBetween('created_at', [$timeline['starting_date'], $timeline['ending_date']])
            ->get();

        return view('food_handlers_clinic.index', compact('food_clinics'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('food_handlers_clinic.create');
    }

    public function renewal(Request $request)
    {
        $application = EstablishmentClinics::find($request->route('id'));

        return view('food_handlers_clinic.renew', compact('application'));
    }

    public function renew(Request $request)
    {
        $food_handlers_clinic = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'contact_person' => 'required|string',
            'telephone' => 'required|regex:/^\+1+\(+[0-9]{3}+\)+[0-9]{3}+\-+[0-9]{4}+$/',
            'fax_no' => 'nullable|regex:/^\+1+\(+[0-9]{3}+\)+[0-9]{3}+\-+[0-9]{4}+$/',
            'no_of_employees' => 'numeric|required',
            'proposed_date' => 'required',
            'proposed_time' => 'required',
            'application_date' => 'required|date'
        ]);

        $food_handlers_clinic['user_id'] = auth()->user()->id;

        $app_id = EstablishmentClinics::create($food_handlers_clinic)->id;
        $old_application = EstablishmentClinics::find($request->old_app_id);

        dd($old_application);

        if ($app_id) {
            return redirect()->route('food-handlers-clinic.index', ['id' => 0])->with('success', 'Food Handlers Clinic application was created successfully. The application number is: ' . $app_id);
        } else {
            return redirect()->route('food-handlers-clinic.index', ['id' => 0])->with('error', 'Error processing Food Handlers Clinic application.');
        }

        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $food_handlers_clinic = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'contact_person' => 'required|string',
            'telephone' => 'required|regex:/^\+1+\(+[0-9]{3}+\)+[0-9]{3}+\-+[0-9]{4}+$/',
            'fax_no' => 'nullable|regex:/^\+1+\(+[0-9]{3}+\)+[0-9]{3}+\-+[0-9]{4}+$/',
            'no_of_employees' => 'numeric|required',
            'proposed_date' => 'required',
            'proposed_time' => 'required',
            'application_date' => 'required|date'
        ]);

        $food_handlers_clinic['user_id'] = auth()->user()->id;

        $app_id = EstablishmentClinics::create($food_handlers_clinic)->id;

        if ($app_id) {
            return redirect()->route('food-handlers-clinic.index', ['id' => 0])->with('success', 'Food Handlers Clinic application was created successfully. The application number is: ' . $app_id);
        } else {
            return redirect()->route('food-handlers-clinic.index', ['id' => 0])->with('error', 'Error processing Food Handlers Clinic application.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $application = EstablishmentClinics::find($request->route('id'));

        return view('food_handlers_clinic.view', compact('application'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $application = EstablishmentClinics::find($request->route('id'));

        $edit_mode = 1;

        return view('food_handlers_clinic.view', compact('application', 'edit_mode'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $food_handlers_clinic = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'contact_person' => 'required|string',
            'telephone' => 'required|regex:/^\+1+\(+[0-9]{3}+\)+[0-9]{3}+\-+[0-9]{4}+$/',
            'fax_no' => 'nullable|regex:/^\+1+\(+[0-9]{3}+\)+[0-9]{3}+\-+[0-9]{4}+$/',
            'no_of_employees' => 'numeric|required',
            'proposed_date' => 'required',
            'proposed_time' => 'required',
            'application_date' => 'required|date'
        ]);

        if (EstablishmentClinics::find($request->id)->update(
            [
                'name' => $food_handlers_clinic['name'],
                'address' => $food_handlers_clinic['address'],
                'contact_person' => $food_handlers_clinic['contact_person'],
                'telephone' => $food_handlers_clinic['telephone'],
                'fax_no' => $food_handlers_clinic['fax_no'],
                'proposed_date' => $food_handlers_clinic['proposed_date'],
                'proposed_time' => $food_handlers_clinic['proposed_time'],
                'application_date' => $food_handlers_clinic['application_date']
            ]
        )) {
            return redirect()->route('food-handlers-clinic.index', ['id' => 0])->with('success', 'Application ID: ' . $request->id . ' has been updated successfully.');
        }
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
