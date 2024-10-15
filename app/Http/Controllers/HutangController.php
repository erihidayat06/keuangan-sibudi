<?php

namespace App\Http\Controllers;

use App\Models\Hutang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class HutangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hutangs = Hutang::user()->get();

        $sisa = 0;
        foreach ($hutangs as $hutang) {
            $sisa = $sisa + ($hutang->nilai - $hutang->pembayaran);
        }
        return view('hutang.index', ['hutangs' => $hutangs, 'sisa' => $sisa]);
    }

    public function exportPdf()
    {

        $hutangs = Hutang::user()->get();

        $sisa = 0;
        foreach ($hutangs as $hutang) {
            $sisa = $sisa + ($hutang->nilai - $hutang->pembayaran);
        }
        $data = ['hutangs' => $hutangs, 'sisa' => $sisa];

        // Gunakan facade PDF
        $pdf = PDF::loadView('hutang.pdf', $data);

        // Mengunduh PDF dengan nama "laporan.pdf"
        return $pdf->stream('laporan.pdf');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('hutang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'kreditur' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'nilai' => 'required|numeric',
        ]);
        $validate['user_id'] = auth()->user()->id;
        Hutang::create($validate);

        // Redirect with success message
        return redirect()->route('hutang.index')->with('success', 'Hutang berhasil ditambahkan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Hutang $hutang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hutang $hutang)
    {
        return view('hutang.edit', ['hutang' => $hutang]);
    }

    /**
     * Bayar the specified resource in storage.
     */
    public function bayar(Request $request, Hutang $hutang)
    {


        if ($request->aksi == '+') {
            $pembayaran = $hutang->pembayaran +  $request->pembayaran;
        } elseif ($request->aksi == '-') {
            $pembayaran = $hutang->pembayaran -  $request->pembayaran;
        }

        Hutang::where('id', $hutang->id)->update(['pembayaran' => $pembayaran]);
        // Redirect with success message
        return redirect()->route('hutang.index')->with('success', 'Hutang berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Hutang $hutang)
    {
        $validate = $request->validate([
            'kreditur' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'nilai' => 'required|numeric',
        ]);
        $validate['user_id'] = auth()->user()->id;
        Hutang::where('id', $hutang->id)->update($validate);

        // Redirect with success message
        return redirect()->route('hutang.index')->with('success', 'Hutang berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hutang $hutang)
    {
        Hutang::where('id', $hutang->id)->delete();

        return redirect()->route('hutang.index')->with('error', 'Hutang berhasil dihapus.');
    }
}
