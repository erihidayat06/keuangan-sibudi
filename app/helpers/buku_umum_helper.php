<?php

use App\Models\Buk;

if (!function_exists('bukuUmum')) {
    function bukuUmum($transaksi, $jenis, $jenis_lr, $jenis_dana, $nilai,  $akun, $id_akun, $tanggal)
    {
        $user_id = auth()->user()->id;

        return Buk::create(['transaksi' => $transaksi, 'jenis' => $jenis, 'jenis_lr' => $jenis_lr, 'jenis_dana' => $jenis_dana, 'nilai' => $nilai, 'user_id' => $user_id, 'akun' => $akun, 'id_akun' => $id_akun, 'tanggal' => $tanggal]);
    }
}
