<?php

namespace App\Http\Controllers;

use App\Imports\BalitaImport;
use App\Models\Balita;
use App\Models\Pelayanan;
use App\Models\Posyandu;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;

// use function PHPUnit\Framework\isNull;

class BalitaController extends Controller
{
    public function index()
    {
        $thisDay = date('Y-m-d');
        $fiveYearAgo = date('Y-m-d', strtotime('-5 years'));

        // Pimpinan
        if (auth()->user()->level == 'pimpinan' && auth()->user()->area == 'all') {
            $balitas = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->orderBy('tgl_lahir', 'DESC')->get();
            $title = "Daftar Balita Pekalongan Utara";
        } else {
            $balitas = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->where('kelurahan', auth()->user()->area)->orderBy('tgl_lahir', 'DESC')->get();
            $title = "Daftar Balita Kelurahan " . ucwords(strtolower(auth()->user()->area));
        }

        // Puskesmas
        if (auth()->user()->level == 'admin' && auth()->user()->area == 'KUSUMA BANGSA') {
            $balitas = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereIn('kelurahan', ['PANJANG WETAN', 'PANJANG BARU', 'KANDANG PANJANG'])->orderBy('tgl_lahir', 'DESC')->get();
            $title = "Daftar Balita Puskesmas Kusuma Bangsa";
        } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'KRAPYAK') {
            $balitas = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereIn('kelurahan', ['KRAPYAK', 'DEGAYU'])->orderBy('tgl_lahir', 'DESC')->get();
            $title = "Daftar Balita Puskesmas Krapyak";
        } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'DUKUH') {
            $balitas = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereIn('kelurahan', ['PADUKUHAN KRATON', 'BANDENGAN'])->orderBy('tgl_lahir', 'DESC')->get();
            $title = "Daftar Balita Puskesmas Dukuh";
        }

        // Posyandu
        if (auth()->user()->level == 'petugas') {
            $balitas = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->where('posyandu', auth()->user()->area)->orderBy('tgl_lahir', 'DESC')->get();

            $namaPos = Posyandu::where('id', auth()->user()->area)->first();
            $title = "Daftar Balita Posyandu " . $namaPos->name;
        }


        return view('petugas.balitaList', [
            'title' => $title,
            'balitas' => $balitas
        ]);
    }

    public function find($data = null)
    {
        $balita = Balita::where('posyandu', auth()->user()->area)->where(function ($query) use ($data) {
            $query->where('nama', 'like', '%' . $data . '%')->orWhere('nik', $data);
        })->orderBy('nama')->get();
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

        // $kecamatan = [
        //     ["id" => "3375010", "name" => "PEKALONGAN BARAT"],
        //     ["id" => "3375020", "name" => "PEKALONGAN TIMUR"],
        //     ["id" => "3375030", "name" => "PEKALONGAN SELATAN"],
        //     ["id" => "3375040", "name" => "PEKALONGAN UTARA"]
        // ];

        $kelurahan = [
            "KRAPYAK", "KANDANG PANJANG", "PANJANG WETAN", "PANJANG BARU", "DEGAYU", "BANDENGAN", "PADUKUHAN KRATON"
        ];

        $posyandu = Posyandu::where('kelurahan', auth()->user()->area)->get();

        return view('petugas.balitaNew', [
            'title' => 'Tambah Data Balita',
            'month' => $bulan,
            'kelurahan' => $kelurahan,
            'posyandu' => $posyandu
        ]);
    }

    public function history()
    {
        // if (session('level') != 'admin') {
        //     return abort(403, 'Anda tidak memiliki hak mengakses laman ini!');
        // }

        $fiveYearAgo = date('Y-m-d', strtotime('-5 years'));

        // Pimpinan
        if (auth()->user()->level == 'pimpinan' && auth()->user()->area == 'all') {
            $data = Balita::where('tgl_lahir', '<', $fiveYearAgo)->orderBy('tgl_lahir', 'DESC')->get();
            $title = "History Balita Pekalongan Utara";
        } else {
            $data = Balita::where('tgl_lahir', '<', $fiveYearAgo)->where('kelurahan', auth()->user()->area)->orderBy('tgl_lahir', 'DESC')->get();
            $title = "History Balita Kelurahan " . ucwords(strtolower(auth()->user()->area));
        }

        // Puskesmas
        if (auth()->user()->level == 'admin' && auth()->user()->area == 'KUSUMA BANGSA') {
            $data = Balita::where('tgl_lahir', '<', $fiveYearAgo)->whereIn('kelurahan', ['PANJANG WETAN', 'PANJANG BARU', 'KANDANG PANJANG'])->orderBy('tgl_lahir', 'DESC')->get();
            $title = "History Balita Puskesmas Kusuma Bangsa";
        } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'KRAPYAK') {
            $data = Balita::where('tgl_lahir', '<', $fiveYearAgo)->whereIn('kelurahan', ['KRAPYAK', 'DEGAYU'])->orderBy('tgl_lahir', 'DESC')->get();
            $title = "History Balita Puskesmas Krapyak";
        } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'DUKUH') {
            $data = Balita::where('tgl_lahir', '<', $fiveYearAgo)->whereIn('kelurahan', ['PADUKUHAN KRATON', 'BANDENGAN'])->orderBy('tgl_lahir', 'DESC')->get();
            $title = "History Balita Puskesmas Dukuh";
        }

        // Posyandu
        if (auth()->user()->level == 'petugas') {
            $data = Balita::where('tgl_lahir', '<', $fiveYearAgo)->where('posyandu', auth()->user()->area)->orderBy('tgl_lahir', 'DESC')->get();

            $namaPos = Posyandu::where('id', auth()->user()->area)->first();
            $title = "History Balita Posyandu " . $namaPos->name;
        }

        return view('admin.balitaHistory', [
            'title' => $title,
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        if ($request->haveNIK == 'y') {
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
                "kelurahan" => "required",
                "posyandu" => "required"
            ]);
        } else {
            $validate = $request->validate([
                "nama" => "required",
                "jenis_kelamin" => "required",
                "namaibu" => "required",
                "nikibu" => "required|min:16",
                "namaayah" => "required",
                "nikayah" => "required|min:16",
                "nokk" => "required|min:16",
                "kecamatan" => "required",
                "kelurahan" => "required",
                "posyandu" => "required"
            ]);
        }

        $balita = [
            "nama" => $validate['nama'],
            "tgl_lahir" => $request->tahun . '-' . $request->bulan . '-' . $request->tgl,
            "nik" => $validate['nik'] ?? "-",
            "jenis_kelamin" => $validate['jenis_kelamin'],
            "nama_ibu" => $validate['namaibu'],
            "nik_ibu" => $validate['nikibu'],
            "nama_ayah" => $validate['namaayah'],
            "nik_ayah" => $validate['nikayah'],
            "no_kk" => $validate['nokk'],
            "kelurahan" => $validate['kelurahan'],
            "kecamatan" => $validate['kecamatan'],
            "posyandu" => $validate['posyandu'],
        ];

        // dd($balita);

        Balita::insert($balita);

        return redirect('/balita')->with('success', 'Data balita berhasil ditambahkan.');
    }

    public function edit($id = null)
    {
        if ($id) {
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
                ["id" => "3375040", "name" => "PEKALONGAN UTARA"]
            ];

            $data = Balita::where('id', $id)->get();
            $posyandu = Posyandu::where('kelurahan', auth()->user()->area)->get();

            return view('admin.balitaEdit', [
                'title' => 'Edit Data Balita',
                'data' => $data[0],
                'month' => $bulan,
                'kecamatan' => $kecamatan,
                'posyandu' => $posyandu
            ]);
        }

        return redirect('/balita');
    }

    public function update(Request $request)
    {
        if ($request->nik == '-') {
            $validate = $request->validate([
                "nama" => "required",
                "jenis_kelamin" => "required",
                "nik" => "required",
                "namaibu" => "required",
                "nikibu" => "required|min:16",
                "namaayah" => "required",
                "nikayah" => "required|min:16",
                "nokk" => "required|min:16",
                "kecamatan" => "required",
                "kelurahan" => "required",
                "posyandu" => "required"
            ]);
        } else {
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
                "kelurahan" => "required",
                "posyandu" => "required"
            ]);
        }


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
            "posyandu" => $validate['posyandu'],
        ];

        Balita::where('id', $request->id)->update($dataUpdated);
        if ($request->nik != '-') {
            Pelayanan::where('id_balita', $request->id)->update(["nik_balita" => $validate['nik']]);
        }

        return redirect('/balita')->with('success', 'Data balita berhasil diupdate.');
    }

    public function destroy($id = 0)
    {
        Balita::where('id', $id)->delete();
        Pelayanan::where('id_balita', $id)->delete();
        return back()->with('success', 'Data balita berhasil dihapus.');
    }

    public function import()
    {
        return view('admin.import', [
            'title' => 'Import Data Balita',
        ]);
    }

    public function import_excel(Request $request)
    {
        // validasi
        $request->validate([
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = rand() . $file->getClientOriginalName();
        $file->move('Dataset_Balita', $nama_file);

        Excel::import(new BalitaImport, public_path('/Dataset_Balita/' . $nama_file));

        return redirect('/balita')->with('success', 'Data Balita Berhasil Diimport!');
    }
}
