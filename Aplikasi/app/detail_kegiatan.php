<?php include 'koneksi.php'; ?>
<?php include 'header.php'; ?>

<?php
$id = intval($_GET['id'] ?? 0);
if (!$id) {
  echo '<div class="alert alert-danger">ID tidak valid.</div>';
  include 'footer.php';
  exit;
}
$sql = "SELECT kg.*, u.nama_lengkap as guru, k.nama_kelas as kelas, j.nama_jenis as jenis
        FROM kegiatan_guru kg
        LEFT JOIN users u ON kg.id_user = u.id_user
        LEFT JOIN kelas k ON kg.id_kelas = k.id_kelas
        LEFT JOIN jenis_kegiatan j ON kg.id_jenis = j.id_jenis
        WHERE kg.id_kegiatan=$id";
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($res);
if (!$row) {
  echo '<div class="alert alert-danger">Data tidak ditemukan.</div>';
  include 'footer.php';
  exit;
}
?>

<h1 class="h3 mb-4">Detail Kegiatan</h1>

<div class="card">
  <div class="card-body">
    <h5><?= htmlspecialchars($row['jenis']); ?> - <?= htmlspecialchars($row['tanggal']); ?></h5>
    <p><strong>Guru:</strong> <?= htmlspecialchars($row['guru']); ?></p>
    <p><strong>Kelas:</strong> <?= htmlspecialchars($row['kelas']); ?></p>
    <p><strong>Laporan:</strong><br><?= nl2br(htmlspecialchars($row['laporan'])); ?></p>
  </div>
</div>

<?php include 'footer.php'; ?>
