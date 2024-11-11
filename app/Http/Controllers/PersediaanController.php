<?php

namespace App\Http\Controllers;

use App\Models\Buk;
use App\Models\Unit;
use App\Models\Persediaan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PersediaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangs = Persediaan::user()->get();

        $total_nilai_awal = 0;
        $total_nilai_akhir = 0;
        $total_laba = 0;
        foreach ($barangs as $barang) {
            $nilai_awal = $barang->jml_awl * $barang->hpp;
            $total_nilai_awal = $total_nilai_awal + $nilai_awal;

            $jumlah_akhir = $barang->jml_awl - ($barang->masuk - $barang->keluar);

            $nilai_akhir = $jumlah_akhir * $barang->hpp;
            $total_nilai_akhir = $total_nilai_akhir + $nilai_akhir;

            $laba = ($barang->jml_awl - $jumlah_akhir) * ($barang->nilai_jual - $barang->hpp);
            $total_laba = $laba + $total_laba;
        }
        $unit = Unit::user()->where('kode', 'pd9876')->get()->first();
        if (!isset($unit->kode)) {
            return view('persediaan.unit');
        } else {
            return view('persediaan.index', [
                'barangs' => $barangs,
                'total_nilai_awal' => $total_nilai_awal,
                'total_nilai_akhir' => $total_nilai_akhir,
                'total_laba' => $total_laba,
            ]);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function storeUnit(Request $request)
    {
        $validated =  $request->validate([
            'nm_unit' => 'required|string|max:255',
            'kepala_unit' => 'required|string|max:255',
            'kode' => 'required',
        ]);

        $validated['user_id'] = auth()->user()->id;


        Unit::create($validated);

        return redirect('/aset/persediaan')->with('success', 'Unit berhasil ditambahkan!');
    }


    public function exportPdf()
    {

        $barangs = Persediaan::user()->get();

        $total_nilai_awal = 0;
        $total_nilai_akhir = 0;
        $total_laba = 0;
        foreach ($barangs as $barang) {
            $nilai_awal = $barang->jml_awl * $barang->hpp;
            $total_nilai_awal = $total_nilai_awal + $nilai_awal;

            $jumlah_akhir = $barang->jml_awl - ($barang->masuk - $barang->keluar);

            $nilai_akhir = $jumlah_akhir * $barang->hpp;
            $total_nilai_akhir = $total_nilai_akhir + $nilai_akhir;

            $laba = ($barang->jml_awl - $jumlah_akhir) * ($barang->nilai_jual - $barang->hpp);
            $total_laba = $laba + $total_laba;
        }
        $data =
            [
                'barangs' => $barangs,
                'total_nilai_awal' => $total_nilai_awal,
                'total_nilai_akhir' => $total_nilai_akhir,
                'total_laba' => $total_laba,
            ];

        // Gunakan facade PDF
        $pdf = PDF::loadView('persediaan.pdf', $data)->setPaper('f4', 'portrait');

        // Mengunduh PDF dengan nama "laporan.pdf"
        return $pdf->stream('laporan.pdf');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('persediaan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'item' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'hpp' => 'required|numeric|min:0',
            'nilai_jual' => 'required|numeric|min:0',
            'jml_awl' => 'required|integer|min:0',
        ]);

        $validated['user_id'] = auth()->user()->id;
        $validated['created_at'] = $request->created_at;

        $total_harga = $validated['hpp'] * $validated['jml_awl'];

        if (Persediaan::create($validated)) {
            bukuUmum('Persediaan ' . $request->item, 'kredit', 'kas', 'operasional', $total_harga, 'persediaan', Persediaan::latest()->first()->id, $request->created_at);
        };
        return redirect('/aset/persediaan')->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Persediaan $persediaan)
    {
        //
    }



    /**
     * penjualan the specified resource in storage.
     */
    public function penjualan(Request $request, Persediaan $persediaan)
    {
        if (isset($request->masuk) && $request->masuk != $persediaan->masuk) {

            $transasksi = 'Jual ' . $request->masuk - $persediaan->masuk . ' ' . $persediaan->item;
            $laba = ($request->masuk - $persediaan->masuk) * ($persediaan->nilai_jual - $persediaan->hpp);

            $masuk = $request->masuk - $persediaan->masuk;

            $year = session('selected_year', date('Y'));
            $tanggal = date('Y-m-d', strtotime($year . date('-m-d')));

            bukuUmum($transasksi, 'debit', 'pupd9876', 'operasional', $laba, null, null, $tanggal);
            bukuUmum($transasksi, 'debit', 'kas', 'operasional', $persediaan->hpp * $masuk, null, null, $tanggal);
            Persediaan::where('id', $persediaan->id)->update(['masuk' => $request->masuk]);
        }
        if (isset($request->keluar) && $request->keluar != $persediaan->keluar) {
            $transasksi = 'Kembalikan ' . $request->keluar - $persediaan->keluar . ' ' . $persediaan->item;
            $laba = ($request->keluar - $persediaan->keluar) * ($persediaan->nilai_jual - $persediaan->hpp);
            $keluar = $request->keluar - $persediaan->keluar;
            bukuUmum($transasksi, 'kredit', 'kas', 'operasional', $persediaan->hpp * $keluar, null, null, $tanggal);
            Persediaan::where('id', $persediaan->id)->update(['keluar' => $request->keluar]);
        }
        return redirect()->back()->with('success', 'Data Berhasil dirubah!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Persediaan $persediaan)
    {
        return view('persediaan.edit', ['barang' => $persediaan]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Persediaan $persediaan)
    {

        $validated = $request->validate([
            'item' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'hpp' => 'required|numeric|min:0',
            'nilai_jual' => 'required|numeric|min:0',
            'jml_awl' => 'required|min:0',
            'masuk' => '',
            'keluar' => '',
        ]);

        if (Persediaan::where('id', $persediaan->id)->update($validated)) {
            updateBukuUmum('persediaan', $persediaan->id, $request->jml_awl * $request->hpp);
        };
        return redirect('/aset/persediaan')->with('success', 'Barang berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Persediaan $persediaan)
    {
        // $nilai

        // Buk::create(['transaksi' => $persediaan->item, 'jenis' => 'kredit', 'nilai' => , 'user_id' => $user_id]);
        Persediaan::where('id', $persediaan->id)->delete();

        return redirect('/aset/persediaan')->with('error', 'Barang berhasil dihapus!');
    }
}
