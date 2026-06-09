<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentTypeSeeder extends Seeder
{
    public function run()
    {
        DB::table('payment_types')->insert([
            ['id' => 1, 'name' => 'Cash',           'created_at' => '2025-03-20 12:00:00', 'updated_at' => '2025-03-20 12:00:00', 'deleted_at' => null],
            ['id' => 2, 'name' => 'NCB - POS',      'created_at' => '2025-03-20 12:00:00', 'updated_at' => '2025-03-20 12:00:00', 'deleted_at' => null],
            ['id' => 3, 'name' => 'Online Payment', 'created_at' => '2025-03-20 12:00:00', 'updated_at' => '2025-03-20 12:00:00', 'deleted_at' => null],
            ['id' => 4, 'name' => 'Wire Transfer',  'created_at' => '2025-10-08 00:00:00', 'updated_at' => '2025-10-08 00:00:00', 'deleted_at' => null],
            ['id' => 5, 'name' => 'Waiver',         'created_at' => '2025-10-08 00:00:00', 'updated_at' => '2025-10-08 00:00:00', 'deleted_at' => null],
        ]);
    }
}
