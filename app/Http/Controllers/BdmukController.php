<?php

namespace App\Http\Controllers;

use App\Models\Bdmuk;
use Illuminate\Http\Request;

class BdmukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asets = Bdmuk::user()->get();

        $akumulasi = 0;
        $investasi = 0;
        foreach ($asets as $aset) {
            $penyusutan = $aset->nilai / $aset->wkt_ekonomis;
            $saat_ini = $aset->nilai - $aset->masa_pakai * $penyusutan;

            $akumulasi = $akumulasi + $penyusutan;
            $investasi = $investasi + $saat_ini;
        }

        return view('bayar_dimuka.index', [
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
        return view('bayar_dimuka.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validasi =  $request->validate([
            'keterangan' => 'required|string|max:255',
            'nilai' => 'required|numeric',
            'wkt_ekonomis' => 'required|min:1',
        ]);
        $validasi['user_id'] = auth()->user()->id;


        // Simpan data ke database
        if (Bdmuk::create($validasi)) {
            bukuUmum('Dibayar di Muka ' . $request->keterangan, 'kredit', 'kas', 'operasional', $request->nilai, 'bdmuk', Bdmuk::latest()->first()->id);
        };

        // Redirect ke halaman daftar aset dengan pesan sukses
        return redirect('/aset/bdmuk')->with('success', 'Aset berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bdmuk $bdmuk)
    {
        //
    }

    /**
     * pakai the specified resource in storage.
     */
    public function pakai(Request $request, Bdmuk $bdmuk)
    {
        if (isset($request->pakai)) {
            if ($request->pakai == 'tambah') {
                $masa_pakai = $bdmuk->masa_pakai + 1;
            } elseif ($request->pakai == 'kurang') {
                $masa_pakai = $bdmuk->masa_pakai - 1;
            }

            Bdmuk::where('id', $bdmuk->id)->update(['masa_pakai' => $masa_pakai]);
            // Redirect ke halaman daftar aset dengan pesan sukses
            return redirect('/aset/bdmuk')->with('success', 'Masa pakai berhasil ditambahkan.');
        }
        // Redirect ke halaman daftar aset dengan pesan sukses
        return redirect('/aset/bdmuk')->with('error', 'Masa pakai gagal ditambahkan.');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bdmuk $bdmuk)
    {
        return view('bayar_dimuka.edit', ['aset' => $bdmuk]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bdmuk $bdmuk)
    {
        // Validasi input
        $validasi =  $request->validate([
            'keterangan' => 'required|string|max:255',
            'nilai' => 'required|numeric',
            'wkt_ekonomis' => 'required|min:1',
            'masa_pakai' => '',
        ]);
        $validasi['user_id'] = auth()->user()->id;
        // Simpan data ke database
        if (Bdmuk::where('id', $bdmuk->id)->update($validasi)) {
            updateBukuUmum('bdmuk', $bdmuk->id, $request->nilai);
        };

        // Redirect ke halaman daftar aset dengan pesan sukses
        return redirect('/aset/bdmuk')->with('success', 'Aset berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bdmuk $bdmuk)
    {
        $bdmuk->delete();

        // Redirect ke halaman daftar aset dengan pesan sukses
        return redirect('/aset/bdmuk')->with('success', 'Aset berhasil dihapus.');
    }
}