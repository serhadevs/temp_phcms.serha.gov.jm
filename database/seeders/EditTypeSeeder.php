<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EditTypeSeeder extends Seeder
{
    public function run()
    {
        DB::table('edit_types')->insert([
            ['id' => 1, 'name' => 'Update', 'created_at' => '2024-05-12 01:03:57', 'updated_at' => '2024-05-12 01:04:52', 'deleted_at' => null],
            ['id' => 2, 'name' => 'Delete', 'created_at' => '2024-05-12 01:04:45', 'updated_at' => '2024-05-12 01:04:57', 'deleted_at' => null],
            ['id' => 3, 'name' => 'create', 'created_at' => null,'updated_at' => null, 'deleted_at' => null],
        ]);
    }
}
