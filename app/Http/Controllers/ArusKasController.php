<?php

namespace App\Http\Controllers;

use App\Models\Buk;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function exportPdf()
    {

        $bukuUmum = Buk::user()->get();

        $kas_awal = $bukuUmum->where('transaksi', 'Saldo Awal')->first();
        $masuk = $bukuUmum->where('jenis', 'debit');
        $keluar = $bukuUmum->where('jenis', 'kredit');
        $kas_akhir = $masuk->sum('nilai') - $keluar->sum('nilai') + $kas_awal;
        $perubahan_kas = $masuk->sum('nilai') - $keluar->sum('nilai');
        $data = [
            'buku_umum' => $bukuUmum,
            'masuk' => $masuk,
            'keluar' => $keluar,
            'kas_akhir' => $kas_akhir,
            'perubahan_kas' => $perubahan_kas
        ];

        // Gunakan facade PDF
        $pdf = PDF::loadView('laporan_arus_kas.pdf', $data)->setPaper('a4', 'portrait');

        // Mengunduh PDF dengan nama "laporan.pdf"
        return $pdf->stream('laporan.pdf');
    }
}
