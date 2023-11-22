<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\Pelayanan;
use App\Charts\StuntingChart;
use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    public function index(StuntingChart $chart)
    {
        if (session()->get('level') == 'petugas') { // Posyandu
            $thisDay = date('Y-m-d');
            $thisMonth = date('Y-m-1');
            $fiveYearAgo = date('Y-m-d', strtotime('-5 years'));

            $balitas = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->where('posyandu', auth()->user()->area)->count();
            $terdata = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->where('posyandu', auth()->user()->area)
                ->whereHas('pelayanan', function ($query) use ($thisMonth, $thisDay) {
                    $query->whereBetween('tgl_pelayanan', [$thisMonth, $thisDay]);
                })->count();

            return view('petugas.home', [
                'title' => 'Home',
                'balitas' => $balitas,
                'terdata' => $terdata
            ]);
        } elseif (session()->get('level') == 'admin') { // Puskesmas
            $thisDay = date('Y-m-d');
            $thisMonth = date('Y-m-1');
            $fiveYearAgo = date('Y-m-d', strtotime('-5 years'));

            if (auth()->user()->level == 'admin' && auth()->user()->area == 'KUSUMA BANGSA') {
                $balitas = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereIn('kelurahan', ['PANJANG WETAN', 'PANJANG BARU', 'KANDANG PANJANG'])->count();
                $unverif = Pelayanan::where('verif', 'n')->whereHas('balita', function ($query) {
                    $query->whereIn('kelurahan', ['PANJANG WETAN', 'PANJANG BARU', 'KANDANG PANJANG']);
                })->count();
                $verif = Pelayanan::where('verif', 'y')->whereBetween('tgl_pelayanan', [$thisMonth, $thisDay])->whereHas('balita', function ($query) {
                    $query->whereIn('kelurahan', ['PANJANG WETAN', 'PANJANG BARU', 'KANDANG PANJANG']);
                })->count();
            } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'KRAPYAK') {
                $balitas = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereIn('kelurahan', ['KRAPYAK', 'DEGAYU'])->count();
                $unverif = Pelayanan::where('verif', 'n')->whereHas('balita', function ($query) {
                    $query->whereIn('kelurahan', ['KRAPYAK', 'DEGAYU']);
                })->count();
                $verif = Pelayanan::where('verif', 'y')->whereBetween('tgl_pelayanan', [$thisMonth, $thisDay])->whereHas('balita', function ($query) {
                    $query->whereIn('kelurahan', ['KRAPYAK', 'DEGAYU']);
                })->count();
            } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'DUKUH') {
                $balitas = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereIn('kelurahan', ['PADUKUHAN KRATON', 'BANDENGAN'])->count();
                $unverif = Pelayanan::where('verif', 'n')->whereHas('balita', function ($query) {
                    $query->whereIn('kelurahan', ['PADUKUHAN KRATON', 'BANDENGAN']);
                })->count();
                $verif = Pelayanan::where('verif', 'y')->whereBetween('tgl_pelayanan', [$thisMonth, $thisDay])->whereHas('balita', function ($query) {
                    $query->whereIn('kelurahan', ['PADUKUHAN KRATON', 'BANDENGAN']);
                })->count();
            }
            return view('admin.home', [
                'title' => 'Home',
                'giziBuruk' => $chart->puskesmasGiziBuruk(auth()->user()->area),
                'balitas' => $balitas,
                'unverif' => $unverif,
                'verif' => $verif
            ]);
        } elseif (session()->get('level') == 'pimpinan') { // Pimpinan
            if (auth()->user()->area != 'all') {
                $stuntingLastMonth = $chart->kelurahanLastMonth(auth()->user()->area);
            } else {
                $stuntingLastMonth = $chart->utaraLastMonth();
            }

            return view('pimpinan.home', [
                'title' => 'Home',
                'giziBuruk' => $chart->giziBuruk(),
                'utaraLastMonth' => $stuntingLastMonth,
                'utaraBalita' => $chart->utaraBalita()
            ]);
        }
    }
}
