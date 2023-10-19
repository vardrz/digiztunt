<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        if (session()->get('level') == 'petugas') {
            return view('petugas.home', ['title' => 'Home']);
        } elseif (session()->get('level') == 'admin') {
            return view('admin.home', ['title' => 'Home']);
        } elseif (session()->get('level') == 'pimpinan') {
            return view('pimpinan.home', ['title' => 'Home']);
        }
    }
}
