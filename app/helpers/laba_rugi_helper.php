<?php

use App\Models\Unit;
use App\Models\Bdmuk;
use App\Models\Bangunan;
use App\Models\Investasi;
use App\Models\Aktivalain;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

if (!function_exists('labaRugi')) {
    function labaRugi($tahun_sekarang)
    {
        $userId = auth()->id();
        $unit_usaha = Unit::user()->get();

        // 1. Inisialisasi struktur prefix
        $jenis_lr_pu = [];
        $jenis_lr_bo = [];
        $jenis_lr_hpp = [];
        $jenis_lr_bno = [];

        $pendapatan = [];
        $totalPendapatanTahun = [];

        // Dynamic keys berdasarkan Unit Usaha
        foreach ($unit_usaha as $unit) {
            $kode = str_replace(' ', '', strtolower($unit->kode));

            $puKey  = 'pu' . $kode;
            $boKey  = 'bo' . $kode;
            $hppKey = 'hpp' . $kode;

            $jenis_lr_pu[]  = $puKey;
            $jenis_lr_bo[]  = $boKey;
            $jenis_lr_hpp[] = $hppKey;

            // Inisialisasi array per bulan (1-12) & total tahunan
            $pendapatan[$puKey]  = array_fill(1, 12, 0);
            $pendapatan[$boKey]  = array_fill(1, 12, 0);
            $pendapatan[$hppKey] = array_fill(1, 12, 0);

            $totalPendapatanTahun[$puKey]  = 0;
            $totalPendapatanTahun[$boKey]  = 0;
            $totalPendapatanTahun[$hppKey] = 0;
        }

        // Keys manual untuk 'bno' (1 - 5)
        for ($i = 1; $i <= 5; $i++) {
            $bnoKey = 'bno' . $i;
            $jenis_lr_bno[] = $bnoKey;

            $pendapatan[$bnoKey] = array_fill(1, 12, 0);
            $totalPendapatanTahun[$bnoKey] = 0;
        }

        // Struktur rekapitulasi bulanan
        $pendapatanBulan = [
            'pu'   => array_fill(1, 12, 0),
            'hpp'  => array_fill(1, 12, 0),
            'bo'   => array_fill(1, 12, 0),
            'bno'  => array_fill(1, 12, 0),
        ];

        $tahun = [
            'pu'   => 0,
            'hpp'  => 0,
            'bo'   => 0,
            'bno'  => 0,
        ];

        // 2. Ambil SEMUA transaksi kas/buku sekaligus dalam 1 QUERY
        $transaksis = DB::table('buks')
            ->where('user_id', $userId)
            ->whereYear('tanggal', $tahun_sekarang)
            ->get();

        // 3. Olah data transaksi di memori (PHP)
        foreach ($transaksis as $transaksi) {
            $month = Carbon::parse($transaksi->tanggal)->month;
            $jenis_lr = str_replace(' ', '', strtolower($transaksi->jenis_lr));
            $jenis = strtolower(trim($transaksi->jenis)); // Ambil kolom 'jenis' (debit/kredit/tetap)
            $nilai = (float) $transaksi->nilai;

            // KONDISI KHUSUS: Jika jenis_lr diawali 'bo' DAN kolom jenis 'tetap' -> Alihkan ke HPP
            if (str_starts_with($jenis_lr, 'bo') && $jenis == 'tetap') {
                $jenis_lr = 'hpp' . substr($jenis_lr, 2); // Mengubah bopd9876 -> hpppd9876
            }

            // Jika Kredit pada PU, mengurangi pendapatan
            if ($jenis == 'kredit' && str_starts_with($jenis_lr, 'pu')) {
                $nilai = -$nilai;
            }

            // Simpan ke detail akun ($pendapatan)
            if (isset($pendapatan[$jenis_lr])) {
                $pendapatan[$jenis_lr][$month] += $nilai;
                $totalPendapatanTahun[$jenis_lr] += $nilai;
            }

            // Simpan ke grup besar ($pendapatanBulan & $tahun)
            if (str_starts_with($jenis_lr, 'pu')) {
                $pendapatanBulan['pu'][$month] += $nilai;
                $tahun['pu'] += $nilai;
            } elseif (str_starts_with($jenis_lr, 'hpp')) {
                $pendapatanBulan['hpp'][$month] += $nilai;
                $tahun['hpp'] += $nilai;
            } elseif (str_starts_with($jenis_lr, 'bo')) {
                $pendapatanBulan['bo'][$month] += $nilai;
                $tahun['bo'] += $nilai;
            } elseif (str_starts_with($jenis_lr, 'bno')) {
                $pendapatanBulan['bno'][$month] += $nilai;
                $tahun['bno'] += $nilai;
            }
        }



        // 4. Hitung Penyusutan Aset
        $iventaris   = Investasi::user()->get();
        $bangunans   = Bangunan::user()->get();
        $bdmuks      = Bdmuk::user()->get();
        $aktiva_lain = Aktivalain::user()->get();

        $iventari = akumulasiPenyusutanIventasi($iventaris)['akumu'] ?? 0;
        $bangunan = akumulasiPenyusutan($bangunans)['akumu'] ?? 0;
        $bdmuk    = akumulasiPenyusutan($bdmuks)['akumu'] ?? 0;
        $aktiva   = akumulasiPenyusutan($aktiva_lain)['akumu'] ?? 0;

        $akumulasiPenyusutan = $iventari + $bangunan + $bdmuk + $aktiva;

        // 5. Kalkulasi Laba Kotor, Total Biaya, dan Laba/Rugi Bersih
        $labaKotor = [];
        $totalBiaya = [];
        $labaRugi = [];

        for ($m = 1; $m <= 12; $m++) {
            $pu  = $pendapatanBulan['pu'][$m];
            $hpp = $pendapatanBulan['hpp'][$m];
            $bo  = $pendapatanBulan['bo'][$m];
            $bno = $pendapatanBulan['bno'][$m];

            $labaKotor[$m]  = $pu - $hpp;
            $totalBiaya[$m] = $bo + $bno;
            $labaRugi[$m]   = $labaKotor[$m] - $totalBiaya[$m];
        }

        // Total Akumulasi Tahunan
        $akumulasiHpp       = $tahun['hpp'];
        $akumulasiLabaKotor = $tahun['pu'] - $akumulasiHpp;

        $akumulasiBiayaOperasional = $tahun['bo'] + $tahun['bno'];
        $totalBiayaPlusPenyusutan  = $akumulasiBiayaOperasional + $akumulasiPenyusutan;

        $totalLabaRugi = $akumulasiLabaKotor - $totalBiayaPlusPenyusutan;

        return [
            'pendapatan'           => $pendapatan,
            'pendapatanBulan'      => $pendapatanBulan,
            'pendapatanTahun'      => $tahun,
            'tahun'                => $totalPendapatanTahun,
            'labaKotor'            => $labaKotor,
            'totalBiaya'           => $totalBiaya,
            'akumulasiHpp'         => $akumulasiHpp,
            'akumulasiLabaKotor'   => $akumulasiLabaKotor,
            'akumulasiBiaya'       => $totalBiayaPlusPenyusutan,
            'labaRugi'             => $labaRugi,
            'totalLabaRugi'        => $totalLabaRugi,
            'akumulasi_penyusutan' => $akumulasiPenyusutan
        ];
    }
}

