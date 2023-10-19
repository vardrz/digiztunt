<?php

namespace App\Http\Controllers;

use App\Models\BadutaAntopometri;
use App\Models\Pelayanan;
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

    public function verif(Request $req)
    {
        $standarAntro = BadutaAntopometri::where('usia', $req->usia)->where('jenis_kelamin', $req->jenis_kelamin)->first();

        $bbu_zscore = round((intval($req->bb) - $standarAntro->bbuMedian) / ($standarAntro->bbuMedian - $standarAntro->bbuMin1sd), 2);
        $tbu_zscore = round((intval($req->tb) - $standarAntro->tbuMedian) / ($standarAntro->tbuMedian - $standarAntro->tbuMin1sd), 2);

        Pelayanan::where('id', $req->id)->update(['verif' => 'y', 'bbu' => $bbu_zscore, 'tbu' => $tbu_zscore]);

        return redirect('/status')->with('success', 'Pendataan berhasil diverfikasi.');
    }

    public function status()
    {
        $data = Pelayanan::where('verif', 'y')->get();

        return view('admin.stantingHasil', [
            'title' => 'Hasil Pendataan',
            'data' => $data
        ]);
    }
}
