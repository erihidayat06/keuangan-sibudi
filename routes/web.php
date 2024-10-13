<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BuksController;
use App\Http\Controllers\BdmukController;
use App\Http\Controllers\DithnController;
use App\Http\Controllers\ModalController;
use App\Http\Controllers\HutangController;
use App\Http\Controllers\NeracaController;
use App\Http\Controllers\ArusKasController;
use App\Http\Controllers\PiutangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BangunanController;
use App\Http\Controllers\LabaRugiController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\BagiHasilController;
use App\Http\Controllers\InvestasiController;
use App\Http\Controllers\AktivalainController;
use App\Http\Controllers\PenyusutanController;
use App\Http\Controllers\PersediaanController;
use App\Http\Controllers\LaporanLabaRugiController;
use App\Http\Controllers\LaporanPerubahanModalController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [ProfileController::class, 'index'])->middleware('auth');
Route::put('/{profil:id}', [ProfileController::class, 'update'])->middleware('auth');

Route::resource('/modal', ModalController::class);
Route::resource('/hutang', HutangController::class);
Route::resource('/aset/buk', BuksController::class);

Route::resource('/aset/pinjaman', PinjamanController::class);
Route::put('/aset/pinjaman/bayar/{pinjaman:id}', [PinjamanController::class, 'bayar']);

Route::resource('/aset/piutang', PiutangController::class);
Route::put('/aset/piutang/bayar/{piutang:id}', [PiutangController::class, 'bayar']);

Route::resource('/aset/persediaan', PersediaanController::class);
Route::put('/aset/persedian/jual/{persediaan:id}', [PersediaanController::class, 'penjualan']);

Route::resource('/aset/bdmuk', BdmukController::class);
Route::put('/aset/bdmuk/pakai/{bdmuk:id}', [BdmukController::class, 'pakai']);

Route::resource('/aset/investasi', InvestasiController::class);
Route::put('/aset/investasi/pakai/{investasi:id}', [InvestasiController::class, 'pakai']);

Route::resource('/aset/bangunan', BangunanController::class);
Route::put('/aset/bangunan/pakai/{bangunan:id}', [BangunanController::class, 'pakai']);

Route::resource('/aset/aktivalain', AktivalainController::class);
Route::put('/aset/aktivalain/pakai/{aktivalain:id}', [AktivalainController::class, 'pakai']);

Route::put('hutang/bayar/{hutang:id}', [HutangController::class, 'bayar']);


Route::resource('/dithn', DithnController::class);
Route::get('/rincian-laba-rugi', [LabaRugiController::class, 'index']);
Route::get('/penyusutan', [PenyusutanController::class, 'index']);
Route::get('/bagi-hasil', [BagiHasilController::class, 'index']);
Route::put('/bagi-hasil/{dithn:id}', [BagiHasilController::class, 'update']);

Route::get('/laporan-keuangan/neraca', [NeracaController::class, 'index']);
Route::get('/laporan-keuangan/laporan-laba-rugi', [LaporanLabaRugiController::class, 'index']);
Route::get('/laporan-keuangan/laporan-arus-kas', [ArusKasController::class, 'index']);
Route::get('/laporan-keuangan/laporan-perubahan-modal', [LaporanPerubahanModalController::class, 'index']);


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
