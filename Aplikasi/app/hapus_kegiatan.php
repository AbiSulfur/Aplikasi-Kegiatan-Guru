<?php include 'koneksi.php'; ?>
<?php
$id = intval($_GET['id'] ?? 0);
if ($id) {
  mysqli_query($conn, "DELETE FROM kegiatan_guru WHERE id_kegiatan=$id");
}
header('Location: kegiatan.php');
exit;
