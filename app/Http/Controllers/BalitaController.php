<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\Pelayanan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

// use function PHPUnit\Framework\isNull;

class BalitaController extends Controller
{
    public function index()
    {
        $thisDay = date('Y-m-d');
        $fiveYearAgo = date('Y-m-d', strtotime('-5 years'));

        $balitas = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->orderBy('tgl_lahir', 'DESC')->get();

        return view('petugas.balitaList', [
            'title' => 'Daftar Data Balita',
            'balitas' => $balitas
        ]);
    }

    public function find($data = null)
    {
        $balita = Balita::where('nama', 'like', '%' . $data . '%')->orWhere('nik', $data)->orderBy('nama')->get();
        return $balita;
    }

    public function new()
    {
        $bulan = [
            ['no' => '1', 'name' => 'Januari'],
            ['no' => '2', 'name' => 'Februari'],
            ['no' => '3', 'name' => 'Maret'],
            ['no' => '4', 'name' => 'April'],
            ['no' => '5', 'name' => 'Mei'],
            ['no' => '6', 'name' => 'Juni'],
            ['no' => '7', 'name' => 'Juli'],
            ['no' => '8', 'name' => 'Agustus'],
            ['no' => '9', 'name' => 'September'],
            ['no' => '10', 'name' => 'Oktober'],
            ['no' => '11', 'name' => 'November'],
            ['no' => '12', 'name' => 'Desember']
        ];

        $kecamatan = [
            ["id" => "3375010", "name" => "PEKALONGAN BARAT"],
            ["id" => "3375020", "name" => "PEKALONGAN TIMUR"],
            ["id" => "3375030", "name" => "PEKALONGAN SELATAN"],
            ["id" => "3375040", "name" => "PEKALONGAN UTARA"]
        ];

        return view('petugas.balitaNew', [
            'title' => 'Tambah Data Balita',
            'month' => $bulan,
            'kecamatan' => $kecamatan
        ]);
    }

    public function history()
    {
        if (session('level') != 'admin') {
            return abort(403, 'Anda tidak memiliki hak mengakses laman ini!');
        }

        $fiveYearAgo = date('Y-m-d', strtotime('-5 years'));
        $data = Balita::where('tgl_lahir', '<', $fiveYearAgo)->get();

        return view('admin.balitaHistory', [
            'title' => 'History Data Balita',
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            "nama" => "required",
            "jenis_kelamin" => "required",
            "nik" => "required|unique:balitas|min:16",
            "namaibu" => "required",
            "nikibu" => "required|min:16",
            "namaayah" => "required",
            "nikayah" => "required|min:16",
            "nokk" => "required|min:16",
            "kecamatan" => "required",
            "kelurahan" => "required"
        ]);

        $balita = [
            "nama" => $validate['nama'],
            "tgl_lahir" => $request->tahun . '-' . $request->bulan . '-' . $request->tgl,
            "nik" => $validate['nik'],
            "jenis_kelamin" => $validate['jenis_kelamin'],
            "nama_ibu" => $validate['namaibu'],
            "nik_ibu" => $validate['nikibu'],
            "nama_ayah" => $validate['namaayah'],
            "nik_ayah" => $validate['nikayah'],
            "no_kk" => $validate['nokk'],
            "kelurahan" => $validate['kelurahan'],
            "kecamatan" => $validate['kecamatan'],
        ];

        Balita::insert($balita);

        return redirect('/pelayanan')->with('success', 'Data balita berhasil ditambahkan.');
    }

    public function edit($nik = null)
    {
        if ($nik) {
            $bulan = [
                ['no' => '1', 'name' => 'Januari'],
                ['no' => '2', 'name' => 'Februari'],
                ['no' => '3', 'name' => 'Maret'],
                ['no' => '4', 'name' => 'April'],
                ['no' => '5', 'name' => 'Mei'],
                ['no' => '6', 'name' => 'Juni'],
                ['no' => '7', 'name' => 'Juli'],
                ['no' => '8', 'name' => 'Agustus'],
                ['no' => '9', 'name' => 'September'],
                ['no' => '10', 'name' => 'Oktober'],
                ['no' => '11', 'name' => 'November'],
                ['no' => '12', 'name' => 'Desember']
            ];

            $kecamatan = [
                ["id" => "3375010", "name" => "PEKALONGAN BARAT"],
                ["id" => "3375020", "name" => "PEKALONGAN TIMUR"],
                ["id" => "3375030", "name" => "PEKALONGAN SELATAN"],
                ["id" => "3375040", "name" => "PEKALONGAN UTARA"]
            ];

            $data = Balita::where('nik', $nik)->get();

            return view('admin.balitaEdit', [
                'title' => 'Edit Data Balita',
                'data' => $data[0],
                'month' => $bulan,
                'kecamatan' => $kecamatan
            ]);
        }

        return redirect('/balita');
    }

    public function update(Request $request)
    {
        $validate = $request->validate([
            "nama" => "required",
            "jenis_kelamin" => "required",
            "nik" => "required|min:16",
            "namaibu" => "required",
            "nikibu" => "required|min:16",
            "namaayah" => "required",
            "nikayah" => "required|min:16",
            "nokk" => "required|min:16",
            "kecamatan" => "required",
            "kelurahan" => "required"
        ]);

        $dataUpdated = [
            "nama" => $validate['nama'],
            "tgl_lahir" => $request->tahun . '-' . $request->bulan . '-' . $request->tgl,
            "nik" => $validate['nik'],
            "jenis_kelamin" => $validate['jenis_kelamin'],
            "nama_ibu" => $validate['namaibu'],
            "nik_ibu" => $validate['nikibu'],
            "nama_ayah" => $validate['namaayah'],
            "nik_ayah" => $validate['nikayah'],
            "no_kk" => $validate['nokk'],
            "kelurahan" => $validate['kelurahan'],
            "kecamatan" => $validate['kecamatan'],
        ];

        Balita::where('id', $request->id)->update($dataUpdated);

        return redirect('/balita')->with('success', 'Data balita berhasil diupdate.');
    }

    public function destroy($nik = 0)
    {
        Balita::where('nik', $nik)->delete();
        Pelayanan::where('nik_balita', $nik)->delete();
        return back()->with('success', 'Data balita berhasil dihapus.');
    }
}
