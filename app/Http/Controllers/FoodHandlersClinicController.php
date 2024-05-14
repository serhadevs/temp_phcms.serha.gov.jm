<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use App\Models\EditTransactions;
use App\Models\EstablishmentClinics;
use App\Models\HealthInterview;
use App\Models\PermitApplication;
use App\Models\Renewals;
use App\Models\SignOff;
use App\Models\TestResult;
use Illuminate\Http\Request;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

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

    public function renewal($id)
    {
        $application = EstablishmentClinics::find($id);
        $permit_applications = PermitApplication::where('establishment_clinic_id', $id)->get();

        return view('food_handlers_clinic.renew', compact('application', 'permit_applications'));
    }

    public function renew(Request $request, $id)
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
            'application_date' => 'required|date',
            'renewable_permits' => 'required'
        ]);

        try {

            $food_handlers_clinic['user_id'] = auth()->user()->id;

            $old_clinic = EstablishmentClinics::find($id);

            if ($new_clinic = EstablishmentClinics::create($food_handlers_clinic)) {
                DB::beginTransaction();
                if (Renewals::create([
                    'new_application_id' => $new_clinic->id,
                    'application_type_id' => 4,
                    'old_application_id' => $id
                ])) {
                    $counter = 0;
                    $old_clinic->update(['deleted_at' => new DateTime()]);
                    $permit_ids = explode(",", $food_handlers_clinic['renewable_permits']);
                    foreach ($permit_ids as $permit_id) {
                        if ($permit = PermitApplication::find($permit_id)) {
                            $new_permit = $permit->toArray();
                            unset($new_permit['id']);
                            unset($new_permit['created_at']);
                            unset($new_permit['updated_at']);
                            unset($new_permit['sign_off_status']);
                            unset($new_permit['reprint']);
                            unset($new_permit['deleted_at']);
                            unset($new_permit['no_of_years']);
                            $new_permit['user_id'] = auth()->user()->id;
                            $new_permit['establishment_clinic_id'] = $new_clinic->id;
                            $new_permit['applied_before'] = 1;
                            $new_permit['application_date'] = $food_handlers_clinic['application_date'];
                            if ($new_permit_application = PermitApplication::create($new_permit)) {
                                if (Renewals::create([
                                    'new_application_id' => $new_permit_application->id,
                                    'application_type_id' => 1,
                                    'old_application_id' => $permit->id
                                ])) {
                                    if ($appointment = Appointments::where('permit_application_id', $permit_id)->first()) {
                                        $appointment->update(['deleted_at' => new DateTime()]);
                                    }
                                    if ($health_interview = HealthInterview::where('permit_application_id', $permit_id)->first()) {
                                        $health_interview->update(['deleted_at' => new DateTime()]);
                                    }
                                    if ($test_result = TestResult::where('application_id', $permit_id)->where('application_type_id', 1)->first()) {
                                        $test_result->update(['deleted_at' => new DateTime()]);
                                    }
                                    $permit->update(['deleted_at' => new DateTime()]);
                                }
                            }
                        }
                        $counter++;
                    }
                }
                DB::commit();
                return redirect()->route('food-handlers-clinic.index', ['id' => 0])->with('success', 'Food Handlers Clinic Renewal has been successfully processed for ' . $new_clinic->name . '. ' . $counter . ' Food Handlers Applications were also entered. The Clinic Application ID is: ' . $new_clinic->id);
            }
        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
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
    public function destroy(Request $request, $id)
    {
        try{
            if($food_clinic = EstablishmentClinics::with('permits')->find($id)){
                DB::beginTransaction();
                if(EditTransactions::create([
                    'application_type_id'=>4,
                    'table_id'=>$id,
                    'system_operation_type_id'=> 1,
                    'edit_type_id'=>2,
                    'user_id'=>auth()->user()->id,
                    'facility_id'=>auth()->user()->facility_id,
                    'reason'=>$request->data['reason']
                ])){
                    foreach($food_clinic->permits as $permit){
                        // PermitApplicationController->destroy($request, $permit->id);
                        
                    }
                }else{

                }
            }else{
                throw new Exception("This Food Establishment does not exist.");
            }
        }catch(Exception $e){

        }
    }
}
