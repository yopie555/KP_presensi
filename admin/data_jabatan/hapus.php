<?php 

session_start();
require_once('../../config.php');

$id = $_GET['id'];

$result = mysqli_query($connection, "DELETE FROM jabatan WHERE id = $id") or die(mysqli_error($connection));

$_SESSION['berhasil'] = 'Data jabatan berhasil dihapus';
header('Location: jabatan.php');
exit();

include('../layout/footer.php');

?>