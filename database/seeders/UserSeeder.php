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
            // Puskesmas
            [
                'name' => 'Puskesmas Kusuma Bangsa',
                'email' => 'puskesmas-kusumabangsa@mail.com',
                'password' => Hash::make('kusumabangsa'),
                'level' => 'admin',
                'area' => 'KUSUMA BANGSA'
            ],
            [
                'name' => 'Puskesmas Krapyak',
                'email' => 'puskesmas-krapyak@mail.com',
                'password' => Hash::make('krapyak'),
                'level' => 'admin',
                'area' => 'KRAPYAK'
            ],
            [
                'name' => 'Puskesmas Dukuh',
                'email' => 'puskesmas-dukuh@mail.com',
                'password' => Hash::make('dukuh'),
                'level' => 'admin',
                'area' => 'DUKUH'
            ],
            // Posyandu
            // --- Puskesmas Kusuma Bangsa ---
            [
                'name' => 'Posyandu Rajawali',
                'email' => 'pos-rajawali@mail.com',
                'password' => Hash::make('rajawali'),
                'level' => 'petugas',
                'area' => 'PANJANG WETAN'
            ],
            [
                'name' => 'Posyandu Sehat Barokah',
                'email' => 'pos-sehatbarokah@mail.com',
                'password' => Hash::make('sehatbarokah'),
                'level' => 'petugas',
                'area' => 'PANJANG WETAN'
            ],
            [
                'name' => 'Posyandu Wijaya Kusuma',
                'email' => 'pos-wijayakusuma@mail.com',
                'password' => Hash::make('wijayakusuma'),
                'level' => 'petugas',
                'area' => 'PANJANG BARU'
            ],
            [
                'name' => 'Posyandu Cempaka',
                'email' => 'pos-cempaka@mail.com',
                'password' => Hash::make('cempaka'),
                'level' => 'petugas',
                'area' => 'PANJANG BARU'
            ],
            [
                'name' => 'Posyandu Bakti Ibu',
                'email' => 'pos-baktiibu@mail.com',
                'password' => Hash::make('baktiibu'),
                'level' => 'petugas',
                'area' => 'KANDANG PANJANG'
            ],
            [
                'name' => 'Posyandu Kusuma',
                'email' => 'pos-kusuma@mail.com',
                'password' => Hash::make('kusuma'),
                'level' => 'petugas',
                'area' => 'KANDANG PANJANG'
            ]
        ]);
    }
}
