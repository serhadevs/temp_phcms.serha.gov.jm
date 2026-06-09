<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PriceSeeder extends Seeder
{
    public function run()
    {
        DB::table('prices')->insert([
            ['id' => 1, 'application_type_id' => 1, 'price' => '500.00', 'created_at' => null, 'updated_at' => null],
            ['id' => 2, 'application_type_id' => 2, 'price' => '500.00', 'created_at' => null, 'updated_at' => null],
            ['id' => 3, 'application_type_id' => 3, 'price' => '500.00', 'created_at' => null, 'updated_at' => null],
            ['id' => 4, 'application_type_id' => 4, 'price' => '500.00', 'created_at' => null, 'updated_at' => null],
            ['id' => 5, 'application_type_id' => 5, 'price' => '500.00', 'created_at' => null, 'updated_at' => null],
            ['id' => 6, 'application_type_id' => 6, 'price' => '500.00', 'created_at' => null, 'updated_at' => null],
            ['id' => 7, 'application_type_id' => 1, 'price' => '300.00', 'created_at' => null, 'updated_at' => null],
            ['id' => 8, 'application_type_id' => 1, 'price' => '300.00', 'created_at' => null, 'updated_at' => null],
            ['id' => 9, 'application_type_id' => 1, 'price' => '0.00',   'created_at' => null, 'updated_at' => null],
        ]);
    }
}
