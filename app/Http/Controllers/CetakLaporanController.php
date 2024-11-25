<?php

namespace App\Http\Controllers;

use App\Models\Buk;
use App\Models\Ekuit;
use App\Models\Modal;
use App\Models\Unit;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CetakLaporanController extends Controller
{
    public function exportPdf()
    {
        $selectedYear = session('selected_year', date('Y'));
        $startYear = 2000;

        // Saldo awal
        $saldo_lalu = 0;

        // Hitung saldo tahun-tahun sebelumnya
        for ($year = $selectedYear - 1; $year >= $startYear; $year--) {
            $transaksis_lalu = Buk::user()->whereYear('tanggal', $year)->get();
            $debit_lalu = $transaksis_lalu->where('jenis', 'debit')->sum('nilai');
            $kredit_lalu = $transaksis_lalu->where('jenis', 'kredit')->sum('nilai');
            $saldo_lalu += $debit_lalu - $kredit_lalu;

            if ($transaksis_lalu->isEmpty()) break;
        }

        // Hitung transaksi tahun yang dipilih
        $bukuUmum = Buk::user()->whereYear('tanggal', $selectedYear)->get();
        $debit = $bukuUmum->where('jenis', 'debit')->sum('nilai');
        $kredit = $bukuUmum->where('jenis', 'kredit')->sum('nilai');
        $saldo = $saldo_lalu + ($debit - $kredit);

        // Hitung kas masuk, keluar, dan perubahan kas
        $masuk = $bukuUmum->where('jenis', 'debit');
        $keluar = $bukuUmum->where('jenis', 'kredit');
        $perubahan_kas = $masuk->sum('nilai') - $keluar->sum('nilai');
        $kas_akhir = $saldo_lalu + $perubahan_kas;

        // Data neraca dan laporan laba rugi
        $neraca = neraca();
        $labaRugi = labaRugi($selectedYear);

        // Modal dan ekuitas
        $modalDesa = Modal::user()->sum('mdl_desa');
        $modalMasyarakat = Modal::user()->sum('mdl_masyarakat');
        $ekuitas = Ekuit::user()->first();
        $nmDesa = auth()->user()->profil->desa;

        // Data yang akan dikirim ke view PDF
        $data = [
            'tahun' => $selectedYear,
            'saldo_lalu' => $saldo_lalu,
            'saldo' => $saldo_lalu,
            'kas_akhir' => $kas_akhir,
            'perubahan_kas' => $perubahan_kas,
            'pendapatan' => $labaRugi['pendapatan'],
            'pendapatanBulan' => $labaRugi['pendapatanBulan'],
            'pendapatanTahun' => $labaRugi['pendapatanTahun'],
            'totalBiaya' => $labaRugi['totalBiaya'],
            'akumulasiBiaya' => $labaRugi['akumulasiBiaya'],
            'labaRugi' => $labaRugi['labaRugi'],
            'totalLabaRugi' => $labaRugi['totalLabaRugi'],
            'akumulasi_penyusutan' => $labaRugi['akumulasi_penyusutan'],
            'piutang' => $neraca['piutang'],
            'saldo_pinjam' => $neraca['saldo_pinjam'],
            'persediaan_dagang' => $neraca['persediaan_dagang'],
            'bayar_dimuka' => $neraca['bayar_dimuka'],
            'investasi' => $neraca['investasi'],
            'bangunan' => $neraca['bangunan'],
            'aktiva_lain' => $neraca['aktiva_lain'],
            'total_aktiva' => $neraca['total_aktiva'],
            'kas' => $neraca['kas'],
            'hutang' => $neraca['hutang'],
            'modal_desa' => $modalDesa,
            'modal_masyarakat' => $modalMasyarakat,
            'modal_bersama' => $neraca['modal_bersama'],
            'ditahan' => $neraca['ditahan'],
            'laba_rugi_berjalan' => $labaRugi['totalLabaRugi'],
            'passiva' => $neraca['passiva'],
            'ekuitas' => $ekuitas,
            'laba_berjalan' => labaRugi($ekuitas->tahun)['totalLabaRugi'],
            'buku_umum' => $bukuUmum,
            'masuk' => $masuk,
            'keluar' => $keluar,
            'units' => Unit::user()->get(),
            'nm_desa' => $nmDesa,
        ];

        // Generate PDF
        $pdf = PDF::loadView('laporan_keuangan.pdf', $data)->setPaper([0, 0, 595.276, 935.433], 'portrait');

        // Stream atau unduh PDF
        return $pdf->stream('laporan_keuangan.pdf');
    }
}
