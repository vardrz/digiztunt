<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Charts\StuntingChart;
use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    public function index(StuntingChart $chart)
    {
        if (session()->get('level') == 'petugas') {
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
        } elseif (session()->get('level') == 'admin') {
            return view('pimpinan.home', [
                'title' => 'Home',
                'giziBuruk' => $chart->puskesmasGiziBuruk(auth()->user()->area),
                'utaraLastMonth' => $chart->utaraLastMonth(),
                'utaraBalita' => $chart->utaraBalita()
            ]);
        } elseif (session()->get('level') == 'pimpinan') {
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
