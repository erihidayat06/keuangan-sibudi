<?php

namespace App\Http\Controllers;

use App\Models\Aktivalain;
use Illuminate\Http\Request;

class AktivalainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asets = Aktivalain::user()->get();

        $akumulasi = 0;
        $investasi = 0;
        foreach ($asets as $aset) {
            $penyusutan = $aset->nilai / $aset->wkt_ekonomis;
            $saat_ini = $aset->nilai - $aset->masa_pakai * $penyusutan;

            $akumulasi = $akumulasi + $penyusutan;
            $investasi = $investasi + $saat_ini;
        }

        return view('aktiva.index', [
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
        return view('aktiva.create');
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
        if (Aktivalain::create($validasi)) {
            bukuUmum('Aktiva Lain ' . $request->jenis, 'kredit', 'kas', 'operasional', $request->nilai, 'aktiva_lain', Aktivalain::latest()->first()->id);
        };

        // Redirect ke halaman daftar aset dengan pesan sukses
        return redirect('/aset/aktivalain')->with('success', 'Aset berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Aktivalain $aktivalain)
    {
        //
    }


    /**
     * pakai the specified resource in storage.
     */
    public function pakai(Request $request, Aktivalain $aktivalain)
    {
        if (isset($request->pakai)) {
            if ($request->pakai == 'tambah') {
                $masa_pakai = $aktivalain->masa_pakai + 1;
            } elseif ($request->pakai == 'kurang') {
                $masa_pakai = $aktivalain->masa_pakai - 1;
            }

            Aktivalain::where('id', $aktivalain->id)->update(['masa_pakai' => $masa_pakai]);
            // Redirect ke halaman daftar aset dengan pesan sukses
            return redirect('/aset/aktivalain')->with('success', 'Masa pakai berhasil ditambahkan.');
        }
        // Redirect ke halaman daftar aset dengan pesan sukses
        return redirect('/aset/aktivalain')->with('error', 'Masa pakai gagal ditambahkan.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Aktivalain $aktivalain)
    {
        return view('aktiva.edit', ['aset' => $aktivalain]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Aktivalain $aktivalain)
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
        if (Aktivalain::where('id', $aktivalain->id)->update($validasi)) {
            updateBukuUmum('aktiva_lain', $aktivalain->id, $request->nilai);
        };

        // Redirect ke halaman daftar aset dengan pesan sukses
        return redirect('/aset/aktivalain')->with('success', 'Aset berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aktivalain $aktivalain)
    {
        $aktivalain->delete();

        // Redirect ke halaman daftar aset dengan pesan sukses
        return redirect('/aset/aktivalain')->with('success', 'Aset berhasil dihapus.');
    }
}
