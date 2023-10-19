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
            [
                'name' => 'Petugas Posyandu',
                'email' => 'posyandu@mail.com',
                'password' => Hash::make('posyandu'),
                'level' => 'petugas'
            ],
            [
                'name' => 'Petugas Puskesmas',
                'email' => 'puskesmas@mail.com',
                'password' => Hash::make('puskesmas'),
                'level' => 'admin'
            ],
            [
                'name' => 'Pimpinan',
                'email' => 'pimpinan@mail.com',
                'password' => Hash::make('pimpinan'),
                'level' => 'pimpinan'
            ]
        ]);
    }
}
