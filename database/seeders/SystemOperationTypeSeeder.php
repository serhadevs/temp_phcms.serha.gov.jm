<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemOperationTypeSeeder extends Seeder
{
    public function run()
    {
        DB::table('system_operation_types')->insert([
            ['id' =>  1, 'name' => 'Application',              'created_at' => '2024-05-12 01:08:30', 'updated_at' => '2024-05-12 01:09:00', 'deleted_at' => null],
            ['id' =>  2, 'name' => 'Health Interview',         'created_at' => '2024-05-12 01:08:34', 'updated_at' => '2024-05-12 01:09:04', 'deleted_at' => null],
            ['id' =>  3, 'name' => 'Test Results',             'created_at' => '2024-05-12 01:08:38', 'updated_at' => '2024-05-12 01:09:07', 'deleted_at' => null],
            ['id' =>  4, 'name' => 'Sign Offs',                'created_at' => '2024-05-12 01:08:41', 'updated_at' => '2024-05-12 01:09:11', 'deleted_at' => null],
            ['id' =>  5, 'name' => 'Payment',                  'created_at' => '2024-05-12 01:08:45', 'updated_at' => '2024-05-12 01:09:15', 'deleted_at' => null],
            ['id' =>  6, 'name' => 'Apointment',               'created_at' => '2024-05-12 10:36:43', 'updated_at' => '2024-05-12 10:36:51', 'deleted_at' => null],
            ['id' =>  7, 'name' => 'Health Interview Symptom', 'created_at' => '2024-05-20 13:15:47', 'updated_at' => '2024-05-20 13:15:47', 'deleted_at' => null],
            ['id' =>  8, 'name' => 'Travel History',           'created_at' => '2024-05-20 13:29:00', 'updated_at' => '2024-05-20 13:29:00', 'deleted_at' => null],
            ['id' =>  9, 'name' => 'Operators',                'created_at' => null,                  'updated_at' => null,                  'deleted_at' => null],
            ['id' => 10, 'name' => 'Managers',                 'created_at' => null,                  'updated_at' => null,                  'deleted_at' => null],
            ['id' => 11, 'name' => 'Service',                  'created_at' => null,                  'updated_at' => null,                  'deleted_at' => null],
            ['id' => 12, 'name' => 'Category',                 'created_at' => null,                  'updated_at' => null,                  'deleted_at' => null],
        ]);
    }
}
