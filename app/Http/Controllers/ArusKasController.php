<?php

namespace App\Http\Controllers;

use App\Models\Buk;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ArusKasController extends Controller
{
    public function index()
    {
        $selectedYear = session('selected_year', date('Y')); // Tahun yang dipilih
        $startYear = 2000; // Tahun awal untuk memulai pencarian (sesuaikan sesuai kebutuhan)

        $saldo_lalu = 0; // Inisialisasi saldo awal

        // Loop untuk menghitung saldo hingga akhir tahun sebelumnya
        for ($year = $selectedYear - 1; $year >= $startYear; $year--) {
            $transaksis_lalu = Buk::user()->whereYear('tanggal', $year)->get();
            $debit_lalu = $transaksis_lalu->where('jenis', 'debit')->sum('nilai');
            $kredit_lalu = $transaksis_lalu->where('jenis', 'kredit')->sum('nilai');

            // Tambahkan ke saldo lalu
            $saldo_lalu += $debit_lalu - $kredit_lalu;

            // Hentikan perulangan jika tidak ada transaksi pada tahun tersebut
            if ($transaksis_lalu->isEmpty()) {
                break;
            }
        }

        // Ambil data buku umum hanya untuk tahun yang dipilih
        $bukuUmum = Buk::user()->whereYear('tanggal', $selectedYear)->get();

        // Hitung transaksi tahun yang dipilih
        $debit = $bukuUmum->where('jenis', 'debit')->sum('nilai');
        $kredit = $bukuUmum->where('jenis', 'kredit')->sum('nilai');

        // Saldo tahun ini berdasarkan transaksi tahun ini + saldo sebelumnya
        $saldo_tahun_ini = $saldo_lalu + ($debit - $kredit);

        // Perhitungan kas masuk, kas keluar, dan kas akhir
        $masuk = $bukuUmum->where('jenis', 'debit');
        $keluar = $bukuUmum->where('jenis', 'kredit');
        $perubahan_kas = $masuk->sum('nilai') - $keluar->sum('nilai');
        $kas_akhir = $saldo_lalu + $perubahan_kas;

        // Return data ke view
        return view('laporan_arus_kas.index', [
            'saldo' => $saldo_lalu, // Saldo akhir tahun sebelumnya
            'saldo_tahun_ini' => $saldo_tahun_ini, // Saldo kumulatif akhir
            'buku_umum' => $bukuUmum,
            'masuk' => $masuk,
            'keluar' => $keluar,
            'kas_akhir' => $kas_akhir,
            'perubahan_kas' => $perubahan_kas
        ]);
    }



    public function exportPdf()
    {
        $selectedYear = session('selected_year', date('Y')); // Tahun yang dipilih
        $startYear = 2000; // Tahun awal untuk memulai pencarian (sesuaikan sesuai kebutuhan)

        $saldo_lalu = 0; // Inisialisasi saldo awal

        // Loop untuk menghitung saldo hingga akhir tahun sebelumnya
        for ($year = $selectedYear - 1; $year >= $startYear; $year--) {
            $transaksis_lalu = Buk::user()->whereYear('tanggal', $year)->get();
            $debit_lalu = $transaksis_lalu->where('jenis', 'debit')->sum('nilai');
            $kredit_lalu = $transaksis_lalu->where('jenis', 'kredit')->sum('nilai');

            // Tambahkan ke saldo lalu
            $saldo_lalu += $debit_lalu - $kredit_lalu;

            // Hentikan perulangan jika tidak ada transaksi pada tahun tersebut
            if ($transaksis_lalu->isEmpty()) {
                break;
            }
        }

        // Ambil data buku umum hanya untuk tahun yang dipilih
        $bukuUmum = Buk::user()->whereYear('tanggal', $selectedYear)->get();

        // Hitung transaksi tahun yang dipilih
        $debit = $bukuUmum->where('jenis', 'debit')->sum('nilai');
        $kredit = $bukuUmum->where('jenis', 'kredit')->sum('nilai');

        // Saldo tahun ini berdasarkan transaksi tahun ini + saldo sebelumnya
        $saldo_tahun_ini = $saldo_lalu + ($debit - $kredit);

        // Perhitungan kas masuk, kas keluar, dan kas akhir
        $masuk = $bukuUmum->where('jenis', 'debit');
        $keluar = $bukuUmum->where('jenis', 'kredit');
        $perubahan_kas = $masuk->sum('nilai') - $keluar->sum('nilai');
        $kas_akhir = $saldo_lalu + $perubahan_kas;
        // Data yang akan diteruskan ke PDF
        $data = [
            'saldo' => $saldo_lalu, // Saldo kumulatif akhir
            'buku_umum' => $bukuUmum,
            'masuk' => $masuk,
            'keluar' => $keluar,
            'kas_akhir' => $kas_akhir,
            'perubahan_kas' => $perubahan_kas,
        ];

        // Generate PDF menggunakan Laravel DomPDF
        $pdf = PDF::loadView('laporan_arus_kas.pdf', $data)->setPaper([0, 0, 595.276, 935.433], 'portrait');

        // Return PDF sebagai file unduhan
        return $pdf->stream('laporan.pdf');
    }
}
