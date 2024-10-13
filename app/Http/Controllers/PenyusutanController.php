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
        $asets = Investasi::user()->get();

        $investasi = 0;
        foreach ($asets as $aset) {
            $penyusutan = $aset->nilai / $aset->wkt_ekonomis;
            $saat_ini =
                $aset->jumlah * $aset->nilai - $aset->masa_pakai * $penyusutan * $aset->jumlah;
            $investasi = $investasi + $saat_ini;
        }

        $asets = Bdmuk::user()->get();
        $bdmuk = 0;
        foreach ($asets as $aset) {
            $penyusutan = $aset->nilai / $aset->wkt_ekonomis;
            $saat_ini = $aset->nilai - $aset->masa_pakai * $penyusutan;
            $bdmuk = $bdmuk + $saat_ini;
        }


        $asets = Bangunan::user()->get();
        $bangunan = 0;
        foreach ($asets as $aset) {
            $penyusutan = $aset->nilai / $aset->wkt_ekonomis;
            $saat_ini = $aset->nilai - $aset->masa_pakai * $penyusutan;
            $bangunan = $bangunan + $saat_ini;
        }


        $asets = Aktivalain::user()->get();
        $aktiva = 0;
        foreach ($asets as $aset) {
            $penyusutan = $aset->nilai / $aset->wkt_ekonomis;
            $saat_ini = $aset->nilai - $aset->masa_pakai * $penyusutan;
            $aktiva = $aktiva + $saat_ini;
        }

        $total = $investasi + $bdmuk + $bangunan + $aktiva;

        return view('penyusutan.index', [
            'investasi' => $investasi,
            'bdmuk' => $bdmuk,
            'bangunan' => $bangunan,
            'aktiva' => $aktiva,
            'total' => $total
        ]);
    }
}
