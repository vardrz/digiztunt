<?php

namespace App\Charts;

use App\Models\Balita;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class StuntingChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    private function countDataGiziBuruk($data, $ukur, $between)
    {
        // dd($between);
        $count = 0;
        foreach ($data as $d) {
            // $cek = $d->pelayanan->where('verif', 'y')->whereBetween('tgl_pelayanan', $between);
            // dd($cek);

            if ($ukur == 'tbu') {
                if ($d->status == null) {
                    // $count = 0;
                } elseif ($d->pelayanan->where('verif', 'y')->whereBetween('tgl_pelayanan', $between)->last() == null) {
                    // $count++;
                } elseif ($d->pelayanan->where('verif', 'y')->whereBetween('tgl_pelayanan', $between)->last()->tbu < -2) {
                    $count++;
                }
            } else {
                if ($d->status == null) {
                    // $count = 0;
                } elseif ($d->pelayanan->where('verif', 'y')->whereBetween('tgl_pelayanan', $between)->last() == null) {
                    // $count++;
                } elseif ($d->pelayanan->where('verif', 'y')->whereBetween('tgl_pelayanan', $between)->last()->bbu < -2) {
                    $count++;
                }
            }
        }

        return $count;
    }

    private function countData($data, $ukur)
    {
        // dd($data[0]->pelayanan->last()->verif == 'n');
        $count = 0;
        foreach ($data as $d) {
            if ($ukur == 'tbu') {
                if ($d->status == null) {
                    // $count = 0;
                } elseif ($d->pelayanan->where('verif', 'y')->last()->tbu < -2) {
                    $count++;
                }
            } else {
                if ($d->status == null) {
                    // $count = 0;
                } elseif ($d->pelayanan->where('verif', 'y')->last()->bbu < -2) {
                    $count++;
                }
            }
        }

        return $count;
    }

    public function giziBuruk($between, $title): \ArielMejiaDev\LarapexCharts\AreaChart
    {
        $thisDay = date('Y-m-d');
        $fiveYearAgo = date('Y-m-d', strtotime('-5 years'));

        $pendek = [];
        $kurus = [];

        $panjangwetan = Balita::where('kelurahan', 'PANJANG WETAN')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->get();
        $pendek[0] = $this->countDataGiziBuruk($panjangwetan, 'tbu', $between);
        $kurus[0] = $this->countDataGiziBuruk($panjangwetan, 'bbu', $between);
        $panjangbaru = Balita::where('kelurahan', 'PANJANG BARU')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->get();
        $pendek[1] = $this->countDataGiziBuruk($panjangbaru, 'tbu', $between);
        $kurus[1] = $this->countDataGiziBuruk($panjangbaru, 'bbu', $between);
        $kandangpanjang = Balita::where('kelurahan', 'KANDANG PANJANG')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->get();
        $pendek[2] = $this->countDataGiziBuruk($kandangpanjang, 'tbu', $between);
        $kurus[2] = $this->countDataGiziBuruk($kandangpanjang, 'bbu', $between);
        $krapyak = Balita::where('kelurahan', 'KRAPYAK')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->get();
        $pendek[3] = $this->countDataGiziBuruk($krapyak, 'tbu', $between);
        $kurus[3] = $this->countDataGiziBuruk($krapyak, 'bbu', $between);
        $degayu = Balita::where('kelurahan', 'DEGAYU')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->get();
        $pendek[4] = $this->countDataGiziBuruk($degayu, 'tbu', $between);
        $kurus[4] = $this->countDataGiziBuruk($degayu, 'bbu', $between);
        $padukuhankraton = Balita::where('kelurahan', 'PADUKUHAN KRATON')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->get();
        $pendek[5] = $this->countDataGiziBuruk($padukuhankraton, 'tbu', $between);
        $kurus[5] = $this->countDataGiziBuruk($padukuhankraton, 'bbu', $between);
        $bandengan = Balita::where('kelurahan', 'BANDENGAN')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->get();
        $pendek[6] = $this->countDataGiziBuruk($bandengan, 'tbu', $between);
        $kurus[6] = $this->countDataGiziBuruk($bandengan, 'bbu', $between);

        return $this->chart->areaChart()
            ->setTitle('Grafik Masalah Gizi Balita ' . $title,)
            ->setSubtitle('Jumlah masalah gizi balita per kelurahan.')
            ->addData('Pendek', $pendek)
            ->addData('Kurus', $kurus)
            ->setXAxis(['Panjang Wetan', 'Panjang Baru', 'Kandang Panjang', 'Krapyak', 'Degayu', 'Padukuhan Kraton', 'Bandengan'])
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

    public function puskesmasGiziBuruk($puskesmas): \ArielMejiaDev\LarapexCharts\BarChart
    {
        $thisDay = date('Y-m-d');
        $fiveYearAgo = date('Y-m-d', strtotime('-5 years'));

        $pendek = [];
        $kurus = [];

        if ($puskesmas == 'KUSUMA BANGSA') {
            $panjangwetan = Balita::where('kelurahan', 'PANJANG WETAN')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->get();
            $pendek[0] = $this->countData($panjangwetan, 'tbu');
            $kurus[0] = $this->countData($panjangwetan, 'bbu');
            $panjangbaru = Balita::where('kelurahan', 'PANJANG BARU')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->get();
            $pendek[1] = $this->countData($panjangbaru, 'tbu');
            $kurus[1] = $this->countData($panjangbaru, 'bbu');
            $kandangpanjang = Balita::where('kelurahan', 'KANDANG PANJANG')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->get();
            $pendek[2] = $this->countData($kandangpanjang, 'tbu');
            $kurus[2] = $this->countData($kandangpanjang, 'bbu');
        } elseif ($puskesmas == 'KRAPYAK') {
            $krapyak = Balita::where('kelurahan', 'KRAPYAK')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->get();
            $pendek[0] = $this->countData($krapyak, 'tbu');
            $kurus[0] = $this->countData($krapyak, 'bbu');
            $degayu = Balita::where('kelurahan', 'DEGAYU')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->get();
            $pendek[1] = $this->countData($degayu, 'tbu');
            $kurus[1] = $this->countData($degayu, 'bbu');
        } else {
            $padukuhankraton = Balita::where('kelurahan', 'PADUKUHAN KRATON')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->get();
            $pendek[0] = $this->countData($padukuhankraton, 'tbu');
            $kurus[0] = $this->countData($padukuhankraton, 'bbu');
            $bandengan = Balita::where('kelurahan', 'BANDENGAN')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->get();
            $pendek[1] = $this->countData($bandengan, 'tbu');
            $kurus[1] = $this->countData($bandengan, 'bbu');
        }

        // $kurus[0] = Balita::where('kelurahan', 'PANJANG WETAN')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
        //     $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('bbu', '<', -2);
        // })->count();

        if ($puskesmas == 'KUSUMA BANGSA') {
            $axisX = ['Panjang Wetan', 'Panjang Baru', 'Kandang Panjang'];
        } elseif ($puskesmas == 'KRAPYAK') {
            $axisX = ['Krapyak', 'Degayu'];
        } else {
            $axisX = ['Padukuhan Kraton', 'Bandengan'];
        }

        return $this->chart->barChart()
            ->setTitle('Grafik Masalah Gizi Balita',)
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
