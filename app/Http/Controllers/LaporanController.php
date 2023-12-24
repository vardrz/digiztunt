<?php

namespace App\Http\Controllers;

use App\Exports\LaporanExport;
use App\Models\Balita;
use App\Models\Pelayanan;
use App\Models\Posyandu;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index()
    {
        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        return view('petugas.laporan', [
            'title' => 'Rekap Laporan',
            'bulan' => $bulan
        ]);
    }

    public function rekap($tahun = null, $bulan = null)
    {
        $bulanName = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        return Excel::download(new LaporanExport($tahun, $bulan), 'RekapLaporan-' . $bulanName[$bulan - 1] . $tahun . '.xlsx');
    }
}
