<?php

if (!function_exists('unitUsaha')) {
    function unitUsaha()
    {
        return auth()->user()->profil;
    }
}
