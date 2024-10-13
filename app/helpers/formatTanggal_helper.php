<?php

if (!function_exists('formatTanggal')) {
    function formatTanggal($tanggal)
    {
        return date('d F Y', strtotime($tanggal));
    }
}
