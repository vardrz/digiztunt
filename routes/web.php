<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BalitaController;
use App\Http\Controllers\PelayananController;
use App\Http\Controllers\StantingController;
use App\Http\Controllers\PosyanduController;
use App\Http\Controllers\KaderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Login
Route::get('/', [AuthController::class, 'index'])->middleware('guest')->name('login');
Route::post('/', [AuthController::class, 'auth']);
Route::post('/logout', [AuthController::class, 'logout']);

// User
Route::get('/home/{tahun?}/{bulan?}', [HomeController::class, 'index'])->middleware('auth');
Route::get('/user/password', [HomeController::class, 'password'])->middleware('auth');
Route::post('/user/password', [HomeController::class, 'changePass'])->middleware('auth');

// Balita
Route::get('/balita', [BalitaController::class, 'index'])->middleware('auth');  // Read
Route::get('/balita/history', [BalitaController::class, 'history'])->middleware('auth');
Route::get('/balita/find/{data}', [BalitaController::class, 'find'])->middleware('auth');
Route::get('/balita/new', [BalitaController::class, 'new'])->middleware('auth');  // Create
Route::post('/balita/new', [BalitaController::class, 'store']);
Route::get('/balita/edit/{id?}', [BalitaController::class, 'edit'])->middleware('auth');  // Update
Route::post('/balita/update', [BalitaController::class, 'update'])->middleware('auth');
Route::post('/balita/delete/{id}', [BalitaController::class, 'destroy'])->middleware('auth'); // Delete

// Pelayanan
Route::get('/pelayanan', [PelayananController::class, 'index'])->middleware('auth');
Route::post('/pelayanan', [PelayananController::class, 'store']);
Route::get('/pelayanan/find/{data}', [PelayananController::class, 'find'])->middleware('auth');

// Penimbangan
Route::get('/verifikasi', [StantingController::class, 'index'])->middleware('auth');
Route::post('/verifikasi/accept', [StantingController::class, 'verif'])->middleware('auth');
Route::post('/verifikasi/update', [StantingController::class, 'update'])->middleware('auth');
Route::get('/status/{tahun?}/{bulan?}', [StantingController::class, 'status'])->middleware('auth');
Route::get('/belum-ditimbang/{tahun?}/{bulan?}', [StantingController::class, 'belumDitimbang'])->middleware('auth');

// Posyandu
Route::get('/posyandu', [PosyanduController::class, 'index'])->middleware('auth');
Route::post('/posyandu', [PosyanduController::class, 'store'])->middleware('auth');
Route::get('/posyandu/edit/{id?}', [PosyanduController::class, 'edit'])->middleware('auth');
Route::post('/posyandu/update', [PosyanduController::class, 'update'])->middleware('auth');
Route::post('/posyandu/delete', [PosyanduController::class, 'destroy'])->middleware('auth');

// Kader
Route::get('/kader', [KaderController::class, 'index'])->middleware('auth');
Route::get('/kader/generate', [KaderController::class, 'generate'])->middleware('auth');
Route::post('/kader', [KaderController::class, 'store'])->middleware('auth');
Route::post('/kader/reset', [KaderController::class, 'resetPassword'])->middleware('auth');
Route::post('/kader/delete', [KaderController::class, 'destroy'])->middleware('auth');

// Excel
Route::get('/import', [BalitaController::class, 'import'])->middleware('auth');
Route::post('/import', [BalitaController::class, 'import_excel'])->middleware('auth');

// Rekap Laporan
Route::get('/laporan', [LaporanController::class, 'index'])->middleware('auth');
Route::get('/rekap/{tahun?}/{bulan?}', [LaporanController::class, 'rekap'])->middleware('auth');
