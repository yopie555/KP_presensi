<?php

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

$lokasi_presensi = $_SESSION['lokasi_presensi'];
$result = mysqli_query($connection, "SELECT * FROM lokasi_presensi WHERE nama_lokasi = '$lokasi_presensi'");

while ($lokasi = mysqli_fetch_array($result)) {
    $latitude_kantor = $lokasi['latitude'];
    $longitude_kantor = $lokasi['longitude'];
    $radius = $lokasi['radius'];
    $zona_waktu = $lokasi['zona_waktu'];
    $jam_pulang = $lokasi['jam_pulang'];

    if ($zona_waktu == 'WIB') {
        date_default_timezone_set('Asia/Jakarta');
    } else if ($zona_waktu == 'WITA') {
        date_default_timezone_set('Asia/Makassar');
    } else {
        date_default_timezone_set('Asia/Jayapura');
    }
}

?>

<style>
    .parent-date {
        display: grid;
        grid-template-columns: auto auto auto auto auto;
        font-size: 20px;
        text-align: center;
        justify-content: center;
    }

    .parent_clock {
        display: grid;
        grid-template-columns: auto auto auto auto auto;
        font-size: 30px;
        text-align: center;
        font-weight: bold;
        justify-content: center;
    }
</style>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-4">
                <div class="card text-center h-100">
                    <div class="card-header">Presensi Masuk</div>
                    <?php
                    $id_pegawai = $_SESSION['id'];
                    $tanggal_hari_ini = date('Y-m-d');
                    $cek_presensi_masuk = mysqli_query($connection, "SELECT * FROM presensi WHERE id_pegawai = '$id_pegawai' AND  tanggal_masuk = '$tanggal_hari_ini'");

                    if (mysqli_num_rows($cek_presensi_masuk) === 0) {
                    ?>
                        <div class="card-body">
                            <div class="parent-date">
                                <div id="tanggal_masuk"></div>
                                <div class="ms-2"></div>
                                <div id="bulan_masuk"></div>
                                <div class="ms-2"></div>
                                <div id="tahun_masuk"></div>
                            </div>
                            <div class="parent_clock">
                                <div id="jam_masuk"></div>
                                <div>:</div>
                                <div id="menit_masuk"></div>
                                <div>:</div>
                                <div id="detik_masuk"></div>
                            </div>
                            <form method="POST" action="<?= base_url('pegawai/presensi/presensi_masuk.php') ?>">
                                <input type="" name="latitude_kantor" value="<?= $latitude_kantor ?>">
                                <input type="" name="longitude_kantor" value="<?= $longitude_kantor ?>">
                                <input type="" name="latitude_pegawai" id="latitude_pegawai">
                                <input type="" name="longitude_pegawai" id="longitude_pegawai">
                                <input type="" name="radius" value="<?= $radius ?>">
                                <input type="" name="zona_waktu" value="<?= $zona_waktu ?>">
                                <input type="" name="tanggal_masuk" value="<?= date('Y-m-d') ?>">
                                <input type="" name="jam_masuk" value="<?= date('H:i:s') ?>">

                                <button type="submit" name="tombol_masuk" class="btn btn-primary mt-3">Masuk</button>
                            </form>
                        <?php } else { ?>
                            <div class="card-body">
                                <i class="fa-regular fa-circle-check fa-4x text-success"></i>
                                <h4 class="my-3">Anda sudah melakukan <br> presensi masuk</h4>
                            <?php } ?>
                            </div>
                        </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center h-100">
                        <div class="card-header">Presensi Keluar</div>
                        <div class="card-body">
                            <?php
                            $ambil_data_presensi = mysqli_query($connection, "SELECT * FROM presensi WHERE id_pegawai = '$id_pegawai' AND tanggal_masuk = '$tanggal_hari_ini'");
                            ?>

                            <?php $waktu_sekarang = date('H:i:s');
                            if (strtotime($waktu_sekarang) <= strtotime($jam_pulang)) { ?>
                                <div class="card-body">
                                    <i class="fa-regular fa-circle-xmark fa-4x text-danger"></i>
                                    <h4 class="my-3">Belum Waktunya Absen Pulang</h4>
                                </div>
                            <?php } elseif (strtotime($waktu_sekarang) >= strtotime($jam_pulang) && mysqli_num_rows($ambil_data_presensi) == 0) { ?>
                                <div class="card-body">
                                    <i class="fa-regular fa-circle-xmark fa-4x text-danger"></i>
                                    <h4 class="my-3">Silahkan melakukan presensi masuk <br> terlebih dahulu</h4>
                                </div>
                            <?php } else { ?>

                                <?php while ($cek_presensi_keluar = mysqli_fetch_array($ambil_data_presensi)) : ?>
                                    <?php if (($cek_presensi_keluar['tanggal_masuk']) && $cek_presensi_keluar['tanggal_keluar'] == '0000-00-00') { ?>
                                        <div class="parent-date">
                                            <div id="tanggal_keluar"></div>
                                            <div class="ms-2"></div>
                                            <div id="bulan_keluar"></div>
                                            <div class="ms-2"></div>
                                            <div id="tahun_keluar"></div>
                                        </div>
                                        <div class="parent_clock">
                                            <div id="jam_keluar"></div>
                                            <div>:</div>
                                            <div id="menit_keluar"></div>
                                            <div>:</div>
                                            <div id="detik_keluar"></div>
                                        </div>
                                        <form method="post" action="<?= base_url('pegawai/presensi/presensi_keluar.php')  ?>">
                                            <input type="hidden" name="id" value="<?= $cek_presensi_keluar['id'] ?>">
                                            <input type="hidden" name="latitude_kantor" value="<?= $latitude_kantor ?>">
                                            <input type="hidden" name="longitude_kantor" value="<?= $longitude_kantor ?>">
                                            <input type="hidden" name="latitude_pegawai" id="latitude_pegawai">
                                            <input type="hidden" name="longitude_pegawai" id="longitude_pegawai">
                                            <input type="hidden" name="radius" value="<?= $radius ?>">
                                            <input type="hidden" name="zona_waktu" value="<?= $zona_waktu ?>">
                                            <input type="hidden" name="tanggal_keluar" value="<?= date('Y-m-d') ?>">
                                            <input type="hidden" name="jam_keluar" value="<?= date('H:i:s') ?>">

                                            <button name="tombol-keluar" type="submit" class="btn btn-danger mt-3">Keluar</button>
                                        </form>
                                    <?php } else { ?>
                                        <div class="card-body">
                                            <i class="fa-regular fa-circle-check fa-4x text-success"></i>
                                            <h4 class="my-3">Anda telah melakukan <br> presensi keluar</h4>
                                        </div>
                                    <?php } ?>
                                <?php endwhile; ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    </div>

    <script>
        //waktu masuk
        window.setTimeout("waktu_masuk()", 1000);
        nama_bulan = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ]

        function waktu_masuk() {
            var waktu = new Date();
            var tanggal = waktu.getDate();
            var bulan = waktu.getMonth();
            var tahun = waktu.getFullYear();
            var jam = waktu.getHours();
            var menit = waktu.getMinutes();
            var detik = waktu.getSeconds();

            document.getElementById("tanggal_masuk").innerHTML = tanggal;
            document.getElementById("bulan_masuk").innerHTML = nama_bulan[bulan];
            document.getElementById("tahun_masuk").innerHTML = tahun;
            document.getElementById("jam_masuk").innerHTML = jam;
            document
                .getElementById("menit_masuk")
                .innerHTML = menit;
            document
                .getElementById("detik_masuk")
                .innerHTML = detik;

            setTimeout("waktu_masuk()", 1000);
        }

        //waktu keluar
        window.setTimeout("waktu_keluar()", 1000);

        function waktu_keluar() {
            var waktu = new Date();
            var tanggal = waktu.getDate();
            var bulan = waktu.getMonth();
            var tahun = waktu.getFullYear();
            var jam = waktu.getHours();
            var menit = waktu.getMinutes();
            var detik = waktu.getSeconds();

            document.getElementById("tanggal_keluar").innerHTML = tanggal;
            document.getElementById("bulan_keluar").innerHTML = nama_bulan[bulan];
            document.getElementById("tahun_keluar").innerHTML = tahun;
            document.getElementById("jam_keluar").innerHTML = jam;
            document
                .getElementById("menit_keluar")
                .innerHTML = menit;
            document
                .getElementById("detik_keluar")
                .innerHTML = detik;

            setTimeout("waktu_keluar()", 1000);
        }

        getLocation();

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function showPosition(position) {
            $('#latitude_pegawai').val(position.coords.latitude);
            $('#longitude_pegawai').val(position.coords.longitude);
        }
    </script>

    <?php include('../../pegawai/layout/footer.php') ?>