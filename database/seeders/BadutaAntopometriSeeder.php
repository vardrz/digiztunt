<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BadutaAntopometriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('baduta_antopometris')->insert([
            ['usia' => 0, 'jenis_kelamin' => 'lk', 'bbuMedian' => 3.3, 'bbuMin1sd' => 2.9, 'tbuMedian' => 49.9, 'tbuMin1sd' => 48.0],
            ['usia' => 1, 'jenis_kelamin' => 'lk', 'bbuMedian' => 4.5, 'bbuMin1sd' => 3.9, 'tbuMedian' => 54.7, 'tbuMin1sd' => 52.8],
            ['usia' => 2, 'jenis_kelamin' => 'lk', 'bbuMedian' => 5.6, 'bbuMin1sd' => 4.9, 'tbuMedian' => 58.4, 'tbuMin1sd' => 56.4],
            ['usia' => 3, 'jenis_kelamin' => 'lk', 'bbuMedian' => 6.4, 'bbuMin1sd' => 5.7, 'tbuMedian' => 61.4, 'tbuMin1sd' => 59.4],
            ['usia' => 4, 'jenis_kelamin' => 'lk', 'bbuMedian' => 7.0, 'bbuMin1sd' => 6.2, 'tbuMedian' => 63.9, 'tbuMin1sd' => 61.8],
            ['usia' => 5, 'jenis_kelamin' => 'lk', 'bbuMedian' => 7.5, 'bbuMin1sd' => 6.7, 'tbuMedian' => 65.9, 'tbuMin1sd' => 63.8],
            ['usia' => 6, 'jenis_kelamin' => 'lk', 'bbuMedian' => 7.9, 'bbuMin1sd' => 7.1, 'tbuMedian' => 67.6, 'tbuMin1sd' => 65.5],
            ['usia' => 7, 'jenis_kelamin' => 'lk', 'bbuMedian' => 8.3, 'bbuMin1sd' => 7.4, 'tbuMedian' => 69.2, 'tbuMin1sd' => 67.0],
            ['usia' => 8, 'jenis_kelamin' => 'lk', 'bbuMedian' => 8.6, 'bbuMin1sd' => 7.7, 'tbuMedian' => 70.6, 'tbuMin1sd' => 68.4],
            ['usia' => 9, 'jenis_kelamin' => 'lk', 'bbuMedian' => 8.9, 'bbuMin1sd' => 8.0, 'tbuMedian' => 72.0, 'tbuMin1sd' => 69.7],
            ['usia' => 10, 'jenis_kelamin' => 'lk', 'bbuMedian' => 9.2, 'bbuMin1sd' => 8.2, 'tbuMedian' => 73.3, 'tbuMin1sd' => 71.0],
            ['usia' => 11, 'jenis_kelamin' => 'lk', 'bbuMedian' => 9.4, 'bbuMin1sd' => 8.4, 'tbuMedian' => 74.5, 'tbuMin1sd' => 72.2],
            ['usia' => 12, 'jenis_kelamin' => 'lk', 'bbuMedian' => 9.6, 'bbuMin1sd' => 8.6, 'tbuMedian' => 75.7, 'tbuMin1sd' => 73.4],
            ['usia' => 13, 'jenis_kelamin' => 'lk', 'bbuMedian' => 9.9, 'bbuMin1sd' => 8.8, 'tbuMedian' => 76.9, 'tbuMin1sd' => 74.5],
            ['usia' => 14, 'jenis_kelamin' => 'lk', 'bbuMedian' => 10.1, 'bbuMin1sd' => 9.0, 'tbuMedian' => 78.0, 'tbuMin1sd' => 75.6],
            ['usia' => 15, 'jenis_kelamin' => 'lk', 'bbuMedian' => 10.3, 'bbuMin1sd' => 9.2, 'tbuMedian' => 79.1, 'tbuMin1sd' => 76.6],
            ['usia' => 16, 'jenis_kelamin' => 'lk', 'bbuMedian' => 10.5, 'bbuMin1sd' => 9.4, 'tbuMedian' => 80.2, 'tbuMin1sd' => 77.6],
            ['usia' => 17, 'jenis_kelamin' => 'lk', 'bbuMedian' => 10.7, 'bbuMin1sd' => 9.6, 'tbuMedian' => 81.2, 'tbuMin1sd' => 78.6],
            ['usia' => 18, 'jenis_kelamin' => 'lk', 'bbuMedian' => 10.9, 'bbuMin1sd' => 9.8, 'tbuMedian' => 82.3, 'tbuMin1sd' => 79.6],
            ['usia' => 19, 'jenis_kelamin' => 'lk', 'bbuMedian' => 11.1, 'bbuMin1sd' => 10.0, 'tbuMedian' => 83.2, 'tbuMin1sd' => 80.5],
            ['usia' => 20, 'jenis_kelamin' => 'lk', 'bbuMedian' => 11.3, 'bbuMin1sd' => 10.1, 'tbuMedian' => 84.2, 'tbuMin1sd' => 81.4],
            ['usia' => 21, 'jenis_kelamin' => 'lk', 'bbuMedian' => 11.5, 'bbuMin1sd' => 10.3, 'tbuMedian' => 85.1, 'tbuMin1sd' => 82.3],
            ['usia' => 22, 'jenis_kelamin' => 'lk', 'bbuMedian' => 11.8, 'bbuMin1sd' => 10.5, 'tbuMedian' => 86.0, 'tbuMin1sd' => 83.1],
            ['usia' => 23, 'jenis_kelamin' => 'lk', 'bbuMedian' => 12.0, 'bbuMin1sd' => 10.7, 'tbuMedian' => 86.9, 'tbuMin1sd' => 83.9]
        ]);
    }
}
