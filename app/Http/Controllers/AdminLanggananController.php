<?php

namespace App\Http\Controllers;

use App\Models\Langganan;
use Illuminate\Http\Request;

class AdminLanggananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $langganans = Langganan::orderBy('jumlah_bulan', 'asc')->get();
        return view('admin.langganan.index', ['langganans' => $langganans]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.langganan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated =  $request->validate([
            'jumlah_bulan' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:1',
            'waktu' => 'required',
        ]);

        Langganan::create($validated);

        return redirect('/admin/langganan')->with('success', 'Langganan Berhasil di tambah');
    }

    /**
     * Display the specified resource.
     */
    public function show(Langganan $langganan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Langganan $langganan)
    {

        return view('admin.langganan.edit', ['data' => $langganan]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Langganan $langganan)
    {
        $validated =    $request->validate([
            'jumlah_bulan' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'waktu' => 'required|string',
        ]);

        $validated['harga'] = str_replace('.', '', $request->harga);

        Langganan::where('id', $langganan->id)->update($validated);

        return redirect('/admin/langganan')->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Langganan $langganan)
    {
        $langganan->delete();

        return redirect('/admin/langganan')->with('error', 'Data berhasil dihapus');
    }
}