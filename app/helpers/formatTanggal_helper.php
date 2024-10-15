<?php

if (!function_exists('formatTanggal')) {
    function formatTanggal($tanggal)
    {
        return date('d_F_Y', strtotime($tanggal));
    }
}
