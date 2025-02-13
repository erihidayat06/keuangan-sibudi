<?php

namespace App\Http\Controllers\AnalisaKetahananPangan;

use App\Models\Akps;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Kebutuhan;
use App\Models\Penjualan;


class AkpsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $akp = Akps::user()->whereyear('created_at', session('selected_year', date('Y')))->get()->first();

        $created_at = Carbon::now()->year(session('selected_year', date('Y')));
        if ($akp == null) {
            Akps::create(['user_id' => auth()->user()->id, 'created_at' => $created_at]);
        }


        $kebutuhan_create = Kebutuhan::user()->whereyear('created_at', session('selected_year', date('Y')))->get()->first();

        $created_at = Carbon::now()->year(session('selected_year', date('Y')));
        if ($kebutuhan_create == null) {
            $data = [
                ['user_id' => auth()->user()->id, 'created_at' => $created_at, 'uraian' => 'Biaya Sewa Tanah', 'kategori' => 'Sewa Tanah/Bangunan', 'jumlah' => 1],
                ['user_id' => auth()->user()->id, 'created_at' => $created_at, 'uraian' => 'Biaya Sewa Bangunan', 'kategori' => 'Sewa Tanah/Bangunan', 'jumlah' => 1],
                ['user_id' => auth()->user()->id, 'created_at' => $created_at, 'uraian' => 'Transportasi hasil panen', 'kategori' => 'Distribusi', 'jumlah' => null],
                ['user_id' => auth()->user()->id, 'created_at' => $created_at, 'uraian' => 'Perbaikan dan pemeliharaan', 'kategori' => 'Sarana Prasarana', 'jumlah' => null],
                ['user_id' => auth()->user()->id, 'created_at' => $created_at, 'uraian' => 'Pelatihan pemberdayaan masyarakat', 'kategori' => 'Pekerja', 'jumlah' => null],
                ['user_id' => auth()->user()->id, 'created_at' => $created_at, 'uraian' => 'Tenaga Kerja', 'kategori' => 'Pekerja', 'jumlah' => null],
                ['user_id' => auth()->user()->id, 'created_at' => $created_at, 'uraian' => 'Pembelian Pupuk', 'kategori' => 'Bahan Pemeliharaan', 'jumlah' => null],
            ];
            Kebutuhan::insert($data);
        }


        $penjualan = Penjualan::user()->tahun()->get();

        $kebutuhans =  [
            'Sewa Tanah/Bangunan',
            'Sewa Alat',
            'Pengadaan Alat',
            'Sarana Prasarana',
            'Bibit/ Benih',
            'Bahan Pemeliharaan',
            'Pembiayaan-pembiayaan mingguan',
            'Pekerja',
            'Distribusi'
        ];

        foreach ($kebutuhans as $index => $value) {
            $kebutuhan[$value] = Kebutuhan::user()->kategori($value)->tahun()->get();
        };




        $title = 'ANALISA KETAHANAN PANGAN';
        return view('akps.index', [
            'title' => $title,
            'akp' => $akp,
            'penjualans' => $penjualan,
            'kebutuhans' => $kebutuhan
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Akps $akps)
    {
        $validate = $request->validate([
            'status' => 'required|string',
            'dana' => 'required|numeric',
            'alokasi' => 'required|numeric',
            'tematik' => 'required|string',
            'pendapatan' => 'required|string',
            'pembiayaan' => 'required|string',
        ]);


        Akps::where('id', $akps->id)->update($validate);

        return redirect()->back()->with('success', 'Berhasil diupdate');
    }
}
