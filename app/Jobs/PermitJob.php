<?php

namespace App\Jobs;

use App\Models\Downloads;
use App\Models\PermitApplication;
use App\Models\User;
use App\Models\ZippedApplications;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
// use Zipper;

class PermitJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        //$user = User::where('role_id', 108)->get();
        
        $permit_applications = PermitApplication::with('permitCategory', 'payment', 'appointment.examDate.examSites', 'user', 'establishmentClinics', 'testResults', 'signOffs')
            ->where('photo_upload', '<>', NULL)
            ->has('signOffs')
            ->has('testResults')
            //->whereRelation('signOffs', 'created_at', '>', '2024-01-01')
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
                    $folder_date_exist = Storage::disk('public')->exists("downloads/txts/" . $key . "/" . "STC");
                    $content = "";
                    $counter = 0;

                    foreach ($sch_permit as $index) {
                        $permit_download_exist = ZippedApplications::where('application_id', $index->id)->where('application_type_id', 1)->first();

                        if (!$permit_download_exist) {
                            $ext = pathinfo(storage_path() . $index->photo_upload, PATHINFO_EXTENSION);

                            $photo_exists = Storage::disk('public')->exists("photo_uploads/" . $index->permit_no . "." . $ext);

                            if ($photo_exists) {
                                if ($folder_date_exist) {
                                    $photo_already_copied = Storage::disk('public')->exists("downloads/txts/" . $key . "_" . $rand_string . "/" . "STC" . "/" . $index->permit_no . "." . $ext);
                                } else {
                                    $photo_already_copied = Storage::disk('public')->exists("downloads/txts/" . $key . "/" . "STC" . "/" . $index->permit_no . "." . $ext);
                                }

                                if ($folder_date_exist) {
                                    if (!$photo_already_copied) {
                                        Storage::disk("public")->copy("photo_uploads/" . $index->permit_no . "." . $ext, "downloads/txts/" . $key . "_" . $rand_string . "/" . "STC" . "/" . $index->permit_no . "." . $ext);
                                    }
                                } else {
                                    if (!$photo_already_copied) {
                                        Storage::disk("public")->copy("photo_uploads/" . $index->permit_no . "." . $ext, "downloads/txts/" . $key . "/" . "STC" . "/" . $index->permit_no . "." . $ext);
                                    }
                                }

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

                                $counter++;
                            }

                            if ($folder_date_exist) {
                                Storage::disk("public")->put("downloads/txts/" . $key . "_" . $rand_string . "/" . "STC" . "/" . "STC" . "-" . $key . "-Food_Handler_Permits.txt", $content);
                            } else {
                                Storage::disk("public")->put("downloads/txts/" . $key . "/" . "STC" . "/" . "STC" . "-" . $key . "-Food_Handler_Permits.txt", $content);
                            }

                            ZippedApplications::create(
                                [
                                    'application_type_id' => '1',
                                    'application_id' => $index->id,
                                   
                                    // 'application_amount' => $counter,
                                    // 'category' => 'Food Handlers Permit',
                                    'download_id' => 0,
                                    'written' => 1,
                                ]
                            );
                        }
                    }

                    if (!empty($content)) {
                        if ($folder_date_exist) {
                            $download_url = "downloads/archives/" . "STC-" . $key . "_" . $rand_string . ".zip";
                        } else {
                            $download_url = "downloads/archives/" . "STC-" . $key . ".zip";
                        }

                        $create_download = Downloads::create(
                            [
                                'application_type_id' => 1,
                                'application_amount' => $counter,
                                'category' => 'Food Handlers Permit',
                                'download_url' => $download_url
                            ]
                        );

                        foreach ($sch_permit as $each_permit) {
                            ZippedApplications::where('application_id', $each_permit->id)->where('application_type_id', 1)->first()->update(
                                [
                                    'download_id' => $create_download->id
                                ]
                            );
                        }

                        if ($folder_date_exist) {
                            $files = glob(storage_path('app/public/downloads/txts/' . $key . "_" . $rand_string . '/STC/*'));
                            $zip = new ZipArchive();
                            if ($zip->open(storage_path('app/public/downloads/archives/' . "STC-" . $key . "_" . $rand_string . '.zip'), ZipArchive::CREATE)) {
                                foreach ($files as $file) {
                                    $zip->addFile($file, basename($file));
                                }
                            }
                            $zip->close();
                        } else {
                            $files = glob(storage_path('app/public/downloads/txts/' . $key . '/STC/*'));
                            $zip = new ZipArchive();
                            if ($zip->open(storage_path('app/public/downloads/archives/' . "STC-" . $key . '.zip'), ZipArchive::CREATE)) {
                                foreach ($files as $file) {
                                    $zip->addFile($file, basename($file));
                                }
                            }
                            $zip->close();
                        }
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
                    $folder_date_exist = Storage::disk('public')->exists("downloads/txts/" . $key . "/" . "STT");
                    $content = "";
                    $counter = 0;

                    foreach ($stt_permit as $index) {
                        $permit_download_exist = ZippedApplications::where('application_id', $index->id)->where('application_type_id', 1)->first();

                        if (!$permit_download_exist) {
                            $ext = pathinfo(storage_path() . $index->photo_upload, PATHINFO_EXTENSION);

                            $photo_exists = Storage::disk('public')->exists("photo_uploads/" . $index->permit_no . "." . $ext);
                            // dd($photo_exists);

                            if ($photo_exists) {
                                if ($folder_date_exist) {
                                    $photo_already_copied = Storage::disk('public')->exists("downloads/txts/" . $key . "_" . $rand_string . "/" . "STT" . "/" . $index->permit_no . "." . $ext);
                                } else {
                                    $photo_already_copied = Storage::disk('public')->exists("downloads/txts/" . $key . "/" . "STT" . "/" . $index->permit_no . "." . $ext);
                                }

                                if ($folder_date_exist) {
                                    if (!$photo_already_copied) {
                                        Storage::disk("public")->copy("photo_uploads/" . $index->permit_no . "." . $ext, "downloads/txts/" . $key . "_" . $rand_string . "/" . "STT" . "/" . $index->permit_no . "." . $ext);
                                    }
                                } else {
                                    if (!$photo_already_copied) {
                                        Storage::disk("public")->copy("photo_uploads/" . $index->permit_no . "." . $ext, "downloads/txts/" . $key . "/" . "STT" . "/" . $index->permit_no . "." . $ext);
                                    }
                                }

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

                                $counter++;
                            }

                            if ($folder_date_exist) {
                                Storage::disk("public")->put("downloads/txts/" . $key . "_" . $rand_string . "/" . "STT" . "/" . "STT" . "-" . $key . "-Food_Handler_Permits.txt", $content);
                            } else {
                                Storage::disk("public")->put("downloads/txts/" . $key . "/" . "STT" . "/" . "STT" . "-" . $key . "-Food_Handler_Permits.txt", $content);
                            }

                            ZippedApplications::create(
                                [
                                    'application_type_id' => '1',
                                    'application_id' => $index->id,
                                    // 'application_amount' => $counter,
                                    // 'category' => 'Food Handlers Permit',
                                    'download_id' => 0,
                                    'written' => 1,
                                ]
                            );
                        }
                    }

                    if (!empty($content)) {
                        if ($folder_date_exist) {
                            $download_url = "downloads/archives/" . "STT-" . $key . "_" . $rand_string . ".zip";
                        } else {
                            $download_url = "downloads/archives/" . "STT-" . $key . ".zip";
                        }

                        $create_download = Downloads::create(
                            [
                                'application_type_id' => 1,
                                'application_amount' => $counter,
                                'category' => 'Food Handlers Permit',
                                'download_url' => $download_url
                            ]
                        );

                        foreach ($stt_permit as $each_permit) {
                            ZippedApplications::where('application_id', $each_permit->id)->where('application_type_id', 1)->first()->update(
                                [
                                    'download_id' => $create_download->id
                                ]
                            );
                        }

                        if ($folder_date_exist) {
                            $files = glob(storage_path('app/public/downloads/txts/' . $key . "_" . $rand_string . '/STT/*'));
                            $zip = new ZipArchive();
                            if ($zip->open(storage_path('app/public/downloads/archives/' . "STT-" . $key . "_" . $rand_string . '.zip'), ZipArchive::CREATE)) {
                                foreach ($files as $file) {
                                    $zip->addFile($file, basename($file));
                                }
                            }
                            $zip->close();
                        } else {
                            $files = glob(storage_path('app/public/downloads/txts/' . $key . '/STT/*'));
                            $zip = new ZipArchive();
                            if ($zip->open(storage_path('app/public/downloads/archives/' . "STT-" . $key . '.zip'), ZipArchive::CREATE)) {
                                foreach ($files as $file) {
                                    $zip->addFile($file, basename($file));
                                }
                            }
                            $zip->close();
                        }
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
                    $folder_date_exist = Storage::disk('public')->exists("downloads/txts/" . $key . "/" . "KSA");
                    $content = "";
                    $counter = 0;

                    foreach ($ksa_permit as $index) {
                        $permit_download_exist = ZippedApplications::where('application_id', $index->id)->where('application_type_id', 1)->first();

                        if (!$permit_download_exist) {
                            $ext = pathinfo(storage_path() . $index->photo_upload, PATHINFO_EXTENSION);

                            $photo_exists = Storage::disk('public')->exists("photo_uploads/" . $index->permit_no . "." . $ext);

                            if ($photo_exists) {
                                if ($folder_date_exist) {
                                    $photo_already_copied = Storage::disk('public')->exists("downloads/txts/" . $key . "_" . $rand_string . "/" . "KSA" . "/" . $index->permit_no . "." . $ext);
                                } else {
                                    $photo_already_copied = Storage::disk('public')->exists("downloads/txts/" . $key . "/" . "KSA" . "/" . $index->permit_no . "." . $ext);
                                }

                                if ($folder_date_exist) {
                                    if (!$photo_already_copied) {
                                        Storage::disk("public")->copy("photo_uploads/" . $index->permit_no . "." . $ext, "downloads/txts/" . $key . "_" . $rand_string . "/" . "KSA" . "/" . $index->permit_no . "." . $ext);
                                    }
                                } else {
                                    if (!$photo_already_copied) {
                                        Storage::disk("public")->copy("photo_uploads/" . $index->permit_no . "." . $ext, "downloads/txts/" . $key . "/" . "KSA" . "/" . $index->permit_no . "." . $ext);
                                    }
                                }

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

                                $counter++;
                            }

                            if ($folder_date_exist) {
                                Storage::disk("public")->put("downloads/txts/" . $key . "_" . $rand_string . "/" . "KSA" . "/" . "KSA" . "-" . $key . "-Food_Handler_Permits.txt", $content);
                            } else {
                                Storage::disk("public")->put("downloads/txts/" . $key . "/" . "KSA" . "/" . "KSA" . "-" . $key . "-Food_Handler_Permits.txt", $content);
                            }

                            ZippedApplications::create(
                                [
                                    'application_type_id' => '1',
                                    'application_id' => $index->id,
                                    
                                    // 'application_amount' => $counter,
                                    // 'category' => 'Food Handlers Permit',
                                    'download_id' => 0,
                                    'written' => 1,
                                ]
                            );
                        }
                    }

                    if (!empty($content)) {
                        if ($folder_date_exist) {
                            $download_url = "downloads/archives/" . "KSA-" . $key . "_" . $rand_string . ".zip";
                        } else {
                            $download_url = "downloads/archives/" . "KSA-" . $key . ".zip";
                        }

                        $create_download = Downloads::create(
                            [
                                'application_type_id' => 1,
                                'application_amount' => $counter,
                                'category' => 'Food Handlers Permit',
                                'download_url' => $download_url
                            ]
                        );

                        foreach ($ksa_permit as $each_permit) {
                            ZippedApplications::where('application_id', $each_permit->id)->where('application_type_id', 1)->first()->update(
                                [
                                    'download_id' => $create_download->id
                                ]
                            );
                        }

                        if ($folder_date_exist) {
                            $files = glob(storage_path('app/public/downloads/txts/' . $key . "_" . $rand_string . '/KSA/*'));
                            $zip = new ZipArchive();
                            if ($zip->open(storage_path('app/public/downloads/archives/' . "KSA-" . $key . "_" . $rand_string . '.zip'), ZipArchive::CREATE)) {
                                foreach ($files as $file) {
                                    $zip->addFile($file, basename($file));
                                }
                            }
                            $zip->close();
                        } else {
                            $files = glob(storage_path('app/public/downloads/txts/' . $key . '/KSA/*'));
                            $zip = new ZipArchive();
                            if ($zip->open(storage_path('app/public/downloads/archives/' . "KSA-" . $key . '.zip'), ZipArchive::CREATE)) {
                                foreach ($files as $file) {
                                    $zip->addFile($file, basename($file));
                                }
                            }
                            $zip->close();
                        }
                    }
                }
            }
        }

        //Send the notification 


    }
}
