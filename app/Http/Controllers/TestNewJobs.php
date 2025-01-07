<?php

namespace App\Http\Controllers;

use App\Models\Downloads;
use App\Models\EstablishmentClinics;
use App\Models\PermitApplication;
use App\Models\ZippedApplications;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use ZipArchive;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TestNewJobs extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function printClinicPermits($clinic_id)
    {
        try {
            $counter = 0;
            $rand_string = explode('.', time() / rand(10000, 99999))[0];
            $permits = PermitApplication::with('signOffs.user', 'user', 'establishmentClinics')
                ->has('signOffs')
                ->where('establishment_clinic_id', $clinic_id)
                ->get();

            $content = "";
            $key = $permits->first()?->establishmentClinics?->proposed_date;
            $zip = new ZipArchive();
            $download_url = "downloads/archives/" . "KSA-" . $key . "_" . $rand_string . '.zip';

            $create_download = Downloads::create([
                'application_type_id' => 1,
                'application_amount' => 0,
                'category' => 'Food Handlers Permit',
                'download_url' => $download_url
            ]);

            if ($zip->open(storage_path('app/public/downloads/archives/' . "KSA-" . $key . "_" . $rand_string . '.zip'), ZipArchive::CREATE)) {
                foreach ($permits as $index) {
                    $ext = explode(".", $index->photo_upload)[1];
                    $file = glob(storage_path('app/public/' . $index->photo_upload));
                    $zip->addFile($file[0], basename($file[0]));

                    $content = $content . strtoupper(substr($index->permit_no, 0, -2)) . "\t"
                        . strtoupper($index->lastname . "\t" . strtoupper($index->firstname)) . "\t"
                        . "S1" . "\t"
                        . "SCHD" . "\t"
                        . strtoupper($index->permitCategory?->name) . "\t"
                        . Carbon::parse($key)->format('m/d/Y') . "\t"
                        . Carbon::parse($index->signOffs?->expiry_date)->format('m/d/Y')
                        . "\t" . strtoupper($index->permit_no) . '.' . $ext . "\t"
                        . "DR. " . strtoupper($index->signOffs?->user?->firstname) . " "
                        . strtoupper($index->signOffs?->user?->lastname) . ".wmf"
                        . ($index->permitCategory?->name == "Tourist Establishments Foodhandlers" ? "\t" . $index->permit_type . "TRUE\t" : "\t" . strtoupper($index->permit_type) . "\t") . "KSA-" . explode('-', $index->signOffs?->sign_off_date)[0] . "-" . "\r\n";

                    ZippedApplications::create([
                        'application_type_id' => '1',
                        'application_id' => $index->id,
                        'download_id' => $create_download->id
                    ]);
                    $counter++;
                }
                $zip->addFromString("KSA" . "-" . $key . "-Food_Handler_Permits.txt", $content);
            }
            $zip->close();
            $create_download->update(['application_amount' => $counter]);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function permitJob($id)
    {
        //Get all permit applications
        $permit_applications = PermitApplication::with('permitCategory', 'payment', 'appointment.examDate.examSites', 'user', 'establishmentClinics', 'testResults', 'signOffs', 'zippedApplication')
            ->where('photo_upload', '<>', NULL)
            ->where('photo_upload', '<>', '0')
            ->has('signOffs')
            ->doesntHave('zippedApplication')
            ->has('payment')
            ->whereRelation('signOffs', 'created_at', '>', '2024-' . $id . '-15')
            ->whereRelation('signOffs', 'created_at', '<', '2024-' . ($id + 1) . '-15')
            ->has('testResults')
            ->get();
        
            // dd($permit_applications);

        //group by facility
        $grouped_by_facility = $permit_applications->groupBy('user.facility_id');

        $rand_string = explode('.', time() / rand(10000, 99999))[0];

        //go through each group
        // foreach ($grouped_by_facility as $key => $facility_permit) {
        //     //Key = facility_id
        //     if ($key == 1) {
        //         $sch_per_date = $facility_permit->groupBy(function ($facility_permit) {
        //             if ($facility_permit->establishment_clinic_id == NULL) {
        //                 return $facility_permit->appointment[0]?->appointment_date;
        //             } else {
        //                 return $facility_permit->establishmentClinics?->proposed_date;
        //             }
        //         });

        //         foreach ($sch_per_date as $key => $sch_permit) {
        //             $content = "";
        //             $counter = 0;

        //             $zip = new ZipArchive();
        //             $download_url = "downloads/archives/" . "STC-" . $key . "_" . $rand_string . '.zip';

        //             $create_download = Downloads::create([
        //                 'application_type_id' => 1,
        //                 'application_amount' => 0,
        //                 'category' => 'Food Handlers Permit',
        //                 'download_url' => $download_url
        //             ]);
        //             // $zip->open(storage_path('app/public/downloads/archives/' . "STC-" . $key . "_" . $rand_string . '.zip'
        //             if ($zip->open(storage_path('app/public/' . $download_url), ZipArchive::CREATE)) {
        //                 DB::beginTransaction();
        //                 foreach ($sch_permit as $index) {
        //                     $ext = pathinfo(storage_path() . $index->photo_upload, PATHINFO_EXTENSION);
        //                     $photo_exists = Storage::disk('public')->exists("photo_uploads/" . $index->permit_no . "." . $ext);
        //                     if ($photo_exists) {
        //                         $content = $content . strtoupper(substr($index->permit_no, 0, -2)) . "\t"
        //                             . strtoupper($index->lastname . "\t" . strtoupper($index->firstname)) . "\t"
        //                             . "S1" . "\t"
        //                             . "SCHD" . "\t"
        //                             . strtoupper($index->permitCategory?->name) . "\t"
        //                             . Carbon::parse($key)->format('m/d/Y') . "\t"
        //                             . Carbon::parse($index->signOffs?->expiry_date)->format('m/d/Y')
        //                             . "\t" . strtoupper($index->permit_no) . '.' . $ext . "\t"
        //                             . "DR. " . strtoupper($index->signOffs?->user?->firstname) . " "
        //                             . strtoupper($index->signOffs?->user?->lastname) . ".wmf"
        //                             . ($index->permitCategory?->name == "Tourist Establishments Foodhandlers" ? "\t" . $index->permit_type . "TRUE\t" : "\t" . strtoupper($index->permit_type) . "\t") . "STC-" . explode('-', $index->signOffs?->sign_off_date)[0] . "-" . "\r\n";

        //                         if (str_contains($content, $index->permit_no)) {
        //                             ZippedApplications::create([
        //                                 'application_type_id' => '1',
        //                                 'application_id' => $index->id,
        //                                 'download_id' => 0
        //                             ]);
        //                             $counter++;
        //                         }
        //                     }
        //                 }
        //                 if ($content != "") {
        //                     $zip->addFromString("STC" . "-" . $key . "-Food_Handler_Permits.txt", $content);
        //                 }
        //                 DB::commit();
        //             }
        //             $zip->close();

        //             if ($content == "") {
        //                 //Delete zip file 
        //                 foreach (ZippedApplications::where('download_id', $create_download->id) as $zippedApp) {
        //                     $zippedApp->update(['deleted_at' => new DateTime()]);
        //                 }
        //                 $create_download->update(["deleted_at" => new DateTime()]);
        //             }
        //         }
        //     } else if ($key == 2) {
        //         $stt_per_date = $facility_permit->groupBy(function ($facility_permit) {
        //             if ($facility_permit->establishment_clinic_id == NULL) {
        //                 return $facility_permit->appointment[0]?->appointment_date;
        //             } else {
        //                 return $facility_permit->establishmentClinics?->proposed_date;
        //             }
        //         });

        //         foreach ($stt_per_date as $key => $stt_permit) {
        //             $content = "";
        //             $counter = 0;

        //             $zip = new ZipArchive();
        //             $download_url = "downloads/archives/" . "STT-" . $key . "_" . $rand_string . ".zip";

        //             $create_download = Downloads::create([
        //                 'application_type_id' => 1,
        //                 'application_amount' => 0,
        //                 'category' => 'Food Handlers Permit',
        //                 'download_url' => $download_url
        //             ]);

        //             if ($zip->open(storage_path('app/public/' . $download_url), ZipArchive::CREATE)) {
        //                 DB::beginTransaction();
        //                 foreach ($stt_permit as $index) {
        //                     $ext = pathinfo(storage_path() . $index->photo_upload, PATHINFO_EXTENSION);
        //                     $photo_exists = Storage::disk('public')->exists("photo_uploads/" . $index->permit_no . "." . $ext);
        //                     if ($photo_exists) {
        //                         $content = $content . strtoupper(substr($index->permit_no, 0, -2)) . "\t"
        //                             . strtoupper($index->lastname . "\t" . strtoupper($index->firstname)) . "\t"
        //                             . "S1" . "\t"
        //                             . "STHD" . "\t"
        //                             . strtoupper($index->permitCategory?->name) . "\t"
        //                             . Carbon::parse($key)->format('m/d/Y') . "\t"
        //                             . Carbon::parse($index->signOffs?->expiry_date)->format('m/d/Y')
        //                             . "\t" . strtoupper($index->permit_no) . '.' . $ext . "\t"
        //                             . "DR. " . strtoupper($index->signOffs?->user?->firstname) . " "
        //                             . strtoupper($index->signOffs?->user?->lastname) . ".wmf"
        //                             . ($index->permitCategory?->name == "Tourist Establishments Foodhandlers" ? "\t" . $index->permit_type . "TRUE\t" : "\t" . strtoupper($index->permit_type) . "\t") . "STT-" . explode('-', $index->signOffs?->sign_off_date)[0] . "-" . "\r\n";

        //                         if (str_contains($content, $index->permit_no)) {
        //                             ZippedApplications::create([
        //                                 'application_type_id' => '1',
        //                                 'application_id' => $index->id,
        //                                 'download_id' => 0
        //                             ]);
        //                             $counter++;
        //                         }
        //                     }
        //                 }
        //                 if ($content != "") {
        //                     $zip->addFromString("STT" . "-" . $key . "-Food_Handler_Permits.txt", $content);
        //                 }
        //                 DB::commit();
        //             }
        //             $zip->close();

        //             if ($content == "") {
        //                 //Delete zip file 
        //                 foreach (ZippedApplications::where('download_id', $create_download->id) as $zippedApp) {
        //                     $zippedApp->update(['deleted_at' => new DateTime()]);
        //                 }
        //                 $create_download->update(["deleted_at" => new DateTime()]);
        //             }
        //         }
        //     } else if ($key == 3) {
        //         $ksa_per_date = $facility_permit->groupBy(function ($facility_permit) {
        //             if ($facility_permit->establishment_clinic_id == NULL) {
        //                 return $facility_permit->appointment[0]?->appointment_date;
        //             } else {
        //                 return $facility_permit->establishmentClinics?->proposed_date;
        //             }
        //         });

        //         foreach ($ksa_per_date as $key => $ksa_permit) {
        //             $content = "";
        //             $counter = 0;

        //             $zip = new ZipArchive();
        //             $download_url = "downloads/archives/" . "KSA-" . $key . "_" . $rand_string . '.zip';

        //             $create_download = Downloads::create([
        //                 'application_type_id' => 1,
        //                 'application_amount' => 0,
        //                 'category' => 'Food Handlers Permit',
        //                 'download_url' => $download_url
        //             ]);
        //             // $zip->open(storage_path('app/public/downloads/archives/' . "STC-" . $key . "_" . $rand_string . '.zip'
        //             if ($zip->open(storage_path('app/public/' . $download_url), ZipArchive::CREATE)) {
        //                 DB::beginTransaction();
        //                 foreach ($ksa_permit as $index) {
        //                     $ext = pathinfo(storage_path() . $index->photo_upload, PATHINFO_EXTENSION);
        //                     $photo_exists = Storage::disk('public')->exists("photo_uploads/" . $index->permit_no . "." . $ext);
        //                     if ($photo_exists) {
        //                         $content = $content . strtoupper(substr($index->permit_no, 0, -2)) . "\t"
        //                             . strtoupper($index->lastname . "\t" . strtoupper($index->firstname)) . "\t"
        //                             . "S1" . "\t"
        //                             . "KSAHD" . "\t"
        //                             . strtoupper($index->permitCategory?->name) . "\t"
        //                             . Carbon::parse($key)->format('m/d/Y') . "\t"
        //                             . Carbon::parse($index->signOffs?->expiry_date)->format('m/d/Y')
        //                             . "\t" . strtoupper($index->permit_no) . '.' . $ext . "\t"
        //                             . "DR. " . strtoupper($index->signOffs?->user?->firstname) . " "
        //                             . strtoupper($index->signOffs?->user?->lastname) . ".wmf"
        //                             . ($index->permitCategory?->name == "Tourist Establishments Foodhandlers" ? "\t" . $index->permit_type . "TRUE\t" : "\t" . strtoupper($index->permit_type) . "\t") . "KSA-" . explode('-', $index->signOffs?->sign_off_date)[0] . "-" . "\r\n";

        //                         if (str_contains($content, $index->permit_no)) {
        //                             ZippedApplications::create([
        //                                 'application_type_id' => '1',
        //                                 'application_id' => $index->id,
        //                                 'download_id' => 0
        //                             ]);
        //                             $counter++;
        //                         }
        //                     }
        //                 }
        //                 if ($content != "") {
        //                     $zip->addFromString("STC" . "-" . $key . "-Food_Handler_Permits.txt", $content);
        //                 }
        //             }
        //         }
        //     }
        // }
        foreach ($grouped_by_facility as $key => $facility_permit) {
            //Key = facility_id
            if ($key == 1) {
                $sch_per_date = $facility_permit->groupBy(function ($facility_permit) {
                    if ($facility_permit->establishment_clinic_id == NULL) {
                        return $facility_permit->appointment[0]?->appointment_date;
                    } else {
                        return $facility_permit->establishmentClinics?->proposed_date;
                    }
                });

                foreach ($sch_per_date as $key => $sch_permit) {
                    $content = "";
                    $counter = 0;

                    $zip = new ZipArchive();
                    $download_url = "downloads/archives/" . "STC-" . $key . "_" . $rand_string . '.zip';

                    $create_download = Downloads::create([
                        'application_type_id' => 1,
                        'application_amount' => 0,
                        'category' => 'Food Handlers Permit',
                        'download_url' => $download_url
                    ]);
                    if ($zip->open(storage_path('app/public/' . $download_url), ZipArchive::CREATE)) {
                        DB::beginTransaction();
                        foreach ($sch_permit as $index) {
                            $ext = pathinfo(storage_path() . $index->photo_upload, PATHINFO_EXTENSION);
                            $photo_exists = Storage::disk('public')->exists("photo_uploads/" . $index->permit_no . "." . $ext);
                            if ($photo_exists) {
                                $content = $content . strtoupper(substr($index->permit_no, 0, -2)) . "\t"
                                    . strtoupper($index->lastname . "\t" . strtoupper($index->firstname)) . "\t"
                                    . "S1" . "\t"
                                    . "SCHD" . "\t"
                                    . strtoupper($index->permitCategory?->name) . "\t"
                                    . Carbon::parse($key)->format('m/d/Y') . "\t"
                                    . Carbon::parse($index->signOffs?->expiry_date)->format('m/d/Y')
                                    . "\t" . strtoupper($index->permit_no) . '.' . $ext . "\t"
                                    . "DR. " . strtoupper($index->signOffs?->user?->firstname) . " "
                                    . strtoupper($index->signOffs?->user?->lastname) . ".wmf"
                                    . ($index->permitCategory?->name == "Tourist Establishments Foodhandlers" ? "\t" . $index->permit_type . "TRUE\t" : "\t" . strtoupper($index->permit_type) . "\t") . "STC-" . explode('-', $index->signOffs?->sign_off_date)[0] . "-" . "\r\n";

                                if (str_contains($content, $index->permit_no)) {
                                    ZippedApplications::create([
                                        'application_type_id' => '1',
                                        'application_id' => $index->id,
                                        'download_id' => 0
                                    ]);
                                    $counter++;
                                }
                            }
                        }
                        if ($content != "") {
                            $zip->addFromString("STC" . "-" . $key . "-Food_Handler_Permits.txt", $content);
                        }
                        DB::commit();
                    }
                    $zip->close();

                    if ($content == "") {
                        //Delete zip file 
                        foreach (ZippedApplications::where('download_id', $create_download->id) as $zippedApp) {
                            $zippedApp->update(['deleted_at' => new DateTime()]);
                        }
                        $create_download->update(["deleted_at" => new DateTime()]);
                    }
                }
            } else if ($key == 2) {
                $stt_per_date = $facility_permit->groupBy(function ($facility_permit) {
                    if ($facility_permit->establishment_clinic_id == NULL) {
                        return $facility_permit->appointment[0]?->appointment_date;
                    } else {
                        return $facility_permit->establishmentClinics?->proposed_date;
                    }
                });

                foreach ($stt_per_date as $key => $stt_permit) {
                    $content = "";
                    $counter = 0;

                    $zip = new ZipArchive();
                    $download_url = "downloads/archives/" . "STT-" . $key . "_" . $rand_string . ".zip";

                    $create_download = Downloads::create([
                        'application_type_id' => 1,
                        'application_amount' => 0,
                        'category' => 'Food Handlers Permit',
                        'download_url' => $download_url
                    ]);

                    if ($zip->open(storage_path('app/public/' . $download_url), ZipArchive::CREATE)) {
                        DB::beginTransaction();
                        foreach ($stt_permit as $index) {
                            $ext = pathinfo(storage_path() . $index->photo_upload, PATHINFO_EXTENSION);
                            $photo_exists = Storage::disk('public')->exists("photo_uploads/" . $index->permit_no . "." . $ext);
                            if ($photo_exists) {
                                $content = $content . strtoupper(substr($index->permit_no, 0, -2)) . "\t"
                                    . strtoupper($index->lastname . "\t" . strtoupper($index->firstname)) . "\t"
                                    . "S1" . "\t"
                                    . "STHD" . "\t"
                                    . strtoupper($index->permitCategory?->name) . "\t"
                                    . Carbon::parse($key)->format('m/d/Y') . "\t"
                                    . Carbon::parse($index->signOffs?->expiry_date)->format('m/d/Y')
                                    . "\t" . strtoupper($index->permit_no) . '.' . $ext . "\t"
                                    . "DR. " . strtoupper($index->signOffs?->user?->firstname) . " "
                                    . strtoupper($index->signOffs?->user?->lastname) . ".wmf"
                                    . ($index->permitCategory?->name == "Tourist Establishments Foodhandlers" ? "\t" . $index->permit_type . "TRUE\t" : "\t" . strtoupper($index->permit_type) . "\t") . "STT-" . explode('-', $index->signOffs?->sign_off_date)[0] . "-" . "\r\n";

                                if (str_contains($content, $index->permit_no)) {
                                    ZippedApplications::create([
                                        'application_type_id' => '1',
                                        'application_id' => $index->id,
                                        'download_id' => 0
                                    ]);
                                    $counter++;
                                }
                            }
                        }
                        if ($content != "") {
                            $zip->addFromString("STT" . "-" . $key . "-Food_Handler_Permits.txt", $content);
                        }
                        DB::commit();
                    }
                    $zip->close();

                    if ($content == "") {
                        //Delete zip file 
                        foreach (ZippedApplications::where('download_id', $create_download->id) as $zippedApp) {
                            $zippedApp->update(['deleted_at' => new DateTime()]);
                        }
                        $create_download->update(["deleted_at" => new DateTime()]);
                    }
                }
            } else if ($key == 3) {
                $ksa_per_date = $facility_permit->groupBy(function ($facility_permit) {
                    if ($facility_permit->establishment_clinic_id == NULL) {
                        return $facility_permit->appointment[0]?->appointment_date;
                    } else {
                        return $facility_permit->establishmentClinics?->proposed_date;
                    }
                });

                foreach ($ksa_per_date as $key => $ksa_permit) {
                    $content = "";
                    $counter = 0;

                    $zip = new ZipArchive();
                    $download_url = "downloads/archives/" . "KSA-" . $key . "_" . $rand_string . '.zip';

                    $create_download = Downloads::create([
                        'application_type_id' => 1,
                        'application_amount' => 0,
                        'category' => 'Food Handlers Permit',
                        'download_url' => $download_url
                    ]);
                    if ($zip->open(storage_path('app/public/downloads/archives/' . "KSA-" . $key . "_" . $rand_string . '.zip'), ZipArchive::CREATE)) {
                        DB::beginTransaction();
                        foreach ($ksa_permit as $index) {
                            $ext = pathinfo(storage_path() . $index->photo_upload, PATHINFO_EXTENSION);
                            $photo_exists = Storage::disk('public')->exists("photo_uploads/" . $index->permit_no . "." . $ext);
                            if ($photo_exists) {
                                $content = $content . strtoupper(substr($index->permit_no, 0, -2)) . "\t"
                                    . strtoupper($index->lastname . "\t" . strtoupper($index->firstname)) . "\t"
                                    . "S1" . "\t"
                                    . "KSAHD" . "\t"
                                    . strtoupper($index->permitCategory?->name) . "\t"
                                    . Carbon::parse($key)->format('m/d/Y') . "\t"
                                    . Carbon::parse($index->signOffs?->expiry_date)->format('m/d/Y')
                                    . "\t" . strtoupper($index->permit_no) . '.' . $ext . "\t"
                                    . "DR. " . strtoupper($index->signOffs?->user?->firstname) . " "
                                    . strtoupper($index->signOffs?->user?->lastname) . ".wmf"
                                    . ($index->permitCategory?->name == "Tourist Establishments Foodhandlers" ? "\t" . $index->permit_type . "TRUE\t" : "\t" . strtoupper($index->permit_type) . "\t") . "KSA-" . explode('-', $index->signOffs?->sign_off_date)[0] . "-" . "\r\n";

                                if (str_contains($content, $index->permit_no)) {
                                    ZippedApplications::create([
                                        'application_type_id' => '1',
                                        'application_id' => $index->id,
                                        'download_id' => 0
                                    ]);
                                    $counter++;
                                }
                            }
                        }
                        if ($content != "") {
                            $zip->addFromString("KSA" . "-" . $key . "-Food_Handler_Permits.txt", $content);
                        }
                       $zip->close(); 
                    }
                    
                    if ($content == "") {
                        //Delete zip file 
                        foreach (ZippedApplications::where('download_id', $create_download->id) as $zippedApp) {
                            $zippedApp->update(['deleted_at' => new DateTime()]);
                        }
                        $create_download->update(["deleted_at" => new DateTime()]);
                    }
                }
            }
        }
    }

    //Neded for setting the record clean
    public function clearAllNonExistentFoodHandlers()
    {
        try {
            $permits_affected = [];
            $i = 0;
            $unzipped_permits = ZippedApplications::where('application_type_id', 1)
                ->with('permitApplication')
                ->doesntHave('permitApplication')
                ->where('created_at', '>', '2024-01-15')
                ->where('created_at', '<', '2024-12-23 23:59:59')
                ->where('written', NULL)
                ->get();

            DB::beginTransaction();
            foreach ($unzipped_permits as $unzipped_permit) {
                $unzipped_permit->update(['written' => 1]);
                $permits_affected[$i] = $unzipped_permit->application_id;
                $i++;
            }
            DB::commit();
            return $permits_affected;
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    //Check if any of the unzipped records were actually non-existent

    //Delete all unzipped records
    public function deleteAllUnzippedPermits()
    {
        try {
            $unzipped_permits = ZippedApplications::where('application_type_id', 1)
                ->where('created_at', '>', '2024-01-15')
                ->where('created_at', '<', '2024-12-23 23:59:59')
                ->where('written', NULL)
                ->get();

            DB::beginTransaction();
            foreach ($unzipped_permits as $unzipped_permit) {
                $unzipped_permit->update(['deleted_at' => new DateTime()]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
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
