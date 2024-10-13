<?php

use App\Models\Bukbesar;

if (!function_exists('formatRupiah')) {
    function formatRupiah($angka)
    {
        return 'Rp' . number_format($angka, 0, ',', '.');
    }
}

if (!function_exists('formatNomor')) {
    function formatNomor($angka)
    {
        return number_format($angka, 0, ',', '.');
    }
}
