<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanLabaRugiController extends Controller
{
    public function index()
    {
        $units = Unit::user()->get();
        $labaRugi = labaRugi(session('selected_year', date('Y')));



        return view('laporan_laba_rugi.index', [
            'pendapatan'           => $labaRugi['pendapatan'],
            'pendapatanBulan'      => $labaRugi['pendapatanBulan'],
            'pendapatanTahun'      => $labaRugi['pendapatanTahun'],
            'tahun'                => $labaRugi['tahun'],

            // Variabel Baru (HPP & Laba Kotor)
            'labaKotor'            => $labaRugi['labaKotor'],
            'akumulasiHpp'         => $labaRugi['akumulasiHpp'],
            'akumulasiLabaKotor'   => $labaRugi['akumulasiLabaKotor'],

            'totalBiaya'           => $labaRugi['totalBiaya'],
            'akumulasiBiaya'       => $labaRugi['akumulasiBiaya'],
            'labaRugi'             => $labaRugi['labaRugi'],
            'totalLabaRugi'        => $labaRugi['totalLabaRugi'],
            'akumulasi_penyusutan' => $labaRugi['akumulasi_penyusutan'],
            'units'                => $units
        ]);
    }

    public function exportPdf()
    {
        $units = Unit::user()->get();
        $labaRugi = labaRugi(session('selected_year', date('Y')));

        $data = [
            'pendapatan'           => $labaRugi['pendapatan'],
            'pendapatanBulan'      => $labaRugi['pendapatanBulan'],
            'pendapatanTahun'      => $labaRugi['pendapatanTahun'],
            'tahun'                => $labaRugi['tahun'],

            // Variabel Baru (HPP & Laba Kotor)
            'labaKotor'            => $labaRugi['labaKotor'],
            'akumulasiHpp'         => $labaRugi['akumulasiHpp'],
            'akumulasiLabaKotor'   => $labaRugi['akumulasiLabaKotor'],

            'totalBiaya'           => $labaRugi['totalBiaya'],
            'akumulasiBiaya'       => $labaRugi['akumulasiBiaya'],
            'labaRugi'             => $labaRugi['labaRugi'],
            'totalLabaRugi'        => $labaRugi['totalLabaRugi'],
            'akumulasi_penyusutan' => $labaRugi['akumulasi_penyusutan'],
            'units'                => $units
        ];

        // Gunakan facade PDF
        $pdf = Pdf::loadView('laporan_laba_rugi.pdf', $data);

        // Mengunduh PDF dengan nama "laporan.pdf"
        return $pdf->stream('laporan.pdf');
    }
}
