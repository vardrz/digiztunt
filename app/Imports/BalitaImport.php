<?php

namespace App\Imports;

use App\Models\Balita;
use App\Models\Posyandu;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BalitaImport implements ToModel, WithHeadingRow
{
    private function posyandu($posyandu, $kelurahan)
    {
        $pos = Posyandu::where('name', 'like', '%' . $posyandu . '%')->where('kelurahan', $kelurahan)->first();
        return $pos->id;
    }

    private function unixDate($date)
    {
        if (str_contains($date, '/') or str_contains($date, '-')) {
            return date('Y-m-d', strtotime(str_replace('/', '-', $date)));
        }

        $excel_date = $date;
        $unix_date = ($excel_date - 25569) * 86400;
        $excel_date = 25569 + ($unix_date / 86400);
        $unix_date = ($excel_date - 25569) * 86400;

        return gmdate("Y-m-d", $unix_date);
    }

    public function model(array $col)
    {
        return new Balita([
            "nama" => $col['nama'],
            "tgl_lahir" => $this->unixDate($col['tanggal_lahir']),
            "nik" => $col['nik'],
            "jenis_kelamin" => $col['jenis_kelamin'],
            "nama_ibu" => $col['nama_ibu'],
            "nik_ibu" => $col['nik_ibu'],
            "nama_ayah" => $col['nama_ayah'],
            "nik_ayah" => $col['nik_ayah'],
            "no_kk" => $col['no_kk'],
            "kelurahan" => $col['kelurahan'],
            "kecamatan" => 'PEKALONGAN UTARA',
            "posyandu" => $this->posyandu($col['posyandu'], $col['kelurahan']),
        ]);
    }
}
