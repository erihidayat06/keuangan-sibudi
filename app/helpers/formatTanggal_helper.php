<?php

if (!function_exists('formatTanggal')) {
    function formatTanggal($tanggal)
    {
        return date('d_F_Y', strtotime($tanggal));
    }
}

if (!function_exists('tanggal')) {
    function tanggal($tanggal)
    {
        return date('Y-m-d', strtotime($tanggal));
    }
}

if (!function_exists('masaPakai')) {
    function masaPakai($tahun_beli, $masa_ekomomis)
    {
        $currentYear = session('selected_year', date('Y'));
        $tahunBeli = date('Y', strtotime($tahun_beli));

        // Mendapatkan bulan sekarang
        $currentMonth = date('m');

        // Menghitung selisih tahun
        $selisih = $currentYear - $tahunBeli;
        $tahun = $currentYear - $tahunBeli;

        // Jika bulan berada di luar Januari sampai April, tambahkan 1
        if ($currentMonth > 4) {
            $selisih += 1;
        }

        $masa_pakai = 0;

        if ($selisih > $masa_ekomomis) {
            $masa_pakai = $masa_ekomomis;
        } elseif ($selisih >= 0) {
            $masa_pakai = $selisih;
        }

        return ['masa_pakai' => $masa_pakai, 'tahun' => $tahun];
    }
}



if (!function_exists('created_at')) {
    function created_at()
    {
        $date = new DateTime(); // Menggunakan tanggal saat ini

        // Mengatur tahun berdasarkan session atau default tahun saat ini
        $date->setDate(session('selected_year', date('Y')), $date->format('m'), $date->format('d'));

        // Mengembalikan dalam format timestamps
        return $date->format('Y-m-d H:i:s'); // Menghasilkan timestamps dari tanggal yang diubah
    }
}
