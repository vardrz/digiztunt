<?php

namespace App\Http\Controllers;

use App\Charts\StuntingChart;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(StuntingChart $chart)
    {
        if (session()->get('level') == 'petugas') {
            return view('petugas.home', ['title' => 'Home']);
        } elseif (session()->get('level') == 'admin') {
            return view('pimpinan.home', [
                'title' => 'Home',
                'kota' => $chart->kota(),
                'utaraCount' => $chart->utaraCount(),
                'utaraList' => $chart->utaraList()
            ]);
        } elseif (session()->get('level') == 'pimpinan') {
            return view('pimpinan.home', [
                'title' => 'Home',
                'kota' => $chart->kota(),
                'utaraCount' => $chart->utaraCount(),
                'utaraList' => $chart->utaraList()
            ]);
        }
    }
}
