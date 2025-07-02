<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            ProjectTypeSeeder::class,
            RoleSeeder::class,
            CountryTableSeeder::class,
            AdminSeeder::class,
            UserSeeder::class,
            CertificateSeeder::class,
            // SellCertificateSeeder::class,
            BankDetailSeeder::class,
//            NotificationSeeder::class,
        ]);
    }
}
