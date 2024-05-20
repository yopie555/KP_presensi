<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: ../../auth/login.php?pesan=belum_login');
    exit();
} else if ($_SESSION['role'] != 'admin') {
    header('Location: ../../auth/login.php?pesan=tolak_akses');
    exit();
}

$judul_halaman = "Data Jabatan";
include('../../admin/layout/header.php');
require_once('../../config.php');

$result = mysqli_query($connection, "SELECT * FROM jabatan ORDER BY id DESC") or die(mysqli_error($connection));
?>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <a href="<?= base_url('admin/data_jabatan/tambah.php') ?>" class="btn btn-primary">
            <span class="text">
                <i class="fa-solid fa-circle-plus"></i>
                Tambah data
            </span>
        </a>
        <div class="row row-deck row-cards mt-2">
            <table class="table table-bordered">
                <tr class="text-center">
                    <th>No.</th>
                    <th>Nama Jabatan</th>
                    <th>Aksi</th>
                </tr>
                <?php if (mysqli_num_rows($result) === 0) : ?>
                    <tr>
                        <td colspan="3" class="text-center">Data kosong</td>
                    </tr>
                <?php else : ?>
                    <?php $no = 1;
                    while ($jabatan = mysqli_fetch_array($result)) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $jabatan['jabatan'] ?></td>
                            <td class="text-center">
                                <a href="<?= base_url('admin/data_jabatan/edit.php?id=' . $jabatan['id']) ?>" class="badge bg-primary badge-pill">Edit</a>
                                <a href="<?= base_url('admin/data_jabatan/hapus.php?id=' . $jabatan['id']) ?>" class="badge bg-danger badge-pill tombol-hapus">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>

<?php include('../../admin/layout/footer.php') ?>