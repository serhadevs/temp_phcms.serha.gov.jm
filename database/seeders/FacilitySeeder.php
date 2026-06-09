<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacilitySeeder extends Seeder
{
    public function run()
    {
        DB::table('facilities')->insert([
            ['id' => 1, 'name' => 'St. Catherine Health Dept.',          'abbr' => 'STC', 'created_at' => null, 'updated_at' => null],
            ['id' => 2, 'name' => 'St. Thomas Health Dept',              'abbr' => 'STT', 'created_at' => null, 'updated_at' => null],
            ['id' => 3, 'name' => 'Kingston & St. Andrew Health Dept',   'abbr' => 'KSA', 'created_at' => null, 'updated_at' => null],
        ]);
    }
}
