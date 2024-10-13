<?php

namespace App\Http\Controllers;

use App\Models\Buk;
use Illuminate\Http\Request;

class ArusKasController extends Controller
{
    public function index()
    {
        $bukuUmum = Buk::user()->get();

        $kas_awal = $bukuUmum->where('transaksi', 'Saldo Awal')->first();
        $masuk = $bukuUmum->where('jenis', 'debit');
        $keluar = $bukuUmum->where('jenis', 'kredit');
        $kas_akhir = $masuk->sum('nilai') - $keluar->sum('nilai') + $kas_awal;
        $perubahan_kas = $masuk->sum('nilai') - $keluar->sum('nilai');
        return view('laporan_arus_kas.index', [
            'buku_umum' => $bukuUmum,
            'masuk' => $masuk,
            'keluar' => $keluar,
            'kas_akhir' => $kas_akhir,
            'perubahan_kas' => $perubahan_kas
        ]);
    }
}
