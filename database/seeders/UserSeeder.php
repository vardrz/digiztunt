<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            // Pimpinan Kecamatan
            [
                'name' => 'Pimpinan Kecamatan',
                'email' => 'pimpinan@mail.com',
                'password' => Hash::make('pimpinan'),
                'level' => 'pimpinan',
                'area' => 'all'
            ],
            // Kelurahan
            [
                'name' => 'Pimpinan Kel. Panjang Wetan',
                'email' => 'panjangwetan@mail.com',
                'password' => Hash::make('panjangwetan'),
                'level' => 'pimpinan',
                'area' => 'PANJANG WETAN'
            ],
            [
                'name' => 'Pimpinan Kel. Panjang Baru',
                'email' => 'panjangbaru@mail.com',
                'password' => Hash::make('panjangbaru'),
                'level' => 'pimpinan',
                'area' => 'PANJANG BARU'
            ],
            [
                'name' => 'Pimpinan Kel. Kandang Panjang',
                'email' => 'kandangpanjang@mail.com',
                'password' => Hash::make('kandangpanjang'),
                'level' => 'pimpinan',
                'area' => 'KANDANG PANJANG'
            ],
            [
                'name' => 'Pimpinan Kel. Krapyak',
                'email' => 'krapyak@mail.com',
                'password' => Hash::make('krapyak'),
                'level' => 'pimpinan',
                'area' => 'KRAPYAK'
            ],
            [
                'name' => 'Pimpinan Kel. Degayu',
                'email' => 'degayu@mail.com',
                'password' => Hash::make('degayu'),
                'level' => 'pimpinan',
                'area' => 'DEGAYU'
            ],
            [
                'name' => 'Pimpinan Kel. Padukuhan Kraton',
                'email' => 'padukuhankraton@mail.com',
                'password' => Hash::make('padukuhankraton'),
                'level' => 'pimpinan',
                'area' => 'PADUKUHAN KRATON'
            ],
            [
                'name' => 'Pimpinan Kel. Bandengan',
                'email' => 'bandengan@mail.com',
                'password' => Hash::make('bandengan'),
                'level' => 'pimpinan',
                'area' => 'BANDENGAN'
            ],
            // Puskesmas
            [
                'name' => 'Petugas Kusuma Bangsa',
                'email' => 'puskesmas-kusumabangsa',
                'password' => Hash::make('kusumabangsa'),
                'level' => 'admin',
                'area' => 'KUSUMA BANGSA'
            ],
            [
                'name' => 'Petugas Krapyak',
                'email' => 'puskesmas-krapyak',
                'password' => Hash::make('krapyak'),
                'level' => 'admin',
                'area' => 'KRAPYAK'
            ],
            [
                'name' => 'Petugas Dukuh',
                'email' => 'puskesmas-dukuh',
                'password' => Hash::make('dukuh'),
                'level' => 'admin',
                'area' => 'DUKUH'
            ],
            // Posyandu
            // --- Puskesmas Kusuma Bangsa ---
            // [
            //     'name' => 'Kader Rajawali',
            //     'email' => 'panjangwetan-rajawali',
            //     'password' => Hash::make('123456'),
            //     'level' => 'petugas',
            //     'area' => '1'
            // ],
            // [
            //     'name' => 'Kader Wijaya Kusuma',
            //     'email' => 'panjangbaru-wijayakusuma',
            //     'password' => Hash::make('123456'),
            //     'level' => 'petugas',
            //     'area' => '16'
            // ],
            // [
            //     'name' => 'Kader Bakti Ibu',
            //     'email' => 'kandangpanjang-baktiibu',
            //     'password' => Hash::make('123456'),
            //     'level' => 'petugas',
            //     'area' => '29'
            // ],
            // // --- Puskesmas Krapyak ---
            // [
            //     'name' => 'Kader Melati I',
            //     'email' => 'krapyak-melatisatu',
            //     'password' => Hash::make('123456'),
            //     'level' => 'petugas',
            //     'area' => '45'
            // ],
            // [
            //     'name' => 'Kader Melati',
            //     'email' => 'degayu-melati',
            //     'password' => Hash::make('123456'),
            //     'level' => 'petugas',
            //     'area' => '67'
            // ],
            // // --- Puskesmas Dukuh ---
            // [
            //     'name' => 'Kader Nusa Indah',
            //     'email' => 'kraton-nusaindah',
            //     'password' => Hash::make('123456'),
            //     'level' => 'petugas',
            //     'area' => '77'
            // ],
            // [
            //     'name' => 'Kader Flamboyan',
            //     'email' => 'bandengan-flamboyan',
            //     'password' => Hash::make('123456'),
            //     'level' => 'petugas',
            //     'area' => '94'
            // ],
        ]);
    }
}
