<?php include '../koneksi.php'; ?>
<?php include '../includes/header.php'; ?>

<h1 class="h3 mb-4">Tambah Kegiatan</h1>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id_user = intval($_POST['id_user']);
  $id_kelas = intval($_POST['id_kelas']);
  $id_jenis = intval($_POST['id_jenis']);
  $tanggal = $_POST['tanggal'];
  $laporan = $_POST['laporan'];

  $stmt = mysqli_prepare($conn, "INSERT INTO kegiatan_guru (id_user, id_kelas, id_jenis, tanggal, laporan) VALUES (?,?,?,?,?)");
  mysqli_stmt_bind_param($stmt, 'iiiss', $id_user, $id_kelas, $id_jenis, $tanggal, $laporan);
  if (mysqli_stmt_execute($stmt)) {
    echo '<div class="alert alert-success">Kegiatan berhasil ditambahkan. <a href="kegiatan.php">Lihat daftar</a></div>';
  } else {
    echo '<div class="alert alert-danger">Gagal menambahkan kegiatan.</div>';
  }
}
?>

<form method="post" action="">
  <div class="mb-3">
    <label class="form-label">Guru</label>
    <select name="id_user" class="form-select" required>
      <option value="">-- Pilih Guru --</option>
      <?php
      $q = mysqli_query($conn, "SELECT id_user, nama_lengkap FROM users WHERE role='guru'");
      while ($g = mysqli_fetch_assoc($q)) {
        echo '<option value="'.$g['id_user'].'">'.htmlspecialchars($g['nama_lengkap']).'</option>';
      }
      ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Kelas</label>
    <select name="id_kelas" class="form-select" required>
      <option value="">-- Pilih Kelas --</option>
      <?php
      $q = mysqli_query($conn, "SELECT id_kelas, nama_kelas FROM kelas");
      while ($k = mysqli_fetch_assoc($q)) {
        echo '<option value="'.$k['id_kelas'].'">'.htmlspecialchars($k['nama_kelas']).'</option>';
      }
      ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Jenis Kegiatan</label>
    <select name="id_jenis" class="form-select" required>
      <option value="">-- Pilih Jenis --</option>
      <?php
      $q = mysqli_query($conn, "SELECT id_jenis, nama_jenis FROM jenis_kegiatan");
      while ($j = mysqli_fetch_assoc($q)) {
        echo '<option value="'.$j['id_jenis'].'">'.htmlspecialchars($j['nama_jenis']).'</option>';
      }
      ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Tanggal</label>
    <input type="date" name="tanggal" class="form-control" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Laporan</label>
    <textarea name="laporan" class="form-control" rows="5" required></textarea>
  </div>

  <button class="btn btn-primary">Simpan</button>
  <a href="kegiatan.php" class="btn btn-secondary">Batal</a>
</form>

<?php include '../includes/footer.php'; ?>
