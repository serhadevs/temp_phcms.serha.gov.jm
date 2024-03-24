<?php

namespace App\Http\Controllers;

use App\Models\Downloads;
use Illuminate\Http\Request;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;

class DownloadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function food_handlers()
    {
        $downloads = Downloads::with('zippedApplications.payment.facility')
            ->where('application_type_id', 1)
            ->whereRelation('zippedApplications.payment', 'application_type_id', 1)
            ->where('created_at', '>', '2023-12-05')
            ->get();

        $application_type_id = 1;

        return view('downloads.food_handlers_permits', compact('downloads', 'application_type_id'));
    }

    public function food_est()
    {
        $downloads = Downloads::with('zippedApplications.payment.facility')
            ->where('application_type_id', 3)
            ->whereRelation('zippedApplications.payment', 'application_type_id', 1)
            ->where('created_at', '>', '2024-01-01')
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
        $download = Downloads::find($request->download_id);
        if ($download) {
            $download->update([
                'download_date' => new DateTime()
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
