<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentTypeFacilitySeeder extends Seeder
{
    public function run()
    {
        DB::table('payment_type_facilities')->insert([
            ['payment_type_id' => 1, 'facility_id' => 1, 'created_at' => '2025-03-31 17:00:00', 'updated_at' => '2025-03-31 17:00:00', 'deleted_at' => null, 'status' => null],
            ['payment_type_id' => 1, 'facility_id' => 2, 'created_at' => '2025-03-31 17:00:00', 'updated_at' => '2025-03-31 17:00:00', 'deleted_at' => null, 'status' => null],
            ['payment_type_id' => 1, 'facility_id' => 3, 'created_at' => '2025-03-31 17:00:00', 'updated_at' => '2025-03-31 17:00:00', 'deleted_at' => null, 'status' => null],
            ['payment_type_id' => 2, 'facility_id' => 1, 'created_at' => '2025-03-31 17:00:00', 'updated_at' => '2025-03-31 17:00:00', 'deleted_at' => null, 'status' => null],
            ['payment_type_id' => 2, 'facility_id' => 2, 'created_at' => '2025-09-15 09:00:00', 'updated_at' => null,                  'deleted_at' => null, 'status' => null],
            ['payment_type_id' => 2, 'facility_id' => 3, 'created_at' => '2025-09-15 09:00:00', 'updated_at' => null,                  'deleted_at' => null, 'status' => null],
            ['payment_type_id' => 3, 'facility_id' => 1, 'created_at' => '2025-09-15 09:00:00', 'updated_at' => null,                  'deleted_at' => null, 'status' => null],
            ['payment_type_id' => 4, 'facility_id' => 1, 'created_at' => '2025-10-08 09:00:00', 'updated_at' => null,                  'deleted_at' => null, 'status' => null],
            ['payment_type_id' => 4, 'facility_id' => 2, 'created_at' => '2025-10-08 09:00:00', 'updated_at' => null,                  'deleted_at' => null, 'status' => null],
            ['payment_type_id' => 4, 'facility_id' => 3, 'created_at' => '2025-10-08 09:00:00', 'updated_at' => null,                  'deleted_at' => null, 'status' => null],
            ['payment_type_id' => 5, 'facility_id' => 1, 'created_at' => '2025-10-08 09:00:00', 'updated_at' => null,                  'deleted_at' => null, 'status' => null],
            ['payment_type_id' => 5, 'facility_id' => 2, 'created_at' => '2025-10-08 09:00:00', 'updated_at' => null,                  'deleted_at' => null, 'status' => null],
            ['payment_type_id' => 5, 'facility_id' => 3, 'created_at' => '2025-10-08 09:00:00', 'updated_at' => null,                  'deleted_at' => null, 'status' => null],
        ]);
    }
}
