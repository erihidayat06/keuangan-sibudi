<?php

use App\Models\Bukbesar;

if (!function_exists('bukuBesar')) {
    function bukuBesar($nama_akun, $debit = null, $kredit = null, $keterangan)
    {
        return Bukbesar::create(['nama_akun' => $nama_akun, 'debit' => $debit, 'kredit' => $kredit, 'keterangan' => $keterangan]);
    }
}
