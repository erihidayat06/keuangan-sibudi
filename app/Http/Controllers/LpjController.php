<?php

namespace App\Http\Controllers;

use App\Models\Lpj;
use App\Models\Unit;
use App\Models\Ekuit;
use App\Models\Modal;
use App\Models\Rasio;
use App\Models\Profil;
use App\Models\Proker;
use App\Models\Target;
use App\Models\Alokasi;
use App\Models\Program;
use App\Models\Kerjasama;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LpjController extends Controller
{
    public function index()
    {
        $user_id = auth()->user()->id;
        if (Lpj::user()->where('tahun', session('selected_year', date('Y')))->get()->first() == null) {
            Lpj::create(['user_id' => $user_id, 'tahun' => session('selected_year', date('Y'))]);
        }

        $lpj = Lpj::user()->where('tahun', session('selected_year', date('Y')))->get()->first();
        return view('lpj.index', ['lpj' => $lpj]);
    }

    public function update(Request $request, Lpj $lpj)
    {
        $validated = $request->validate([
            'kegiatan_usaha' => 'required|in:Sesuai,Tidak Sesuai',
            'penambahan_modal' => 'required|in:Ada,Tidak Ada',
            'hasil_capaian' => 'nullable|string',
            'kebijakan_strategi' => 'required|string',
            'tantangan_hambatan' => 'required|string',
            'apresiasi' => 'required|string',
            'tugas_pengawasan' => 'required|string',
            'pandangan_pengawas' => 'required|string',
            'catatan_pengawas' => 'required|string',
            'rekomendasi_pengawas' => 'required|string',
            'hasil_kinerja' => 'required|string',
            'permasalahan_usaha' => 'required|string',
            'potensi_peluang' => 'required|string',
            'strategi_kebijakan' => 'required|string',
        ]);

        $lpj = Lpj::findOrFail($lpj->id);
        $lpj->update($validated);

        return back()->with('success', 'Berhasil dibuat');
    }


    public function exportPdf()
    {
        $lpj = Lpj::user()->where('tahun', session('selected_year', date('Y')))->get()->first();
        $profil = Profil::user()->get()->first();
        $units = Unit::user()->get();
        $modals = Modal::user()->get();
        $labaRugi = labaRugiTahun(session('selected_year', date('Y')));
        $neraca = neraca();
        $target = Target::user()->where('tahun', session('selected_year', date('Y')) - 1)->get()->first();
        $units = Unit::user()->get();
        $proker = Proker::user()->where('tahun', session('selected_year', date('Y')))->get()->first();
        $programs = Program::user()->where('tahun', session('selected_year', date('Y')))->get();
        $kerjasamas = Kerjasama::user()->where('tahun', session('selected_year', date('Y')))->get();
        $alokasis = Alokasi::user()->where('tahun', session('selected_year', date('Y')))->get();
        $rasios = Rasio::user()->where('tahun', session('selected_year', date('Y')))->get();
        $ekuitas = Ekuit::user()->get()->first();

        $modal_desa = Modal::user()->get()->sum('mdl_desa');
        $modal_masyarakat = Modal::user()->get()->sum('mdl_masyarakat');
        $modal_bersama = Modal::user()->get()->sum('mdl_bersama');
        $tahun = session('selected_year') ?? date('Y');

        $data = [
            'profil' => $profil,
            'units' => $units,
            'modals'  => $modals,
            'units' => $units,
            'pendapatan' => $labaRugi['pendapatan'],
            'pendapatanBulan' => $labaRugi['pendapatanBulan'],
            'pendapatanTahun' => $labaRugi['pendapatanTahun'],
            'tahun' => $labaRugi['tahun'],
            'totalBiaya' => $labaRugi['totalBiaya'],
            'akumulasiBiaya' => $labaRugi['akumulasiBiaya'],
            'labaRugi' => $labaRugi['labaRugi'],
            'totalLabaRugi' => $labaRugi['totalLabaRugi'],
            'akumulasi_penyusutan' => $labaRugi['akumulasi_penyusutan'],
            'target' => $target,
            'proker' => $proker,
            'programs' => $programs,
            'kerjasamas' => $kerjasamas,
            'alokasis' => $alokasis,
            'rasios' => $rasios,
            'aktiva' => $neraca['aktiva'],
            'lpj' => $lpj,
            'modal_desa' => $modal_desa,
            'modal_masyarakat' => $modal_masyarakat,
            'modal_bersama' => $modal_bersama,
            'ekuitas' => $ekuitas,
            'ditahan' => $neraca['ditahan'],
            'laba_berjalan' => labaRugiTahun($tahun)['totalLabaRugi']
        ];

        // Generate PDF
        $pdf = PDF::loadView('lpj.pdf', $data)->setPaper([0, 0, 595.276, 935.433], 'portrait');

        // Stream atau unduh PDF
        return $pdf->stream('lpj.pdf');
    }
}