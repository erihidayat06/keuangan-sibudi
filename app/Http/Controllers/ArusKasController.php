<?php

namespace App\Http\Controllers;

use App\Models\Buk;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ArusKasController extends Controller
{
    public function index()
    {
        $transaksis_lalu = Buk::user()->whereYear('tanggal', session('selected_year', date('Y')) - 1)->get();
        $debit_lalu = $transaksis_lalu->where('jenis', 'debit')->sum('nilai');
        $kredit_lalu = $transaksis_lalu->where('jenis', 'kredit')->sum('nilai');
        $saldo_lalu = $debit_lalu - $kredit_lalu;

        $transaksis = Buk::user()->whereYear('tanggal', session('selected_year', date('Y')))->get();
        $debit = $transaksis->where('jenis', 'debit')->sum('nilai');
        $kredit = $transaksis->where('jenis', 'kredit')->sum('nilai');
        $saldo = $debit - $kredit;
        $saldo = $saldo + $saldo_lalu;

        $bukuUmum = Buk::user()->whereYear('tanggal', session('selected_year', date('Y')))->get();

        // $kas_awal = $bukuUmum->where('transaksi', 'Saldo Awal')->first();
        $masuk = $bukuUmum->where('jenis', 'debit');
        $keluar = $bukuUmum->where('jenis', 'kredit');
        $perubahan_kas = $masuk->sum('nilai') - $keluar->sum('nilai');

        $kas_akhir = $saldo_lalu + $perubahan_kas;
        return view('laporan_arus_kas.index', [
            'saldo' => $saldo_lalu,
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
