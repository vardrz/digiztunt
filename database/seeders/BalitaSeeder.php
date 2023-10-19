<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BalitaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('balitas')->insert([
            [
                'nama' => 'Tomi Satrio Gunawan',
                'tgl_lahir' => '2023-03-10',
                'nik' => '1234567890123456',
                'jenis_kelamin' => 'lk',
                'nama_ibu' => 'Anisa Ratu Wibawa',
                'nik_ibu' => '3312345678901234',
                'nama_ayah' => 'Edi Ahmad Gunawan',
                'nik_ayah' => '3312345678904321',
                'no_kk' => '3312345678901000',
                'kelurahan' => 'PODOSUGIH',
                'kecamatan' => 'PEKALONGAN BARAT'
            ],
            [
                'nama' => 'Bobi Satrio Gunawan',
                'tgl_lahir' => '2022-01-11',
                'nik' => '1234567890123457',
                'jenis_kelamin' => 'lk',
                'nama_ibu' => 'Anisa Ratu Wibawa',
                'nik_ibu' => '3312345678901234',
                'nama_ayah' => 'Edi Ahmad Gunawan',
                'nik_ayah' => '3312345678904321',
                'no_kk' => '3312345678901000',
                'kelurahan' => 'PODOSUGIH',
                'kecamatan' => 'PEKALONGAN BARAT'
            ],
            [
                'nama' => 'Vina Pandu Winata',
                'tgl_lahir' => '2018-07-22',
                'nik' => '1234567890123400',
                'jenis_kelamin' => 'pr',
                'nama_ibu' => 'Siti Tirta Ayu',
                'nik_ibu' => '3322345678901234',
                'nama_ayah' => 'Rahman Surya Santoso',
                'nik_ayah' => '3322345678904321',
                'no_kk' => '3322345678904000',
                'kelurahan' => 'SETONO',
                'kecamatan' => 'PEKALONGAN TIMUR'
            ],
        ]);
    }
}
