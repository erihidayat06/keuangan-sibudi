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
        $bukuUmum = Buk::user()->get();
        $units = Unit::user()->get();

        $kas_awal = $bukuUmum->where('transaksi', 'Saldo Awal')->first();
        $masuk = $bukuUmum->where('jenis', 'debit');
        $keluar = $bukuUmum->where('jenis', 'kredit');
        $kas_akhir = $masuk->sum('nilai') - $keluar->sum('nilai') + $kas_awal;
        $perubahan_kas = $masuk->sum('nilai') - $keluar->sum('nilai');
        $ekuitas = Ekuit::user()->get()->first();


        $modal_desa = Modal::user()->get()->sum('mdl_desa');
        $modal_masyarakat = Modal::user()->get()->sum('mdl_masyarakat');
        $neraca =  neraca();
        $labaRugi = labaRugi(session('selected_year', date('Y')));
        $data = [
            'pendapatan' => $labaRugi['pendapatan'],
            'pendapatanBulan' => $labaRugi['pendapatanBulan'],
            'pendapatanTahun' => $labaRugi['pendapatanTahun'],
            'tahun' => $labaRugi['tahun'],
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
            'modal_desa' => $neraca['modal_desa'],
            'modal_masyarakat' => $neraca['modal_masyarakat'],
            'modal_bersama' => $neraca['modal_bersama'],
            'ditahan' => $neraca['ditahan'],
            'laba_rugi_berjalan' => labaRugi(session('selected_year', date('Y')))['totalLabaRugi'],
            'passiva' => $neraca['passiva'],
            'modal_desa' => $modal_desa,
            'modal_masyarakat' => $modal_masyarakat,
            'ekuitas' => $ekuitas,
            'laba_berjalan' => labaRugi($ekuitas->tahun)['totalLabaRugi'],
            'buku_umum' => $bukuUmum,
            'masuk' => $masuk,
            'keluar' => $keluar,
            'kas_akhir' => $kas_akhir,
            'perubahan_kas' => $perubahan_kas,
            'units' => $units
        ];

        // Gunakan facade PDF
        $pdf = PDF::loadView('laporan_keuangan.pdf', $data)->setPaper('f4', 'portrait');;

        // Mengunduh PDF dengan nama "laporan.pdf"
        return $pdf->stream('laporan.pdf');
    }
}
