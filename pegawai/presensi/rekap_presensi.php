<?php
ob_start();
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: ../../auth/login.php?pesan=belum_login');
    exit();
} else {
    if ($_SESSION['role'] != 'pegawai') {
        header('Location: ../../auth/login.php?pesan=tolak_akses');
        exit();
    }
}

include('../../pegawai/layout/header.php');
include_once("../../config.php");

$id = $_SESSION['id'];
$result = mysqli_query($connection, "SELECT * FROM presensi WHERE id_pegawai = '$id' ORDER BY tanggal_masuk DESC");
$lokasi_presensi = $_SESSION['lokasi_presensi'];
$lokasi = mysqli_query($connection, "SELECT * FROM lokasi_presensi WHERE nama_lokasi= '$lokasi_presensi'");

while ($lokasi_result = mysqli_fetch_array($lokasi)) :
    $jam_masuk_kantor = date('H:i:s', strtotime($lokasi_result['jam_masuk']));
endwhile;
?>

<div class="page-body">
    <div class="container-xl">
        <table class="table table-bordered">
            <tr class="text-center">
                <th>No.</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Total Jam</th>
                <th>Total Terlambat</th>
            </tr>

            <?php
            $no = 1;
            while ($rekap = mysqli_fetch_array($result)) :
                // Menghitung total jam kerja
                $jam_tanggal_masuk = date('Y-m-d H:i:s', strtotime($rekap['tanggal_masuk'] . ' ' . $rekap['jam_masuk']));
                $jam_tanggal_keluar = date('Y-m-d H:i:s', strtotime($rekap['tanggal_keluar'] . ' ' . $rekap['jam_keluar']));

                $timstamp_masuk = strtotime($jam_tanggal_masuk);
                $timstamp_keluar = strtotime($jam_tanggal_keluar);

                $selisih = $timstamp_keluar - $timstamp_masuk;
                $total_jam_kerja = floor($selisih / 3600);
                $selisih -= $total_jam_kerja * 3600;
                $selisih_menit_kerja = floor($selisih / 60);

                // Menghitung total terlambat
                $jam_masuk = date('H:i:s', strtotime($rekap['jam_masuk']));
                $timestamp_jam_masuk_real = strtotime($jam_masuk);
                $timestamp_jam_masuk_kantor = strtotime($jam_masuk_kantor);

                $terlambat = $timestamp_jam_masuk_real - $timestamp_jam_masuk_kantor;
                $total_jam_terlambat = floor($terlambat / 3600);
                $terlambat -= $total_jam_terlambat * 3600;
                $selisih_menit_terlambat = floor($terlambat / 60);
            ?>

                <tr class="text-center">
                    <td><?= $no++; ?></td>
                    <td><?= date('d F Y', strtotime($rekap['tanggal_masuk'])) ?></td>
                    <td><?= $rekap['jam_masuk']; ?></td>
                    <td><?= $rekap['jam_keluar']; ?></td>
                    <td>
                        <?php if($rekap['tanggal_keluar'] == '0000-00-00') : ?>
                            <span>0 jam 0 menit</span>
                        <?php else : ?>
                        <?= $total_jam_kerja . ' Jam ' . $selisih_menit_kerja . ' Menit' ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php
                        if ($total_jam_terlambat < 0) : ?>
                            <span class="badge bg-success">On Time</span>
                        <?php else : ?>

                            <?= $total_jam_terlambat . ' Jam ' . $selisih_menit_terlambat . ' Menit' ?>
                    </td>
                <?php endif; ?>
                </tr>

            <?php endwhile; ?>
        </table>
    </div>
</div>