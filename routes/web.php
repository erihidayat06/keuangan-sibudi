<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDataUserController;
use App\Http\Controllers\AdminLanggananController;
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
use App\Http\Controllers\GantiPasswordController;
use App\Http\Controllers\LanggananController;
use App\Http\Controllers\PenyusutanController;
use App\Http\Controllers\PersediaanController;
use App\Http\Controllers\LaporanLabaRugiController;
use App\Http\Controllers\LaporanPerubahanModalController;
use App\Http\Controllers\RekonsiliasiController;
use App\Http\Controllers\TahunController;
use App\Http\Controllers\UnitController;
use App\Mail\GantiPasswordEmail;
use App\Models\Rekonsiliasi;

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

Route::get('/', [ProfileController::class, 'index'])->middleware('auth', 'langganan', 'bumdes');
Route::put('/{profil:id}', [ProfileController::class, 'update'])->middleware('auth', 'langganan', 'bumdes');

// Modal
Route::resource('/modal', ModalController::class)->middleware('auth', 'langganan', 'bumdes');
Route::get('/export-pdf/modal', [ModalController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');


// hutang
Route::resource('/hutang', HutangController::class)->middleware('auth', 'langganan', 'bumdes');
Route::get('/export-pdf/hutang', [HutangController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');
Route::put('hutang/bayar/{hutang:id}', [HutangController::class, 'bayar'])->middleware('auth', 'langganan', 'bumdes');


// Buku Kas
Route::resource('/aset/buk', BuksController::class)->middleware('auth', 'langganan', 'bumdes');
Route::get('/export-pdf/buk', [BuksController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');

// Pinjaman
Route::resource('/aset/pinjaman', PinjamanController::class)->middleware('auth', 'langganan', 'bumdes');
Route::put('/aset/pinjaman/bayar/{pinjaman:id}', [PinjamanController::class, 'bayar'])->middleware('auth', 'langganan', 'bumdes');
Route::get('/export-pdf/pinjaman', [PinjamanController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');
Route::post('/aset/pinjaman/unit/', [PinjamanController::class, 'storeUnit'])->middleware('auth', 'langganan', 'bumdes');


// Piutang
Route::resource('/aset/piutang', PiutangController::class)->middleware('auth', 'langganan', 'bumdes');
Route::put('/aset/piutang/bayar/{piutang:id}', [PiutangController::class, 'bayar'])->middleware('auth', 'langganan', 'bumdes');
Route::get('/export-pdf/piutang', [PiutangController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');

// Persedian
Route::resource('/aset/persediaan', PersediaanController::class)->middleware('auth', 'langganan', 'bumdes');
Route::put('/aset/persedian/jual/{persediaan:id}', [PersediaanController::class, 'penjualan'])->middleware('auth', 'langganan', 'bumdes');
Route::get('/export-pdf/persediaan', [PersediaanController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');
Route::post('/aset/persediaan/unit/tambah', [PersediaanController::class, 'storeUnit'])->middleware('auth', 'langganan', 'bumdes');

// Bayar dimuka
Route::resource('/aset/bdmuk', BdmukController::class)->middleware('auth', 'langganan', 'bumdes');
Route::put('/aset/bdmuk/pakai/{bdmuk:id}', [BdmukController::class, 'pakai'])->middleware('auth', 'langganan', 'bumdes');
Route::get('/export-pdf/bdmuk', [BdmukController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');

// Inventari
Route::resource('/aset/investasi', InvestasiController::class)->middleware('auth', 'langganan', 'bumdes');
Route::put('/aset/investasi/pakai/{investasi:id}', [InvestasiController::class, 'pakai'])->middleware('auth', 'langganan', 'bumdes');
Route::get('/export-pdf/investasi', [InvestasiController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');

// Bangunan
Route::resource('/aset/bangunan', BangunanController::class)->middleware('auth', 'langganan', 'bumdes');
Route::put('/aset/bangunan/pakai/{bangunan:id}', [BangunanController::class, 'pakai'])->middleware('auth', 'langganan', 'bumdes');
Route::get('/export-pdf/bangunan', [BangunanController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');

// Aktiva lain
Route::resource('/aset/aktivalain', AktivalainController::class)->middleware('auth', 'langganan', 'bumdes');
Route::put('/aset/aktivalain/pakai/{aktivalain:id}', [AktivalainController::class, 'pakai'])->middleware('auth', 'langganan', 'bumdes');
Route::get('/export-pdf/aktivalain', [AktivalainController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');

// Unit
Route::resource('/unit', UnitController::class)->middleware('auth', 'langganan', 'bumdes');

// Ditahan
Route::resource('/dithn', DithnController::class)->middleware('auth', 'langganan', 'bumdes');
Route::get('/export-pdf/dithn', [DithnController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');

// Rincian laba rugi
Route::get('/rincian-laba-rugi', [LabaRugiController::class, 'index'])->middleware('auth', 'langganan', 'bumdes');
Route::get('/export-pdf/rincian-laba-rugi', [LabaRugiController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');

//penyusutan
Route::get('/penyusutan', [PenyusutanController::class, 'index'])->middleware('auth', 'langganan', 'bumdes');

// Route::get('/bagi-hasil', [BagiHasilController::class, 'index'])->middleware('auth', 'langganan', 'bumdes');
// Route::put('/bagi-hasil/{dithn:id}', [BagiHasilController::class, 'update'])->middleware('auth', 'langganan', 'bumdes');

// Rekonsiliasi
Route::resource('/rekonsiliasi', RekonsiliasiController::class)->middleware('auth', 'langganan', 'bumdes');
Route::post('/rekonsiliasi/update', [RekonsiliasiController::class, 'updateJumlah'])->name('rekonsiliasi.update');
Route::get('/export-pdf/rekonsiliasi', [RekonsiliasiController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');


// Laporan keuangan neraca
Route::get('/laporan-keuangan/neraca', [NeracaController::class, 'index'])->middleware('auth', 'langganan', 'bumdes');
Route::get('/export-pdf/neraca', [NeracaController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');

// Laporan laba rugi
Route::get('/laporan-keuangan/laporan-laba-rugi', [LaporanLabaRugiController::class, 'index'])->middleware('auth', 'langganan', 'bumdes');
Route::get('/export-pdf/laporan-laba-rugi', [LaporanLabaRugiController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');

// laporan arus kas
Route::get('/laporan-keuangan/laporan-arus-kas', [ArusKasController::class, 'index'])->middleware('auth', 'langganan', 'bumdes');
Route::get('/export-pdf/laporan-arus-kas', [ArusKasController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');

// Laporan perubahan modal
Route::get('/laporan-keuangan/laporan-perubahan-modal', [LaporanPerubahanModalController::class, 'index'])->middleware('auth', 'langganan', 'bumdes');
Route::post('/laporan-keuangan/laporan-perubahan-modal', [LaporanPerubahanModalController::class, 'store'])->middleware('auth', 'langganan', 'bumdes');
Route::get('/export-pdf/laporan-perubahan-modal', [LaporanPerubahanModalController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');
Route::put('/laporan-keuangan/laporan-perubahan-modal/{ekuit:id}', [LaporanPerubahanModalController::class, 'update'])->middleware('auth', 'langganan', 'bumdes');
Route::get('/laporan-keuangan/laporan-perubahan-modal/ditahan/{ekuit:id}', [LaporanPerubahanModalController::class, 'ditahan'])->middleware('auth', 'langganan', 'bumdes');

Route::get('/export-pdf/cetak-laporan', [CetakLaporanController::class, 'exportPdf'])->middleware('auth', 'langganan', 'bumdes');

// Langganan
Route::post('/set-tahun', [TahunController::class, 'setYear'])->name('setYear');
Route::get('/langganan', [LanggananController::class, 'index'])->middleware(['auth', 'bumdes']);
Route::post('/langganan', [LanggananController::class, 'createTransaction'])->middleware(['auth', 'bumdes']);
Route::post('/langganan/berhasil', [LanggananController::class, 'langgananSuccess'])->middleware(['auth', 'bumdes']);


// Admin
Route::get('/admin', [AdminController::class, 'index'])->middleware('admin');
Route::get('/admin/data-user', [AdminDataUserController::class, 'index'])->middleware('admin');
Route::get('/admin/data-user/create', [AdminDataUserController::class, 'create'])->middleware('admin');
Route::post('/admin/data-user/', [AdminDataUserController::class, 'store'])->middleware('admin');
Route::put('/admin/data-user/{user:id}', [AdminDataUserController::class, 'ubahPassword'])->middleware('admin');
Route::resource('/admin/langganan', AdminLanggananController::class)->middleware('admin');

// Email
Route::post('/kirim-email', [GantiPasswordController::class, 'kirimEmail'])->name('kirim-email')->middleware('guest');
Route::get('/ganti-password', [GantiPasswordController::class, 'index'])->name('kirim-email')->middleware('guest');

// Auth
Auth::routes();
