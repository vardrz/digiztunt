<?php

namespace App\Http\Controllers;

use App\Models\Pelayanan;
use Illuminate\Http\Request;

class PelayananController extends Controller
{
    public function index()
    {
        $bulan = [
            [
                'no' => '1',
                'name' => 'Januari'
            ],
            [
                'no' => '2',
                'name' => 'Februari'
            ],
            [
                'no' => '3',
                'name' => 'Maret'
            ],
            [
                'no' => '4',
                'name' => 'April'
            ],
            [
                'no' => '5',
                'name' => 'Mei'
            ],
            [
                'no' => '6',
                'name' => 'Juni'
            ],
            [
                'no' => '7',
                'name' => 'Juli'
            ],
            [
                'no' => '8',
                'name' => 'Agustus'
            ],
            [
                'no' => '9',
                'name' => 'September'
            ],
            [
                'no' => '10',
                'name' => 'Oktober'
            ],
            [
                'no' => '11',
                'name' => 'November'
            ],
            [
                'no' => '12',
                'name' => 'Desember'
            ]
        ];

        return view('petugas.pelayanan', [
            'title' => 'Pelayanan',
            'month' => $bulan
        ]);
    }

    public function find($data = null)
    {
        // $return = Pelayanan::where('nik_balita', $data)->orderBy('tgl_pelayanan', 'DESC')->get();
        $return = Pelayanan::where('id_balita', $data)->orderBy('tgl_pelayanan', 'DESC')->get();
        return $return;
    }


    public function store(Request $request)
    {
        $validate = $request->validate([
            "id_balita" => "required",
            "nik_balita" => "required",
            "tb" => "required",
            "bb" => "required",
            "lingkar_kepala" => "required",
            "usia" => "required"
        ]);

        $data = [
            "id_balita" => $validate['id_balita'],
            "nik_balita" => $validate['nik_balita'],
            "tgl_pelayanan" => $request->tahun . '-' . $request->bulan . '-' . $request->tgl,
            "tb" => doubleval(str_replace(',', '.', $validate['tb'])),
            "bb" => doubleval(str_replace(',', '.', $validate['bb'])),
            "lingkar_kepala" => doubleval(str_replace(',', '.', $validate['lingkar_kepala'])),
            "usia" => intval($validate['usia'])
        ];

        Pelayanan::updateOrCreate(
            ['id_balita' => $data['id_balita'], 'tgl_pelayanan' => $data['tgl_pelayanan']],
            ['nik_balita' => $data['nik_balita'], 'tb' => $data['tb'], 'bb' => $data['bb'], 'lingkar_kepala' => $data['lingkar_kepala'], 'usia' => $data['usia']]
        );

        return redirect('/pelayanan')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(Pelayanan $pelayanan)
    {
        //
    }

    public function update(Request $request, Pelayanan $pelayanan)
    {
        //
    }

    public function destroy(Pelayanan $pelayanan)
    {
        //
    }
}
