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
use App\Http\Controllers\CetakLaporanController;
use App\Http\Controllers\LanggananController;
use App\Http\Controllers\PenyusutanController;
use App\Http\Controllers\PersediaanController;
use App\Http\Controllers\LaporanLabaRugiController;
use App\Http\Controllers\LaporanPerubahanModalController;
use App\Http\Controllers\TahunController;

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

Route::get('/', [ProfileController::class, 'index'])->middleware('auth', 'verified')->middleware('langganan');
Route::put('/{profil:id}', [ProfileController::class, 'update'])->middleware('auth', 'verified')->middleware('langganan');

Route::resource('/modal', ModalController::class)->middleware('auth', 'verified')->middleware('langganan');
Route::get('/export-pdf/modal', [ModalController::class, 'exportPdf'])->middleware('auth', 'verified')->middleware('langganan');



Route::resource('/hutang', HutangController::class)->middleware('auth', 'verified')->middleware('langganan');
Route::get('/export-pdf/hutang', [HutangController::class, 'exportPdf'])->middleware('auth', 'verified')->middleware('langganan');

Route::resource('/aset/buk', BuksController::class)->middleware('auth', 'verified')->middleware('langganan');
Route::get('/export-pdf/buk', [BuksController::class, 'exportPdf'])->middleware('auth', 'verified')->middleware('langganan');

Route::resource('/aset/pinjaman', PinjamanController::class)->middleware('auth', 'verified')->middleware('langganan');
Route::put('/aset/pinjaman/bayar/{pinjaman:id}', [PinjamanController::class, 'bayar'])->middleware('auth', 'verified')->middleware('langganan');
Route::get('/export-pdf/pinjaman', [PinjamanController::class, 'exportPdf'])->middleware('auth', 'verified')->middleware('langganan');

Route::resource('/aset/piutang', PiutangController::class)->middleware('auth', 'verified')->middleware('langganan');
Route::put('/aset/piutang/bayar/{piutang:id}', [PiutangController::class, 'bayar'])->middleware('auth', 'verified')->middleware('langganan');
Route::get('/export-pdf/piutang', [PiutangController::class, 'exportPdf'])->middleware('auth', 'verified')->middleware('langganan');

Route::resource('/aset/persediaan', PersediaanController::class)->middleware('auth', 'verified')->middleware('langganan');
Route::put('/aset/persedian/jual/{persediaan:id}', [PersediaanController::class, 'penjualan'])->middleware('auth', 'verified')->middleware('langganan');
Route::get('/export-pdf/persediaan', [PersediaanController::class, 'exportPdf'])->middleware('auth', 'verified')->middleware('langganan');

Route::resource('/aset/bdmuk', BdmukController::class)->middleware('auth', 'verified')->middleware('langganan');
Route::put('/aset/bdmuk/pakai/{bdmuk:id}', [BdmukController::class, 'pakai'])->middleware('auth', 'verified')->middleware('langganan');
Route::get('/export-pdf/bdmuk', [BdmukController::class, 'exportPdf'])->middleware('auth', 'verified')->middleware('langganan');

Route::resource('/aset/investasi', InvestasiController::class)->middleware('auth', 'verified')->middleware('langganan');
Route::put('/aset/investasi/pakai/{investasi:id}', [InvestasiController::class, 'pakai'])->middleware('auth', 'verified')->middleware('langganan');
Route::get('/export-pdf/investasi', [InvestasiController::class, 'exportPdf'])->middleware('auth', 'verified')->middleware('langganan');

Route::resource('/aset/bangunan', BangunanController::class)->middleware('auth', 'verified')->middleware('langganan');
Route::put('/aset/bangunan/pakai/{bangunan:id}', [BangunanController::class, 'pakai'])->middleware('auth', 'verified')->middleware('langganan');
Route::get('/export-pdf/bangunan', [BangunanController::class, 'exportPdf'])->middleware('auth', 'verified')->middleware('langganan');

