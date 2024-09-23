<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\TouristEstJob;
use App\Models\Downloads;
use App\Models\PrintableApplications;
use App\Models\TouristEstablishments;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DownloadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function food_handlers($id)
    {
        if (auth()->user()->default_filter_id != "") {
            $id = auth()->user()->default_filter_id;
        }

        $today = date_format(new Datetime(), "Y-m-d");
        $application_type_id = 1;

        $filterTimeline = "";

        if ($id == "0") {
            $filterTimeline = $today;
        } else if ($id == "1") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
            $downloads = Downloads::with('zippedApplications.payment.facility')
                ->where('application_type_id', 1)
                ->whereRelation('zippedApplications.payment', 'application_type_id', 1)
                ->whereBetween('created_at', [$filterTimeline, $today])
                ->get();

            return view('downloads.food_handlers_permits', compact('downloads', 'application_type_id'));
        } else if ($id == "7") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        } else if ($id == "30") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        } else if ($id == "90") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        } else if ($id == "180") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-180 days"), "Y-m-d");
        }

        $downloads = Downloads::with('zippedApplications.payment.facility')
            ->where('application_type_id', 1)
            ->where('created_at', '>', $filterTimeline)
            ->whereRelation('zippedApplications.payment', 'application_type_id', 1)
            ->get();

        // dd($downloads);
        return view('downloads.food_handlers_permits', compact('downloads', 'application_type_id'));
    }

    public function customFilterFHand(Request $request)
    {
        date_default_timezone_set('Etc/GMT+5');
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'interval' => 'nullable|numeric|max:6'
        ]);

        $end_date = $timeline["ending_date"] . " 23:59:59";

        $downloads = Downloads::with('zippedApplications.payment.facility')
            ->where('application_type_id', 1)
            ->whereRelation('zippedApplications.payment', 'application_type_id', 1)
            ->whereBetween('created_at', [$timeline["starting_date"], $end_date])
            ->get();

        $application_type_id = 1;

        return view('downloads.food_handlers_permits', compact('downloads', 'application_type_id'));
    }

    public function food_est($id)
    {
        if (auth()->user()->default_filter_id != "") {
            $id = auth()->user()->default_filter_id;
        }
        $today = date_format(new Datetime(), "Y-m-d");
        $application_type_id = 3;

        $filterTimeline = "";

        if ($id == "0") {
            $filterTimeline = $today;
        } else if ($id == "1") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
            $downloads = Downloads::with('zippedApplications.payment.facility')
                ->where('application_type_id', 3)
                ->whereRelation('zippedApplications.payment', 'application_type_id', 3)
                ->whereBetween('created_at', [$filterTimeline, $today])
                ->get();

            return view('downloads.food_est', compact('downloads', 'application_type_id'));
        } else if ($id == "7") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        } else if ($id == "30") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        } else if ($id == "90") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        } else if ($id == "180") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-180 days"), "Y-m-d");
        }

        $downloads = Downloads::with('zippedApplications.payment.facility')
            ->where('application_type_id', 3)
            ->whereRelation('zippedApplications.payment', 'application_type_id', 3)
            ->where('created_at', '>', $filterTimeline)
            ->get();

        return view('downloads.food_est', compact('downloads', 'application_type_id'));
    }

    public function customFilterFoodEst(Request $request)
    {
        date_default_timezone_set('Etc/GMT+5');
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'interval' => 'nullable|numeric|max:6'
        ]);

        $end_date = $timeline["ending_date"] . " 23:59:59";

        $downloads = Downloads::with('zippedApplications.payment.facility')
            ->where('application_type_id', 3)
            ->whereRelation('zippedApplications.payment', 'application_type_id', 3)
            ->whereBetween('created_at', [$timeline["starting_date"], $end_date])
            ->get();

        $application_type_id = 3;

        return view('downloads.food_est', compact('downloads', 'application_type_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadZip(Request $request)
    {
        $now = \Carbon\Carbon::now()->toDateTimeString();
        $download = Downloads::find($request->download_id);
        if ($download) {
            $download->update([
                'download_date' => $now
            ]);
        }
        return response()->download(storage_path("app/public/") . $download->download_url);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function tourist_est($id)
    {
        if (auth()->user()->default_filter_id != "") {
            $id = auth()->user()->default_filter_id;
        }
        $today = date_format(new Datetime(), "Y-m-d");
        $application_type_id = 1;

        $filterTimeline = "";

        if ($id == "0") {
            $filterTimeline = $today;
        } else if ($id == "1") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-1 days"), "Y-m-d");
            $tourist_ests = TouristEstablishments::with('signOffs', 'testResults', 'payments.facility', 'printableApplication')
                ->has('signOffs')
                ->has('payments')
                ->has('printableApplication')
                ->where('sign_off_status', 1)
                ->whereBetween('created_at', [$filterTimeline, $today])
                ->get();
            return view('downloads.tourist_est', compact('tourist_ests'));
        } else if ($id == "7") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-7 days"), "Y-m-d");
        } else if ($id == "30") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-30 days"), "Y-m-d");
        } else if ($id == "90") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-90 days"), "Y-m-d");
        } else if ($id == "180") {
            $filterTimeline = date_format(date_modify(new DateTime(), "-180 days"), "Y-m-d");
        }

        $tourist_ests = TouristEstablishments::with('signOffs', 'testResults', 'payments.facility', 'printableApplication')
            ->has('signOffs')
            ->has('payments')
            ->has('printableApplication')
            ->where('sign_off_status', 1)
            ->where('created_at', '>', $filterTimeline)
            ->get();

        return view('downloads.tourist_est', compact('tourist_ests'));
    }

    public function customFilterTourEst(Request $request)
    {
        date_default_timezone_set('Etc/GMT+5');
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'interval' => 'nullable|numeric|max:6'
        ]);

        $end_date = $timeline["ending_date"] . " 23:59:59";

        $tourist_ests = TouristEstablishments::with('signOffs', 'testResults', 'payments.facility', 'printableApplication')
            ->has('signOffs')
            ->has('payments')
            ->has('printableApplication')
            ->where('sign_off_status', 1)
            ->whereBetween('created_at', [$timeline['starting_date'], $end_date])
            ->get();

        return view('downloads.tourist_est', compact('tourist_ests'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyPrintable(Request $request)
    {
     $now = \Carbon\Carbon::now()->toDateTimeString();
        try {
            if ($request->route('app_type')) {
              
                PrintableApplications::where('id', $request->route('id'))
                    ->where('application_type_id', $request->route('app_type'))
                    ->update(
                        ['deleted_at' => new DateTime()]
                    );
            } else {
                Downloads::find($request->route('id'))->update([
                    'deleted_at', $now
                ]);
            }
            return "success";
        } catch (Exception $e) {
            return $e->getMessage();
        }
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
    public function destroy(Request $request)
    {
        try {
            if (Downloads::find($request->route('id'))) {
                if (Downloads::find($request->route('id'))->update(['deleted_at' => new DateTime()])) {
                    return "success";
                }
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteAll(Request $request)
    {
        try {
            DB::beginTransaction();
            foreach ($request->data["selected_items"] as $item) {
                if (Downloads::find($item)) {
                    if (!Downloads::find($item)->update(['deleted_at' => new DateTime()])) {
                        throw new Exception("Issue deleting a record");
                    }
                } else {
                    throw new Exception("One of the downloads were not found.");
                }
            }
            DB::commit();
            return "success";
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
