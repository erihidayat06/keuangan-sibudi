<?php

use App\Models\Buk;
use App\Models\Bdmuk;
use App\Models\Dithn;
use App\Models\Modal;
use App\Models\Hutang;
use App\Models\Piutang;
use App\Models\Bangunan;
use App\Models\Pinjaman;
use App\Models\Investasi;
use App\Models\Aktivalain;
use App\Models\Persediaan;

if (!function_exists('neraca')) {
    function neraca()
    {
        // Sisa Piutang
        $piutangs = Piutang::user()->whereYear('created_at', '<=', session('selected_year', date('Y')))->get();
        $sisa_putang = 0;
        foreach ($piutangs as $piutang) {
            $sisa_putang = $sisa_putang + ($piutang->nilai - $piutang->pembayaran);
        }

        // Sldo Pinjam
        $pinjamans = Pinjaman::user()->whereYear('created_at', '<=', session('selected_year', date('Y')))->get();
        $saldo_pinjam = $pinjamans->sum('alokasi') - $pinjamans->sum('realisasi');

        // Persediaan dagang
        $barangs = Persediaan::user()->whereYear('created_at', '<=', session('selected_year', date('Y')))->get();
        $persediaan_dagang = 0;
        foreach ($barangs as $barang) {
            $jumlah_akhir = $barang->jml_awl - ($barang->masuk - $barang->keluar);
            $nilai_akhir = $jumlah_akhir * $barang->hpp;
            $persediaan_dagang = $persediaan_dagang + $nilai_akhir;
        }

        // Bayar di muka
        $asets = Bdmuk::user()->whereYear('created_at', '<=', session('selected_year', date('Y')))->get();

        $bayar_dimuka = 0;
        foreach ($asets as $aset) {
            if ($aset->wkt_ekonomis != 0) {
                $penyusutan = $aset->nilai / $aset->wkt_ekonomis;
            } else {
                $penyusutan = 0;
            }
            $saat_ini = $aset->nilai - masaPakai($aset->created_at, $aset->wkt_ekonomis)['masa_pakai'] * $penyusutan;
            $bayar_dimuka = $bayar_dimuka + $saat_ini;
        }

        // Investasi
        $asets = Investasi::user()->whereYear('tgl_beli', '<=', session('selected_year', date('Y')))->get();
        $investasi = 0;
        foreach ($asets as $aset) {


            $penyusutan = $aset->nilai / $aset->wkt_ekonomis  * $aset->jumlah;
            $saat_ini =
                $aset->jumlah * $aset->nilai - masaPakai($aset->tgl_beli, $aset->wkt_ekonomis)['masa_pakai'] * $penyusutan;
            $investasi = $investasi + $saat_ini;
        }


        // bangunan
        $asets = Bangunan::user()->whereYear('created_at', '<=', session('selected_year', date('Y')))->get();

        $bangunan = 0;
        foreach ($asets as $aset) {
            if ($aset->wkt_ekonomis != 0) {
                $penyusutan = $aset->nilai / $aset->wkt_ekonomis;
            } else {
                $penyusutan = 0;
            }
            $saat_ini = $aset->nilai - masaPakai($aset->created_at, $aset->wkt_ekonomis)['masa_pakai'] * $penyusutan;
            $bangunan = $bangunan + $saat_ini;
        }


        // Aktiva Lain
        $asets = Aktivalain::user()->whereYear('created_at', '<=', session('selected_year', date('Y')))->get();
        $aktiva_lain = 0;
        foreach ($asets as $aset) {
            if ($aset->wkt_ekonomis != 0) {
                $penyusutan = $aset->nilai / $aset->wkt_ekonomis;
            } else {
                $penyusutan = 0;
            }
            $saat_ini = $aset->nilai - masaPakai($aset->created_at, $aset->wkt_ekonomis)['masa_pakai'] * $penyusutan;
            $aktiva_lain = $aktiva_lain + $saat_ini;
        }

        // Kas
        $transaksis = Buk::user()->whereYear('tanggal', '<=', session('selected_year', date('Y')))->get();
        $debit = $transaksis->where('jenis', 'debit')->sum('nilai');
        $kredit = $transaksis->where('jenis', 'kredit')->sum('nilai');
        $saldo = $debit - $kredit;

        $total_aktiva = $saldo + $sisa_putang + $saldo_pinjam + $persediaan_dagang + $bayar_dimuka +  $investasi + $bangunan + $aktiva_lain;



        // Pevvita

        // Hutang
        $hutangs = Hutang::user()->whereYear('created_at', '<=', session('selected_year', date('Y')))->get();

        $totalHutang = 0;
        foreach ($hutangs as $hutang) {
            $totalHutang = $totalHutang + ($hutang->nilai - $hutang->pembayaran);
        }

        // Modal
        $modals = Modal::user()->whereYear('created_at', '<=', session('selected_year', date('Y')))->get();
        $modal_desa = $modals->sum('mdl_desa');
        $modal_masyarakat = $modals->sum('mdl_masyarakat');
        $modal_bersama = $modals->sum('mdl_bersama');

        // Ditahan
        $dithns = Dithn::user()->whereYear('created_at', '<=', session('selected_year', date('Y')))->get();

        $ditahan = 0;
        foreach ($dithns as $dithn) {
            $ditahan = $ditahan + $dithn->akumulasi;
        }
        // Total Passiva
        $passiva =   $totalHutang + $modal_desa + $modal_masyarakat + $modal_bersama + $ditahan + labaRugi(session('selected_year', date('Y')))['totalLabaRugi'];


        return [
            'aktiva' => $total_aktiva,
            'passiva' => $passiva,
            'piutang' => $sisa_putang,
            'saldo_pinjam' => $saldo_pinjam,
            'persediaan_dagang' => $persediaan_dagang,
            'bayar_dimuka' => $bayar_dimuka,
            'investasi' => $investasi,
            'bangunan' => $bangunan,
            'aktiva_lain' => $aktiva_lain,
            'total_aktiva' => $total_aktiva,
            'kas' => $saldo,
            'hutang' => $totalHutang,
            'modal_desa' => $modal_desa,
            'modal_masyarakat' => $modal_masyarakat,
            'modal_bersama' => $modal_bersama,
            'ditahan' => $ditahan,
            'laba_rugi_berjalan' => labaRugi(session('selected_year', date('Y')))['totalLabaRugi'],
            'passiva' => $passiva,
        ];
    }
}
