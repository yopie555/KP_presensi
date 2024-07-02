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

$judul = "Pengajuan Ketidakhadiran";
include('../../pegawai/layout/header.php');
include_once("../../config.php");

if(isset($_POST['submit'])){
    $id = htmlspecialchars($_POST['id_pegawai']);
    $keterangan = htmlspecialchars($_POST['keterangan']);
    $tanggal = htmlspecialchars($_POST['tanggal']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $status_pengajuan = 'PENDING';
    
    if (isset($_FILES['foto'])) {
        $file = $_FILES['foto'];
        $nama_file = $file['name'];
        $file_temp = $file['tmp_name'];
        $ukuran_file = $file['size'];
        $file_direrktori = '../../assets/file_ketidakhadiran/' . $nama_file;
        $ambil_ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
        $ekstensi_diizinkan = ['jpg', 'jpeg', 'png'];
        $max_ukuran = 10 * 1024 * 1024;

        move_uploaded_file($file_temp, $file_direrktori);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($keterangan)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Keterangan wajib di isi";
        }

        if (empty($tanggal)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Tanggal wajib di isi";
        }

        if (empty($deskripsi)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Deskripsi wajib di isi";
        }

        if (!in_array(strtolower($ambil_ekstensi), $ekstensi_diizinkan)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Hanya file jpg, jpeg, png yang diizinkan";
        }
        if ($ukuran_file > $max_ukuran) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Ukuran file terlalu besar. Max 10MB";
        }

        if (!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
        } else {

            $result = mysqli_query($connection, "INSERT INTO ketidakhadiran (id_pegawai, keterangan, tanggal, deskripsi, file, status_pengajuan) VALUES ('$id', '$keterangan', '$tanggal', '$deskripsi', '$nama_file', '$status_pengajuan')");  

            $_SESSION['berhasil'] = 'Data berhasil ditambahkan';
            header("Location: ketidakhadiran.php");
            exit();
        }
    }

}

$id = $_SESSION['id'];
$result = mysqli_query($connection, "SELECT * FROM ketidakhadiran WHERE id_pegawai = '$id' ORDER BY id DESC");

?>

<div class="page-body">
    <div class="container-xl">
        <div class="card col-md-6">
            <div class="card-body">
                <form action="<?= base_url('pegawai/ketidakhadiran/pengajuan_ketidakhadiran.php') ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" value="<?= $_SESSION['id'] ?>" name="id_pegawai">

                    <div class="mb-3">
                        <label for="">Keterangan</label>
                        <select name="keterangan" class="form-control">
                            <option value="">== Pilih Keterangan ==</option>
                            <option <?php if (isset($_POST['keterangan']) && $_POST['keterangan'] == 'Cuti') {
                                        echo 'selected';
                                    } ?> value="Cuti">Cuti</option>
                            <option <?php if (isset($_POST['keterangan']) && $_POST['keterangan'] == 'Izin') {
                                        echo 'selected';
                                    } ?> value="Izin">Izin</option>
                            <option <?php if (isset($_POST['keterangan']) && $_POST['keterangan'] == 'Sakit') {
                                        echo 'selected';
                                    } ?> value="Sakit">Sakit</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" cols="30" rows="5"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="">tanggal</label>
                        <input type="date" name="tanggal" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="">Surat Keterangan</label>
                        <input type="file" name="foto" class="form-control">
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary">Ajukan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('../layout/footer.php'); ?>