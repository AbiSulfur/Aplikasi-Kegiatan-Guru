<?php include 'koneksi.php'; ?>
<?php
$id = intval($_GET['id'] ?? 0);
if ($id) {
  $result = mysqli_query($conn, "DELETE FROM kegiatan_guru WHERE id_kegiatan=$id");
  if ($result) {
    // Set session message for success
    session_start();
    $_SESSION['message'] = 'Kegiatan berhasil dihapus!';
    $_SESSION['message_type'] = 'success';
  } else {
    // Set session message for error
    session_start();
    $_SESSION['message'] = 'Gagal menghapus kegiatan: ' . mysqli_error($conn);
    $_SESSION['message_type'] = 'danger';
  }
} else {
  // Set session message for invalid ID
  session_start();
  $_SESSION['message'] = 'ID kegiatan tidak valid!';
  $_SESSION['message_type'] = 'danger';
}
header('Location: kegiatan.php');
exit;
