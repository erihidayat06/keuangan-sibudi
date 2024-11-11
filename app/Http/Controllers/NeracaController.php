<?php

namespace App\Http\Controllers;


use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class NeracaController extends Controller
{
    public function index()
    {
        $neraca =  neraca();
        return view('neraca.index', [
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
        ]);
    }

    public function exportPdf()
    {
        $neraca =  neraca();

        $data =  [
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
        ];

        // Gunakan facade PDF
        $pdf = PDF::loadView('neraca.pdf', $data);

        // Mengunduh PDF dengan nama "laporan.pdf"
        return $pdf->stream('laporan.pdf');
    }
}
