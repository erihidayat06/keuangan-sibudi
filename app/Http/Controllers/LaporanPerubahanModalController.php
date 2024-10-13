<?php

namespace App\Http\Controllers;

use App\Models\Dithn;
use App\Models\Modal;
use Illuminate\Http\Request;

class LaporanPerubahanModalController extends Controller
{
    public function index()
    {
        $ditahan_umum = Dithn::user()->where('tahun', '2024')->first();
        if (!isset($ditahan_umum)) {
            return redirect('/dithn')->with('error', 'Mohon Tambahkan Data Laba ditahan');
        }

        $modal_desa = Modal::user()->get()->sum('mdl_desa');
        $modal_masyarakat = Modal::user()->get()->sum('mdl_masyarakat');

        // Ditahan
        $dithns = Dithn::user()->get();
        $ditahan = 0;
        foreach ($dithns as $dithn) {
            $ditahan = $ditahan + ($dithn->nilai * ($dithn->akumulasi / 100));
        }

        return view('laporan_perubahan_modal.index', ['modal_desa' => $modal_desa, 'modal_masyarakat' => $modal_masyarakat, 'laba_ditahan' => $ditahan, 'ditahan' => $ditahan_umum, 'laba_berjalan' => labaRugi()['totalLabaRugi']]);
    }
}
