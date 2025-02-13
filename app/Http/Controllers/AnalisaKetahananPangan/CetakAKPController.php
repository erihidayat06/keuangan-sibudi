<?php

namespace App\Http\Controllers\AnalisaKetahananPangan;

use App\Models\Akps;
use App\Models\Kebutuhan;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;

class CetakAKPController extends Controller
{
    public function export()
    {
        $profile = auth()->user()->profil;
        $akp = Akps::user()->whereyear('created_at', session('selected_year', date('Y')))->get()->first();


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


        $penjualans = Penjualan::user()->tahun()->get();


        $x = 0;
        $kategori = ['Bibit/ Benih', 'Sewa Tanah/Bangunan', 'Pengadaan Alat'];

        foreach ($kategori as $key) {
            foreach ($kebutuhan[$key] as $item) {
                $x += $item->harga * $item->jumlah * $item->volume;
            }
        }

        $y = 0;
        $kategori = ['Distribusi', 'Sarana Prasarana', 'Bahan Pemeliharaan', 'Pembiayaan-pembiayaan mingguan'];

        foreach ($kategori as $key) {
            foreach ($kebutuhan[$key] as $item) {
                $y += $item->harga * $item->jumlah * $item->volume;
            }
        }

        $z = 0;
        $kategori = ['Pekerja'];

        foreach ($kategori as $key) {
            foreach ($kebutuhan[$key] as $item) {
                $z += $item->harga * $item->jumlah * $item->volume;
            }
        }

        $total_pengeluaran = $x + $y + $z;
        $manual = $akp->alokasi;

        $total_pendapatan = 0;

        foreach ($penjualans as $penjualan) {
            $total_pendapatan += $penjualan->harga * $penjualan->jumlah;
        }


        $p1 = $manual;

        $q1 = $x + $y + $z;
        $q2 = 0.5 / 100 * $total_pendapatan + $q1;
        $persentase = $akp->pembiayaan / 100;
        $q3 = $x * (1 + $persentase) + $y * (1 + $persentase) + $z * (1 + $persentase);


        $kas_unit = $p1 - $q1;
        $unit_usaha = $total_pendapatan + (10 / 100 * $total_pendapatan);
        $p2 = $p1 - $q1 + $total_pendapatan;
        $p3 = $p2 - $q2 + $unit_usaha;

        $kas_bersih1 = $p1 - $q1;
        $kas_bersih2 = $p2 - $q2;
        $kas_bersih3 = $p3 - $q3;




        $data = [
            'profil' => $profile,
            'akp' => $akp,
            'kebutuhans' => $kebutuhan,
            'penjualans' => $penjualans,
            'total_pengeluaran' => $total_pengeluaran,
            'x' => $x,
            'y' => $y,
            'z' => $z,
            'p1' => $p1,
            'p2' => $p2,
            'p2' => $p2,
            'p3' => $p3,
            'q1' => $q1,
            'q2' => $q2,
            'q3' => $q3,
            'unit_usaha' => $unit_usaha,
            'kas_bersih1' => $kas_bersih1,
            'kas_bersih2' => $kas_bersih2,
            'kas_bersih3' => $kas_bersih3,
            'kas_unit' => $kas_unit,
            'manual' => $manual,
        ];
        // Generate PDF
        $pdf = PDF::loadView('akps.pdf', $data)->setPaper([0, 0, 595.276, 935.433], 'portrait');

        // Stream atau unduh PDF
        return $pdf->stream('akps.pdf');
    }
}
