<?php

namespace App\Http\Controllers;

use App\Models\EstablishmentClinics;
use App\Models\ExamDates;
use App\Models\PermitCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClinicPermitApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::channel('systemOperations')->info('Fetching clinic permit application list', ['user_id' => auth()->user()->id]);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        Log::channel('systemOperations')->info('Loading clinic permit application create form', ['user_id' => auth()->user()->id]);
        $clinic_permit = EstablishmentClinics::whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())->where('id', $request->route('clinic_app_id'))->first();

        if ($clinic_permit->proposed_date == NULL) {
            return redirect()->route('food-handlers-clinics.edit', ['id' => $request->route('clinic_app_id')])->with('error', 'Ensure that the correct date and time for the clinic exercise is entered.');
        }

        $appointments_available = ExamDates::where('facility_id', auth()->user()->facility_id)
            ->where('application_type_id', 1)
            ->get();

        $completed_permit_total = EstablishmentClinics::withCount('permits')
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->where('id', $request->route('clinic_app_id'))
            ->first()
            ->permits_count;

        if ($completed_permit_total >= $clinic_permit->no_of_employees) {
            return redirect()
                ->route('food-handlers-clinic.index', ['id' => 0])
                ->with('error', 'All the applications for this establishment has been entered.');
        }

        $clinic_permit_data = [
            'clinic_app_id' => $clinic_permit->id,
            'no_of_employees' => $clinic_permit->no_of_employees,
            'completed_permits_total' => $completed_permit_total,
            'appointments_available' => $appointments_available
        ];

        $categories = PermitCategory::all();

        return view('food_handlers_permit.create', compact('clinic_permit', 'clinic_permit_data', 'categories', 'appointments_available'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::channel('systemOperations')->info('Creating clinic permit application', ['user_id' => auth()->user()->id]);
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
        Log::channel('systemOperations')->info('Viewing clinic permit application', ['user_id' => auth()->user()->id, 'id' => $id]);
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
        Log::channel('systemOperations')->info('Loading clinic permit application edit form', ['user_id' => auth()->user()->id, 'id' => $id]);
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
        Log::channel('systemOperations')->info('Updating clinic permit application', ['user_id' => auth()->user()->id, 'id' => $id]);
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
        Log::channel('systemOperations')->info('Deleting clinic permit application', ['user_id' => auth()->user()->id, 'id' => $id]);
        //
    }
}
