<?php

namespace App\Http\Controllers;

use App\Models\Buk;
use App\Models\Investasi;
use Illuminate\Http\Request;

class InvestasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asets = Investasi::user()->get();

        $akumulasi = 0;
        $investasi = 0;
        foreach ($asets as $aset) {
            $penyusutan = $aset->nilai / $aset->wkt_ekonomis;
            $saat_ini =
                $aset->jumlah * $aset->nilai - $aset->masa_pakai * $penyusutan * $aset->jumlah;

            $akumulasi = $akumulasi + $penyusutan;
            $investasi = $investasi + $saat_ini;
        }

        return view('investasi.index', [
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
        return view('investasi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validasi =  $request->validate([
            'item' => 'required|string|max:255',
            'tgl_beli' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'nilai' => 'required|numeric',
            'wkt_ekonomis' => 'required|integer|min:1',
        ]);

        $validasi['masa_pakai'] = 1;

        $validasi['user_id'] = auth()->user()->id;

        if (Investasi::create($validasi)) {
            bukuUmum('Investasi ' . $request->item, 'kredit', 'kas', 'investasi', $request->nilai * $request->jumlah, 'investasi', Investasi::latest()->first()->id);
        };


        return redirect('/aset/investasi')->with('success', 'Aset berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Investasi $investasi)
    {
        //
    }

    /**
     * pakai the specified resource in storage.
     */
    public function pakai(Request $request, Investasi $investasi)
    {
        if (isset($request->pakai)) {
            if ($request->pakai == 'tambah') {
                $masa_pakai = $investasi->masa_pakai + 1;
            } elseif ($request->pakai == 'kurang') {
                $masa_pakai = $investasi->masa_pakai - 1;
            }

            Investasi::where('id', $investasi->id)->update(['masa_pakai' => $masa_pakai]);
            // Redirect ke halaman daftar aset dengan pesan sukses
            return redirect('/aset/investasi')->with('success', 'Masa pakai berhasil ditambahkan.');
        }
        // Redirect ke halaman daftar aset dengan pesan sukses
        return redirect('/aset/investasi')->with('error', 'Masa pakai gagal ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Investasi $investasi)
    {
        return view('investasi.edit', [
            'aset' => $investasi
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Investasi $investasi)
    {
        $validasi =  $request->validate([
            'item' => 'required|string|max:255',
            'tgl_beli' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'nilai' => 'required|numeric',
            'wkt_ekonomis' => 'required|integer|min:1',
            'masa_pakai' => '',
        ]);

        $validasi['user_id'] = auth()->user()->id;

        if (Investasi::where('id', $investasi->id)->update($validasi)) {
            updateBukuUmum('investasi', $investasi->id, $request->nilai);
        };

        return redirect('/aset/investasi')->with('success', 'Aset berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Investasi $investasi)
    {
        $investasi->delete();

        return redirect('/aset/investasi')->with('error', 'Aset berhasil dihapus');
    }
}