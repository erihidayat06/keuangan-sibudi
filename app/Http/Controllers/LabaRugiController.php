<?php

namespace App\Http\Controllers;

use App\Models\Buk;
use App\Models\Persediaan;
use Illuminate\Http\Request;
use App\Models\Investasi;

class LabaRugiController extends Controller
{
    public function index()
    {


        return view('laba_rugi.index', [
            'pendapatan' => labaRugi()['pendapatan'],
            'pendapatanBulan' => labaRugi()['pendapatanBulan'],
            'pendapatanTahun' => labaRugi()['pendapatanTahun'],
            'tahun' => labaRugi()['tahun'],
            'totalBiaya' => labaRugi()['totalBiaya'],
            'akumulasiBiaya' => labaRugi()['akumulasiBiaya'],
            'labaRugi' => labaRugi()['labaRugi'],
            'totalLabaRugi' => labaRugi()['totalLabaRugi'],
            'akumulasi_penyusutan' => labaRugi()['akumulasi_penyusutan']
        ]);
    }
}
