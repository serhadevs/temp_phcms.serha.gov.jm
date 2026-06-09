<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StmpSettingSeeder extends Seeder
{
    public function run()
    {
        DB::table('stmp_settings')->insert([
            [
                'id'           => 1,
                'mailer'       => 'smtp',
                'host'         => 'mail.serha.gov.jm',
                'port'         => '587',
                'username'     => 'notifications@serha.gov.jm',
                'password'     => "C[?\"mO_IO>l0G^^E~0|.GIh8£jA^;5<K)s'&",
                'encryption'   => 'ssl',
                'created_at'   => '2024-06-25 16:32:58',
                'updated_at'   => '2026-05-20 08:54:38',
                'deleted_at'   => null,
                'from_address' => 'notifications@serha.gov.jm',
            ],
        ]);
    }
}
