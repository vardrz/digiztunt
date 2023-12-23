<?php

namespace App\Exports;

use App\Models\Balita;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use \PhpOffice\PhpSpreadsheet\Style\Border;

class LaporanExport implements FromView, WithColumnFormatting, WithColumnWidths, WithEvents, WithStyles
{
    use RegistersEventListeners;

    protected $tahun;
    protected $bulan;

    function __construct($tahun, $bulan)
    {
        $this->tahun = $tahun;
        $this->bulan = $bulan;
    }

    public function view(): View
    {
        $bulanName = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $thisDay = date('Y-m-d');
        $fiveYearAgo = date('Y-m-d', strtotime('-5 years'));

        if (auth()->user()->level == 'petugas') {
            $balitas = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->where('posyandu', auth()->user()->area)->orderBy('tgl_lahir', 'DESC')->get();
        } else {
            return back();
        }

        return view('petugas._rekap', [
            'balitas' => $balitas,
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
            'bulanName' => $bulanName
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'C' => '@',
            'H' => '@',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 3,
            'B' => 15,
            'C' => 12.27,
            'D' => 7.5,
            'E' => 3.45,
            'F' => 8,
            'G' => 15,
            'H' => 12.27,
            'I' => 12.27,
            'J' => 13,
            'K' => 12,
            'L' => 7.5,
            'M' => 3.1,
            'N' => 3.1,
            'O' => 3.1,
            'P' => 3.1,
            'Q' => 3.1,
            'R' => 8.3,
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $event->sheet
                    ->getPageSetup()
                    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            },
            AfterSheet::class => function (AfterSheet $event) {
                $alphabet = $event->sheet->getHighestDataColumn();
                $totalRow = $event->sheet->getHighestDataRow();
                $cellRange = 'A1:' . $alphabet . $totalRow;

                $event->sheet->getStyle($cellRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'font' => ['size' => 8]
                ])->getAlignment()->setWrapText(true);
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $header = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'font' => ['bold' => true]
        ];
        $centered = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ]
        ];

        return [
            // Style the first row as text center.
            1 => $header,
            2 => $header,
            3 => $header,
            'A' => $centered,
            'E' => $centered,
            'M' => $centered,
            'N' => $centered,
            'O' => $centered,
            'P' => $centered,
            'Q' => $centered,
            'R' => $centered,
        ];
    }
}
