<?php

namespace App\Http\Controllers;

use App\Models\Buk;
use App\Models\Persediaan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Investasi;

class LabaRugiController extends Controller
{
    public function index()
    {

        $labaRugi =
            labaRugi(session('selected_year', date('Y')));
        return view('laba_rugi.index', [
            'pendapatan' => $labaRugi['pendapatan'],
            'pendapatanBulan' => $labaRugi['pendapatanBulan'],
            'pendapatanTahun' => $labaRugi['pendapatanTahun'],
            'tahun' => $labaRugi['tahun'],
            'totalBiaya' => $labaRugi['totalBiaya'],
            'akumulasiBiaya' => $labaRugi['akumulasiBiaya'],
            'labaRugi' => $labaRugi['labaRugi'],
            'totalLabaRugi' => $labaRugi['totalLabaRugi'],
            'akumulasi_penyusutan' => $labaRugi['akumulasi_penyusutan']
        ]);
    }

    public function exportPdf()
    {

        $labaRugi =
            labaRugi(session('selected_year', date('Y')));
        $data = [
            'pendapatan' => $labaRugi['pendapatan'],
            'pendapatanBulan' => $labaRugi['pendapatanBulan'],
            'pendapatanTahun' => $labaRugi['pendapatanTahun'],
            'tahun' => $labaRugi['tahun'],
            'totalBiaya' => $labaRugi['totalBiaya'],
            'akumulasiBiaya' => $labaRugi['akumulasiBiaya'],
            'labaRugi' => $labaRugi['labaRugi'],
            'totalLabaRugi' => $labaRugi['totalLabaRugi'],
            'akumulasi_penyusutan' => $labaRugi['akumulasi_penyusutan']
        ];

        // Gunakan facade PDF
        $pdf = PDF::loadView('laba_rugi.pdf', $data)->setPaper('a2', 'portrait');

        // Mengunduh PDF dengan nama "laporan.pdf"
        return $pdf->stream('laporan.pdf');
    }
}
