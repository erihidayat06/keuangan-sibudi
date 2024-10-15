<?php

if (!function_exists('unitUsaha')) {
    function unitUsaha()
    {
        return auth()->user()->profil;
    }
}

if (!function_exists('namaUnitUsaha')) {
    function namaUnitUsaha()
    {
        return [
            'pu1' => 'PU ' . unitUsaha()['unt_usaha1'],
            'pu2' => 'PU ' .  unitUsaha()['unt_usaha2'],
            'pu3' => 'PU ' .  unitUsaha()['unt_usaha3'],
            'pu4' => 'PU ' .  unitUsaha()['unt_usaha4'],
            'bo1' => 'BO ' . unitUsaha()['unt_usaha1'],
            'bo2' => 'BO ' . unitUsaha()['unt_usaha2'],
            'bo3' => 'BO ' . unitUsaha()['unt_usaha3'],
            'bo4' => 'BO ' . unitUsaha()['unt_usaha4'],
            'bno1' => 'BNO ' . 'Gaji Perusahaan',
            'bno2' => 'BNO ' . 'Atk',
            'bno3' => 'BNO ' . 'Rapat-rapat',
            'bno4' => 'BNO ' . 'Lain-lain',
            'kas' => 'kas',
        ];
    }
}
