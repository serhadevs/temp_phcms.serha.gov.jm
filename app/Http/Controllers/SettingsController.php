<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StmpSettingsRequest;
use App\Mail\SendTestEmailConfig;
use App\Models\Downloads;
use App\Models\PaymentTypeFacilities;
use App\Models\PermitApplication;
use App\Models\StmpSettings;
use App\Models\ZippedApplications;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class SettingsController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkRole:1');
    }

    public function index()
    {

        $stmp = StmpSettings::find(1);
        $roles = DB::table('roles')->get();
        //dd($roles);
        return view('admin.index', compact('stmp', 'roles'));
    }

    public function store(StmpSettingsRequest $request)
    {

        $stmp_settings = $request->validated();

        $email = $stmp_settings['from_address'];

        try {
            $stmp = StmpSettings::where('id', $stmp_settings['id'])->update($stmp_settings);

            if (!$stmp) {
                return redirect()->back()->with('error', 'Unable to update the STMP Settings');
            }

            // dd($stmp_settings);
            // dispatch(new SendTestEmailConfig());
            Mail::to($email)->send(new SendTestEmailConfig());

            return redirect()->route('admin.index')->with('success', 'Successfully Updated STMP Settings. Test Email was sent to: ' . $email);
        } catch (Exception $e) {
            return redirect()->route('admin.index')->with('error', 'Unable to update STMP Settings ' . $e->getMessage());
        } catch (QueryException $e) {
            return redirect()->route('admin.index')->with('error', 'There is an issue with the query ' . $e->getMessage());
        }
    }

    public function create()
    {
        $stmp = StmpSettings::find(1);

        return view('admin.stmp', compact('stmp'));
    }

    public function TestEmail()
    {
        Mail::to('tywayneb@serha.gov.jm')->send(new SendTestEmailConfig());
    }

    public function allPaymentMethods()
    {
        $ptfs = PaymentTypeFacilities::all();

        return view('admin.payment_type_facilities_setting', compact('ptfs'));
    }

    public function changePMethodActiveStatus($payment_type_id, $facility_id)
    {
        try {
            $ptf = PaymentTypeFacilities::where('payment_type_id', $payment_type_id)
                ->where('facility_id', $facility_id)
                ->first();
            if ($ptf->status == "0") {
                PaymentTypeFacilities::where('payment_type_id', $payment_type_id)
                    ->where('facility_id', $facility_id)->update(["status" => 1]);
                return ['success', "Payment Method has been activated"];
            } else {
                PaymentTypeFacilities::where('payment_type_id', $payment_type_id)
                    ->where('facility_id', $facility_id)->update(["status" => 0]);
                return ['success', "Payment Method has been deactivated"];
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function customPrint()
    {
        return view('admin.custom_print');
    }

    //New Function
    public function generateCustomPrint(Request $request)
    {
        $criteria = $request->validate([
            'application_ids' => "required",
            'application_type_id' => 'required'
        ]);

        if ($criteria['application_type_id'] == '1') {
            $reasons = "";
            $zip_paths = [];

            $application_ids = explode(',', $criteria['application_ids']);

            foreach ($application_ids as $id) {
                $permit = PermitApplication::with('permitCategory', 'payment', 'appointment.examDate.examSites', 'user', 'establishmentClinics', 'testResults', 'signOffs')->find($id);

                if ($permit->photo_upload == NULL) {
                    $reasons = $reasons . "ID: " . $id . "\t Reason: " + "Permit application does not have an image." . "\n";
                }

                if (empty($permit->signOffs)) {
                    $reasons = $reasons . "ID: " . $id . "\t Reason: " + "Permit was not signed off." . "\n";
                }

                if (empty($permit->testResults)) {
                    $reasons = $reasons  . "ID: " . $id . "\t Reason: " + "Permit test results was not entered." . "\n";
                }

                $ext = pathinfo(storage_path() . $permit->photo_upload, PATHINFO_EXTENSION);

                if (!Storage::disk('public')->exists("photo_uploads/" . $permit->permit_no . "." . $ext)) {
                    $reasons = $reasons  . "ID: " . $id . "\t Reason: " + "Photo does not exist" . "\n";
                }
            }

            $permit_applications = PermitApplication::with('permitCategory', 'payment', 'appointment.examDate.examSites', 'user', 'establishmentClinics', 'testResults', 'signOffs')
                ->where('photo_upload', '<>', NULL)
                ->has('signOffs')
                ->has('testResults')
                ->whereIn('application_id', $application_ids)
                ->get();

            $grouped_by_facility = $permit_applications->groupBy('user.facility_id');
            $rand_string = rand(1000, 9999);

            foreach ($grouped_by_facility as $key => $facility_permit) {
                if ($key == 1) {
                    $sch_per_date = $facility_permit->groupBy(function ($facility_permit) {
                        if ($facility_permit->establishment_clinic_id == NULL) {
                            return $facility_permit->appointment[0]?->appointment_date;
                        } else {
                            return $facility_permit->establishmentClinics?->proposed_date;
                        }
                    });

                    foreach ($sch_per_date as $key => $sch_permit) {
                        // $folder_date_exist = Storage::disk('public')->exists("downloads/txts/" . $key . "/" . "STC");
                        $content = "";
                        $counter = 0;
                        foreach ($sch_permit as $index) {
                            $permit_download_exist = ZippedApplications::where('application_id', $index->id)->where('application_type_id', 1)->first();
                            if (!$permit_download_exist) {
                                $ext = pathinfo(storage_path() . $index->photo_upload, PATHINFO_EXTENSION);

                                $photo_exists = Storage::disk('public')->exists("photo_uploads/" . $index->permit_no . "." . $ext);

                                if ($photo_exists) {
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
