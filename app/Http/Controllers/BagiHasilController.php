<?php

namespace App\Http\Controllers;

use App\Models\Buk;
use App\Models\Dithn;
use Illuminate\Http\Request;

class BagiHasilController extends Controller
{
    public function index()
    {

        $ditahan = Dithn::user()->where('tahun', '2024')->first();
        if (!isset($ditahan)) {
            return redirect('/dithn')->with('error', 'Mohon Tambahkan Data Laba ditahan');
        }

        return view('bagi_hasil.index', ['labaBerjalan' => labaRugi()['totalLabaRugi'], 'ditahan' => $ditahan]);
    }

    public function update(Request $request, Dithn $dithn)
    {
        $validated = $request->validate([
            'pades' => 'required|numeric',
            'lainya' => 'required|numeric',
            'akumulasi' => 'required|numeric',
        ]);
        // Simpan ke database
        Dithn::where('id', $dithn->id)->update($validated);

        return redirect()->back()->with('success', 'Data berhasil diubah!');
    }
}
