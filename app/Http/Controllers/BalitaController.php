<?php

namespace App\Http\Controllers;

use App\Imports\BalitaImport;
use App\Models\Balita;
use App\Models\Pelayanan;
use App\Models\Posyandu;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;

// use function PHPUnit\Framework\isNull;

class BalitaController extends Controller
{
    // Fungsi helper untuk menghitung usia, bisa diletakkan di dalam kelas atau sebagai private method
    private function calculateUsia($tanggalLahir)
    {
        $tanggalLahirObj = new \DateTime($tanggalLahir);
        $hariIni = new \DateTime();

        $selisihTahun = $hariIni->format('Y') - $tanggalLahirObj->format('Y');
        $selisihBulan = $hariIni->format('m') - $tanggalLahirObj->format('m');
        $selisihHari = $tanggalLahirObj->diff($hariIni)->format('%a');
        
        $totalBulan = $selisihTahun * 12 + $selisihBulan;

        if($selisihHari > 30){
        return $totalBulan . ' Bulan';
        }else{
        return $selisihHari . ' Hari';
        }
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $thisDay = date('Y-m-d');
            $fiveYearAgo = date('Y-m-d', strtotime('-5 years'));
            
            $query = Balita::with('posyanduRelation')->whereBetween('tgl_lahir', [$fiveYearAgo, $thisDay]);

            // Terapkan filter berdasarkan level dan area pengguna
            if (auth()->user()->level == 'pimpinan' && auth()->user()->area != 'all') {
                $query->where('balitas.kelurahan', auth()->user()->area); // Perubahan di sini
            } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'KUSUMA BANGSA') {
                $query->whereIn('balitas.kelurahan', ['PANJANG WETAN', 'PANJANG BARU', 'KANDANG PANJANG']); // Perubahan di sini
            } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'KRAPYAK') {
                $query->whereIn('balitas.kelurahan', ['KRAPYAK', 'DEGAYU']); // Perubahan di sini
            } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'DUKUH') {
                $query->whereIn('balitas.kelurahan', ['PADUKUHAN KRATON', 'BANDENGAN']); // Perubahan di sini
            } elseif (auth()->user()->level == 'petugas') {
                $query->where('posyandu', auth()->user()->area);
            }
            // Untuk 'pimpinan' dengan area 'all', tidak ada filter tambahan pada query utama

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('usia', function ($row) {
                    return $this->calculateUsia($row->tgl_lahir);
                })
                ->editColumn('nik', function ($row) {
                    return $row->nik == '-' ? '<small>Belum memiliki NIK</small>' : e($row->nik);
                })
                ->editColumn('nama', function ($row) {
                    $posyanduName = $row->posyanduRelation ? $row->posyanduRelation->name : '';

                    // Prepare modal data parameters with proper escaping and JSON encoding
                    $modalParams = [
                        $row->id,
                        json_encode($row->nama),
                        json_encode($row->kelurahan),
                        json_encode($posyanduName), 
                        json_encode($row->nama_ibu),
                        json_encode($row->nik_ibu),
                        json_encode($row->nama_ayah),
                        json_encode($row->nik_ayah),
                        json_encode($row->no_kk)
                    ];

                    // Build onclick handler with joined parameters
                    $onClickHandler = sprintf(
                        "dataModal(%s)", 
                        implode(",", $modalParams)
                    );

                    // Return clickable name span with escaped display name
                    return sprintf(
                        '<span style="cursor: pointer;" onclick="%s">%s</span>',
                        htmlspecialchars($onClickHandler, ENT_QUOTES),
                        htmlspecialchars($row->nama, ENT_QUOTES)
                    );
                })
                ->editColumn('jenis_kelamin', function ($row) {
                    return $row->jenis_kelamin == 'lk' ? 'Laki-laki' : 'Perempuan';
                })
                ->editColumn('tgl_lahir', function ($row) {
                    return date('d-m-Y', strtotime($row->tgl_lahir));
                })
                ->addColumn('posyandu_name', function ($row) {
                    return $row->posyanduRelation ? e($row->posyanduRelation->name) : '-';
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '';
                    if (session('level') == 'pimpinan' && auth()->user()->area != 'all') {
                        $editUrl = '/balita/edit/' . $row->id;
                        $deleteFormId = 'delete' . $row->id;
                        $deleteUrl = url('/balita/delete/' . $row->id);
                        $actionBtn = '<a href="'.$editUrl.'" class="btn btn-lg py-0 px-0 mr-1 text-primary"><i class="fas fa-edit"></i></a>';
                        $actionBtn .= '<button type="button" onclick="del('.$row->id.')" class="btn btn-lg py-0 px-0 text-danger"><i class="fas fa-trash"></i></button>';
                        $actionBtn .= '<form action="'.$deleteUrl.'" method="post" id="'.$deleteFormId.'">'.csrf_field().'</form>';
                    }
                    return $actionBtn;
                })
                ->rawColumns(['action', 'nik', 'nama'])
                ->make(true);
        }

        // Logika untuk judul tetap sama
        $title = "Daftar Balita"; // Default
        if (auth()->user()->level == 'pimpinan' && auth()->user()->area == 'all') {
            $title = "Daftar Balita Pekalongan Utara";
        } elseif (auth()->user()->level == 'pimpinan') { // pimpinan kelurahan
            $title = "Daftar Balita Kelurahan " . ucwords(strtolower(auth()->user()->area));
        } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'KUSUMA BANGSA') {
            $title = "Daftar Balita Puskesmas Kusuma Bangsa";
        } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'KRAPYAK') {
            $title = "Daftar Balita Puskesmas Krapyak";
        } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'DUKUH') {
            $title = "Daftar Balita Puskesmas Dukuh";
        } elseif (auth()->user()->level == 'petugas') {
            $namaPos = Posyandu::where('id', auth()->user()->area)->first();
            $title = "Daftar Balita Posyandu " . ($namaPos ? e($namaPos->name) : 'N/A');
        }
        
        return view('petugas.balitaList', compact('title'));
    }

    public function find($data = null)
    {
        $balita = Balita::where('posyandu', auth()->user()->area)->where(function ($query) use ($data) {
            $query->where('nama', 'like', '%' . $data . '%')->orWhere('nik', $data);
        })->orderBy('nama')->get();
        return $balita;
    }

    public function new()
    {
        $bulan = [
            ['no' => '1', 'name' => 'Januari'],
            ['no' => '2', 'name' => 'Februari'],
            ['no' => '3', 'name' => 'Maret'],
            ['no' => '4', 'name' => 'April'],
            ['no' => '5', 'name' => 'Mei'],
            ['no' => '6', 'name' => 'Juni'],
            ['no' => '7', 'name' => 'Juli'],
            ['no' => '8', 'name' => 'Agustus'],
            ['no' => '9', 'name' => 'September'],
            ['no' => '10', 'name' => 'Oktober'],
            ['no' => '11', 'name' => 'November'],
            ['no' => '12', 'name' => 'Desember']
        ];

        // $kecamatan = [
        //     ["id" => "3375010", "name" => "PEKALONGAN BARAT"],
        //     ["id" => "3375020", "name" => "PEKALONGAN TIMUR"],
        //     ["id" => "3375030", "name" => "PEKALONGAN SELATAN"],
        //     ["id" => "3375040", "name" => "PEKALONGAN UTARA"]
        // ];

        $kelurahan = [
            "KRAPYAK", "KANDANG PANJANG", "PANJANG WETAN", "PANJANG BARU", "DEGAYU", "BANDENGAN", "PADUKUHAN KRATON"
        ];

        $posyandu = Posyandu::where('kelurahan', auth()->user()->area)->get();

        return view('petugas.balitaNew', [
            'title' => 'Tambah Data Balita',
            'month' => $bulan,
            'kelurahan' => $kelurahan,
            'posyandu' => $posyandu
        ]);
    }

    public function history(Request $request)
    {
        // if (session('level') != 'admin') {
        //     return abort(403, 'Anda tidak memiliki hak mengakses laman ini!');
        // }

        $fiveYearAgo = date('Y-m-d', strtotime('-5 years'));

        if ($request->ajax()) {
            $query = Balita::with('posyanduRelation')->where('tgl_lahir', '<', $fiveYearAgo);

            // Filter berdasarkan level dan area pengguna
            if (auth()->user()->level == 'pimpinan' && auth()->user()->area == 'all') {
                // Tidak ada filter tambahan untuk pimpinan 'all'
            } elseif (auth()->user()->level == 'pimpinan') { // Pimpinan dengan area spesifik
                $query->where('balitas.kelurahan', auth()->user()->area); // Perubahan di sini
            } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'KUSUMA BANGSA') {
                $query->whereIn('balitas.kelurahan', ['PANJANG WETAN', 'PANJANG BARU', 'KANDANG PANJANG']); // Perubahan di sini
            } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'KRAPYAK') {
                $query->whereIn('balitas.kelurahan', ['KRAPYAK', 'DEGAYU']); // Perubahan di sini
            } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'DUKUH') {
                $query->whereIn('balitas.kelurahan', ['PADUKUHAN KRATON', 'BANDENGAN']); // Perubahan di sini
            } elseif (auth()->user()->level == 'petugas') {
                // Asumsi 'posyandu' di tabel balitas adalah ID posyandu
                $query->where('posyandu', auth()->user()->area); 
            }

            return DataTables::of($query)
                ->addIndexColumn() // Menambahkan kolom DT_RowIndex (nomor urut)
                ->addColumn('usia', function ($row) {
                    $tanggalLahirObj = new \DateTime($row->tgl_lahir);
                    $hariIni = new \DateTime();
                    
                    $selisihTahun = $hariIni->format('Y') - $tanggalLahirObj->format('Y');
                    $selisihBulan = $hariIni->format('m') - $tanggalLahirObj->format('m');

                    return $selisihTahun . ' Tahun ' . $selisihBulan . ' Bulan';
                })
                ->editColumn('nik', function ($row) {
                    return $row->nik == '-' ? '<small>Belum memiliki NIK</small>' : e($row->nik);
                })
                ->editColumn('nama', function ($row) {
                    $posyanduName = $row->posyanduRelation ? $row->posyanduRelation->name : '';
                    return '<span style="cursor: pointer;" onclick="dataModal(\''. $row->id .'\',\''. htmlspecialchars($row->nama, ENT_QUOTES) .'\',\''. htmlspecialchars($row->kelurahan, ENT_QUOTES) .'\',\''. htmlspecialchars($posyanduName, ENT_QUOTES) .'\',\''. htmlspecialchars($row->nama_ibu, ENT_QUOTES) .'\',\''. htmlspecialchars($row->nik_ibu, ENT_QUOTES) .'\',\''. htmlspecialchars($row->nama_ayah, ENT_QUOTES) .'\',\''. htmlspecialchars($row->nik_ayah, ENT_QUOTES) .'\',\''. htmlspecialchars($row->no_kk, ENT_QUOTES) .'\')">'. htmlspecialchars($row->nama, ENT_QUOTES) .'</span>';
                })
                ->editColumn('jenis_kelamin', function ($row) {
                    return $row->jenis_kelamin == 'lk' ? 'Laki-laki' : 'Perempuan';
                })
                ->editColumn('tgl_lahir', function ($row) {
                    return date('d-m-Y', strtotime($row->tgl_lahir));
                })
                ->addColumn('posyandu_name', function ($row) {
                    return $row->posyanduRelation ? $row->posyanduRelation->name : '-';
                })
                ->rawColumns(['nik', 'nama'])
                ->make(true);
        }

        // Logika untuk menentukan judul (bisa disederhanakan atau dipindahkan jika perlu)
        $title = "History Balita"; // Judul default
        if (auth()->user()->level == 'pimpinan' && auth()->user()->area == 'all') {
            $title = "History Balita Pekalongan Utara";
        } elseif (auth()->user()->level == 'pimpinan') {
            $title = "History Balita Kelurahan " . ucwords(strtolower(auth()->user()->area));
        } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'KUSUMA BANGSA') {
            $title = "History Balita Puskesmas Kusuma Bangsa";
        } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'KRAPYAK') {
            $title = "History Balita Puskesmas Krapyak";
        } elseif (auth()->user()->level == 'admin' && auth()->user()->area == 'DUKUH') {
            $title = "History Balita Puskesmas Dukuh";
        } elseif (auth()->user()->level == 'petugas') {
            $namaPos = Posyandu::find(auth()->user()->area);
            $title = "History Balita Posyandu " . ($namaPos ? $namaPos->name : 'Tidak Diketahui');
        }

        return view('admin.balitaHistory', [
            'title' => $title
        ]);
    }

    public function store(Request $request)
    {
        if ($request->haveNIK == 'y') {
            $validate = $request->validate([
                "nama" => "required",
                "jenis_kelamin" => "required",
                "nik" => "required|unique:balitas|min:16",
                "namaibu" => "required",
                "nikibu" => "required|min:16",
                "namaayah" => "required",
                "nikayah" => "required|min:16",
                "nokk" => "required|min:16",
                "kecamatan" => "required",
                "kelurahan" => "required",
                "posyandu" => "required"
            ]);
        } else {
            $validate = $request->validate([
                "nama" => "required",
                "jenis_kelamin" => "required",
                "namaibu" => "required",
                "nikibu" => "required|min:16",
                "namaayah" => "required",
                "nikayah" => "required|min:16",
                "nokk" => "required|min:16",
                "kecamatan" => "required",
                "kelurahan" => "required",
                "posyandu" => "required"
            ]);
        }

        $balita = [
            "nama" => $validate['nama'],
            "tgl_lahir" => $request->tahun . '-' . $request->bulan . '-' . $request->tgl,
            "nik" => $validate['nik'] ?? "-",
            "jenis_kelamin" => $validate['jenis_kelamin'],
            "nama_ibu" => $validate['namaibu'],
            "nik_ibu" => $validate['nikibu'],
            "nama_ayah" => $validate['namaayah'],
            "nik_ayah" => $validate['nikayah'],
            "no_kk" => $validate['nokk'],
            "kelurahan" => $validate['kelurahan'],
            "kecamatan" => $validate['kecamatan'],
            "posyandu" => $validate['posyandu'],
        ];

        // dd($balita);

        Balita::insert($balita);

        return redirect('/balita')->with('success', 'Data balita berhasil ditambahkan.');
    }

    public function edit($id = null)
    {
        if ($id) {
            $bulan = [
                ['no' => '1', 'name' => 'Januari'],
                ['no' => '2', 'name' => 'Februari'],
                ['no' => '3', 'name' => 'Maret'],
                ['no' => '4', 'name' => 'April'],
                ['no' => '5', 'name' => 'Mei'],
                ['no' => '6', 'name' => 'Juni'],
                ['no' => '7', 'name' => 'Juli'],
                ['no' => '8', 'name' => 'Agustus'],
                ['no' => '9', 'name' => 'September'],
                ['no' => '10', 'name' => 'Oktober'],
                ['no' => '11', 'name' => 'November'],
                ['no' => '12', 'name' => 'Desember']
            ];

            $kecamatan = [
                ["id" => "3375040", "name" => "PEKALONGAN UTARA"]
            ];

            $data = Balita::where('id', $id)->get();
            $posyandu = Posyandu::where('kelurahan', auth()->user()->area)->get();

            return view('admin.balitaEdit', [
                'title' => 'Edit Data Balita',
                'data' => $data[0],
                'month' => $bulan,
                'kecamatan' => $kecamatan,
                'posyandu' => $posyandu
            ]);
        }

        return redirect('/balita');
    }

    public function update(Request $request)
    {
        if ($request->nik == '-') {
            $validate = $request->validate([
                "nama" => "required",
                "jenis_kelamin" => "required",
                "nik" => "required",
                "namaibu" => "required",
                "nikibu" => "required|min:16",
                "namaayah" => "required",
                "nikayah" => "required|min:16",
                "nokk" => "required|min:16",
                "kecamatan" => "required",
                "kelurahan" => "required",
                "posyandu" => "required"
            ]);
        } else {
            $validate = $request->validate([
                "nama" => "required",
                "jenis_kelamin" => "required",
                "nik" => "required|min:16",
                "namaibu" => "required",
                "nikibu" => "required|min:16",
                "namaayah" => "required",
                "nikayah" => "required|min:16",
                "nokk" => "required|min:16",
                "kecamatan" => "required",
                "kelurahan" => "required",
                "posyandu" => "required"
            ]);
        }


        $dataUpdated = [
            "nama" => $validate['nama'],
            "tgl_lahir" => $request->tahun . '-' . $request->bulan . '-' . $request->tgl,
            "nik" => $validate['nik'],
            "jenis_kelamin" => $validate['jenis_kelamin'],
            "nama_ibu" => $validate['namaibu'],
            "nik_ibu" => $validate['nikibu'],
            "nama_ayah" => $validate['namaayah'],
            "nik_ayah" => $validate['nikayah'],
            "no_kk" => $validate['nokk'],
            "kelurahan" => $validate['kelurahan'],
            "kecamatan" => $validate['kecamatan'],
            "posyandu" => $validate['posyandu'],
        ];

        Balita::where('id', $request->id)->update($dataUpdated);
        if ($request->nik != '-') {
            Pelayanan::where('id_balita', $request->id)->update(["nik_balita" => $validate['nik']]);
        }

        return redirect('/balita')->with('success', 'Data balita berhasil diupdate.');
    }

    public function destroy($id = 0)
    {
        Balita::where('id', $id)->delete();
        Pelayanan::where('id_balita', $id)->delete();
        return back()->with('success', 'Data balita berhasil dihapus.');
    }

    public function import()
    {
        return view('admin.import', [
            'title' => 'Import Data Balita',
        ]);
    }

    public function import_excel(Request $request)
    {
        // validasi
        $request->validate([
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = rand() . $file->getClientOriginalName();
        $file->move('Dataset_Balita', $nama_file);

        Excel::import(new BalitaImport, public_path('/Dataset_Balita/' . $nama_file));

        return redirect('/balita')->with('success', 'Data Balita Berhasil Diimport!');
    }
}
