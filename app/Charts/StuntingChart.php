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

    public function kota(): \ArielMejiaDev\LarapexCharts\BarChart
    {
        $data = [];

        $data[0] = Balita::where('kecamatan', 'PEKALONGAN UTARA')->where('status', 'Stunting')->count();
        $data[1] = Balita::where('kecamatan', 'PEKALONGAN TIMUR')->where('status', 'Stunting')->count();
        $data[2] = Balita::where('kecamatan', 'PEKALONGAN SELATAN')->where('status', 'Stunting')->count();
        $data[3] = Balita::where('kecamatan', 'PEKALONGAN BARAT')->where('status', 'Stunting')->count();

        return $this->chart->barChart()
            ->setTitle('Stunting di Kota Pekalongan',)
            ->setSubtitle('Data 3 bulan terakhir.')
            // ->addData('Jumlah Stunting', $data)
            ->addData('Aug', [10, 3, 4, 1])
            ->addData('Sept', [7, 2, 3, 2])
            ->addData('Oct', [6, 2, 2, 1])
            ->setHeight(400)
            ->setFontFamily('Roboto')
            ->setXAxis(['Pekalongan Utara', 'Pekalongan Timur', 'Pekalongan Selatan', 'Pekalongan Barat'])
            ->setGrid();
    }

    public function utaraCount(): \ArielMejiaDev\LarapexCharts\LineChart
    {
        return $this->chart->LineChart()
            ->setTitle('Stunting : Kec. Pekalongan Utara',)
            ->setSubtitle('Data 5 bulan terakhir.')
            ->addData('Jumlah', [10, 3, 4, 1, 1])
            ->setHeight(280)
            ->setFontFamily('Roboto')
            ->setXAxis(['Jun', 'Jul', 'Aug', 'Sept', 'Oct'])
            ->setGrid();
    }

    public function utaraList(): \ArielMejiaDev\LarapexCharts\BarChart
    {
        return $this->chart->BarChart()
            ->setTitle('Stunting : Kec. Pekalongan Utara',)
            ->setSubtitle('Data per kelurahan.')
            ->addData('Jumlah', [2, 3, 4, 1, 1, 2, 3])
            ->setHeight(280)
            ->setFontFamily('Roboto')
            ->setXAxis(['Krapyak', 'Kandang Panjang', 'Panjang Wetan', 'Padukuhan Kraton', 'Degayu', 'Bandengan', 'Panjang Baru'])
            ->setGrid();
    }
}
