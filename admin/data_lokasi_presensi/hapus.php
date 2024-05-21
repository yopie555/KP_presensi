<?php 

session_start();
require_once('../../config.php');

$id = $_GET['id'];

$result = mysqli_query($connection, "DELETE FROM lokasi_presensi WHERE id = $id") or die(mysqli_error($connection));

$_SESSION['berhasil'] = 'Data lokasi presensi berhasil dihapus';
header('Location: lokasi_presensi.php');
exit();

include('../layout/footer.php');

?>