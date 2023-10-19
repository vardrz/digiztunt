<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PelayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pelayanans')->insert([
            [
                'nik_balita' => '1234567890123456',
                'tgl_pelayanan' => '2023-08-20',
                'tb' => 64.50,
                'bb' => 7.40,
                'lingkar_kepala' => 40.80,
                'usia' => 5
            ],
            [
                'nik_balita' => '1234567890123456',
                'tgl_pelayanan' => '2023-09-20',
                'tb' => 65.20,
                'bb' => 7.80,
                'lingkar_kepala' => 41.00,
                'usia' => 6
            ],
        ]);
    }
}
