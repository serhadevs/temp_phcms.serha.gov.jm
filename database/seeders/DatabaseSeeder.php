<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            FacilitySeeder::class,
            IdentificationTypeSeeder::class,
            PaymentTypeSeeder::class,
            PaymentTypeFacilitySeeder::class,
            PermitCategorySeeder::class,
            PriceSeeder::class,
            StmpSettingSeeder::class,
            SymptomSeeder::class,
            SystemOperationTypeSeeder::class,
            WaiverEstablishmentSeeder::class,
            ApplicationTypeSeeder::class,
            DefaultFilterSeeder::class,
            EditTypeSeeder::class,
            EstablishmentCategorySeeder::class,
            ExamSiteSeeder::class,
        ]);
    }
}
