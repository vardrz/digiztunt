<?php

namespace App\Http\Controllers;

use App\Models\Posyandu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PosyanduController extends Controller
{
    public function index()
    {
        if (auth()->user()->area == 'KUSUMA BANGSA') {
            $kelurahan = ['PANJANG WETAN', 'PANJANG BARU', 'KANDANG PANJANG'];
        } elseif (auth()->user()->area == 'KRAPYAK') {
            $kelurahan = ['KRAPYAK', 'DEGAYU'];
        } else {
            $kelurahan = ['PADUKUHAN KRATON', 'BANDENGAN'];
        }

        $data = Posyandu::whereIn('kelurahan', $kelurahan)->orderBy('kelurahan', 'desc')->get();

        return view('admin.posyanduList', [
            'title' => 'POSYANDU PUSKESMAS ' . auth()->user()->area,
            'kelurahan' => $kelurahan,
            'posyandu' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "name" => ["required", Rule::unique('posyandus')->where(function ($query) use ($request) {
                return $query->where('name', $request->name)
                    ->where('kelurahan', $request->kelurahan);
            })],
            "kelurahan" => "required",
        ]);

        if ($validate->fails()) {
            return redirect()->back()->with('fail', '')->withErrors($validate)->withInput();
        } else {
            $data = [
                'name' => $request->name,
                'kelurahan' => $request->kelurahan
            ];

            Posyandu::insert($data);
            return redirect()->back()->with('success', 'Berhasil tambah data posyandu.');
        }
    }

    public function edit($id = null)
    {
        if ($id) {
            $data = Posyandu::where('id', $id)->get();
            if (auth()->user()->area == 'KUSUMA BANGSA') {
                $kelurahan = ['PANJANG WETAN', 'PANJANG BARU', 'KANDANG PANJANG'];
            } elseif (auth()->user()->area == 'KRAPYAK') {
                $kelurahan = ['KRAPYAK', 'DEGAYU'];
            } else {
                $kelurahan = ['PADUKUHAN KRATON', 'BANDENGAN'];
            }

            return view('admin.posyanduEdit', [
                'title' => 'Edit Data Posyandu',
                'data' => $data[0],
                'kelurahan' => $kelurahan
            ]);
        }

        return redirect('/posyandu');
    }

    public function update(Request $request)
    {
        if (strtolower($request->name)  != strtolower($request->oldName)) {
            $validate = Validator::make($request->all(), [
                "name" => ["required", Rule::unique('posyandus')->where(function ($query) use ($request) {
                    return $query->where('name', $request->name)
                        ->where('kelurahan', $request->kelurahan);
                })],
                "kelurahan" => "required",
            ]);

            if ($validate->fails()) {
                return redirect()->back()->withErrors($validate)->withInput();
            }
        }

        $data = [
            'name' => $request->name,
            'kelurahan' => $request->kelurahan
        ];

        Posyandu::where('id', $request->id)->update($data);
        return redirect('/posyandu')->with('success', 'Berhasil update data posyandu.');
    }

    public function destroy(Request $req)
    {
        Posyandu::where('id', $req->id)->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }
}
