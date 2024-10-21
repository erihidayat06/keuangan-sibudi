<?php

namespace App\Http\Controllers;

use App\Models\Piutang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PiutangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $piutangs = Piutang::user()->get();

        $sisa = 0;
        foreach ($piutangs as $piutang) {
            $sisa = $sisa + ($piutang->nilai - $piutang->pembayaran);
        }
        return view('piutang.index', ['piutangs' => $piutangs, 'sisa' => $sisa]);
    }


    public function exportPdf()
    {

        $piutangs = Piutang::user()->get();

        $sisa = 0;
        foreach ($piutangs as $piutang) {
            $sisa = $sisa + ($piutang->nilai - $piutang->pembayaran);
        }
        $data = ['piutangs' => $piutangs, 'sisa' => $sisa];

        // Gunakan facade PDF
        $pdf = PDF::loadView('piutang.pdf', $data);

        // Mengunduh PDF dengan nama "laporan.pdf"
        return $pdf->stream('laporan.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('piutang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kreditur' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'nilai' => 'required|numeric',
        ]);
        $validated['user_id'] = auth()->user()->id;
        $validated['created_at'] = $request->created_at;
        if (Piutang::create($validated) && $request->has('no_kas')) {
            bukuUmum('Piutang', 'kredit', 'kas', 'operasional', $request->nilai, 'piutang', Piutang::latest()->first()->id, $request->created_at);
        };

        // Redirect with success message
        return redirect()->route('piutang.index')->with('success', 'piutang berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Piutang $piutang)
    {
        //
    }

    /**
     * Bayar the specified resource in storage.
     */
    public function bayar(Request $request, Piutang $piutang)
    {

        $input_realisasi = str_replace('.', '', $request->pembayaran);

        if ($request->aksi == '+') {
            $pembayaran = $piutang->pembayaran +  $input_realisasi;
            $jenis = 'debit';
        } elseif ($request->aksi == '-') {
            $pembayaran = $piutang->pembayaran -  $input_realisasi;
            $jenis = 'kredit';
        }


        if (Piutang::where('id', $piutang->id)->update(['pembayaran' => $pembayaran])) {
            bukuUmum('Setor ' . $piutang->kreditur, $jenis, 'kas', 'pendanaan', $input_realisasi, null, null, $piutang->created_at);
        };
        // Redirect with success message
        return redirect()->route('piutang.index')->with('success', 'piutang berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Piutang $piutang)
    {
        return view('piutang.edit', ['piutang' => $piutang]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Piutang $piutang)
    {
        $validated = $request->validate([
            'kreditur' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'nilai' => 'required|numeric',
        ]);
        $validated['user_id'] = auth()->user()->id;


        if (Piutang::where('id', $piutang->id)->update($validated)) {
            updateBukuUmum('piutang', $piutang->id, $request->nilai);
        };

        // Redirect with success message
        return redirect()->route('piutang.index')->with('success', 'Piutang berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Piutang $piutang)
    {
        Piutang::where('id', $piutang->id)->delete();

        return redirect()->route('piutang.index')->with('error', 'Piutang berhasil dihapus.');
    }
}
