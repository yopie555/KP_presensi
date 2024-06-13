<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js" integrity="sha512-dQIiHSl2hr3NWKKLycPndtpbh5iaHLo6MwrXm7F0FM5e+kL2U16oE9uIwPHUl6fQBeCthiEuV/rzP3MiAB8Vfw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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

if (isset($_POST['tombol_masuk'])) {
    $latitude_pegawai = $_POST['latitude_pegawai'];
    $longitude_pegawai = $_POST['longitude_pegawai'];
    $latitude_kantor = $_POST['latitude_kantor'];
    $longitude_kantor = $_POST['longitude_kantor'];
    $radius = $_POST['radius'];
    $zona_waktu = $_POST['zona_waktu'];
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $jam_masuk = $_POST['jam_masuk'];
}

// hervasine formula
$perbedaan_koordinat = $longitude_kantor - $longitude_pegawai;
$jarak = sin(deg2rad($latitude_kantor)) * sin(deg2rad($latitude_pegawai)) +  cos(deg2rad($latitude_kantor)) * cos(deg2rad($latitude_pegawai)) * cos(deg2rad($perbedaan_koordinat));
$jarak = acos($jarak);
$jarak = rad2deg($jarak);
$mil = $jarak * 60 * 1.1515; //rumus konversi jarak kebentuk mile
$jarak_km = $mil * 1.609344; //rumus konversi jarak kebentuk kilometer
$jarak_meter = $jarak_km * 1000; //rumus konversi jarak kebentuk meter

?>

<?php
if ($jarak_meter > $radius) { ?>

    <?=
    $_SESSION['gagal'] = "Anda berada di luar area kantor";
    header('Location: ../home/home.php');
    exit();
    ?>

<?php } else { ?>
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d757.7629415686725!2d<?= $longitude_pegawai ?>!3d<?= $latitude_pegawai ?>!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sid!2sid!4v1718203016609!5m2!1sid!2sid" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card text-center">
                        <div class="card-body" style="margin: auto;">
                            <div id="my_camera"></div>
                            <div id="my_result"></div>
                            <div><?= date('d F Y', strtotime($tanggal_masuk)) . '-' . $jam_masuk ?></div>
                            <button class="btn btn-primary mt-2" id="ambil_foto">Masuk</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script language="JavaScript">
        Webcam.set({
            width: 320,
            height: 240,
            dest_width: 320,
            dest_height: 240,
            image_format: 'jpeg',
            jpeg_quality: 90,
            force_flash: false
        });
        Webcam.attach('#my_camera');

        document.getElementById('ambil_foto').addEventListener('click', function() {
            Webcam.snap(function(data_uri) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    document.getElementById('my_result').innerHTML = '<img src="' + data_uri + '"/>';
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        window.location.href = '../home/home.php';
                    }
                };
                xhttp.open("POST", "presensi_masuk_aksi.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send(
                    'photo=' + encodeURIComponent(data_uri)
                );
            });
        });
    </script>
    <!-- <a href="javascript:void(take_snapshot())">Take Snapshot</a> -->
<?php } ?>

<?php include('../layout/footer.php'); ?>