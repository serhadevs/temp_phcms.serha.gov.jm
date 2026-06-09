<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IdentificationTypeSeeder extends Seeder
{
    public function run()
    {
        DB::table('identification_types')->insert([
            ['id' => 1, 'name' => "Driver's License", 'abbr' => 'DL',   'created_at' => '2025-10-30 16:23:33', 'updated_at' => '2025-10-30 16:23:33', 'deleted_at' => null],
            ['id' => 2, 'name' => 'Passport',         'abbr' => 'PP',   'created_at' => '2025-10-30 16:23:33', 'updated_at' => '2025-10-30 16:23:33', 'deleted_at' => null],
            ['id' => 3, 'name' => 'Electoral ID',     'abbr' => 'EID',  'created_at' => '2025-10-30 16:23:33', 'updated_at' => '2025-10-30 16:23:33', 'deleted_at' => null],
            ['id' => 4, 'name' => 'Student ID',       'abbr' => 'STID', 'created_at' => '2025-10-30 16:23:33', 'updated_at' => '2025-10-30 16:23:33', 'deleted_at' => null],
        ]);
    }
}
