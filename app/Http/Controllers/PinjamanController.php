<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;

use Illuminate\Http\Request;

class PinjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $pinjamans = Pinjaman::user()->get();
        $tunggakan = $pinjamans->sum('alokasi') - $pinjamans->sum('realisasi');
        $saldo_bunga = 0;
        foreach ($pinjamans as $pinjaman) {
            $bunga = $pinjaman->alokasi * ($pinjaman->bunga / 100);
            $total_bunga = $bunga * $pinjaman->angsuran;
            $saldo_bunga = $saldo_bunga + $total_bunga;
        }

        return view('pinjaman.index', [
            'pinjamans' => $pinjamans,
            'tunggakan' => $tunggakan,
            'saldo_bunga' => $saldo_bunga
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pinjaman.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'nasabah' => 'required|string|max:255',
            'tgl_pinjam' => 'required|date',
            'alokasi' => 'required|string|max:255',
            'bunga' => 'required|numeric|min:0|max:100',
        ]);

        $validate['user_id'] = auth()->user()->id;

        if (Pinjaman::create($validate)) {
            bukuUmum('Pinjaman ' . $request->nasabah, 'kredit', 'kas', 'operasional', $request->alokasi, 'pinjaman', Pinjaman::latest()->first()->id);
        };

        return redirect('/aset/pinjaman')->with('success', 'Pinjaman berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pinjaman $pinjaman)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pinjaman $pinjaman)
    {
        return view('pinjaman.edit', ['pinjaman' => $pinjaman]);
    }


    public function bayar(Request $request, Pinjaman $pinjaman)
    {


        if ($request->aksi == '+') {
            $realisasi = $pinjaman->realisasi +  $request->realisasi;
            $angsuran = $pinjaman->angsuran + 1;
        } elseif ($request->aksi == '-') {
            $realisasi = $pinjaman->realisasi -  $request->realisasi;
            $angsuran = $pinjaman->angsuran - 1;
        }

        $bunga = $pinjaman->alokasi * ($pinjaman->bunga / 100);

        bukuUmum('Bunga Storan ' . $pinjaman->nasabah, 'debit', 'pu3', 'operasional', $bunga, null, null);
        bukuUmum('Storan ' . $pinjaman->nasabah, 'debit', 'kas', 'operasional', $request->realisasi, null, null);


        Pinjaman::where('id', $pinjaman->id)->update(['realisasi' => $realisasi, 'angsuran' => $angsuran]);
        // Redirect with success message
        return redirect()->route('pinjaman.index')->with('success', 'pinjaman berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pinjaman $pinjaman)
    {
        $validate = $request->validate([
            'nasabah' => 'required|string|max:255',
            'tgl_pinjam' => 'required|date',
            'alokasi' => 'required|string|max:255',
            'bunga' => 'required|numeric|min:0|max:100',
        ]);

        if (Pinjaman::where('id', $pinjaman->id)->update($validate)) {
            updateBukuUmum('pinjaman', $pinjaman, $request->alokasi);
        };

        return redirect('/aset/pinjaman')->with('success', 'Pinjaman berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pinjaman $pinjaman)
    {
        $pinjaman->delete();

        return redirect('/aset/pinjaman')->with('error', 'Pinjaman berhasil dihapus');
    }
}
