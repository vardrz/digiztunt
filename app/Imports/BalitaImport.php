<?php

namespace App\Imports;

use App\Models\Balita;
use App\Models\Posyandu;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BalitaImport implements ToModel, WithHeadingRow
{
    public function model(array $col)
    {
        $pos = Posyandu::where('name', 'like', '%' . $col['posyandu'] . '%')->where('kelurahan', $col['kelurahan'])->first();

        return new Balita([
            "nama" => $col['nama'],
            "tgl_lahir" => date('Y-m-d', strtotime(str_replace('/', '-', $col['tanggal_lahir']))),
            "nik" => $col['nik'],
            "jenis_kelamin" => $col['jenis_kelamin'],
            "nama_ibu" => $col['nama_ibu'],
            "nik_ibu" => $col['nik_ibu'],
            "nama_ayah" => $col['nama_ayah'],
            "nik_ayah" => $col['nik_ibu'],
            "no_kk" => $col['no_kk'],
            "kelurahan" => $col['kelurahan'],
            "kecamatan" => 'PEKALONGAN UTARA',
            "posyandu" => $pos->id,
        ]);
    }
}
