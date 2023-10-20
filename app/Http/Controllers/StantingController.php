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
        $data = Pelayanan::where('verif', 'n')->get();

        return view('admin.stantingVerifikasi', [
            'title' => 'Verifikasi Pendataan',
            'data' => $data
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

        Balita::where('nik', $req->nik)->update(['status' => $status]);
        Pelayanan::where('id', $req->id)->update(['verif' => 'y', 'bbu' => $bbu_zscore, 'tbu' => $tbu_zscore]);

        return redirect('/status')->with('success', 'Data berhasil diverifikasi.');
    }

    public function status($month = null)
    {
        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        if ($month != null) {
            $between = [date('Y-') . $month . '-1', date('Y-') . $month . '-28'];
            $bln = $month - 1;
        } else {
            $between = [date('Y-m-1'), date('Y-m-28')];
            $bln = date('m') - 1;
        }

        $data = Pelayanan::where('verif', 'y')->whereBetween('tgl_pelayanan', $between)->get();

        return view('admin.stantingHasil', [
            'title' => 'Hasil Perhitungan Stunting Tahun Ini',
            'data' => $data,
            'bulan' => [$bulan[$bln], $bln],
        ]);
    }
}
