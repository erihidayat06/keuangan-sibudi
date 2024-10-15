<?php

namespace App\Http\Controllers;

use App\Models\Buk;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BuksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        $transaksis_lalu = Buk::user()->whereYear('created_at', session('selected_year', date('Y')) - 1)->get();
        $debit_lalu = $transaksis_lalu->where('jenis', 'debit')->sum('nilai');
        $kredit_lalu = $transaksis_lalu->where('jenis', 'kredit')->sum('nilai');
        $saldo_lalu = $debit_lalu - $kredit_lalu;

        $transaksis = Buk::user()->whereYear('created_at', session('selected_year', date('Y')))->get();
        $debit = $transaksis->where('jenis', 'debit')->sum('nilai');
        $kredit = $transaksis->where('jenis', 'kredit')->sum('nilai');
        $saldo = $debit - $kredit;
        $saldo = $saldo + $saldo_lalu;

        return view('buku_kas.index', [
            'transaksis' => $transaksis,
            'saldo' => $saldo,
            'saldo_lalu' => $saldo_lalu,

        ]);
    }

    public function exportPdf()
    {

        $transaksis_lalu = Buk::user()->whereYear('created_at', session('selected_year', date('Y')) - 1)->get();
        $debit_lalu = $transaksis_lalu->where('jenis', 'debit')->sum('nilai');
        $kredit_lalu = $transaksis_lalu->where('jenis', 'kredit')->sum('nilai');
        $saldo_lalu = $debit_lalu - $kredit_lalu;

        $transaksis = Buk::user()->whereYear('created_at', session('selected_year', date('Y')))->get();
        $debit = $transaksis->where('jenis', 'debit')->sum('nilai');
        $kredit = $transaksis->where('jenis', 'kredit')->sum('nilai');
        $saldo = $debit - $kredit;
        $saldo = $saldo + $saldo_lalu;
        $data = [
            'transaksis' => $transaksis,
            'saldo' => $saldo,
            'saldo_lalu' => $saldo_lalu,

        ];

        // Gunakan facade PDF
        $pdf = PDF::loadView('buku_kas.pdf', $data)->setPaper('a2', 'portrait');

        // Mengunduh PDF dengan nama "laporan.pdf"
        return $pdf->stream('laporan.pdf');
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
