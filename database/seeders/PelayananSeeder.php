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
        DB::table('pelayanans')->insert(
            [
                // Bulan Sebelumnya
                // [
                //     "nik_balita" => "2110051001990001",
                //     'tgl_pelayanan' => '2023-09-17',
                //     "tb" => 72,
                //     "bb" => 9,
                //     "lingkar_kepala" => 45,
                //     "usia" => 11,
                // ],
                // [
                //     "nik_balita" => "2108151002990004",
                //     'tgl_pelayanan' => '2023-09-17',
                //     "tb" => 80,
                //     "bb" => 11,
                //     "lingkar_kepala" => 46,
                //     "usia" => 13,
                // ],
                // [
                //     "nik_balita" => "2106221003990007",
                //     'tgl_pelayanan' => '2023-09-17',
                //     "tb" => 57.5,
                //     "bb" => 6.4,
                //     "lingkar_kepala" => 47,
                //     "usia" => 3,
                // ],
                // [
                //     "nik_balita" => "2105111004990010",
                //     'tgl_pelayanan' => '2023-09-17',
                //     "tb" => 78.2,
                //     "bb" => 11,
                //     "lingkar_kepala" => 48,
                //     "usia" => 16,
                // ],
                // [
                //     "nik_balita" => "2103271005990013",
                //     'tgl_pelayanan' => '2023-09-17',
                //     "tb" => 62,
                //     "bb" => 7.0,
                //     "lingkar_kepala" => 49,
                //     "usia" => 6,
                // ],
                // [
                //     "nik_balita" => "2011181006990016",
                //     'tgl_pelayanan' => '2023-09-17',
                //     "tb" => 69.5,
                //     "bb" => 7.8,
                //     "lingkar_kepala" => 50,
                //     "usia" => 10,
                // ],
                // [
                //     "nik_balita" => "2009091007990019",
                //     'tgl_pelayanan' => '2023-09-17',
                //     "tb" => 70.6,
                //     "bb" => 8.2,
                //     "lingkar_kepala" => 51,
                //     "usia" => 8,
                // ],
                // [
                //     "nik_balita" => "2007141008990022",
                //     'tgl_pelayanan' => '2023-09-17',
                //     "tb" => 77.5,
                //     "bb" => 10.9,
                //     "lingkar_kepala" => 52,
                //     "usia" => 14,
                // ],
                // [
                //     "nik_balita" => "2004301009990025",
                //     'tgl_pelayanan' => '2023-09-17',
                //     "tb" => 66,
                //     "bb" => 7.6,
                //     "lingkar_kepala" => 53,
                //     "usia" => 5,
                // ],
                // [
                //     "nik_balita" => "2002191010990028",
                //     'tgl_pelayanan' => '2023-09-17',
                //     "tb" => 64.5,
                //     "bb" => 5.6,
                //     "lingkar_kepala" => 54,
                //     "usia" => 7,
                // ],

                // Bulan ini
                [
                    "nik_balita" => "2110051001990001",
                    'tgl_pelayanan' => '2023-10-15',
                    "tb" => 72.5,
                    "bb" => 9.4,
                    "lingkar_kepala" => 45,
                    "usia" => 12,
                ],
                [
                    "nik_balita" => "2108151002990004",
                    'tgl_pelayanan' => '2023-10-15',
                    "tb" => 80.3,
                    "bb" => 12,
                    "lingkar_kepala" => 46,
                    "usia" => 14,
                ],
                [
                    "nik_balita" => "2106221003990007",
                    'tgl_pelayanan' => '2023-10-15',
                    "tb" => 57.9,
                    "bb" => 7.0,
                    "lingkar_kepala" => 47,
                    "usia" => 4,
                ],
                [
                    "nik_balita" => "2105111004990010",
                    'tgl_pelayanan' => '2023-10-15',
                    "tb" => 78.8,
                    "bb" => 11.5,
                    "lingkar_kepala" => 48,
                    "usia" => 17,
                ],
                [
                    "nik_balita" => "2103271005990013",
                    'tgl_pelayanan' => '2023-10-15',
                    "tb" => 62.5,
                    "bb" => 7.4,
                    "lingkar_kepala" => 49,
                    "usia" => 7,
                ],
                [
                    "nik_balita" => "2011181006990016",
                    'tgl_pelayanan' => '2023-10-15',
                    "tb" => 70,
                    "bb" => 8.2,
                    "lingkar_kepala" => 50,
                    "usia" => 11,
                ],
                [
                    "nik_balita" => "2009091007990019",
                    'tgl_pelayanan' => '2023-10-15',
                    "tb" => 71.1,
                    "bb" => 8.4,
                    "lingkar_kepala" => 51,
                    "usia" => 9,
                ],
                [
                    "nik_balita" => "2007141008990022",
                    'tgl_pelayanan' => '2023-10-15',
                    "tb" => 78.0,
                    "bb" => 11.4,
                    "lingkar_kepala" => 52,
                    "usia" => 15,
                ],
                [
                    "nik_balita" => "2004301009990025",
                    'tgl_pelayanan' => '2023-10-15',
                    "tb" => 67,
                    "bb" => 8.0,
                    "lingkar_kepala" => 53,
                    "usia" => 6,
                ],
                [
                    "nik_balita" => "2002191010990028",
                    'tgl_pelayanan' => '2023-10-15',
                    "tb" => 65.5,
                    "bb" => 5.9,
                    "lingkar_kepala" => 54,
                    "usia" => 8,
                ],
            ]
        );
    }
}
