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

    public function giziBuruk(): \ArielMejiaDev\LarapexCharts\AreaChart
    {
        $thisDay = date('Y-m-d');
        $fiveYearAgo = date('Y-m-d', strtotime('-5 years'));

        $pendek = [];
        $pendek[0] = Balita::where('kelurahan', 'PANJANG WETAN')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
            $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('tbu', '<', -2);
        })->count();
        $pendek[1] = Balita::where('kelurahan', 'PANJANG BARU')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
            $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('tbu', '<', -2);
        })->count();
        $pendek[2] = Balita::where('kelurahan', 'KANDANG PANJANG')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
            $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('tbu', '<', -2);
        })->count();
        $pendek[3] = Balita::where('kelurahan', 'KRAPYAK')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
            $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('tbu', '<', -2);
        })->count();
        $pendek[4] = Balita::where('kelurahan', 'DEGAYU')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
            $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('tbu', '<', -2);
        })->count();
        $pendek[5] = Balita::where('kelurahan', 'PADUKUHAN KRATON')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
            $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('tbu', '<', -2);
        })->count();
        $pendek[6] = Balita::where('kelurahan', 'BANDENGAN')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
            $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('tbu', '<', -2);
        })->count();

        $kurus = [];
        $kurus[0] = Balita::where('kelurahan', 'PANJANG WETAN')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
            $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('bbu', '<', -2);
        })->count();
        $kurus[1] = Balita::where('kelurahan', 'PANJANG BARU')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
            $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('bbu', '<', -2);
        })->count();
        $kurus[2] = Balita::where('kelurahan', 'KANDANG PANJANG')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
            $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('bbu', '<', -2);
        })->count();
        $kurus[3] = Balita::where('kelurahan', 'KRAPYAK')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
            $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('bbu', '<', -2);
        })->count();
        $kurus[4] = Balita::where('kelurahan', 'DEGAYU')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
            $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('bbu', '<', -2);
        })->count();
        $kurus[5] = Balita::where('kelurahan', 'PADUKUHAN KRATON')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
            $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('bbu', '<', -2);
        })->count();
        $kurus[6] = Balita::where('kelurahan', 'BANDENGAN')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
            $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('bbu', '<', -2);
        })->count();

        return $this->chart->areaChart()
            ->setTitle('Grafik Masalah Gizi Balita',)
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
        $fiveMonthAgo = date('Y-m-1', strtotime('-4 month'));
        $fourMonthAgo = date('Y-m-1', strtotime('-3 month'));
        $threeMonthAgo = date('Y-m-1', strtotime('-2 month'));
        $twoMonthAgo = date('Y-m-1', strtotime('-1 month'));
        $thisMonth = date('Y-m-1', strtotime('-0 month'));

        $data = [];
        $data[0] = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) use ($fiveMonthAgo, $fourMonthAgo) {
            $query->whereBetween('tgl_pelayanan', [$fiveMonthAgo, $fourMonthAgo])->where('tbu', '<', -2)->orderBy('tgl_pelayanan', 'desc')->limit(1);
        })->count();
        $data[1] = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) use ($fourMonthAgo, $threeMonthAgo) {
            $query->whereBetween('tgl_pelayanan', [$fourMonthAgo, $threeMonthAgo])->where('tbu', '<', -2)->orderBy('tgl_pelayanan', 'desc')->limit(1);
        })->count();
        $data[2] = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) use ($threeMonthAgo, $twoMonthAgo) {
            $query->whereBetween('tgl_pelayanan', [$threeMonthAgo, $twoMonthAgo])->where('tbu', '<', -2)->orderBy('tgl_pelayanan', 'desc')->limit(1);
        })->count();
        $data[3] = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) use ($twoMonthAgo, $thisMonth) {
            $query->whereBetween('tgl_pelayanan', [$twoMonthAgo, $thisMonth])->where('tbu', '<', -2)->orderBy('tgl_pelayanan', 'desc')->limit(1);
        })->count();
        $data[4] = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) use ($thisDay, $thisMonth) {
            $query->whereBetween('tgl_pelayanan', [$thisMonth, $thisDay])->where('tbu', '<', -2)->orderBy('tgl_pelayanan', 'desc')->limit(1);
        })->count();

        $lastMonthName = [date('M', strtotime('-4 month')), date('M', strtotime('-3 month')), date('M', strtotime('-2 month')), date('M', strtotime('-1 month')), date('M', strtotime('-0 month'))];

        return $this->chart->LineChart()
            ->setTitle('Stunting : Kec. Pekalongan Utara',)
            ->setSubtitle('(Kasus Tubuh Pendek) Data 5 bulan terakhir.')
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
        if ($puskesmas == 'KUSUMA BANGSA') {
            $pendek[0] = Balita::where('kelurahan', 'PANJANG WETAN')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
                $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('tbu', '<', -2);
            })->count();
            $pendek[1] = Balita::where('kelurahan', 'PANJANG BARU')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
                $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('tbu', '<', -2);
            })->count();
            $pendek[2] = Balita::where('kelurahan', 'KANDANG PANJANG')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
                $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('tbu', '<', -2);
            })->count();
        } elseif ($puskesmas == 'KRAPYAK') {
            $pendek[0] = Balita::where('kelurahan', 'KRAPYAK')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
                $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('tbu', '<', -2);
            })->count();
            $pendek[1] = Balita::where('kelurahan', 'DEGAYU')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
                $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('tbu', '<', -2);
            })->count();
        } else {
            $pendek[0] = Balita::where('kelurahan', 'PADUKUHAN KRATON')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
                $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('tbu', '<', -2);
            })->count();
            $pendek[1] = Balita::where('kelurahan', 'BANDENGAN')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
                $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('tbu', '<', -2);
            })->count();
        }

        $kurus = [];
        if ($puskesmas == 'KUSUMA BANGSA') {
            $kurus[0] = Balita::where('kelurahan', 'PANJANG WETAN')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
                $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('bbu', '<', -2);
            })->count();
            $kurus[1] = Balita::where('kelurahan', 'PANJANG BARU')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
                $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('bbu', '<', -2);
            })->count();
            $kurus[2] = Balita::where('kelurahan', 'KANDANG PANJANG')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
                $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('bbu', '<', -2);
            })->count();
        } elseif ($puskesmas == 'KRAPYAK') {
            $kurus[0] = Balita::where('kelurahan', 'KRAPYAK')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
                $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('bbu', '<', -2);
            })->count();
            $kurus[1] = Balita::where('kelurahan', 'DEGAYU')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
                $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('bbu', '<', -2);
            })->count();
        } else {
            $kurus[0] = Balita::where('kelurahan', 'PADUKUHAN KRATON')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
                $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('bbu', '<', -2);
            })->count();
            $kurus[1] = Balita::where('kelurahan', 'BANDENGAN')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->whereHas('pelayanan', function ($query) {
                $query->orderBy('tgl_pelayanan', 'desc')->limit(1)->where('bbu', '<', -2);
            })->count();
        }

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
