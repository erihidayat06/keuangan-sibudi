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
        $total_aktiva = 0;
        $passiva = 0;
        if (isset(auth()->user()->id)) {
            // Sisa Piutang
            $piutangs = Piutang::user()->get();
            $sisa_putang = 0;
            foreach ($piutangs as $piutang) {
                $sisa_putang = $sisa_putang + ($piutang->nilai - $piutang->pembayaran);
            }

            // Sldo Pinjam
            $pinjamans = Pinjaman::user()->get();
            $saldo_pinjam = $pinjamans->sum('alokasi') - $pinjamans->sum('realisasi');

            // Persediaan dagang
            $barangs = Persediaan::user()->get();
            $persediaan_dagang = 0;
            foreach ($barangs as $barang) {
                $jumlah_akhir = $barang->jml_awl - ($barang->masuk - $barang->keluar);
                $nilai_akhir = $jumlah_akhir * $barang->hpp;
                $persediaan_dagang = $persediaan_dagang + $nilai_akhir;
            }

            // Bayar di muka
            $asets = Bdmuk::user()->get();

            $bayar_dimuka = 0;
            foreach ($asets as $aset) {
                $penyusutan = $aset->nilai / $aset->wkt_ekonomis;
                $saat_ini = $aset->nilai - $aset->masa_pakai * $penyusutan;
                $bayar_dimuka = $bayar_dimuka + $saat_ini;
            }

            // Investasi
            $asets = Investasi::user()->get();
            $investasi = 0;
            foreach ($asets as $aset) {
                $penyusutan = $aset->nilai / $aset->wkt_ekonomis;
                $saat_ini =
                    $aset->jumlah * $aset->nilai - $aset->masa_pakai * $penyusutan * $aset->jumlah;
                $investasi = $investasi + $saat_ini;
            }

            // bangunan
            $asets = Bangunan::user()->get();

            $bangunan = 0;
            foreach ($asets as $aset) {
                $penyusutan = $aset->nilai / $aset->wkt_ekonomis;
                $saat_ini = $aset->nilai - $aset->masa_pakai * $penyusutan;
                $bangunan = $bangunan + $saat_ini;
            }


            // Aktiva Lain
            $asets = Aktivalain::user()->get();
            $aktiva_lain = 0;
            foreach ($asets as $aset) {
                $penyusutan = $aset->nilai / $aset->wkt_ekonomis;
                $saat_ini = $aset->nilai - $aset->masa_pakai * $penyusutan;
                $aktiva_lain = $aktiva_lain + $saat_ini;
            }

            // Kas
            $transaksis = Buk::user()->get();
            $debit = $transaksis->where('jenis', 'debit')->sum('nilai');
            $kredit = $transaksis->where('jenis', 'kredit')->sum('nilai');
            $saldo = $debit - $kredit;

            $total_aktiva = $saldo + $sisa_putang + $saldo_pinjam + $persediaan_dagang + $bayar_dimuka +  $investasi + $bangunan + $aktiva_lain;

            // Hutang
            $hutangs = Hutang::user()->get();

            $totalHutang = 0;
            foreach ($hutangs as $hutang) {
                $totalHutang = $totalHutang + ($hutang->nilai - $hutang->pembayaran);
            }

            // Modal
            $modals = Modal::user()->get();
            $modal_desa = $modals->sum('mdl_desa');
            $modal_masyarakat = $modals->sum('mdl_masyarakat');

            // Ditahan
            $dithns = Dithn::user()->get();

            $ditahan = 0;
            foreach ($dithns as $dithn) {
                $ditahan = $ditahan + ($dithn->nilai * ($dithn->akumulasi / 100));
            }
            // Total Passiva
            $passiva =   $totalHutang + $modal_desa + $modal_masyarakat + $ditahan + labaRugi()['totalLabaRugi'];
        }
        return ['aktiva' => $total_aktiva, 'passiva' => $passiva];
    }
}
