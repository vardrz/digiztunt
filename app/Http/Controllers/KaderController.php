<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Posyandu;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class KaderController extends Controller
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

        $data = User::where('level', 'petugas')->whereHas('posyandu', function ($query) use ($kelurahan) {
            $query->whereIn('kelurahan', $kelurahan);
        })->orderBy('area')->get();

        $posyandu = Posyandu::whereIn('kelurahan', $kelurahan)->orderBy('kelurahan', 'desc')->get();

        return view('admin.kaderList', [
            'title' => 'Akun Kader Posyandu',
            'posyandu' => $posyandu,
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required|unique:users",
            "posyandu" => "required",
        ]);

        if ($validate->fails()) {
            return redirect()->back()->with('fail', '')->withErrors($validate)->withInput();
        } else {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('123456'),
                'level' => 'petugas',
                'area' => $request->posyandu
            ];

            User::insert($data);
            return redirect()->back()->with('success', 'Berhasil buat akun kader.');
        }
    }

    public function resetPassword(Request $req)
    {
        User::where('id', $req->id)->update(['password' => Hash::make('123456')]);
        return back()->with('success', 'Password berhasil direset.');
    }

    public function destroy(Request $req)
    {
        User::where('id', $req->id)->delete();
        return back()->with('success', 'Akun kader dihapus.');
    }

    public function generate()
    {
        $added = 0;
        $data = [];

        $pos = Posyandu::all();
        foreach ($pos as $p) {
            $push = [
                'name' => 'Kader ' . $p->name,
                'email' => str_replace(' ', '', strtolower($p->kelurahan)) . '-' . str_replace(' ', '', str_replace([' iii', ' ii', ' i', '1ndah', '1bu'], ['3', '2', '1', 'indah', 'ibu'], strtolower($p->name))),
                'password' => Hash::make('123456'),
                'level' => 'petugas',
                'area' => $p->id,
            ];
            array_push($data, $push);
        }

        foreach ($data as $d) {
            User::insert($d);
            $added++;
        }

        return $added . " Data Kader Berhasil Ditambahkan.";
    }

    public function update(Request $request)
    {
        $kader = User::findOrFail($request->id_kader);

        $rules = [
            'edit_name' => 'required|string',
            'edit_email' => 'required|string|unique:users,email,' . $kader->id,
        ];

        $messages = [
            'edit_name.required' => 'Nama kader tidak boleh kosong.',
            'edit_email.required' => 'Username kader tidak boleh kosong.',
            'edit_email.unique' => 'Username sudah digunakan oleh akun lain.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'updateKader') // Memberikan nama khusus untuk error bag
                ->withInput()
                ->with('error_modal_id', $request->id_kader); // Kirim ID kader untuk membuka modal yang benar jika ada error
        }

        $kader->name = $request->edit_name;
        $kader->email = $request->edit_email;
        $kader->save();

        return redirect()->back()->with('success', 'Data kader berhasil diperbarui.');
    }
}
