<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstablishmentCategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('establishment_categories')->insert([
            ['id' =>  1, 'name' => 'Full service restaurants, including a-la-carte restaurants',                                                                             'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' =>  2, 'name' => 'Quick service restaurants (including fanchise operators, pizzerias, delicatessens and all other types of cafes)',                        'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' =>  3, 'name' => 'Food service operations within institutions, including hospitals, schools, colleges, universities',                                       'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' =>  4, 'name' => 'Food processing and manufacturing plants, including beverage, bottling, canning and ice making plants',                                  'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' =>  5, 'name' => 'Meat, poultry and fish processing plants',                                                                                              'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' =>  6, 'name' => 'Milk, ice cream and frozen novelty plants',                                                                                             'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' =>  7, 'name' => 'Food commissaries and dry food stands',                                                                                                 'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' =>  8, 'name' => 'In-flight food catering services and other food catering establishments',                                                                'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' =>  9, 'name' => 'Meat, poultry, fish shops',                                                                                                             'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => 10, 'name' => 'Supermarkets, bakeries, pastry shops',                                                                                                  'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => 11, 'name' => 'Food warehouses, cold storage facilities and wholesale food stores',                                                                     'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => 12, 'name' => 'Itinerant vendors, snack shops and food shops',                                                                                         'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => 13, 'name' => 'Any coin-operated food vending machine located on premises accessible to the public',                                                    'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => 14, 'name' => 'Any other public food-handling premises or food vending operations',                                                                     'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => 15, 'name' => 'Hospitals',                                                                                                                             'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => 16, 'name' => 'Ice Making plants',                                                                                                                     'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => 17, 'name' => 'Meats processing plants',                                                                                                               'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => 18, 'name' => 'Poultry processing plants',                                                                                                             'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => 19, 'name' => 'Fish processing plants',                                                                                                                'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => 20, 'name' => 'Meat shops',                                                                                                                            'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => 21, 'name' => 'Poultry shops',                                                                                                                         'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => 22, 'name' => 'Fish shops',                                                                                                                            'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => 23, 'name' => 'Supermarkets',                                                                                                                          'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => 24, 'name' => 'Bakeries',                                                                                                                              'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => 25, 'name' => 'Pastry shops',                                                                                                                          'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => 26, 'name' => 'Food warehouses',                                                                                                                       'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => 27, 'name' => 'Cold Storage Facilities',                                                                                                               'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => 28, 'name' => 'Wholesale food stores',                                                                                                                 'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => 29, 'name' => 'Itinerant vendors',                                                                                                                     'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => 30, 'name' => 'Snacks shops',                                                                                                                          'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => 31, 'name' => 'Food shops',                                                                                                                            'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
        ]);
    }
}
