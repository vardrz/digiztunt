<?php

namespace App\Charts;

use App\Models\Balita;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\DB; // Pastikan DB facade di-import

class StuntingChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    private function getCountGiziBurukForKelurahan($kelurahan, $ukur, $between, $fiveYearAgo, $thisDay)
    {
        return Balita::where('kelurahan', $kelurahan)
            ->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
            ->whereHas('pelayanan', function ($query) use ($ukur, $between) {
                $query->where('verif', 'y')
                      ->whereBetween('tgl_pelayanan', $between)
                      ->when($ukur == 'tbu', function ($q) {
                          $q->where('tbu', '<', -2);
                      })
                      ->when($ukur == 'bbu', function ($q) {
                          $q->where('bbu', '<', -2);
                      })
                      // Ensure we are checking the latest relevant record within the $between period
                      // This part is tricky with whereHas and might need a subquery for perfect accuracy
                      // For now, this counts balita if ANY verified pelayanan in the period matches criteria.
                      // A more precise (but complex) query would ensure it's the *last* record in the period.
                      ;
            })
            ->count();
    }

    // Optimized version of countData, assuming we want the latest overall verified pelayanan
    private function getCountForKelurahan($kelurahan, $ukur, $fiveYearAgo, $thisDay)
    {
        // Subquery untuk mendapatkan id pelayanan terakhir yang terverifikasi untuk setiap balita
        $latestVerifiedPelayananSubquery = DB::table('pelayanans as p_sub')
            ->select('p_sub.id_balita', DB::raw('MAX(p_sub.id) as latest_pelayanan_id'))
            ->where('p_sub.verif', 'y')
            ->groupBy('p_sub.id_balita');

        $query = Balita::joinSub($latestVerifiedPelayananSubquery, 'latest_pelayanan', function ($join) {
            $join->on('balitas.id', '=', 'latest_pelayanan.id_balita');
        })
        ->join('pelayanans as p_main', 'p_main.id', '=', 'latest_pelayanan.latest_pelayanan_id')
        ->where('balitas.kelurahan', $kelurahan)
        ->whereBetween('balitas.tgl_lahir', [$fiveYearAgo, $thisDay]);

        if ($ukur == 'tbu') {
            $query->where('p_main.tbu', '<', -2);
        } elseif ($ukur == 'bbu') {
            $query->where('p_main.bbu', '<', -2);
        }

        return $query->count();
    }

    public function puskesmasGiziBuruk($puskesmas): \ArielMejiaDev\LarapexCharts\BarChart
    {
        $thisDay = date('Y-m-d');
        $fiveYearAgo = date('Y-m-d', strtotime('-5 years'));

        $pendek = [];
        $kurus = [];
        $axisX = [];

        // Mapping puskesmas ke kelurahan tetap sama
        $puskesmasKelurahanMap = [
            'KUSUMA BANGSA' => ['PANJANG WETAN', 'PANJANG BARU', 'KANDANG PANJANG'],
            'KRAPYAK' => ['KRAPYAK', 'DEGAYU'],
            'DUKUH' => ['PADUKUHAN KRATON', 'BANDENGAN'] 
        ];

        if (isset($puskesmasKelurahanMap[$puskesmas])) {
            $axisX = $puskesmasKelurahanMap[$puskesmas];
            foreach ($axisX as $index => $kelurahanName) {
                $pendek[$index] = $this->getCountForKelurahan($kelurahanName, 'tbu', $fiveYearAgo, $thisDay);
                $kurus[$index] = $this->getCountForKelurahan($kelurahanName, 'bbu', $fiveYearAgo, $thisDay);
            }
        } else {
            // Log atau handle jika puskesmas tidak ditemukan di map
            // error_log("Puskesmas tidak ditemukan di map: " . $puskesmas);
        }

        return $this->chart->barChart()
            ->setTitle('Grafik Masalah Gizi Balita Puskesmas ' . $puskesmas)
            ->setSubtitle('Jumlah masalah gizi balita per kelurahan.')
            ->addData('Pendek', $pendek)
            ->addData('Kurus', $kurus)
            ->setXAxis($axisX)
            ->setHeight(320)
            ->setFontFamily('Montserrat')
            ->setColors(['#FFC107', '#303F9F'])
            ->setMarkers(['#FFC107', '#303F9F'], 7, 10)
            ->setGrid();
    }

    public function giziBuruk($between, $title): \ArielMejiaDev\LarapexCharts\AreaChart
    {
        $thisDay = date('Y-m-d');
        $fiveYearAgo = date('Y-m-d', strtotime('-5 years'));

        $kelurahanList = [
            'PANJANG WETAN', 'PANJANG BARU', 'KANDANG PANJANG',
            'KRAPYAK', 'DEGAYU', 'PADUKUHAN KRATON', 'BANDENGAN'
        ];

        $pendek = [];
        $kurus = [];

        foreach ($kelurahanList as $index => $kelurahanName) {
            // Menggunakan method yang sudah dioptimasi
            $pendek[$index] = $this->getCountGiziBurukForKelurahan($kelurahanName, 'tbu', $between, $fiveYearAgo, $thisDay);
            $kurus[$index] = $this->getCountGiziBurukForKelurahan($kelurahanName, 'bbu', $between, $fiveYearAgo, $thisDay);
        }

        return $this->chart->areaChart()
            ->setTitle('Grafik Masalah Gizi Balita ' . $title)
            ->setSubtitle('Jumlah masalah gizi balita per kelurahan.')
            ->addData('Pendek', $pendek)
            ->addData('Kurus', $kurus)
            ->setXAxis($kelurahanList) // Menggunakan list kelurahan untuk XAxis
            ->setHeight(320)
            ->setFontFamily('Montserrat')
            ->setColors(['#FFC107', '#303F9F'])
            ->setMarkers(['#FFC107', '#303F9F'], 7, 10)
            ->setGrid();
    }

    public function utaraLastMonth(): \ArielMejiaDev\LarapexCharts\LineChart
    {
        $thisDay = date('Y-m-d');
        $fiveYearAgo = date('Y-m-d', strtotime('-5 years'));

        $twelveMonthAgo = date('Y-m-1', strtotime('-11 month'));
        $elevenMonthAgo = date('Y-m-1', strtotime('-10 month'));
        $tenMonthAgo = date('Y-m-1', strtotime('-9 month'));
        $nineMonthAgo = date('Y-m-1', strtotime('-8 month'));
        $eightMonthAgo = date('Y-m-1', strtotime('-7 month'));
        $sevenMonthAgo = date('Y-m-1', strtotime('-6 month'));
        $sixMonthAgo = date('Y-m-1', strtotime('-5 month'));
        $fiveMonthAgo = date('Y-m-1', strtotime('-4 month'));
        $fourMonthAgo = date('Y-m-1', strtotime('-3 month'));
        $threeMonthAgo = date('Y-m-1', strtotime('-2 month'));
        $twoMonthAgo = date('Y-m-1', strtotime('-1 month'));
        $thisMonth = date('Y-m-1', strtotime('-0 month'));

        $data = [];
        $data[0] = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
            ->whereHas('pelayanan', function ($query) use ($twelveMonthAgo, $elevenMonthAgo) {
                $query->whereBetween('tgl_pelayanan', [$twelveMonthAgo, $elevenMonthAgo])->where('tbu', '<', -2)->orderBy('tgl_pelayanan', 'desc')->limit(1);
            })->count();
        $data[1] = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
            ->whereHas('pelayanan', function ($query) use ($elevenMonthAgo, $tenMonthAgo) {
                $query->whereBetween('tgl_pelayanan', [$elevenMonthAgo, $tenMonthAgo])->where('tbu', '<', -2)->orderBy('tgl_pelayanan', 'desc')->limit(1);
            })->count();
        $data[2] = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
            ->whereHas('pelayanan', function ($query) use ($tenMonthAgo, $nineMonthAgo) {
                $query->whereBetween('tgl_pelayanan', [$tenMonthAgo, $nineMonthAgo])->where('tbu', '<', -2)->orderBy('tgl_pelayanan', 'desc')->limit(1);
            })->count();
        $data[3] = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
            ->whereHas('pelayanan', function ($query) use ($nineMonthAgo, $eightMonthAgo) {
                $query->whereBetween('tgl_pelayanan', [$nineMonthAgo, $eightMonthAgo])->where('tbu', '<', -2)->orderBy('tgl_pelayanan', 'desc')->limit(1);
            })->count();
        $data[4] = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
            ->whereHas('pelayanan', function ($query) use ($eightMonthAgo, $sevenMonthAgo) {
                $query->whereBetween('tgl_pelayanan', [$eightMonthAgo, $sevenMonthAgo])->where('tbu', '<', -2)->orderBy('tgl_pelayanan', 'desc')->limit(1);
            })->count();
        $data[5] = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
            ->whereHas('pelayanan', function ($query) use ($sevenMonthAgo, $sixMonthAgo) {
                $query->whereBetween('tgl_pelayanan', [$sevenMonthAgo, $sixMonthAgo])->where('tbu', '<', -2)->orderBy('tgl_pelayanan', 'desc')->limit(1);
            })->count();
        $data[6] = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
            ->whereHas('pelayanan', function ($query) use ($sixMonthAgo, $fiveMonthAgo) {
                $query->whereBetween('tgl_pelayanan', [$sixMonthAgo, $fiveMonthAgo])->where('tbu', '<', -2)->orderBy('tgl_pelayanan', 'desc')->limit(1);
            })->count();
        $data[7] = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
            ->whereHas('pelayanan', function ($query) use ($fiveMonthAgo, $fourMonthAgo) {
                $query->whereBetween('tgl_pelayanan', [$fiveMonthAgo, $fourMonthAgo])->where('tbu', '<', -2)->orderBy('tgl_pelayanan', 'desc')->limit(1);
            })->count();
        $data[8] = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
            ->whereHas('pelayanan', function ($query) use ($fourMonthAgo, $threeMonthAgo) {
                $query->whereBetween('tgl_pelayanan', [$fourMonthAgo, $threeMonthAgo])->where('tbu', '<', -2)->orderBy('tgl_pelayanan', 'desc')->limit(1);
            })->count();
        $data[9] = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
            ->whereHas('pelayanan', function ($query) use ($threeMonthAgo, $twoMonthAgo) {
                $query->whereBetween('tgl_pelayanan', [$threeMonthAgo, $twoMonthAgo])->where('tbu', '<', -2)->orderBy('tgl_pelayanan', 'desc')->limit(1);
            })->count();
        $data[10] = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
            ->whereHas('pelayanan', function ($query) use ($twoMonthAgo, $thisMonth) {
                $query->whereBetween('tgl_pelayanan', [$twoMonthAgo, $thisMonth])->where('tbu', '<', -2)->orderBy('tgl_pelayanan', 'desc')->limit(1);
            })->count();
        $data[11] = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
            ->whereHas('pelayanan', function ($query) use ($thisDay, $thisMonth) {
                $query->whereBetween('tgl_pelayanan', [$thisMonth, $thisDay])->where('tbu', '<', -2)->orderBy('tgl_pelayanan', 'desc')->limit(1);
            })->count();

        $lastMonthName = [
            date('M', strtotime('-11 month')),
            date('M', strtotime('-10 month')),
            date('M', strtotime('-9 month')),
            date('M', strtotime('-8 month')),
            date('M', strtotime('-7 month')),
            date('M', strtotime('-6 month')),
            date('M', strtotime('-5 month')),
            date('M', strtotime('-4 month')),
            date('M', strtotime('-3 month')),
            date('M', strtotime('-2 month')),
            date('M', strtotime('-1 month')),
            date('M', strtotime('-0 month'))
        ];

        return $this->chart->LineChart()
            ->setTitle('Stunting : Kec. Pekalongan Utara',)
            ->setSubtitle('(Kasus Tubuh Pendek) Data 12 bulan terakhir.')
            ->addData('Jumlah', $data)
            ->setXAxis($lastMonthName)
            ->setHeight(320)
            ->setFontFamily('Montserrat')
            ->setColors(['#303F9F'])
            ->setMarkers(['#303F9F'], 7, 10)
            ->setGrid();
    }

    public function utaraBalita(): \ArielMejiaDev\LarapexCharts\BarChart
    {
        $thisDay = date('Y-m-d');
        $fiveYearAgo = date('Y-m-d', strtotime('-5 years'));

        $data = [];
        $data[0] = Balita::where('kelurahan', 'PANJANG WETAN')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->count();
        $data[1] = Balita::where('kelurahan', 'PANJANG BARU')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->count();
        $data[2] = Balita::where('kelurahan', 'KANDANG PANJANG')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->count();
        $data[3] = Balita::where('kelurahan', 'KRAPYAK')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->count();
        $data[4] = Balita::where('kelurahan', 'DEGAYU')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->count();
        $data[5] = Balita::where('kelurahan', 'PADUKUHAN KRATON')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->count();
        $data[6] = Balita::where('kelurahan', 'BANDENGAN')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->count();

        return $this->chart->BarChart()
            ->setTitle('Jumlah Balita Kec. Pekalongan Utara',)
            ->setSubtitle('Data per kelurahan.')
            ->addData('Jumlah', $data)
            ->setXAxis(['Panjang Wetan', 'Panjang Baru', 'Kandang Panjang', 'Krapyak', 'Degayu', 'Padukuhan Kraton', 'Bandengan'])
            ->setHeight(320)
            ->setFontFamily('Montserrat')
            ->setColors(['#303F9F'])
            ->setGrid();
    }

    public function kelurahanLastMonth($kel): \ArielMejiaDev\LarapexCharts\LineChart
    {
        $thisDay = date('Y-m-d');
        $fiveYearAgo = date('Y-m-d', strtotime('-5 years'));
        $fiveMonthAgo = date('Y-m-1', strtotime('-4 month'));
        $fourMonthAgo = date('Y-m-1', strtotime('-3 month'));
        $threeMonthAgo = date('Y-m-1', strtotime('-2 month'));
        $twoMonthAgo = date('Y-m-1', strtotime('-1 month'));
        $thisMonth = date('Y-m-1', strtotime('-0 month'));

        $data = [];
        $data[0] = Balita::where('kelurahan', $kel)->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
            ->whereHas('pelayanan', function ($query) use ($fiveMonthAgo, $fourMonthAgo) {
                $query->whereBetween('tgl_pelayanan', [$fiveMonthAgo, $fourMonthAgo])->where('tbu', '<', -2)->orderBy('tgl_pelayanan', 'desc')->limit(1);
            })->count();
        $data[1] = Balita::where('kelurahan', $kel)->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
            ->whereHas('pelayanan', function ($query) use ($fourMonthAgo, $threeMonthAgo) {
                $query->whereBetween('tgl_pelayanan', [$fourMonthAgo, $threeMonthAgo])->where('tbu', '<', -2)->orderBy('tgl_pelayanan', 'desc')->limit(1);
            })->count();
        $data[2] = Balita::where('kelurahan', $kel)->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
            ->whereHas('pelayanan', function ($query) use ($threeMonthAgo, $twoMonthAgo) {
                $query->whereBetween('tgl_pelayanan', [$threeMonthAgo, $twoMonthAgo])->where('tbu', '<', -2)->orderBy('tgl_pelayanan', 'desc')->limit(1);
            })->count();
        $data[3] = Balita::where('kelurahan', $kel)->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
            ->whereHas('pelayanan', function ($query) use ($twoMonthAgo, $thisMonth) {
                $query->whereBetween('tgl_pelayanan', [$twoMonthAgo, $thisMonth])->where('tbu', '<', -2)->orderBy('tgl_pelayanan', 'desc')->limit(1);
            })->count();
        $data[4] = Balita::where('kelurahan', $kel)->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
            ->whereHas('pelayanan', function ($query) use ($thisDay, $thisMonth) {
                $query->whereBetween('tgl_pelayanan', [$thisMonth, $thisDay])->where('tbu', '<', -2)->orderBy('tgl_pelayanan', 'desc')->limit(1);
            })->count();

        $lastMonthName = [date('M', strtotime('-4 month')), date('M', strtotime('-3 month')), date('M', strtotime('-2 month')), date('M', strtotime('-1 month')), date('M', strtotime('-0 month'))];

        return $this->chart->LineChart()
            ->setTitle('Stunting : Kelurahan ' . ucfirst(strtolower($kel)),)
            ->setSubtitle('(Kasus Tubuh Pendek) Data 5 bulan terakhir.')
            ->addData('Jumlah', $data)
            ->setXAxis($lastMonthName)
            ->setHeight(320)
            ->setFontFamily('Montserrat')
            ->setColors(['#303F9F'])
            ->setMarkers(['#303F9F'], 7, 10)
            ->setGrid();
    }
}
