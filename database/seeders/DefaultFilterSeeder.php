<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultFilterSeeder extends Seeder
{
    public function run()
    {
        DB::table('default_filters')->insert([
            ['id' => 1,   'name' => 'yesterday'],
            ['id' => 7,   'name' => 'last week'],
            ['id' => 30,  'name' => 'last month'],
            ['id' => 90,  'name' => 'last 3 months'],
            ['id' => 180, 'name' => 'last 6 months'],
            ['id' => 181, 'name' => 'today'],
        ]);
    }
}
