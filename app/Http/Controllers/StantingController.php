<?php

namespace App\Http\Controllers;

use App\Models\BalitaAntopometri;
use App\Models\Pelayanan;
use App\Models\Balita;
use Illuminate\Http\Request;

class StantingController extends Controller
{
    public function index()
    {
        if (auth()->user()->level == 'admin' && auth()->user()->area == 'KUSUMA BANGSA') {
            $data = Pelayanan::where('verif', 'n')->whereHas('balita', function ($query) {
                $query->whereIn('kelurahan', ['PANJANG WETAN', 'PANJANG BARU', 'KANDANG PANJANG']);
            })->get();
            $title = 'Data Penimbangan Balita Puskesmas Kusuma Bangsa';
        } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'KRAPYAK') {
            $data = Pelayanan::where('verif', 'n')->whereHas('balita', function ($query) {
                $query->whereIn('kelurahan', ['KRAPYAK', 'DEGAYU']);
            })->get();
            $title = 'Data Penimbangan Balita Puskesmas Krapyak';
        } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'DUKUH') {
            $data = Pelayanan::where('verif', 'n')->whereHas('balita', function ($query) {
                $query->whereIn('kelurahan', ['PADUKUHAN KRATON', 'BANDENGAN']);
            })->get();
            $title = 'Data Penimbangan Balita Puskesmas Dukuh';
        }

        return view('admin.stantingVerifikasi', [
            'title' => 'Verifikasi ' . $title,
            'data' => $data,
        ]);
    }

    private function cekTBU($tbu_zscore)
    {
        if ($tbu_zscore < -3) {
            return "Stunting";
        } elseif ($tbu_zscore >= -3 && $tbu_zscore < -2) {
            return "Stunting";
        } elseif ($tbu_zscore >= -2 && $tbu_zscore <= 3) {
            return "Normal";
        } elseif ($tbu_zscore > 3) {
            return "Normal";
        }
    }

    public function verif(Request $req)
    {
        $standarAntro = BalitaAntopometri::where('usia', $req->usia)->where('jenis_kelamin', $req->jenis_kelamin)->first();

        $bbu_zscore = round((intval($req->bb) - $standarAntro->bbuMedian) / ($standarAntro->bbuMedian - $standarAntro->bbuMin1sd), 2);
        $tbu_zscore = round((intval($req->tb) - $standarAntro->tbuMedian) / ($standarAntro->tbuMedian - $standarAntro->tbuMin1sd), 2);

        $status = $this->cekTBU($tbu_zscore);

        Balita::where('id', $req->id_balita)->update(['status' => $status]);
        Pelayanan::where('id', $req->id)->update(['verif' => 'y', 'bbu' => $bbu_zscore, 'tbu' => $tbu_zscore]);

        return back()->with('success', 'Data berhasil diverifikasi.');
    }

    public function update(Request $req)
    {
        $data = [
            'tb' => str_replace([','], ['.'], $req->tb),
            'bb' => str_replace([','], ['.'], $req->bb),
            'lingkar_kepala' => str_replace([','], ['.'], $req->lingkar_kepala)
        ];

        Pelayanan::where('id', $req->id)->update($data);
        return redirect('/verifikasi')->with('success', 'Data berhasil diperbarui.');
    }

    public function status($year = null, $month = null)
    {
        $tahun = ($year == null) ? date('Y') : $year;
        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        if ($month != null) {
            // $between = [date('Y-') . $month . '-1', date('Y-') . $month . '-28'];
            $between = [$tahun . '-' . $month . '-1', $tahun . '-' . $month . '-28'];
            $bln = $month - 1;
        } else {
            // $between = [date('Y-m-1'), date('Y-m-28')];
            $between = [$tahun . '-' . date('m-1'), $tahun . '-' . date('m-28')];
            $bln = date('m') - 1;
        }

        // Pimpinan
        if (auth()->user()->level == 'pimpinan' && auth()->user()->area == 'all') {
            $data = Pelayanan::where('verif', 'y')->whereBetween('tgl_pelayanan', $between)->get();
            $title = 'Perhitungan Stunting Pekalongan Utara ' . $bulan[$bln] . ' ' . $tahun;
        } else {
            $data = Pelayanan::where('verif', 'y')->whereBetween('tgl_pelayanan', $between)->whereHas('balita', function ($query) {
                $query->where('kelurahan', auth()->user()->area);
            })->get();
            $title = 'Perhitungan Stunting Kelurahan ' . ucwords(strtolower(auth()->user()->area)) . ' ' . $bulan[$bln] . ' ' . $tahun;
        }

        // Puskesmas
        if (auth()->user()->level == 'admin' && auth()->user()->area == 'KUSUMA BANGSA') {
            $data = Pelayanan::where('verif', 'y')->whereBetween('tgl_pelayanan', $between)->whereHas('balita', function ($query) {
                $query->whereIn('kelurahan', ['PANJANG WETAN', 'PANJANG BARU', 'KANDANG PANJANG']);
            })->get();
            $title = 'Perhitungan Stunting Puskesmas Kusuma Bangsa ' . $bulan[$bln] . ' ' . $tahun;
        } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'KRAPYAK') {
            $data = Pelayanan::where('verif', 'y')->whereBetween('tgl_pelayanan', $between)->whereHas('balita', function ($query) {
                $query->whereIn('kelurahan', ['KRAPYAK', 'DEGAYU']);
            })->get();
            $title = 'Perhitungan Stunting Puskesmas Krapyak ' . $bulan[$bln] . ' ' . $tahun;
        } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'DUKUH') {
            $data = Pelayanan::where('verif', 'y')->whereBetween('tgl_pelayanan', $between)->whereHas('balita', function ($query) {
                $query->whereIn('kelurahan', ['PADUKUHAN KRATON', 'BANDENGAN']);
            })->get();
            $title = 'Perhitungan Stunting Puskesmas Dukuh ' . $bulan[$bln] . ' ' . $tahun;
        }

        return view('admin.stantingHasil', [
            'title' => $title,
            'data' => $data,
            'listBulan' => $bulan,
            'bulan' => [$bulan[$bln], $bln],
            'tahun' => $tahun,
        ]);
    }
}
