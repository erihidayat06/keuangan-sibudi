<?php

use App\Models\Buk;

if (!function_exists('updateBukuUmum')) {
    function updateBukuUmum($akun, $id_akun, $nilai)
    {
        return
            Buk::where('akun', $akun)->where('id_akun', $id_akun)->update(['nilai' => $nilai]);
    }
}
