<?php

namespace App\Http\Controllers;

use App\Models\Dithn;
use App\Models\Ekuit;
use App\Models\Hutang;
use App\Models\Modal;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPerubahanModalController extends Controller
{
    public function index()
    {
        $ekuitas = Ekuit::user()->get()->first();

        $modal_desa = Modal::user()->get()->sum('mdl_desa');
        $modal_masyarakat = Modal::user()->get()->sum('mdl_masyarakat');
        $tahun = !isset($ekuitas->tahun) ? session('selected_year', date('Y')) : $ekuitas->tahun;


        return view('laporan_perubahan_modal.index', ['modal_desa' => $modal_desa, 'modal_masyarakat' => $modal_masyarakat, 'ekuitas' => $ekuitas,  'laba_berjalan' => labaRugi($tahun)['totalLabaRugi']]);
    }

    public function exportPdf()
    {

        $ekuitas = Ekuit::user()->get()->first();


        $modal_desa = Modal::user()->get()->sum('mdl_desa');
        $modal_masyarakat = Modal::user()->get()->sum('mdl_masyarakat');
        $data = [
            'modal_desa' => $modal_desa,
            'modal_masyarakat' => $modal_masyarakat,
            'ekuitas' => $ekuitas,
            'laba_berjalan' => labaRugi($ekuitas->tahun)['totalLabaRugi']
        ];

        // Gunakan facade PDF
        $pdf = PDF::loadView('laporan_perubahan_modal.pdf', $data);

        // Mengunduh PDF dengan nama "laporan.pdf"
        return $pdf->stream('laporan.pdf');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required|numeric|digits:4',
            'pades' => 'required|numeric',
            'lainya' => 'required|numeric',
            'akumulasi' => 'required|numeric',
        ]);

        $validated['user_id'] = auth()->user()->id;

        Ekuit::create($validated);

        return redirect()->back()->with('success', 'Berhasil di tambah');
    }
    public function update(Request $request, Ekuit $ekuit)
    {

        $validated = $request->validate([
            'tahun' => 'required|numeric|digits:4',
            'pades' => 'required|numeric',
            'lainya' => 'required|numeric',
            'akumulasi' => 'required|numeric',
        ]);

        $validated['user_id'] = auth()->user()->id;

        Ekuit::where('id', $ekuit->id)->update($validated);

        return redirect()->back()->with('success', 'Berhasil di rubah');
    }

    public function ditahan(Ekuit $ekuit)
    {
        $hasil = labaRugi($ekuit->tahun)['totalLabaRugi'] > 0 ? 'Untung' : 'Rugi';
        $labaRugi =   labaRugi($ekuit->tahun)['totalLabaRugi'];
        Dithn::create([
            'tahun' => $ekuit->tahun,
            'hasil' => $hasil,
            'nilai' => $labaRugi,
            'pades' => $ekuit->pades,
            'lainya' => $ekuit->lainya,
            'akumulasi' => $ekuit->akumulasi,
            'user_id' => auth()->user()->id
        ]);

        Hutang::insert([
            [
                'kreditur' => 'pemdes',
                'keterangan' => 'PADes',
                'nilai' => $labaRugi * ($ekuit->pades / 100),
                'user_id' => auth()->user()->id
            ],
            [
                'kreditur' => 'pengelola BUMDes',
                'keterangan' => 'SHU',
                'nilai' => $labaRugi * ($ekuit->lainya / 100),
                'user_id' => auth()->user()->id
            ]
        ]);

        return redirect()->back()->with('success', 'Berhasil menambahkan laba di tahan');
    }
}
