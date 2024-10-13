<?php

use App\Models\Buk;
use App\Models\Investasi;


if (!function_exists('labaRugi')) {
    function labaRugi()
    {

        // Inisialisasi array untuk setiap jenis_lr
        $pendapatan = [
            'pu1' => [],
            'pu2' => [],
            'pu3' => [],
            'pu4' => [],
            'bo1' => [],
            'bo2' => [],
            'bo3' => [],
            'bo4' => [],
            'bno1' => [],
            'bno2' => [],
            'bno3' => [],
            'bno4' => [],
            'bno5' => [],
        ];

        // Inisialisasi variabel untuk akumulasi tahunan
        $totalPendapatanTahun = [
            'pu1' => 0,
            'pu2' => 0,
            'pu3' => 0,
            'pu4' => 0,
            'bo1' => 0,
            'bo2' => 0,
            'bo3' => 0,
            'bo4' => 0,
            'bno1' => 0,
            'bno2' => 0,
            'bno3' => 0,
            'bno4' => 0,
            'bno5' => 0,
        ];

        // Loop untuk setiap bulan (1 - 12)
        for ($i = 1; $i <= 12; $i++) {
            // Loop untuk setiap jenis_lr
            foreach (array_keys($pendapatan) as $jenis_lr) {
                // Ambil data berdasarkan bulan, tahun, dan jenis_lr
                $buku_kas = Buk::where('user_id', auth()->id()) // Filter berdasarkan user
                    ->whereYear('created_at', '2024') // Filter berdasarkan tahun
                    ->whereMonth('created_at', $i) // Filter berdasarkan bulan
                    ->where('jenis_lr', $jenis_lr) // Filter berdasarkan jenis_lr
                    ->get();

                // Variabel untuk menampung total nilai per bulan
                $totalNilai = 0;

                // Loop untuk setiap transaksi dalam bulan tersebut
                foreach ($buku_kas as $transaksi) {
                    if ($transaksi->jenis == 'debit') {
                        // Jika debit, tambahkan nilainya
                        $totalNilai += $transaksi->nilai;
                    } elseif ($transaksi->jenis == 'kredit' and in_array($jenis_lr, ['pu1', 'pu2', 'pu3', 'pu4'])) {
                        // Jika kredit, kurangi nilainya
                        $totalNilai -= $transaksi->nilai;
                    } else {
                        $totalNilai += $transaksi->nilai;
                    }
                }

                // Simpan total pendapatan per bulan untuk jenis_lr tertentu
                $pendapatan[$jenis_lr][$i] = $totalNilai;

                // Akumulasi total tahunan untuk jenis_lr tersebut
                $totalPendapatanTahun[$jenis_lr] += $totalNilai;
            }
        }


        // Array untuk menyimpan hasil per bulan dan per jenis lr
        $pendapatanBulan = [
            'pu' => array_fill(1, 12, 0),
            'bo' => array_fill(1, 12, 0),
            'bno' => array_fill(1, 12, 0),
        ];

        // Total pendapatanBulan tahunan
        $tahun = [
            'pu' => 0,
            'bo' => 0,
            'bno' => 0,
        ];

        // Mengambil semua transaksi dalam tahun yang dipilih
        $transaksis = Buk::whereYear('created_at', '2024')->get();

        foreach ($transaksis as $transaksi) {
            $month = $transaksi->created_at->month;
            $jenis_lr = strtolower($transaksi->jenis_lr); // Mengkonversi ke lowercase untuk kemudahan
            $nilai = $transaksi->nilai;

            // Jika jenis_lr adalah kredit, nilai akan negatif
            if ($transaksi->jenis == 'kredit' && in_array($jenis_lr, ['pu1', 'pu2', 'pu3', 'pu4'])) {
                $nilai = -$nilai;
            }

            // Menambah nilai transaksi ke bulan dan jenis_lr yang sesuai
            if (in_array($jenis_lr, ['pu1', 'pu2', 'pu3', 'pu4'])) {
                $pendapatanBulan['pu'][$month] += $nilai;
                $tahun['pu'] += $nilai;
            } elseif (in_array($jenis_lr, ['bo1', 'bo2', 'bo3', 'bo4'])) {
                $pendapatanBulan['bo'][$month] += $nilai;
                $tahun['bo'] += $nilai;
            } elseif (in_array($jenis_lr, ['bno1', 'bno2', 'bno3', 'bno4', 'bno5'])) {
                $pendapatanBulan['bno'][$month] += $nilai;
                $tahun['bno'] += $nilai;
            }
        }
        $asets = Investasi::user()->get();

        $akumulasi = 0;
        $investasi = 0;
        foreach ($asets as $aset) {
            $penyusutan = $aset->nilai / $aset->wkt_ekonomis * $aset->masa_pakai;
            $saat_ini =
                $aset->jumlah * $aset->nilai - $aset->masa_pakai * $penyusutan * $aset->jumlah;

            $akumulasi = $akumulasi + $penyusutan;
            $investasi = $investasi + $saat_ini;
        }




        $totalBiaya = [];
        $labaRugi = [];


        foreach ($pendapatanBulan['bo'] as $key => $value) {
            $totalBiaya[] = $pendapatanBulan['bno'][$key] + $value;
            $labaRugi[] = $pendapatanBulan['pu'][$key] - ($pendapatanBulan['bno'][$key] + $value);
        }

        $akumulasitotalBiaya = array_sum($pendapatanBulan['bo']) + array_sum($pendapatanBulan['bno']);
        $akumulasilabaRugi = array_sum($pendapatanBulan['pu']) - (array_sum($pendapatanBulan['bo']) + array_sum($pendapatanBulan['bno']) + $akumulasi);



        $akumulasiBiaya = $akumulasitotalBiaya + $akumulasi;
        $totalLabaRugi = $akumulasilabaRugi;

        return ([
            'pendapatan' => $pendapatan,
            'pendapatanBulan' => $pendapatanBulan,
            'pendapatanTahun' => $tahun,
            'tahun' => $totalPendapatanTahun,
            'totalBiaya' => $totalBiaya,
            'akumulasiBiaya' => $akumulasiBiaya,
            'labaRugi' => $labaRugi,
            'totalLabaRugi' => $totalLabaRugi,
            'akumulasi_penyusutan' => $akumulasi
        ]);
    }
}
