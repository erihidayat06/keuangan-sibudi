<?php

namespace App\Http\Controllers;

use App\Models\Bangunan;
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
        foreach ($asets as $aset) {
            $penyusutan = $aset->nilai / $aset->wkt_ekonomis;
            $saat_ini = $aset->nilai - $aset->masa_pakai * $penyusutan;

            $akumulasi = $akumulasi + $penyusutan;
            $investasi = $investasi + $saat_ini;
        }

        return view('bangunan.index', [
            "asets" => $asets,
            'akumulasi' => $akumulasi,
            'investasi' => $investasi
        ]);
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
        $validasi =  $request->validate([
            'jenis' => 'required|string|max:255',
            'nilai' => 'required|numeric',
            'wkt_ekonomis' => 'required|min:1',
        ]);
        $validasi['user_id'] = auth()->user()->id;

        // Simpan data ke database
        if (Bangunan::create($validasi)) {
            bukuUmum('Bangunan ' . $request->jenis, 'kredit', 'kas', 'operasional', $request->nilai, 'bangunan', Bangunan::latest()->first()->id);
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
        $validasi =  $request->validate([
            'jenis' => 'required|string|max:255',
            'nilai' => 'required|numeric',
            'wkt_ekonomis' => 'required|min:1',
            'masa_pakai' => '',
        ]);
        $validasi['user_id'] = auth()->user()->id;
        // Simpan data ke database
        if (Bangunan::where('id', $bangunan->id)->update($validasi)) {
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