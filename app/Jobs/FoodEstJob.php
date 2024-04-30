<?php

namespace App\Jobs;

use App\Models\Downloads;
use App\Models\EstablishmentApplications;
use App\Models\ZippedApplications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class FoodEstJob implements ShouldQueue
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
        $food_establishments = EstablishmentApplications::with('user', 'testResults', 'operators', 'establishmentCategory', 'signOff')
            ->has('signOff')
            ->has('testResults')
            ->whereRelation('signOff', 'created_at', '>', '2024-01-01')
            ->get();

        // dd($food_establishments);

        $grouped_by_facility = $food_establishments->groupBy('user.facility_id');
        // dd($grouped_by_facility);

        $rand_string = rand(1000, 9999);

        foreach ($grouped_by_facility as $key => $facility_permit) {
            dd($facility_permit);
            if ($key == 1) { //St. Catherine Health Dept.
                $sch_per_date = $facility_permit->groupBy('testResults.test_date');
                foreach ($sch_per_date as $key => $sch_permit) {
                    $folder_date_exist = Storage::disk('public')->exists("downloads/establishment-txts/" . $key . "/" . "STC");
                    $content = "";

                    $counter = 0;

                    foreach ($sch_permit as $item) {

                        $permit_download_exist = ZippedApplications::where('application_id', $item->id)->where('application_type_id', 3)->first();

                        if (!$permit_download_exist) {
                            $content = $content . "\t" .
                                trim(ucwords(strtolower($item->establishment_name))) . "\t" . trim(ucwords(strtolower($item->operators[0]?->name_of_operator))) . "\t"
                                . trim(ucwords(strtolower($item->establishment_address))) . "\t" . trim(ucwords(strtolower($item->establishment_address))) . "\t"
                                . $item->permit_no . "Z" . $item->zone . "-0010233" . "\r\n";

                            $counter++;

                            if ($folder_date_exist) {
                                Storage::disk("public")->put("downloads/establishment-txts/" . $key . "_" . $rand_string . "/" . "STC" . "/" . "STC" . "-" . $key . "-Food_Establishment.txt", $content);
                            } else {
                                Storage::disk("public")->put("downloads/establishment-txts/" . $key . "/" . "STC" . "/" . "STC" . "-" . $key . "-Food_Establishment.txt", $content);
                            }

                            $create_download = ZippedApplications::create([
                                'application_type_id' => 3,
                                'application_id' => $item->id,
                                'download_id' => 0
                            ]);
                        }
                    }

                    if (!empty($content)) {
                        if ($folder_date_exist) {
                            $download_url = "downloads/establishment-archives/" . "STC-" . $key . "_" . $rand_string . ".zip";
                        } else {
                            $download_url = "downloads/establishment-archives/" . "STC-" . $key . ".zip";
                        }

                        $create_download = Downloads::create([
                            'application_type_id' => 3,
                            'application_amount' => $counter,
                            'category' => "Food Establishment",
                            'download_url' => $download_url
                        ]);

                        foreach ($sch_permit as $each_permit) {
                            ZippedApplications::where('application_id', $each_permit->id)
                                ->where('application_type_id', 3)
                                ->first()
                                ->update(
                                    [
                                        'download_id' => $create_download->id
                                    ]
                                );
                        }

                        if ($folder_date_exist) {
                            $files = glob(storage_path('app/public/downloads/establishment-txts/' . $key . "_" . $rand_string . '/STC/*'));
                            $zip = new ZipArchive();
                            if ($zip->open(storage_path('app/public/downloads/establishment-archives/' . "STC-" . $key . "_" . $rand_string . '.zip'), ZipArchive::CREATE)) {
                                foreach ($files as $file) {
                                    $zip->addFile($file, basename($file));
                                }
                            }
                            $zip->close();
                        } else {
                            $files = glob(storage_path('app/public/downloads/establishment-txts/' . $key . '/STC/*'));
                            $zip = new ZipArchive();
                            if ($zip->open(storage_path('app/public/downloads/establishment-archives/' . "STC-" . $key . '.zip'), ZipArchive::CREATE)) {
                                foreach ($files as $file) {
                                    $zip->addFile($file, basename($file));
                                }
                            }
                            $zip->close();
                        }
                    }
                }
            } else if ($key == 2) {
                $stt_per_date = $facility_permit->groupBy('testResults.test_date');
                foreach ($stt_per_date as $key => $stt_permit) {
                    $folder_date_exist = Storage::disk('public')->exists("downloads/establishment-txts/" . $key . "/" . "STT");
                    // dd("here");
                    $content = "";
                    $counter = 0;

                    foreach ($stt_permit as $item) {

                        $permit_download_exist = ZippedApplications::where('application_id', $item->id)->where('application_type_id', 3)->first();
                        // dd($item);
                        if (!$permit_download_exist) {
                            $content = $content . "\t" .
                                ucwords(strtolower($item->establishment_name)) . "\t" . ucwords(strtolower($item->operators[0]?->name_of_operator)) . "\t"
                                . ucwords(strtolower($item->establishment_address)) . "\t" . ucwords(strtolower($item->establishment_address)) . "\t"
                                . $item->permit_no . "Z" . $item->zone . "-0010233" . "\r\n";

                            $counter++;

                            if ($folder_date_exist) {
                                Storage::disk("public")->put("downloads/establishment-txts/" . $key . "_" . $rand_string . "/" . "STT" . "/" . "STT" . "-" . $key . "-Food_Establishment.txt", $content);
                            } else {
                                Storage::disk("public")->put("downloads/establishment-txts/" . $key . "/" . "STT" . "/" . "STT" . "-" . $key . "-Food_Establishment.txt", $content);
                            }

                            ZippedApplications::create([
                                'application_type_id' => 3,
                                'application_id' => $item->id,
                                'download_id' => 0
                            ]);
                        }
                    }

                    if (!empty($content)) {
                        if ($folder_date_exist) {
                            $download_url = "downloads/establishment-archives/" . "STT-" . $key . "_" . $rand_string . ".zip";
                        } else {
                            $download_url = "downloads/establishment-archives/" . "STT-" . $key . ".zip";
                        }

                        $create_download = Downloads::create([
                            'application_type_id' => 3,
                            'application_amount' => $counter,
                            'category' => "Food Establishment",
                            'download_url' => $download_url
                        ]);

                        foreach ($stt_permit as $each_permit) {
                            ZippedApplications::where('application_id', $each_permit->id)
                                ->where('application_type_id', 3)
                                ->first()
                                ->update(
                                    [
                                        'download_id' => $create_download->id
                                    ]
                                );
                        }

                        if ($folder_date_exist) {
                            $files = glob(storage_path('app/public/downloads/establishment-txts/' . $key . "_" . $rand_string . '/STT/*'));
                            $zip = new ZipArchive();
                            if ($zip->open(storage_path('app/public/downloads/establishment-archives/' . "STT-" . $key . "_" . $rand_string . '.zip'), ZipArchive::CREATE)) {
                                foreach ($files as $file) {
                                    $zip->addFile($file, basename($file));
                                }
                            }
                        } else {
                            $files = glob(storage_path('app/public/downloads/establishment-txts/' . $key . '/STT/*'));
                            $zip = new ZipArchive();
                            if ($zip->open(storage_path('app/public/downloads/establishment-archives/' . "STT-" . $key . '.zip'), ZipArchive::CREATE)) {
                                foreach ($files as $file) {
                                    $zip->addFile($file, basename($file));
                                }
                            }
                            $zip->close();
                        }
                    }
                }
            } else if ($key == 3) {
                $ksa_per_date = $facility_permit->groupBy('testResults.test_date');
                foreach ($ksa_per_date as $key => $ksa_permit) {
                    $folder_date_exist = Storage::disk('public')->exists("downloads/establishment-txts/" . $key . "/" . "KSA");
                    $content = "";

                    $counter = 0;

                    foreach ($ksa_permit as $item) {

                        $permit_download_exist = ZippedApplications::where('application_id', $item->id)->where('application_type_id', 3)->first();

                        if (!$permit_download_exist) {
                            $content = $content . "\t" .
                                ucwords(strtolower($item->establishment_name)) . "\t" . ucwords(strtolower($item->operators[0]?->name_of_operator)) . "\t"
                                . ucwords(strtolower($item->establishment_address)) . "\t" . ucwords(strtolower($item->establishment_address)) . "\t"
                                . $item->permit_no . "Z" . $item->zone . "-0010233" . "\r\n";

                            $counter++;

                            if ($folder_date_exist) {
                                Storage::disk("public")->put("downloads/establishment-txts/" . $key . "_" . $rand_string . "/" . "KSA" . "/" . "KSA" . "-" . $key . "-Food_Establishment.txt", $content);
                            } else {
                                Storage::disk("public")->put("downloads/establishment-txts/" . $key . "/" . "KSA" . "/" . "KSA" . "-" . $key . "-Food_Establishment.txt", $content);
                            }

                            ZippedApplications::create([
                                'application_type_id' => 3,
                                'application_id' => $item->id,
                                'download_id' => 0
                            ]);
                        }
                    }

                    if (!empty($content)) {
                        if ($folder_date_exist) {
                            $download_url = "downloads/establishment-archives/" . "KSA-" . $key . "_" . $rand_string . ".zip";
                        } else {
                            $download_url = "downloads/establishment-archives/" . "KSA-" . $key . ".zip";
                        }

                        $create_download = Downloads::create([
                            'application_type_id' => 3,
                            'application_amount' => $counter,
                            'category' => "Food Establishment",
                            'download_url' => $download_url
                        ]);

                        foreach ($ksa_permit as $each_permit) {
                            ZippedApplications::where('application_id', $each_permit->id)
                                ->where('application_type_id', 3)
                                ->first()
                                ->update(
                                    [
                                        'download_id' => $create_download->id
                                    ]
                                );
                        }

                        if ($folder_date_exist) {
                            $files = glob(storage_path('app/public/downloads/establishment-txts/' . $key . "_" . $rand_string . '/KSA/*'));
                            $zip = new ZipArchive();
                            if ($zip->open(storage_path('app/public/downloads/establishment-archives/' . "KSA-" . $key . "_" . $rand_string . '.zip'), ZipArchive::CREATE)) {
                                foreach ($files as $file) {
                                    $zip->addFile($file, basename($file));
                                }
                            }
                            $zip->close();
                        } else {
                            $files = glob(storage_path('app/public/downloads/establishment-txts/' . $key . '/KSA/*'));
                            $zip = new ZipArchive();
                            if ($zip->open(storage_path('app/public/downloads/establishment-archives/' . "KSA-" . $key . '.zip'), ZipArchive::CREATE)) {
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
    }
}
