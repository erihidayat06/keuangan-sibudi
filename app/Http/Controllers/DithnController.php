<?php

namespace App\Http\Controllers;

use App\Models\Dithn;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class DithnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dithns = Dithn::user()->get();


        $total = 0;
        foreach ($dithns as $dithn) {
            $total = $total + ($dithn->nilai * ($dithn->akumulasi / 100));
        }
        return view('ditahan.index', ['dithns' => $dithns, 'total' => $total]);
    }

    public function exportPdf()
    {

        $dithns = Dithn::user()->get();


        $total = 0;
        foreach ($dithns as $dithn) {
            $total = $total + ($dithn->nilai * ($dithn->akumulasi / 100));
        }
        $data = ['dithns' => $dithns, 'total' => $total];

        // Gunakan facade PDF
        $pdf = PDF::loadView('ditahan.pdf', $data);

        // Mengunduh PDF dengan nama "laporan.pdf"
        return $pdf->stream('laporan.pdf');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ditahan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'hasil' => 'required|string|max:255',
            'nilai' => 'required|numeric',
            'pades' => 'required|numeric',
            'lainya' => 'required|numeric',
            'akumulasi' => 'required|numeric',
            'tahun' => 'required|numeric|digits:4',
        ]);
        $validated['user_id'] = auth()->user()->id;
        // Simpan ke database
        Dithn::create($validated);

        return redirect('/dithn')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Dithn $dithn)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dithn $dithn)
    {
        return view('ditahan.edit', ['pendapatan' => $dithn]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dithn $dithn)
    {
        $validated = $request->validate([
            'hasil' => 'required|string|max:255',
            'nilai' => 'required|numeric',
            'pades' => 'required|numeric',
            'lainya' => 'required|numeric',
            'akumulasi' => 'required|numeric',
            'tahun' => 'required|numeric|digits:4',
        ]);
        $validated['user_id'] = auth()->user()->id;
        // Simpan ke database
        Dithn::where('id', $dithn->id)->update($validated);

        return redirect('/dithn')->with('success', 'Data berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dithn $dithn)
    {
        $dithn->delete();
        return redirect('/dithn')->with('error', 'Data berhasil dihapus!');
    }
}
