<?php

use App\Models\Unit;

if (!function_exists('unitUsaha')) {
    function unitUsaha()
    {
        return auth()->user()->profil;
    }
}

if (!function_exists('namaUnitUsaha')) {
    function namaUnitUsaha()
    {
        $array_pendapatan = [
            'bno1' => 'BNO ' . 'Gaji Perusahaan',
            'bno2' => 'BNO ' . 'Atk',
            'bno3' => 'BNO ' . 'Rapat-rapat',
            'bno4' => 'BNO ' . 'Lain-lain',
            'kas' => 'kas',
        ];
        $units = Unit::user()->get();
        // Loop untuk setiap unit usaha
        foreach ($units as $unit) {
            $array_pendapatan['pu' . $unit->kode] = $unit->nm_unit;
        }
        // Loop untuk setiap unit usaha
        foreach ($units as $unit) {
            $array_pendapatan['bo' . $unit->kode] = $unit->nm_unit;
        }



        return $array_pendapatan;
    }
}
