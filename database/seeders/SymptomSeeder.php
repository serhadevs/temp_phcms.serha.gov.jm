<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SymptomSeeder extends Seeder
{
    public function run()
    {
        DB::table('symptoms')->insert([
            ['id' => 1, 'name' => 'Skin Rash',                                                     'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => 2, 'name' => 'Boils or Sores',                                                'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => 3, 'name' => 'Diarrhoea and/or vomiting now or within the last seven days',   'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => 4, 'name' => 'Discharge from the eye',                                        'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => 5, 'name' => 'Discharge from the ear',                                        'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => 6, 'name' => 'Discharge from the nose',                                       'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
        ]);
    }
}