Route::resource('/aset/aktivalain', AktivalainController::class)->middleware('auth', 'verified')->middleware('langganan');
Route::put('/aset/aktivalain/pakai/{aktivalain:id}', [AktivalainController::class, 'pakai'])->middleware('auth', 'verified')->middleware('langganan');
Route::get('/export-pdf/aktivalain', [AktivalainController::class, 'exportPdf'])->middleware('auth', 'verified')->middleware('langganan');

Route::put('hutang/bayar/{hutang:id}', [HutangController::class, 'bayar'])->middleware('auth', 'verified')->middleware('langganan');


Route::resource('/dithn', DithnController::class)->middleware('auth', 'verified')->middleware('langganan');
Route::get('/export-pdf/dithn', [DithnController::class, 'exportPdf'])->middleware('auth', 'verified')->middleware('langganan');
Route::get('/rincian-laba-rugi', [LabaRugiController::class, 'index'])->middleware('auth', 'verified')->middleware('langganan');
Route::get('/export-pdf/rincian-laba-rugi', [LabaRugiController::class, 'exportPdf'])->middleware('auth', 'verified')->middleware('langganan');
Route::get('/penyusutan', [PenyusutanController::class, 'index'])->middleware('auth', 'verified')->middleware('langganan');
Route::get('/bagi-hasil', [BagiHasilController::class, 'index'])->middleware('auth', 'verified')->middleware('langganan');
Route::put('/bagi-hasil/{dithn:id}', [BagiHasilController::class, 'update'])->middleware('auth', 'verified')->middleware('langganan');

Route::get('/laporan-keuangan/neraca', [NeracaController::class, 'index'])->middleware('auth', 'verified')->middleware('langganan');
Route::get('/export-pdf/neraca', [NeracaController::class, 'exportPdf'])->middleware('auth', 'verified')->middleware('langganan');
Route::get('/laporan-keuangan/laporan-laba-rugi', [LaporanLabaRugiController::class, 'index'])->middleware('auth', 'verified')->middleware('langganan');
Route::get('/export-pdf/laporan-laba-rugi', [LaporanLabaRugiController::class, 'exportPdf'])->middleware('auth', 'verified')->middleware('langganan');
Route::get('/laporan-keuangan/laporan-arus-kas', [ArusKasController::class, 'index'])->middleware('auth', 'verified')->middleware('langganan');
Route::get('/export-pdf/laporan-arus-kas', [ArusKasController::class, 'exportPdf'])->middleware('auth', 'verified')->middleware('langganan');

Route::get('/laporan-keuangan/laporan-perubahan-modal', [LaporanPerubahanModalController::class, 'index'])->middleware('auth', 'verified')->middleware('langganan');
Route::post('/laporan-keuangan/laporan-perubahan-modal', [LaporanPerubahanModalController::class, 'store'])->middleware('auth', 'verified')->middleware('langganan');
Route::get('/export-pdf/laporan-perubahan-modal', [LaporanPerubahanModalController::class, 'exportPdf'])->middleware('auth', 'verified')->middleware('langganan');
Route::put('/laporan-keuangan/laporan-perubahan-modal/{ekuit:id}', [LaporanPerubahanModalController::class, 'update'])->middleware('auth', 'verified')->middleware('langganan');
Route::get('/laporan-keuangan/laporan-perubahan-modal/ditahan/{ekuit:id}', [LaporanPerubahanModalController::class, 'ditahan'])->middleware('auth', 'verified')->middleware('langganan');

Route::get('/export-pdf/cetak-laporan', [CetakLaporanController::class, 'exportPdf'])->middleware('auth', 'verified')->middleware('langganan');

Route::post('/set-tahun', [TahunController::class, 'setYear'])->name('setYear');
Route::get('/langganan', [LanggananController::class, 'index'])->middleware(['auth', 'verified']);
Route::post('/langganan', [LanggananController::class, 'createTransaction'])->middleware(['auth', 'verified']);
Route::post('/langganan/berhasil', [LanggananController::class, 'langgananSuccess'])->middleware(['auth', 'verified']);

Auth::routes(['verify' => true]);
