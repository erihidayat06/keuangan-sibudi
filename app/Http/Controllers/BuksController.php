<?php

namespace App\Http\Controllers;

use App\Models\Buk;
use Illuminate\Http\Request;

class BuksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaksis = Buk::user()->get();
        $debit = $transaksis->where('jenis', 'debit')->sum('nilai');
        $kredit = $transaksis->where('jenis', 'kredit')->sum('nilai');
        $saldo = $debit - $kredit;


        return view('buku_kas.index', [
            'transaksis' => $transaksis,
            'saldo' => $saldo,

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('buku_kas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'tanggal' => 'required|date',
            'transaksi' => 'required|string|max:255',
            'jenis' => 'required|string|in:debit,kredit', // e.g., debit or credit
            'jenis_lr' => 'required|string|in:pu1,pu2,pu3,pu4,bo1,bo2,bo3,bo4,bno1,bno2,bno3,bno4,bno5,kas', // e.g., debit or credit
            'jenis_dana' => 'required|string|in:operasional,inventasi,pendanaan', // e.g., debit or credit
            'nilai' => 'required|numeric',
        ]);
        $validate['user_id'] = auth()->user()->id;
        Buk::create($validate);

        return redirect('/aset/buk')->with('success', 'Transaksi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Buk $buk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Buk $buk)
    {

        return view('buku_kas.edit', ['transaksi' => $buk]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Buk $buk)
    {
        $validate = $request->validate([
            'tanggal' => 'required|date',
            'transaksi' => 'required|string|max:255',
            'jenis' => 'required|string|in:debit,kredit', // e.g., debit or credit
            'jenis_lr' => 'required|string|in:pu1,pu2,pu3,pu4,bo1,bo2,bo3,bo4,bno1,bno2,bno3,bno4,bno5,kas', // e.g., debit or credit
            'jenis_dana' => 'required|string|in:operasional,inventasi,pendanaan', // e.g., debit or credit
            'nilai' => 'required|numeric',
        ]);
        $validate['user_id'] = auth()->user()->id;
        Buk::where('id', $buk->id)->update($validate);

        return redirect('/aset/buk')->with('success', 'Transaksi berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Buk $buk)
    {
        Buk::where('id', $buk->id)->delete();

        return redirect('/aset/buk')->with('error', 'Hutang berhasil dihapus.');
    }
}
