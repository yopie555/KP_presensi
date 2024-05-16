<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: ../../auth/login.php?pesan=belum_login');
    exit();
} else if ($_SESSION['role'] != 'admin') {
    header('Location: ../../auth/login.php?pesan=tolak_akses');
    exit();
}

$judul_halaman = "Tambah Data Jabatan";
include('../../admin/layout/header.php');
require_once('../../config.php');

?>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="card col-md-6">
            <div class="card-body">
                <form action="<?= base_url('admin/data_jabatan/tambah/php') ?>" method="post">
                    <div class="mb-3">
                        <label for="">Nama Jabatan</label>
                        <input type="text" class="form-control" name="jabatan">
                    </div>
                    <button class="btn btn-primary" type="submit" name="submit">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('../../admin/layout/footer.php') ?>