<?php
session_start();
ob_start();
if (!isset($_SESSION['login'])) {
    header('Location: ../../auth/login.php?pesan=belum_login');
    exit();
} else if ($_SESSION['role'] != 'admin') {
    header('Location: ../../auth/login.php?pesan=tolak_akses');
    exit();
}

require_once('../../config.php');

$tanggal_dari = $_POST['tanggal_dari'];
$tanggal_sampai = $_POST['tanggal_sampai'];
$result = mysqli_query($connection, "SELECT presensi.*, pegawai.nama, pegawai.lokasi_presensi, pegawai.nip FROM presensi JOIN pegawai ON presensi.id_pegawai = pegawai.id WHERE tanggal_masuk BETWEEN '$tanggal_dari' AND '$tanggal_sampai' ORDER BY tanggal_masuk DESC limit 10") or die(mysqli_error($connection));

$file = "Rekap Presensi Harian.xls";

$test = "<table border='1'>
<span class='text-center'> Rekap Presensi Harian </span>
<br>
<span>Rekap Presensi Tanggal: " . date('d F Y', strtotime($tanggal_dari)) . " s/d " . date('d F Y', strtotime($tanggal_sampai)) . "</span>
<tr class='text-center'>
<th>NO</th>
<th>NAMA</th>
<th>NIP</th>
<th>TANGGAL MASUK</th>
<th>JAM MASUK</th>
<th>TANGGAL KELUAR</th>
<th>JAM KELUAR</th>
<th>TOTAL JAM KERJA</th>
<th>TOTAL JAM TERLAMBAT</th>
</tr>";

$no = 1;
while ($data = mysqli_fetch_array($result)) {
    //menghitung total jam kerja
    $jam_tanggal_masuk = date('Y-m-d H:i:s', strtotime($rekap['tanggal_masuk'] . ' ' . $rekap['jam_masuk']));
    $jam_tanggal_keluar = date('Y-m-d H:i:s', strtotime($rekap['tanggal_keluar'] . ' ' . $rekap['jam_keluar']));

    $timestamp_masuk = strtotime($jam_tanggal_masuk);
    $timestamp_keluar = strtotime($jam_tanggal_keluar);

    $selisih = $timestamp_keluar - $timestamp_masuk;

    $total_jam_kerja = floor($selisih / 3600);
    $selisih -= $total_jam_kerja * 3600;
    $selisih_menit_kerja = floor($selisih / 60);

    //menghitung total terlambat
    $lokasi_presensi = $rekap['lokasi_presensi'];
    $lokasi =  mysqli_query($connection, "SELECT * FROM lokasi_presensi WHERE nama_lokasi = '$lokasi_presensi'") or die(mysqli_error($connection));

    while ($lokasi_result = mysqli_fetch_array($lokasi)) {
        $jam_masuk_kantor = date('H i s', strtotime($lokasi_result['jam_masuk']));
    }

    $jam_masuk = date('H i s', strtotime($rekap['jam_masuk']));
    $timestamp_jam_masuk_real = strtotime($jam_masuk);
    $timestamp_jam_masuk_kantor = strtotime($jam_masuk_kantor);

    $terlambat = $timestamp_jam_masuk_real - $timestamp_jam_masuk_kantor;
    $total_jam_terlambat = floor($terlambat / 3600);
    $terlambat -= $total_jam_terlambat * 3600;
    $selisih_menit_terlambat = floor($terlambat / 60);

    $test .= "<tr class='text-center'>";
    $test .= "<td>" . $no . "</td>";
    $test .= "<td>" . $data['nama'] . "</td>";
    $test .= "<td>" . $data['nip'] . "</td>";
    $test .= "<td>" . $data['tanggal_masuk'] . "</td>";
    $test .= "<td>" . $data['jam_masuk'] . "</td>";
    $test .= "<td>" . $data['tanggal_keluar'] . "</td>";
    $test .= "<td>" . $data['jam_keluar'] . "</td>";
    $test .= "<td>" . $total_jam_kerja . " jam " . $selisih_menit_kerja . " menit" . "</td>";
    $test .= "<td>" . $total_jam_terlambat . " jam " . $selisih_menit_terlambat . " menit" . "</td>";
    $test .= "</tr>";
    $no++;
}

$test .= "</table>";

echo $test;


header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$file");
