<?php

use App\Models\Buk;

if (!function_exists('bukuUmum')) {
    function bukuUmum($transaksi, $jenis, $jenis_lr, $jenis_dana, $nilai,  $akun, $id_akun)
    {
        $user_id = auth()->user()->id;
        $tanggal = new DateTime(date('Y-m-d'));
        $tanggal->setDate(session('selected_year', date('Y')), $tanggal->format('m'), $tanggal->format('d'));

        return Buk::create(['transaksi' => $transaksi, 'jenis' => $jenis, 'jenis_lr' => $jenis_lr, 'jenis_dana' => $jenis_dana, 'nilai' => $nilai, 'user_id' => $user_id, 'akun' => $akun, 'id_akun' => $id_akun, 'tanggal' => $tanggal->format('Y-m-d')]);
    }
}
