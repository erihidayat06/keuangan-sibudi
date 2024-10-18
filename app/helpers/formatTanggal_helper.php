<?php

if (!function_exists('formatTanggal')) {
    function formatTanggal($tanggal)
    {
        return date('d_F_Y', strtotime($tanggal));
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
