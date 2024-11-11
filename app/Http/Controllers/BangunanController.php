<?php

namespace App\Http\Controllers;

use App\Models\Bangunan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BangunanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asets = Bangunan::user()->get();

        $akumulasi = 0;
        $investasi = 0;
        $akumulasi = $akumulasi + akumulasiPenyusutan($asets)['akumu'];
        $investasi = $investasi + akumulasiPenyusutan($asets)['inven'];

        return view('bangunan.index', [
            "asets" => $asets,
            'akumulasi' => $akumulasi,
            'investasi' => $investasi
        ]);
    }

    public function exportPdf()
    {

        $asets = Bangunan::user()->get();

        $akumulasi = 0;
        $investasi = 0;
        $akumulasi = $akumulasi + akumulasiPenyusutan($asets)['akumu'];
        $investasi = $investasi + akumulasiPenyusutan($asets)['inven'];

        $data = [
            "asets" => $asets,
            'akumulasi' => $akumulasi,
            'investasi' => $investasi
        ];

        // Gunakan facade PDF
        $pdf = PDF::loadView('bangunan.pdf', $data)->setPaper('f4', 'portrait');

        // Mengunduh PDF dengan nama "laporan.pdf"
        return $pdf->stream('laporan.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bangunan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated =  $request->validate([
            'jenis' => 'required|string|max:255',
            'nilai' => 'required|numeric',
            'wkt_ekonomis' => 'required|min:1',
        ]);
        $validated['user_id'] = auth()->user()->id;
        $validated['masa_pakai'] = 1;
        $validated['created_at'] = $request->created_at;

        // Simpan data ke database
        if (Bangunan::create($validated)) {
            bukuUmum('Bangunan ' . $request->jenis, 'kredit', 'kas', 'operasional', $request->nilai, 'bangunan', Bangunan::latest()->first()->id, $request->created_at);
        };

        // Redirect ke halaman daftar aset dengan pesan sukses
        return redirect('/aset/bangunan')->with('success', 'Aset berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bangunan $bangunan)
    {
        //
    }

    /**
     * pakai the specified resource in storage.
     */
    public function pakai(Request $request, Bangunan $bangunan)
    {
        if (isset($request->pakai)) {
            if ($request->pakai == 'tambah') {
                $masa_pakai = $bangunan->masa_pakai + 1;
            } elseif ($request->pakai == 'kurang') {
                $masa_pakai = $bangunan->masa_pakai - 1;
            }

            Bangunan::where('id', $bangunan->id)->update(['masa_pakai' => $masa_pakai]);
            // Redirect ke halaman daftar aset dengan pesan sukses
            return redirect('/aset/bangunan')->with('success', 'Masa pakai berhasil ditambahkan.');
        }
        // Redirect ke halaman daftar aset dengan pesan sukses
        return redirect('/aset/bangunan')->with('error', 'Masa pakai gagal ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bangunan $bangunan)
    {
        return view('bangunan.edit', ['aset' => $bangunan]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bangunan $bangunan)
    {
        // Validasi input
        $validated =  $request->validate([
            'jenis' => 'required|string|max:255',
            'nilai' => 'required|numeric',
            'wkt_ekonomis' => 'required|min:1',
            'masa_pakai' => '',
        ]);
        $validated['user_id'] = auth()->user()->id;
        $validated['created_at'] = $request->created_at;
        // Simpan data ke database
        if (Bangunan::where('id', $bangunan->id)->update($validated)) {
            updateBukuUmum('bangunan', $bangunan->id, $request->nilai);
        };

        // Redirect ke halaman daftar aset dengan pesan sukses
        return redirect('/aset/bangunan')->with('success', 'Aset berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bangunan $bangunan)
    {
        $bangunan->delete();

        // Redirect ke halaman daftar aset dengan pesan sukses
        return redirect('/aset/bangunan')->with('success', 'Aset berhasil dihapus.');
    }
}
