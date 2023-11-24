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
            "email" => "required|email|unique:users",
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
}
