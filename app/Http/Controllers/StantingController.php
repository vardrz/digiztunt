<?php

namespace App\Http\Controllers;

use App\Models\BalitaAntopometri;
use App\Models\Pelayanan;
use App\Models\Balita;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class StantingController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $title = 'Data Penimbangan Balita'; // Default title
        $kelurahanIn = [];

        if ($user->level == 'admin') {
            if ($user->area == 'KUSUMA BANGSA') {
                $kelurahanIn = ['PANJANG WETAN', 'PANJANG BARU', 'KANDANG PANJANG'];
                $title = 'Data Penimbangan Balita Puskesmas Kusuma Bangsa';
            } elseif ($user->area == 'KRAPYAK') {
                $kelurahanIn = ['KRAPYAK', 'DEGAYU'];
                $title = 'Data Penimbangan Balita Puskesmas Krapyak';
            } elseif ($user->area == 'DUKUH') {
                $kelurahanIn = ['PADUKUHAN KRATON', 'BANDENGAN'];
                $title = 'Data Penimbangan Balita Puskesmas Dukuh';
            }
        }

        if ($request->ajax()) {
            $data = Pelayanan::with(['balita', 'balita.posyanduRelation'])
                ->where('verif', 'n')
                ->whereHas('balita', function ($query) use ($kelurahanIn) {
                    if (!empty($kelurahanIn)) {
                        $query->whereIn('kelurahan', $kelurahanIn);
                    }
                });

            return Datatables::of($data)
                ->addIndexColumn() // Menambahkan kolom nomor urut DT_RowIndex
                ->addColumn('tgl_pendataan', function ($row) {
                    return date('d-m-Y', strtotime($row->tgl_pelayanan));
                })
                ->addColumn('nama_balita', function ($row) {
                    return $row->balita->nama ?? '-';
                })
                ->addColumn('kelurahan', function ($row) {
                    return $row->balita->kelurahan ?? '-';
                })
                ->addColumn('posyandu', function ($row) {
                    return $row->balita->posyanduRelation->name ?? '-'; // Akses relasi posyandu dari balita
                })
                ->addColumn('usia_balita', function ($row) {
                    return $row->usia . ' Bulan';
                })
                ->addColumn('jenis_kelamin', function ($row) {
                    return ($row->balita->jenis_kelamin == 'lk') ? 'Laki-laki' : 'Perempuan';
                })
                ->addColumn('bb_display', function ($row) {
                    // return $row->bb;
                    return "<button class='btn p-0' style='cursor:help' data-bs-toggle='tooltip' data-bs-placement='bottom' title='Berat badan sebelumnya :&NewLine;" . $this->lastData('bb', $row->id_balita, $row->tgl_pelayanan) . "'>" . $row->bb . "</button>";
                })
                ->addColumn('tb_display', function ($row) {
                    // return $row->tb;
                    return "<button class='btn p-0' style='cursor:help' data-bs-toggle='tooltip' data-bs-placement='bottom' title='Tinggi badan sebelumnya :&NewLine;" . $this->lastData('tb', $row->id_balita, $row->tgl_pelayanan) . "'>" . $row->tb . "</button>";
                })
                ->addColumn('lk_display', function ($row) {
                    // return $row->lingkar_kepala;
                    return "<button class='btn p-0' style='cursor:help' data-bs-toggle='tooltip' data-bs-placement='bottom' title='Lingkar kepala sebelumnya :&NewLine;" . $this->lastData('lk', $row->id_balita, $row->tgl_pelayanan) . "'>" . $row->lingkar_kepala . "</button>";
                })
                ->addColumn('action', function ($row) {
                    $verifBtn = '<button onclick="verif(' . $row->id . ', ' . $row->bb . ', ' . $row->tb . ')" class="btn btn-lg py-0 px-1 text-success" data-bs-placement="bottom" title="Accept"><i class="fas fa-check-square"></i></button>';
                    $updateBtn = '<button onclick="update(' . $row->id . ', \'' . addslashes($row->balita->nama) . '\', ' . $row->bb . ', ' . $row->tb . ', ' . $row->lingkar_kepala . ')" class="btn btn-lg py-0 px-1 text-primary" data-bs-placement="bottom"  title="Update"><i class="fas fa-pen-square"></i></button>';
                    
                    $form = '<form action="/verifikasi/accept" method="post" id="form-' . $row->id . '" class="d-none">'
                            . csrf_field()
                            . '<input type="hidden" name="id_balita" value="' . $row->id_balita . '">'
                            . '<input type="hidden" name="id" value="' . $row->id . '">'
                            . '<input type="hidden" name="bb" value="' . $row->bb . '">'
                            . '<input type="hidden" name="tb" value="' . $row->tb . '">'
                            . '<input type="hidden" name="usia" value="' . $row->usia . '">'
                            . '<input type="hidden" name="jenis_kelamin" value="' . $row->balita->jenis_kelamin . '">'
                            . '</form>';
                    return $verifBtn . ' ' . $updateBtn . $form;
                })
                ->rawColumns(['bb_display', 'tb_display', 'lk_display', 'action'])
                ->make(true);
        }

        return view('admin.stantingVerifikasi', [
            'title' => 'Verifikasi ' . $title,
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
            $bln = $month - 1;
        } else {
            $bln = date('m') - 1;
        }

        // Menentukan title berdasarkan user level dan area
        $title = $this->getStatusTitle($bulan[$bln], $tahun);

        return view('admin.stantingHasil', [
            'title' => $title,
            'listBulan' => $bulan,
            'bulan' => [$bulan[$bln], $bln + 1], // Kirim bulan dan index bulan (1-12)
            'tahun' => $tahun,
        ]);
    }

    // Method baru untuk mendapatkan title berdasarkan user level dan area
    private function getStatusTitle($bulanText, $tahun)
    {
        $user = auth()->user();
        
        if ($user->level == 'pimpinan' && $user->area == 'all') {
            return 'Analisis Stunting Pekalongan Utara ' . $bulanText . ' ' . $tahun;
        } elseif ($user->level == 'admin') {
            if ($user->area == 'KUSUMA BANGSA') {
                return 'Analisis Stunting Puskesmas Kusuma Bangsa ' . $bulanText . ' ' . $tahun;
            } elseif ($user->area == 'KRAPYAK') {
                return 'Analisis Stunting Puskesmas Krapyak ' . $bulanText . ' ' . $tahun;
            } elseif ($user->area == 'DUKUH') {
                return 'Analisis Stunting Puskesmas Dukuh ' . $bulanText . ' ' . $tahun;
            }
        }
        
        // Default untuk pimpinan dengan area spesifik
        return 'Analisis Stunting Kelurahan ' . ucwords(strtolower($user->area)) . ' ' . $bulanText . ' ' . $tahun;
    }

    // Method baru untuk menangani request AJAX dari DataTables
    public function dataStatus(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $bulan = $request->input('bulan', date('m'));

        $query = Pelayanan::with(['balita.posyanduRelation'])
            ->where('verif', 'y')
            ->whereYear('tgl_pelayanan', $tahun)
            ->whereMonth('tgl_pelayanan', $bulan)
            ->select('pelayanans.*') // Pastikan semua kolom pelayanans terpilih
            ->addSelect(DB::raw(
                "CASE 
                    WHEN pelayanans.tbu < -3 THEN 1 
                    WHEN pelayanans.tbu >= -3 AND pelayanans.tbu < -2 THEN 2 
                    WHEN pelayanans.tbu >= -2 AND pelayanans.tbu <= 3 THEN 3 
                    WHEN pelayanans.tbu > 3 THEN 4 
                    ELSE 5 
                END as tb_status_order"
            ))
            ->addSelect(DB::raw(
                "CASE 
                    WHEN pelayanans.bbu < -3 THEN 1 
                    WHEN pelayanans.bbu >= -3 AND pelayanans.bbu < -2 THEN 2 
                    WHEN pelayanans.bbu >= -2 AND pelayanans.bbu <= 1 THEN 3 
                    WHEN pelayanans.bbu > 1 THEN 4 
                    ELSE 5 
                END as bb_status_order"
            ));

        // Filter berdasarkan user level dan area
        $user = auth()->user();
        if ($user->level == 'pimpinan' && $user->area != 'all') {
            $query->whereHas('balita', function ($q) use ($user) {
                $q->where('kelurahan', $user->area);
            });
        } elseif ($user->level == 'admin') {
            if ($user->area == 'KUSUMA BANGSA') {
                $query->whereHas('balita', function ($q) {
                    $q->whereIn('kelurahan', ['PANJANG WETAN', 'PANJANG BARU', 'KANDANG PANJANG']);
                });
            } elseif ($user->area == 'KRAPYAK') {
                $query->whereHas('balita', function ($q) {
                    $q->whereIn('kelurahan', ['KRAPYAK', 'DEGAYU']);
                });
            } elseif ($user->area == 'DUKUH') {
                $query->whereHas('balita', function ($q) {
                    $q->whereIn('kelurahan', ['PADUKUHAN KRATON', 'BANDENGAN']);
                });
            }
        }

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('tgl_pendataan', function ($row) {
                return date('d-m-Y', strtotime($row->tgl_pelayanan));
            })
            ->addColumn('nama_balita', function ($row) {
                return $row->balita->nama ?? '-';
            })
            ->addColumn('usia_balita', function ($row) {
                return $row->usia . ' Bulan';
            })
            ->addColumn('jenis_kelamin', function ($row) {
                return ($row->balita->jenis_kelamin == 'lk') ? 'Laki-laki' : 'Perempuan';
            })
            ->addColumn('kelurahan', function ($row) {
                return $row->balita->kelurahan ?? '-';
            })
            ->addColumn('posyandu', function ($row) {
                return $row->balita->posyanduRelation->name ?? '-';
            })
            ->addColumn('orang_tua', function ($row) {
                return $row->balita->nama_ibu . ' & ' . $row->balita->nama_ayah;
            })
            ->addColumn('bb_display', function ($row) {
                $class = '';
                $status = '';
                
                if ($row->bbu < -3) {
                    $class = 'bg-danger';
                    $status = 'Sangat Kurang ';
                } elseif ($row->bbu >= -3 && $row->bbu < -2) {
                    $class = 'bg-warning';
                    $status = 'Kurang ';
                } elseif ($row->bbu >= -2 && $row->bbu <= 1) {
                    $class = 'bg-success';
                    $status = 'Normal ';
                } elseif ($row->bbu > 1) {
                    $class = 'bg-warning';
                    $status = 'Lebih ';
                }
                
                return "<div class='$class p-2'><b>$status</b><br/>($row->bb kg)</div>";
            })
            ->addColumn('tb_display', function ($row) {
                $class = '';
                $status = '';
                
                if ($row->tbu < -3) {
                    $class = 'bg-danger';
                    $status = 'Sangat Pendek ';
                } elseif ($row->tbu >= -3 && $row->tbu < -2) {
                    $class = 'bg-warning';
                    $status = 'Pendek ';
                } elseif ($row->tbu >= -2 && $row->tbu <= 3) {
                    $class = 'bg-success';
                    $status = 'Normal ';
                } elseif ($row->tbu > 3) {
                    $class = 'bg-success';
                    $status = 'Tinggi ';
                }
                
                return "<div class='$class p-2'><b>$status</b><br/>($row->tb cm)</div>";
            })
            ->rawColumns(['bb_display', 'tb_display'])
            ->orderColumn('bb_display', function ($query, $order) {
                $query->orderBy('bb_status_order', $order);
            })
            ->orderColumn('tb_display', function ($query, $order) {
                $query->orderBy('tb_status_order', $order);
            })
            ->make(true);
    }

    public function belumDitimbang($year = null, $month = null)
    {
        $tahun = ($year == null) ? date('Y') : $year;
        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $bln_index = ($month != null) ? $month - 1 : date('m') - 1;

        // Logika untuk menentukan $title berdasarkan user level dan area
        // Ini bisa disederhanakan atau di-refactor jika perlu
        $user = auth()->user();
        $title = '';
        $area = '';

        $areaPrefix = match($user->level) {
            'pimpinan' => $user->area === 'all' ? 'Kec. Pekalongan Utara' : 'Kelurahan ' . ucwords(strtolower($user->area)),
            'admin' => 'Puskesmas ' . ucwords(strtolower($user->area)),
            'petugas' => 'Posyandu ' . ($user->posyandu->name ?? ''),
            default => 'Kelurahan ' . ucwords(strtolower($user->area))
        };

        $title = "Balita Belum Ditimbang $areaPrefix {$bulan[$bln_index]} $tahun";
        $area = $areaPrefix;

        return view('petugas.balitaBelumDitimbang', [
            // 'title' => $title,
            'title' => $title,
            'area' => $area,
            'listBulan' => $bulan,
            'bulan' => [$bulan[$bln_index], $bln_index + 1], // Kirim bulan dan index bulan (1-12)
            'tahun' => $tahun,
        ]);
    }


    // helper func
    private function lastData($type, $id_balita, $tgl_penimbangan){
        $lastData = Pelayanan::where('id_balita', $id_balita)->where('tgl_pelayanan', '<', $tgl_penimbangan)->orderBy('tgl_pelayanan', 'DESC')->first();
        if($lastData){
            switch ($type) {
            case 'tb':
                return $lastData->tb . ' pada usia ' . $lastData->usia . ' bulan.';
                break;
            case 'bb':
                return $lastData->bb . ' pada usia ' . $lastData->usia . ' bulan.';
                break;
            case 'lk':
                return $lastData->lingkar_kepala . ' pada usia ' . $lastData->usia . ' bulan.';
                break;
            }
        }else{
            return "Belum ada.";
        }
    }


    // api
    private function getBalitaQuery($year, $month, $ditimbang = false)
    {
        $tahun_filter = ($year == null) ? date('Y') : $year;
        $bulan_filter = ($month == null) ? date('m') : $month;

        $firstDayOfMonth = $tahun_filter . '-' . str_pad($bulan_filter, 2, '0', STR_PAD_LEFT) . '-01';
        $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfMonth));
        $fiveYearsAgoFromFirstDay = date('Y-m-d', strtotime($firstDayOfMonth . ' -5 years'));

        $between = [$firstDayOfMonth, $lastDayOfMonth];

        $query = Balita::with('posyanduRelation') // Eager load relasi posyandu
            ->whereBetween('tgl_lahir', [$fiveYearsAgoFromFirstDay, $lastDayOfMonth]); // Balita usia 0-5 tahun pada bulan tersebut

        if ($ditimbang) {
            $query->whereHas('pelayanan', function ($q) use ($between) {
                $q->whereBetween('tgl_pelayanan', $between);
            });
        } else {
            $query->whereDoesntHave('pelayanan', function ($q) use ($between) {
                $q->whereBetween('tgl_pelayanan', $between);
            });
        }

        $user = auth()->user();
        if ($user->level == 'pimpinan' && $user->area != 'all') {
            $query->where('balitas.kelurahan', $user->area);
        } elseif ($user->level == 'admin') {
            if ($user->area == 'KUSUMA BANGSA') {
                $query->whereIn('balitas.kelurahan', ['PANJANG WETAN', 'PANJANG BARU', 'KANDANG PANJANG']);
            } elseif ($user->area == 'KRAPYAK') {
                $query->whereIn('balitas.kelurahan', ['KRAPYAK', 'DEGAYU']);
            } elseif ($user->area == 'DUKUH') {
                $query->whereIn('balitas.kelurahan', ['PADUKUHAN KRATON', 'BANDENGAN']);
            }
        } elseif ($user->level == 'petugas') {
            $query->where('posyandu', $user->area);
        }

        return $query;
    }

    public function dataBelumDitimbang(Request $request)
    {
        $query = $this->getBalitaQuery($request->input('tahun'), $request->input('bulan'), false);

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('nik_display', function ($row) {
                return $row->nik == '-' ? '<small>Belum memiliki NIK</small>' : $row->nik;
            })
            ->addColumn('jenis_kelamin_display', function ($row) {
                return $row->jenis_kelamin == 'lk' ? 'Laki-laki' : 'Perempuan';
            })
            ->addColumn('tgl_lahir_display', function ($row) {
                return date('d-m-Y', strtotime($row->tgl_lahir));
            })
            ->addColumn('posyandu_name', function ($row) {
                return $row->posyanduRelation->name ?? '-';
            })
            ->rawColumns(['nik_display'])
            ->make(true);
    }

    public function dataSudahDitimbang(Request $request)
    {
        $tahun_filter = ($request->input('tahun') == null) ? date('Y') : $request->input('tahun');
        $bulan_filter = ($request->input('bulan') == null) ? date('m') : $request->input('bulan');
        $firstDayOfMonth = $tahun_filter . '-' . str_pad($bulan_filter, 2, '0', STR_PAD_LEFT) . '-01';
        $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfMonth));
        $between = [$firstDayOfMonth, $lastDayOfMonth];

        // Subquery to get the latest pelayanan id for each balita in the given month
        $latestPelayananSubquery = Pelayanan::select('id_balita',
                                           DB::raw('MAX(tgl_pelayanan) as max_tgl_pelayanan'),
                                           DB::raw('MAX(id) as max_id')) // Tie-breaker if multiple on same day
                                     ->whereBetween('tgl_pelayanan', $between)
                                     ->groupBy('id_balita');

        $query = $this->getBalitaQuery($request->input('tahun'), $request->input('bulan'), true) // Base query for balitas that HAVE pelayanan
            ->joinSub($latestPelayananSubquery, 'latest_pelayanan_ids', function ($join) {
                $join->on('balitas.id', '=', 'latest_pelayanan_ids.id_balita');
            })
            ->join('pelayanans as p_join', function ($join) {
                $join->on('latest_pelayanan_ids.max_id', '=', 'p_join.id');
            })
            ->select('balitas.*', 'p_join.usia as usia_saat_timbang_db', 'p_join.tgl_pelayanan as tgl_penimbangan_db', 'p_join.bb as bb_saat_timbang_db', 'p_join.tb as tb_saat_timbang_db');

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('nik_display', function ($row) {
                return $row->nik == '-' ? '<small>Belum memiliki NIK</small>' : $row->nik;
            })
            ->addColumn('jenis_kelamin_display', function ($row) {
                return $row->jenis_kelamin == 'lk' ? 'Laki-laki' : 'Perempuan';
            })
            ->addColumn('posyandu_name', function ($row) {
                return $row->posyanduRelation->name ?? '-';
            })
            ->addColumn('usia_saat_timbang', function ($row) {
                return $row->usia_saat_timbang_db !== null ? $row->usia_saat_timbang_db . ' Bulan' : '-';
            })
            ->addColumn('tgl_penimbangan_display', function ($row) {
                return $row->tgl_penimbangan_db ? date('d-m-Y', strtotime($row->tgl_penimbangan_db)) : '-';
            })
            ->addColumn('bb_saat_timbang', function ($row) {
                return $row->bb_saat_timbang_db !== null ? $row->bb_saat_timbang_db : '-';
            })
            ->addColumn('tb_saat_timbang', function ($row) {
                return $row->tb_saat_timbang_db !== null ? $row->tb_saat_timbang_db : '-';
            })
            ->rawColumns(['nik_display'])
            ->orderColumn('usia_saat_timbang', 'usia_saat_timbang_db $1')
            ->orderColumn('tgl_penimbangan_display', 'tgl_penimbangan_db $1')
            ->orderColumn('bb_saat_timbang', 'bb_saat_timbang_db $1')
            ->orderColumn('tb_saat_timbang', 'tb_saat_timbang_db $1')
            ->make(true);
    }
}
