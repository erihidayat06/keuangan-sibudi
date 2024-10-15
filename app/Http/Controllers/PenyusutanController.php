<?php

namespace App\Http\Controllers;

use App\Models\Bdmuk;
use App\Models\Bangunan;
use App\Models\Investasi;
use App\Models\Aktivalain;
use Illuminate\Http\Request;

class PenyusutanController extends Controller
{
    public function index()
    {
        $tahun_sekarang =  session('selected_year', date('Y'));
        $akumulasi_iven = 0;
        $investasi_iven = 0;

        $asets = Investasi::user()->whereYear('created_at', $tahun_sekarang)->get();
        foreach ($asets as $aset) {
            $penyusutan_iven = $aset->nilai / $aset->wkt_ekonomis * $aset->masa_pakai;
            $saat_ini_iven =
                $aset->jumlah * $aset->nilai - $aset->masa_pakai * $penyusutan_iven * $aset->jumlah;

            $akumulasi_iven = $akumulasi_iven + $penyusutan_iven;
            $investasi_iven = $investasi_iven + $saat_ini_iven;
        }

        $akumulasi_bangunan = 0;
        $investasi_bangunan = 0;
        $asets = Bangunan::user()->whereYear('created_at', $tahun_sekarang)->get();
        foreach ($asets as $aset) {
            $penyusutan_bangunan = $aset->nilai / $aset->wkt_ekonomis * $aset->masa_pakai;
            $saat_ini_bangunan =
                $aset->jumlah * $aset->nilai - $aset->masa_pakai * $penyusutan_bangunan * $aset->jumlah;

            $akumulasi_bangunan = $akumulasi_bangunan + $penyusutan_bangunan;
            $investasi_bangunan = $investasi_bangunan + $saat_ini_bangunan;
        }

        $akumulasi_bdmuk = 0;
        $investasi_bdmuk = 0;
        $asets = Bdmuk::user()->whereYear('created_at', $tahun_sekarang)->get();
        foreach ($asets as $aset) {
            $penyusutan_bdmuk = $aset->nilai / $aset->wkt_ekonomis * $aset->masa_pakai;
            $saat_ini_bdmuk =
                $aset->jumlah * $aset->nilai - $aset->masa_pakai * $penyusutan_bdmuk * $aset->jumlah;

            $akumulasi_bdmuk = $akumulasi_bdmuk + $penyusutan_bdmuk;
            $investasi_bdmuk = $investasi_bdmuk + $saat_ini_bdmuk;
        }


        $asets = Aktivalain::user()->whereYear('created_at', $tahun_sekarang)->get();

        $akumulasi_lain = 0;
        $investasi_lain = 0;
        foreach ($asets as $aset) {
            $penyusutan_lain = $aset->nilai / $aset->wkt_ekonomis * $aset->masa_pakai;
            $saat_ini_lain =
                $aset->jumlah * $aset->nilai - $aset->masa_pakai * $penyusutan_lain * $aset->jumlah;

            $akumulasi_lain = $akumulasi_lain + $penyusutan_lain;
            $investasi_lain = $investasi_lain + $saat_ini_lain;
        }

        $total = $akumulasi_iven + $akumulasi_bangunan + $akumulasi_bdmuk + $akumulasi_lain;

        return view('penyusutan.index', [
            'investasi' => $akumulasi_iven,
            'bdmuk' => $akumulasi_bdmuk,
            'bangunan' => $akumulasi_bangunan,
            'aktiva' => $akumulasi_lain,
            'total' => $total
        ]);
    }
}
