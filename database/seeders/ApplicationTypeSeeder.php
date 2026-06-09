<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationTypeSeeder extends Seeder
{
    public function run()
    {
        DB::table('application_types')->insert([
            ['id' => 1, 'name' => 'Food Handler Permit', 'created_at' => null, 'updated_at' => null],
            ['id' => 2, 'name' => 'Barbers & Cosmet. etc', 'created_at' => null, 'updated_at' => null],
            ['id' => 3, 'name' => 'Food Establishment', 'created_at' => null, 'updated_at' => null],
            ['id' => 4, 'name' => 'Food Handler Clinic', 'created_at' => null, 'updated_at' => null],
            ['id' => 5, 'name' => 'Swimming Pools', 'created_at' => null, 'updated_at' => null],
            ['id' => 6, 'name' => 'Tourist Establishment', 'created_at' => null, 'updated_at' => null],
            ['id' => 7, 'name' => 'Barbershops & Hair Salon', 'created_at' => null, 'updated_at' => null],
        ]);
    }
}
