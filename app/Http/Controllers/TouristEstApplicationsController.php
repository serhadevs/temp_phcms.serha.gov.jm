<?php

namespace App\Http\Controllers;

use App\Models\EstablishmentApplications;
use App\Models\Facility;
use App\Models\Renewals;
use App\Models\TestResult;
use App\Models\TouristEstablishments;
use App\Models\TouristEstManagers;
use App\Models\TouristEstServices;
use App\Models\User;
use Illuminate\Http\Request;
use DateTime;
use Exception;

class TouristEstApplicationsController extends Controller
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
        $filterTimeline = "";

        if ($id == "0") {
            $filterTimeline = $today;
        } else if ($id == "1") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
            $applications = TouristEstablishments::with('payments', 'managers', 'services')
                ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
                ->whereBetween('created_at', [$filterTimeline, $today])
                ->get();
            return view('tourist_est.index', compact('applications'));
        } else if ($id == "7") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        } else if ($id == "30") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        } else if ($id == "90") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        }

        $applications = TouristEstablishments::with('payments', 'managers', 'services')
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->where('created_at', '>', $filterTimeline)
            ->get();

        return view('tourist_est.index', compact('applications'));
    }

    public function customIndex(Request $request)
    {
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'interval' => 'nullable|numeric|max:6'
        ]);

        $applications = TouristEstablishments::with('payments', 'managers', 'services')
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('created_at', [$timeline['starting_date'], $timeline['ending_date'] . " 23:59:59"])
            ->get();

        return view('tourist_est.index', compact('applications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tourist_est.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tourist_est = $request->validate([
            'establishment_name' => 'required',
            'establishment_address' => 'required',
            'bed_capacity' => 'numeric|required',
            'is_eating_establishment' => 'required',
            'eating_establishment_description' => 'nullable',
            'establishment_state' => 'required',
            'officer_firstname' => 'nullable',
            'officer_lastname' => 'nullable',
            'authorized_officer_statement' => 'nullable',
            'statement_date' => 'nullable|date',
            'application_date' => 'required|date',
            'firstname.*' => 'required_with:firstname.0|required_with:lastname.0|required_with:nationality.0',
            'lastname.*' => 'required_with:firstname.0|required_with:lastname.0|required_with:nationality.0',
            'nationality.*' => 'required_with:firstname.0|required_with:lastname.0|required_with:nationality.0',
            'services.*' => 'required_with:services.0'
        ]);

        $tourist_est['permit_no'] = $this->generateTouristPermitNo();
        $tourist_est['user_id'] = auth()->user()->id;

        if ($created_tourist_est = TouristEstablishments::create($tourist_est)) {
            if (!empty($request->firstname)) {
                $i = 0;
                foreach ($request->firstname as $item) {
                    if ($item) {
                        TouristEstManagers::create([
                            'tourist_establishment_id' => $created_tourist_est->id,
                            'firstname' => $item,
                            'lastname' => $request->lastname[$i],
                            'post_held' => $request->post_held[$i],
                            'qualifications' => $request->qualifications[$i],
                            'nationality' => $request->nationality[$i]
                        ]);
                    }
                    $i++;
                }
            }

            if (!empty($request->services)) {
                foreach ($request->services as $name) {
                    if ($name) {
                        TouristEstServices::create(
                            [
                                'tourist_establishment_id' => $created_tourist_est->id,
                                'name' => $name
                            ]

                        );
                    }
                }
            }

            return redirect()->route('tourist-establishments.index.filter', ['id' => 0])->with('success', 'Tourist Establishment Application was added successfully. The Application ID is ' . $created_tourist_est->id);
        }

        return redirect()->route('tourist-establishments.index.filter', ['id' => 0])->with('error', 'Error creating processing tourist establishment application');
    }

    public function generateTouristPermitNo()
    {
        //Generate permit no.
        do {
            $abbr = Facility::where('id', auth()->user()->facility_id)
                ->first()
                ->abbr;
            $digits_limit = 4;
            $current_date = date("my");
            $random_digits = str_pad(rand(0, pow(10, $digits_limit) - 1), $digits_limit, '0', STR_PAD_LEFT);
            $permit_no = $abbr . $random_digits . $current_date;

            $permit_no_exist = TouristEstablishments::where('permit_no', $permit_no)->first();
        } while (!empty($permit_no_exist));

        return $permit_no;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $application = TouristEstablishments::with('managers', 'services', 'user')->find($id);

        $not_modal = 1;

        return view('tourist_est.view', compact('application', 'not_modal'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $application = TouristEstablishments::with('managers', 'services', 'user')->find($id);

        $not_modal = 1;
        $edit_mode = 1;

        return view('tourist_est.view', compact('application', 'not_modal', 'edit_mode'));
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
        $update_tourist_est = $request->validate([
            'establishment_name' => 'required',
            'establishment_address' => 'required',
            'bed_capacity' => 'numeric|required',
            'is_eating_establishment' => 'required',
            'eating_establishment_description' => 'nullable',
            'establishment_state' => 'required',
            'officer_firstname' => 'nullable',
            'officer_lastname' => 'nullable',
            'authorized_officer_statement' => 'nullable',
            'statement_date' => 'nullable|date'
        ]);

        $tourist_est = TouristEstablishments::find($request->app_id);

        if ($tourist_est->update($update_tourist_est)) {
            return redirect()->route('tourist-establishments.index.filter', ['id' => 0])->with('success', 'Tourist Establishment Application for ' . $tourist_est->establishment_name . ' has been updated successfully.');
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $tourist_est_id
     * @return \Illuminate\Http\Response
     */
    public function createManager($tourist_est_id)
    {
        $establishment_name = TouristEstablishments::find($tourist_est_id)->establishment_name;

        return view('tourist_est.create_mangers', compact('establishment_name', 'tourist_est_id'));
    }

    public function storeManager(Request $request)
    {
        $tourist_est_managers = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'post_held' => 'nullable',
            'qualifications' => 'nullable',
            'nationality' => 'required'
        ]);

        $tourist_est = TouristEstablishments::find($request->tourist_est_id);

        $tourist_est_managers['tourist_establishment_id'] = $request->tourist_est_id;

        if (TouristEstManagers::create($tourist_est_managers)) {
            return redirect()->route('tourist-establishments.index.filter', ['id' => 0])->with('success', 'Manager has been added to ' . $tourist_est->establishment_name . ' successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editManager($id)
    {
        $manager = TouristEstManagers::find($id);
        $establishment_name = TouristEstablishments::find($manager->tourist_establishment_id)->establishment_name;

        return view('tourist_est.edit_managers', compact('manager', 'establishment_name'));
    }

    public function updateManager(Request $request)
    {
        $tourist_est_manager_update = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'post_held' => 'nullable',
            'qualifications' => 'nullable',
            'nationality' => 'required'
        ]);

        $tourist_est_manager = TouristEstManagers::find($request->manager_id);
        $establishment_name = TouristEstablishments::find($tourist_est_manager->tourist_establishment_id)->establishment_name;

        if ($tourist_est_manager->update($tourist_est_manager_update)) {
            return redirect()->route('tourist-establishments.index.filter', ['id' => 0])->with('success', $tourist_est_manager->firstname . ' ' . $tourist_est_manager->lastname . ' of Tourist Establishment ' . $establishment_name . ' has been updated successfully.');

            return view('tourist_est.edit_managers', compact('manager', 'establishment_name'));
        }
    }

    public function updateService(Request $request)
    {
        try {
            if (TouristEstServices::find($request->data['id'])->update([
                'name' => $request->data['name']
            ])) {
                return 'success';
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteService(Request $request)
    {
        try {
            if (TouristEstServices::find($request->data['service_id'])->update(['deleted_at' => new DateTime()])) {
                return 'success';
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteManager(Request $request)
    {
        try {
            if (TouristEstManagers::find($request->data['manager_id'])->update(['deleted_at' => new DateTime()])) {
                return 'success';
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function storeService(Request $request)
    {
        try {
            if (TouristEstServices::create([
                'tourist_establishment_id' => $request->data['tourist_est_id'],
                'name' => $request->data['name']
            ])) {
                return 'success';
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function renewal($id)
    {
        $application = TouristEstablishments::with('services', 'managers')->find($id);

        return view('tourist_est.renew', compact('application'));
    }

    public function renew(Request $request, $id)
    {
        $tourist_est = $request->validate([
            'establishment_name' => 'required',
            'establishment_address' => 'required',
            'bed_capacity' => 'numeric|required',
            'is_eating_establishment' => 'required',
            'eating_establishment_description' => 'nullable',
            'establishment_state' => 'required',
            'officer_firstname' => 'nullable',
            'officer_lastname' => 'nullable',
            'authorized_officer_statement' => 'nullable',
            'statement_date' => 'nullable|date',
            'application_date' => 'required|date',
            'firstname.*' => 'required_with:firstname.0|required_with:lastname.0|required_with:nationality.0',
            'lastname.*' => 'required_with:firstname.0|required_with:lastname.0|required_with:nationality.0',
            'nationality.*' => 'required_with:firstname.0|required_with:lastname.0|required_with:nationality.0',
            'services.*' => 'required_with:services.0'
        ]);

        $old_application = TouristEstablishments::find($id);

        $tourist_est['permit_no'] = $old_application->permit_no;
        $tourist_est['user_id'] = auth()->user()->id;

        if ($new_tourist_est = TouristEstablishments::create($tourist_est)) {
            foreach ($old_application->managers as $manager) {
                TouristEstManagers::find($manager->id)->update([
                    'deleted_at' => new DateTime()
                ]);
            }

            foreach ($old_application->services as $service) {
                TouristEstServices::find($service->id)->update([
                    'deleted_at' => new DateTime()
                ]);
            }

            if (!empty($request->firstname)) {
                $i = 0;
                foreach ($request->firstname as $item) {
                    if ($item) {
                        TouristEstManagers::create([
                            'tourist_establishment_id' => $new_tourist_est->id,
                            'firstname' => $item,
                            'lastname' => $request->lastname[$i],
                            'post_held' => $request->post_held[$i],
                            'qualifications' => $request->qualifications[$i],
                            'nationality' => $request->nationality[$i]
                        ]);
                    }
                    $i++;
                }
            }

            if (!empty($request->services)) {
                foreach ($request->services as $name) {
                    if ($name) {
                        TouristEstServices::create(
                            [
                                'tourist_establishment_id' => $new_tourist_est->id,
                                'name' => $name
                            ]

                        );
                    }
                }
            }

            if ($old_test_results = TestResult::where('application_id', $id)->where('application_type_id', 6)->first()) {
                $old_test_results->update([
                    'deleted_at' => new DateTime()
                ]);
            }

            if (Renewals::create(
                [
                    'new_application_id' => $new_tourist_est->id,
                    'application_type_id' => 6,
                    'old_application_id' => $old_application->id

                ]
            )) {
                if ($old_application->update(['deleted_at' => new DateTime()])) {
                    return redirect()->route('tourist-establishments.index.filter', ['id' => 0])->with('success', 'Tourist Establishment ' . $new_tourist_est->establishment_name . ' has been renewed successfully. The Application ID is: ' . $new_tourist_est->id . '.');
                }
            }
        }
        return redirect()->route('tourist-establishments.index.filter', ['id' => 0])->with('error', 'Error processing renewal for Tourist Establishment ' . $old_application->establishment_name . '.');
    }
}
