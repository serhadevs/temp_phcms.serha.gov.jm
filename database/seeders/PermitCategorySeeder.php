<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermitCategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('permit_categories')->insert([
            ['id' =>  1, 'name' => 'General Foodhandlers',                                                                                    'created_at' => null, 'updated_at' => null],
            ['id' =>  2, 'name' => 'Basic Foodhandlers',                                                                                      'created_at' => null, 'updated_at' => null],
            ['id' =>  3, 'name' => 'Streetside and Itinerant Vendors',                                                                        'created_at' => null, 'updated_at' => null],
            ['id' =>  4, 'name' => 'Milk and Dairy Products Handlers',                                                                        'created_at' => null, 'updated_at' => null],
            ['id' =>  5, 'name' => 'Meat/Poultry/Fish and Seafoods Handlers',                                                                 'created_at' => null, 'updated_at' => null],
            ['id' =>  6, 'name' => 'Butchers and Assistants',                                                                                 'created_at' => null, 'updated_at' => null],
            ['id' =>  7, 'name' => 'Restaurant and Catering Establishments Handlers',                                                         'created_at' => null, 'updated_at' => null],
            ['id' =>  8, 'name' => 'Tourist Establishments Foodhandlers',                                                                     'created_at' => null, 'updated_at' => null],
            ['id' =>  9, 'name' => 'Canning, Bottling, Modified Atmospheric Packaging (MAP) and Sous Vide Packaging Handlers',               'created_at' => null, 'updated_at' => null],
            ['id' => 10, 'name' => 'Bakeries and Pastry Shops Handlers',                                                                      'created_at' => null, 'updated_at' => null],
            ['id' => 11, 'name' => 'Institution Handlers',                                                                                    'created_at' => null, 'updated_at' => null],
            ['id' => 12, 'name' => 'Temporary Events Handlers',                                                                               'created_at' => null, 'updated_at' => null],
            ['id' => 13, 'name' => 'Cottage Industry Handlers',                                                                               'created_at' => null, 'updated_at' => null],
        ]);
    }
}
