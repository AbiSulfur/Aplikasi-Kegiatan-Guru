<?php include '../koneksi.php'; ?>
<?php include '../includes/header.php'; ?>

<h1 class="h3 mb-4">Edit Kegiatan</h1>

<?php
$id = intval($_GET['id'] ?? 0);
if (!$id) {
  echo '<div class="alert alert-danger">ID tidak valid.</div>';
  include '../includes/footer.php';
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id_user = intval($_POST['id_user']);
  $id_kelas = intval($_POST['id_kelas']);
  $id_jenis = intval($_POST['id_jenis']);
  $tanggal = $_POST['tanggal'];
  $laporan = $_POST['laporan'];

  $stmt = mysqli_prepare($conn, "UPDATE kegiatan_guru SET id_user=?, id_kelas=?, id_jenis=?, tanggal=?, laporan=? WHERE id_kegiatan=?");
  mysqli_stmt_bind_param($stmt, 'iiissi', $id_user, $id_kelas, $id_jenis, $tanggal, $laporan, $id);
  if (mysqli_stmt_execute($stmt)) {
    echo '<div class="alert alert-success">Kegiatan berhasil diperbarui. <a href="kegiatan.php">Lihat daftar</a></div>';
  } else {
    echo '<div class="alert alert-danger">Gagal memperbarui kegiatan.</div>';
  }
}

// ambil data existing
$q = mysqli_query($conn, "SELECT * FROM kegiatan_guru WHERE id_kegiatan=$id");
$row = mysqli_fetch_assoc($q);
if (!$row) {
  echo '<div class="alert alert-danger">Data tidak ditemukan.</div>';
  include '../includes/footer.php';
  exit;
}
?>

<form method="post" action="">
  <div class="mb-3">
    <label class="form-label">Guru</label>
    <select name="id_user" class="form-select" required>
      <?php
      $q = mysqli_query($conn, "SELECT id_user, nama_lengkap FROM users WHERE role='guru'");
      while ($g = mysqli_fetch_assoc($q)) {
        $sel = $g['id_user']==$row['id_user'] ? 'selected' : '';
        echo '<option value="'.$g['id_user'].'" '.$sel.'>'.htmlspecialchars($g['nama_lengkap']).'</option>';
      }
      ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Kelas</label>
    <select name="id_kelas" class="form-select" required>
      <?php
      $q = mysqli_query($conn, "SELECT id_kelas, nama_kelas FROM kelas");
      while ($k = mysqli_fetch_assoc($q)) {
        $sel = $k['id_kelas']==$row['id_kelas'] ? 'selected' : '';
        echo '<option value="'.$k['id_kelas'].'" '.$sel.'>'.htmlspecialchars($k['nama_kelas']).'</option>';
      }
      ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Jenis Kegiatan</label>
    <select name="id_jenis" class="form-select" required>
      <?php
      $q = mysqli_query($conn, "SELECT id_jenis, nama_jenis FROM jenis_kegiatan");
      while ($j = mysqli_fetch_assoc($q)) {
        $sel = $j['id_jenis']==$row['id_jenis'] ? 'selected' : '';
        echo '<option value="'.$j['id_jenis'].'" '.$sel.'>'.htmlspecialchars($j['nama_jenis']).'</option>';
      }
      ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Tanggal</label>
    <input type="date" name="tanggal" value="<?= $row['tanggal']; ?>" class="form-control" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Laporan</label>
    <textarea name="laporan" class="form-control" rows="5" required><?= htmlspecialchars($row['laporan']); ?></textarea>
  </div>

  <button class="btn btn-primary">Simpan</button>
  <a href="kegiatan.php" class="btn btn-secondary">Batal</a>
</form>

<?php include '../includes/footer.php'; ?>
