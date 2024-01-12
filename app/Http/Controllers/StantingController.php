<?php

namespace App\Http\Controllers;

use App\Models\BalitaAntopometri;
use App\Models\Pelayanan;
use App\Models\Balita;
use Illuminate\Http\Request;

class StantingController extends Controller
{
    public function index()
    {
        if (auth()->user()->level == 'admin' && auth()->user()->area == 'KUSUMA BANGSA') {
            $data = Pelayanan::where('verif', 'n')->whereHas('balita', function ($query) {
                $query->whereIn('kelurahan', ['PANJANG WETAN', 'PANJANG BARU', 'KANDANG PANJANG']);
            })->get();
            $title = 'Data Penimbangan Balita Puskesmas Kusuma Bangsa';
        } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'KRAPYAK') {
            $data = Pelayanan::where('verif', 'n')->whereHas('balita', function ($query) {
                $query->whereIn('kelurahan', ['KRAPYAK', 'DEGAYU']);
            })->get();
            $title = 'Data Penimbangan Balita Puskesmas Krapyak';
        } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'DUKUH') {
            $data = Pelayanan::where('verif', 'n')->whereHas('balita', function ($query) {
                $query->whereIn('kelurahan', ['PADUKUHAN KRATON', 'BANDENGAN']);
            })->get();
            $title = 'Data Penimbangan Balita Puskesmas Dukuh';
        }

        return view('admin.stantingVerifikasi', [
            'title' => 'Verifikasi ' . $title,
            'data' => $data,
        ]);
    }

    private function cekTBU($tbu_zscore)
    {
        if ($tbu_zscore < -3) {
            return "Stunting";
        } elseif ($tbu_zscore >= -3 && $tbu_zscore < -2) {
            return "Stunting";
        } elseif ($tbu_zscore >= -2 && $tbu_zscore <= 3) {
            return "Normal";
        } elseif ($tbu_zscore > 3) {
            return "Normal";
        }
    }

    public function verif(Request $req)
    {
        $standarAntro = BalitaAntopometri::where('usia', $req->usia)->where('jenis_kelamin', $req->jenis_kelamin)->first();

        $bbu_zscore = round((intval($req->bb) - $standarAntro->bbuMedian) / ($standarAntro->bbuMedian - $standarAntro->bbuMin1sd), 2);
        $tbu_zscore = round((intval($req->tb) - $standarAntro->tbuMedian) / ($standarAntro->tbuMedian - $standarAntro->tbuMin1sd), 2);

        $status = $this->cekTBU($tbu_zscore);

        Balita::where('id', $req->id_balita)->update(['status' => $status]);
        Pelayanan::where('id', $req->id)->update(['verif' => 'y', 'bbu' => $bbu_zscore, 'tbu' => $tbu_zscore]);

        return back()->with('success', 'Data berhasil diverifikasi.');
    }

    public function update(Request $req)
    {
        $data = [
            'tb' => str_replace([','], ['.'], $req->tb),
            'bb' => str_replace([','], ['.'], $req->bb),
            'lingkar_kepala' => str_replace([','], ['.'], $req->lingkar_kepala)
        ];

        Pelayanan::where('id', $req->id)->update($data);
        return redirect('/verifikasi')->with('success', 'Data berhasil diperbarui.');
    }

    public function status($year = null, $month = null)
    {
        $tahun = ($year == null) ? date('Y') : $year;
        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        if ($month != null) {
            // $between = [date('Y-') . $month . '-1', date('Y-') . $month . '-28'];
            $between = [$tahun . '-' . $month . '-1', $tahun . '-' . $month . '-28'];
            $bln = $month - 1;
        } else {
            // $between = [date('Y-m-1'), date('Y-m-28')];
            $between = [$tahun . '-' . date('m-1'), $tahun . '-' . date('m-28')];
            $bln = date('m') - 1;
        }

        // Pimpinan
        if (auth()->user()->level == 'pimpinan' && auth()->user()->area == 'all') {
            $data = Pelayanan::where('verif', 'y')->whereBetween('tgl_pelayanan', $between)->get();
            $title = 'Analisis Stunting Pekalongan Utara ' . $bulan[$bln] . ' ' . $tahun;
        } else {
            $data = Pelayanan::where('verif', 'y')->whereBetween('tgl_pelayanan', $between)->whereHas('balita', function ($query) {
                $query->where('kelurahan', auth()->user()->area);
            })->get();
            $title = 'Analisis Stunting Kelurahan ' . ucwords(strtolower(auth()->user()->area)) . ' ' . $bulan[$bln] . ' ' . $tahun;
        }

        // Puskesmas
        if (auth()->user()->level == 'admin' && auth()->user()->area == 'KUSUMA BANGSA') {
            $data = Pelayanan::where('verif', 'y')->whereBetween('tgl_pelayanan', $between)->whereHas('balita', function ($query) {
                $query->whereIn('kelurahan', ['PANJANG WETAN', 'PANJANG BARU', 'KANDANG PANJANG']);
            })->get();
            $title = 'Analisis Stunting Puskesmas Kusuma Bangsa ' . $bulan[$bln] . ' ' . $tahun;
        } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'KRAPYAK') {
            $data = Pelayanan::where('verif', 'y')->whereBetween('tgl_pelayanan', $between)->whereHas('balita', function ($query) {
                $query->whereIn('kelurahan', ['KRAPYAK', 'DEGAYU']);
            })->get();
            $title = 'Analisis Stunting Puskesmas Krapyak ' . $bulan[$bln] . ' ' . $tahun;
        } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'DUKUH') {
            $data = Pelayanan::where('verif', 'y')->whereBetween('tgl_pelayanan', $between)->whereHas('balita', function ($query) {
                $query->whereIn('kelurahan', ['PADUKUHAN KRATON', 'BANDENGAN']);
            })->get();
            $title = 'Analisis Stunting Puskesmas Dukuh ' . $bulan[$bln] . ' ' . $tahun;
        }

        return view('admin.stantingHasil', [
            'title' => $title,
            'data' => $data,
            'listBulan' => $bulan,
            'bulan' => [$bulan[$bln], $bln],
            'tahun' => $tahun,
        ]);
    }

    public function belumDitimbang($year = null, $month = null)
    {
        $tahun = ($year == null) ? date('Y') : $year;
        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        if ($month != null) {
            $fiveYearAgo = date('Y-m-d', strtotime(date($tahun . '-' . $month . '-1') . '-5 years'));
            $thisDay = $tahun . '-' . $month . '-' . '01';

            $between = [$tahun . '-' . $month . '-1', $tahun . '-' . $month . '-28'];
            $bln = $month - 1;
        } else {
            $fiveYearAgo = date('Y-m-d', strtotime(date($tahun . '-m-1') . '-5 years'));
            $thisDay = $tahun . '-' . date('m-d');

            $between = [$tahun . '-' . date('m-1'), $tahun . '-' . date('m-28')];
            $bln = date('m') - 1;
        }

        // Pimpinan
        if (auth()->user()->level == 'pimpinan' && auth()->user()->area == 'all') {
            $belumDitimbang = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
                ->whereDoesntHave('pelayanan', function ($query) use ($between) {
                    $query->whereBetween('tgl_pelayanan', $between);
                })->get();
            $sudahDitimbang = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
                ->whereHas('pelayanan', function ($query) use ($between) {
                    $query->whereBetween('tgl_pelayanan', $between);
                })->get();
            $title = 'Balita Belum Ditimbang Kec. Pekalongan Utara ' . $bulan[$bln] . ' ' . $tahun;
        } else {
            $belumDitimbang = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->where('kelurahan', auth()->user()->area)
                ->whereDoesntHave('pelayanan', function ($query) use ($between) {
                    $query->whereBetween('tgl_pelayanan', $between);
                })->get();
            $sudahDitimbang = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])->where('kelurahan', auth()->user()->area)
                ->whereHas('pelayanan', function ($query) use ($between) {
                    $query->whereBetween('tgl_pelayanan', $between);
                })->get();
            $title = 'Balita Belum Ditimbang Kelurahan ' . ucwords(strtolower(auth()->user()->area)) . ' ' . $bulan[$bln] . ' ' . $tahun;
        }

        // Puskesmas
        if (auth()->user()->level == 'admin' && auth()->user()->area == 'KUSUMA BANGSA') {
            $belumDitimbang = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
                ->whereIn('kelurahan', ['PANJANG WETAN', 'PANJANG BARU', 'KANDANG PANJANG'])
                ->whereDoesntHave('pelayanan', function ($query) use ($between) {
                    $query->whereBetween('tgl_pelayanan', $between);
                })->get();
            $sudahDitimbang = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
                ->whereIn('kelurahan', ['PANJANG WETAN', 'PANJANG BARU', 'KANDANG PANJANG'])
                ->whereHas('pelayanan', function ($query) use ($between) {
                    $query->whereBetween('tgl_pelayanan', $between);
                })->get();
            $title = 'Balita Belum Ditimbang Puskesmas Kusuma Bangsa ' . $bulan[$bln] . ' ' . $tahun;
        } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'KRAPYAK') {
            $belumDitimbang = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
                ->whereIn('kelurahan', ['KRAPYAK', 'DEGAYU'])
                ->whereDoesntHave('pelayanan', function ($query) use ($between) {
                    $query->whereBetween('tgl_pelayanan', $between);
                })->get();
            $sudahDitimbang = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
                ->whereIn('kelurahan', ['KRAPYAK', 'DEGAYU'])
                ->whereHas('pelayanan', function ($query) use ($between) {
                    $query->whereBetween('tgl_pelayanan', $between);
                })->get();
            $title = 'Balita Belum Ditimbang Puskesmas Krapyak ' . $bulan[$bln] . ' ' . $tahun;
        } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'DUKUH') {
            $belumDitimbang = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
                ->whereIn('kelurahan', ['PADUKUHAN KRATON', 'BANDENGAN'])
                ->whereDoesntHave('pelayanan', function ($query) use ($between) {
                    $query->whereBetween('tgl_pelayanan', $between);
                })->get();
            $sudahDitimbang = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
                ->whereIn('kelurahan', ['PADUKUHAN KRATON', 'BANDENGAN'])
                ->whereHas('pelayanan', function ($query) use ($between) {
                    $query->whereBetween('tgl_pelayanan', $between);
                })->get();
            $title = 'Balita Belum Ditimbang Puskesmas Dukuh ' . $bulan[$bln] . ' ' . $tahun;
        }

        // Posyandu
        if (auth()->user()->level == 'petugas') {
            $belumDitimbang = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
                ->where('posyandu', auth()->user()->area)
                ->whereDoesntHave('pelayanan', function ($query) use ($between) {
                    $query->whereBetween('tgl_pelayanan', $between);
                })->get();
            $sudahDitimbang = Balita::whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay])
                ->where('posyandu', auth()->user()->area)
                ->whereHas('pelayanan', function ($query) use ($between) {
                    $query->whereBetween('tgl_pelayanan', $between);
                })->get();
            $title = 'Balita Belum Ditimbang Posyandu ' . auth()->user()->posyandu->name . ' ' . $bulan[$bln] . ' ' . $tahun;
        }

        // foreach ($data as $d) {
        //     echo $d->nama . '<br>';
        // }
        // die;
        return view('petugas.balitaBelumDitimbang', [
            'title' => $title,
            'belumDitimbang' => $belumDitimbang,
            'sudahDitimbang' => $sudahDitimbang,
            'listBulan' => $bulan,
            'bulan' => [$bulan[$bln], $bln],
            'tahun' => $tahun,
            'between' => $between
        ]);
    }
}