if (!function_exists('labaRugiTahun')) {
    function labaRugiTahun($tahun_sekarang)
    {
        $tahun_sekarang = strval($tahun_sekarang);
        $userId = auth()->id();
        $unit_usaha = DB::table('units')->where('user_id', $userId)->get();

        // 1. Inisialisasi struktur prefix
        $jenis_lr_pu = [];
        $jenis_lr_hpp = [];
        $jenis_lr_bo = [];
        $jenis_lr_bno = [];

        $pendapatan = [];
        $totalPendapatanTahun = [];

        foreach ($unit_usaha as $unit) {
            // Hilangkan spasi agar konsisten dengan panggilan di Blade
            $kode = str_replace(' ', '', strtolower($unit->kode));

            $puKey = 'pu' . $kode;
            $hppKey = 'hpp' . $kode;
            $boKey = 'bo' . $kode;

            $jenis_lr_pu[] = $puKey;
            $jenis_lr_hpp[] = $hppKey;
            $jenis_lr_bo[] = $boKey;

            $pendapatan[$puKey] = array_fill(1, 12, 0);
            $pendapatan[$hppKey] = array_fill(1, 12, 0);
            $pendapatan[$boKey] = array_fill(1, 12, 0);

            $totalPendapatanTahun[$puKey] = 0;
            $totalPendapatanTahun[$hppKey] = 0;
            $totalPendapatanTahun[$boKey] = 0;
        }

        for ($i = 1; $i <= 5; $i++) {
            $bnoKey = 'bno' . $i;
            $jenis_lr_bno[] = $bnoKey;

            $pendapatan[$bnoKey] = array_fill(1, 12, 0);
            $totalPendapatanTahun[$bnoKey] = 0;
        }

        $pendapatanBulan = [
            'pu'   => array_fill(1, 12, 0),
            'hpp'  => array_fill(1, 12, 0),
            'bo'   => array_fill(1, 12, 0),
            'bno'  => array_fill(1, 12, 0),
        ];

        $tahun = [
            'pu'   => 0,
            'hpp'  => 0,
            'bo'   => 0,
            'bno'  => 0,
        ];

        // 2. Query Transaksi Buku Kas
        $transaksis = DB::table('buks')
            ->where('user_id', $userId)
            ->whereYear('tanggal', $tahun_sekarang)
            ->get();

        // 3. Olah Transaksi
        foreach ($transaksis as $transaksi) {
            $month = Carbon::parse($transaksi->tanggal)->month;
            $jenis_lr = str_replace(' ', '', strtolower($transaksi->jenis_lr));
            $nilai = (float) $transaksi->nilai;

            // Transaksi Kredit mengurangi nilai (retur/potongan/pengembalian)
            if ($transaksi->jenis == 'kredit') {
                $nilai = -$nilai;
            }

            if (isset($pendapatan[$jenis_lr])) {
                $pendapatan[$jenis_lr][$month] += $nilai;
                $totalPendapatanTahun[$jenis_lr] += $nilai;
            }

            if (in_array($jenis_lr, $jenis_lr_pu)) {
                $pendapatanBulan['pu'][$month] += $nilai;
                $tahun['pu'] += $nilai;
            } elseif (in_array($jenis_lr, $jenis_lr_hpp)) {
                $pendapatanBulan['hpp'][$month] += $nilai;
                $tahun['hpp'] += $nilai;
            } elseif (in_array($jenis_lr, $jenis_lr_bo)) {
                $pendapatanBulan['bo'][$month] += $nilai;
                $tahun['bo'] += $nilai;
            } elseif (in_array($jenis_lr, $jenis_lr_bno)) {
                $pendapatanBulan['bno'][$month] += $nilai;
                $tahun['bno'] += $nilai;
            }
        }

        // 4. Hitung Penyusutan Aset
        $iventaris = DB::table('investasis')
            ->where('user_id', $userId)
            ->whereYear('tgl_beli', '<=', $tahun_sekarang)
            ->get();

        $bangunans = DB::table('bangunans')
            ->where('user_id', $userId)
            ->whereYear('created_at', '<=', $tahun_sekarang)
            ->get();

        $bdmuks = DB::table('bdmuks')
            ->where('user_id', $userId)
            ->whereYear('created_at', '<=', $tahun_sekarang)
            ->get();

        $aktiva_lain = DB::table('aktivalains')
            ->where('user_id', $userId)
            ->whereYear('created_at', '<=', $tahun_sekarang)
            ->get();

        $iventari = akumulasiPenyusutanIventasiTahun($iventaris)['akumu'] ?? 0;
        $bangunan = akumulasiPenyusutanTahun($bangunans)['akumu'] ?? 0;
        $bdmuk    = akumulasiPenyusutanTahun($bdmuks)['akumu'] ?? 0;
        $aktiva   = akumulasiPenyusutanTahun($aktiva_lain)['akumu'] ?? 0;

        $akumulasiPenyusutan = $iventari + $bangunan + $bdmuk + $aktiva;
        $penyusutanBulan = $akumulasiPenyusutan / 12;

        // 5. Kalkulasi Akhir
        $labaKotor = [];
        $totalBiaya = [];
        $labaRugi = [];

        for ($m = 1; $m <= 12; $m++) {
            $pu  = $pendapatanBulan['pu'][$m];
            $hpp = $pendapatanBulan['hpp'][$m];
            $bo  = $pendapatanBulan['bo'][$m];
            $bno = $pendapatanBulan['bno'][$m];

            $labaKotor[$m] = $pu - $hpp;
            $totalBiaya[$m] = $bo + $bno + $penyusutanBulan;
            $labaRugi[$m] = $labaKotor[$m] - $totalBiaya[$m];
        }

        $akumulasiHpp = array_sum($pendapatanBulan['hpp']);
        $akumulasiLabaKotor = $tahun['pu'] - $akumulasiHpp;

        $akumulasiBiayaOperasional = array_sum($pendapatanBulan['bo']) + array_sum($pendapatanBulan['bno']);
        $totalBiayaPlusPenyusutan = $akumulasiBiayaOperasional + $akumulasiPenyusutan;

        $totalLabaRugi = $akumulasiLabaKotor - $totalBiayaPlusPenyusutan;

        return [
            'pendapatan'           => $pendapatan,
            'pendapatanBulan'      => $pendapatanBulan,
            'pendapatanTahun'      => $tahun,
            'tahun'                => $totalPendapatanTahun,
            'labaKotor'            => $labaKotor,
            'totalBiaya'           => $totalBiaya,
            'akumulasiHpp'         => $akumulasiHpp,
            'akumulasiLabaKotor'   => $akumulasiLabaKotor,
            'akumulasiBiaya'       => $totalBiayaPlusPenyusutan,
            'labaRugi'             => $labaRugi,
            'totalLabaRugi'        => $totalLabaRugi,
            'akumulasi_penyusutan' => $akumulasiPenyusutan
        ];
    }
}
