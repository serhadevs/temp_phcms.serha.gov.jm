<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WaiverEstablishmentSeeder extends Seeder
{
    public function run()
    {
        DB::table('waiver_establishments')->insert([
            ['id' =>  1, 'establishment_name' => 'Bellevue Hospital',                        'facility_id' => 2, 'user_id' => 133, 'created_at' => '2025-11-05 12:18:02', 'updated_at' => '2025-11-05 16:18:03', 'deleted_at' => null],
            ['id' =>  2, 'establishment_name' => 'Horizon Adult Remand Centre',              'facility_id' => 2, 'user_id' => 133, 'created_at' => '2025-11-05 12:18:02', 'updated_at' => '2025-11-05 18:37:19', 'deleted_at' => null],
            ['id' =>  3, 'establishment_name' => 'Tower Street Adult Remand',                'facility_id' => 2, 'user_id' => 133, 'created_at' => '2025-11-05 12:18:02', 'updated_at' => '2025-11-05 16:18:03', 'deleted_at' => null],
            ['id' =>  4, 'establishment_name' => 'Carberry Court Special School',            'facility_id' => 2, 'user_id' => 133, 'created_at' => '2025-11-05 12:18:02', 'updated_at' => '2025-11-05 16:18:03', 'deleted_at' => null],
            ['id' =>  5, 'establishment_name' => 'Hope Institute Hospital',                  'facility_id' => 2, 'user_id' => 133, 'created_at' => '2025-11-05 12:18:02', 'updated_at' => '2025-11-05 16:18:03', 'deleted_at' => null],
            ['id' =>  6, 'establishment_name' => 'The Ministry of Health & Wellness',        'facility_id' => 2, 'user_id' => 133, 'created_at' => '2025-11-05 12:18:02', 'updated_at' => '2025-11-05 16:18:03', 'deleted_at' => null],
            ['id' =>  7, 'establishment_name' => 'National Chest Hospital',                  'facility_id' => 2, 'user_id' => 133, 'created_at' => '2025-11-05 12:18:02', 'updated_at' => '2025-11-05 16:18:03', 'deleted_at' => null],
            ['id' =>  8, 'establishment_name' => 'Sir John Golding Rehabilitation Centre',   'facility_id' => 2, 'user_id' => 133, 'created_at' => '2025-11-05 12:18:02', 'updated_at' => '2025-11-05 16:18:03', 'deleted_at' => null],
            ['id' =>  9, 'establishment_name' => 'Bustamante Dietary Department',            'facility_id' => 2, 'user_id' => 133, 'created_at' => '2025-11-05 12:18:02', 'updated_at' => '2025-11-05 16:18:03', 'deleted_at' => null],
            ['id' => 10, 'establishment_name' => 'DEPARTMENT OF DIETETICS KPH AND VJH',     'facility_id' => 3, 'user_id' => 133, 'created_at' => '2026-04-30 18:04:33', 'updated_at' => '2026-04-30 18:04:33', 'deleted_at' => null],
            ['id' => 11, 'establishment_name' => 'KPH/VJH',                                 'facility_id' => 3, 'user_id' => 133, 'created_at' => '2026-05-29 00:00:00', 'updated_at' => '2026-05-29 12:20:30', 'deleted_at' => null],
        ]);
    }
}
