<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: ../../auth/login.php?pesan=belum_login');
    exit();
} else if ($_SESSION['role'] != 'admin') {
    header('Location: ../../auth/login.php?pesan=tolak_akses');
    exit();
}

$judul_halaman = "Data Pegawai";
include('../../admin/layout/header.php');
require_once('../../config.php');

$result = mysqli_query($connection, "SELECT users.id_pegawai, users.username, users.password, users.status, users.role, pegawai.* FROM users JOIN pegawai ON users.id_pegawai = pegawai.id") or die(mysqli_error($connection));

?>

<div class="page-body">
    <div class="container-xl">
        <a href="<?= base_url('admin/data_pegawai/tambah.php') ?>" class="btn btn-primary">
            <span class="text">
                <i class="fa-solid fa-circle-plus"></i>
                Tambah data
            </span>
        </a>
        <table class="table table-bordered mt-3">
            <tr class="text-center"> 
                <th>No</th>
                <th>NIP</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Jabatan</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>    

            <?php if (mysqli_num_rows($result) === 0) { ?>
                <tr>
                    <td colspan="7">Data Kosong, Silahkan tambahkan data baru</td>
                </tr>
            <?php } else { ?>
                <?php $no = 1;
                while ($pegawai = mysqli_fetch_array($result)) : ?>
                    <tr >
                        <td><?= $no++ ?></td>
                        <td><?= $pegawai['nip'] ?></td>
                        <td><?= $pegawai['nama']  ?></td>
                        <td><?= $pegawai['username'] ?></td>
                        <td><?= $pegawai['jabatan'] ?></td>
                        <td><?= $pegawai['role'] ?></td>
                        <td class="text-center">
                            <a href="<?= base_url('admin/data_pegawai/detail.php?id=' . $pegawai['id']) ?>" class="badge badge-pill bg-primary">Detail</a>
                            <a href="<?= base_url('admin/data_pegawai/edit.php?id=' . $pegawai['id']) ?>" class="badge badge-pill bg-primary">Edit</a>
                            <a href="<?= base_url('admin/data_pegawai/hapus.php?id=' . $pegawai['id']) ?>" class="badge badge-pill bg-danger tombol-hapus">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php } ?>
        </table>
    </div>
</div>

<?php include('../layout/footer.php'); ?>