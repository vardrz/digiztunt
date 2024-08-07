<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
            UserSeeder::class,
            // BalitaSeeder::class,
            PosyanduSeeder::class,
            // PelayananSeeder::class,
            BalitaAntopometriSeeder::class,
        ]);
    }
}
