<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateEcardIdsSeeder extends Seeder
{
    public function run()
    {
        // Get records from 2025 onward
        $signOffs = DB::table('sign_offs')
            ->whereDate('created_at', '>=', '2025-01-01')
            ->select('id')
            ->get();

        foreach ($signOffs as $record) {

            // Generate unique random 7 digit number
            do {
                $randomNumber = str_pad(random_int(0, 9999999), 7, '0', STR_PAD_LEFT);
                $ecardId = 'ECARD-' . $randomNumber;

                $exists = DB::table('sign_offs')
                    ->where('ecard_id', $ecardId)
                    ->exists();

            } while ($exists); // ensures uniqueness

            DB::table('sign_offs')
                ->where('id', $record->id)
                ->update([
                    'ecard_id' => $ecardId
                ]);
        }
    }
}