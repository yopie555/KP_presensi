<?php

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'presensi';

$connection = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$connection) {
    echo "Koneksi database gagal: " . mysqli_connect_error();
    exit();
}

function base_url($url = null)
{
    $base_url = 'http://localhost/presensi';
    if ($url != null) {
        return $base_url . "/".$url;
    }
    return $base_url;
}

