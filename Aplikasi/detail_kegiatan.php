<?php include 'koneksi.php'; ?>

<?php
$id = intval($_GET['id'] ?? 0);
if (!$id) {
  echo '<div class="alert alert-danger">
          <i class="bi bi-exclamation-triangle me-2"></i>
          ID kegiatan tidak valid.
        </div>';
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
  echo '<div class="alert alert-danger">
          <i class="bi bi-exclamation-triangle me-2"></i>
          Data kegiatan tidak ditemukan.
        </div>';
  exit;
}

// Format tanggal
$tanggal_formatted = date('d M Y', strtotime($row['tanggal']));
$status_class = (strtotime($row['tanggal']) > time()) ? 'bg-warning' : 'bg-success';
$status_text = (strtotime($row['tanggal']) > time()) ? 'Terjadwal' : 'Selesai';
?>

<!-- Detail Content -->
<div class="row">
  <div class="col-12">
    <!-- Header Info -->
    <div class="bg-light rounded p-3 mb-4">
      <div class="row">
        <div class="col-md-6">
          <h6 class="fw-bold mb-2">
            <i class="bi bi-person-badge me-2 text-primary"></i>Informasi Guru
          </h6>
          <p class="mb-1"><strong>Nama:</strong> <?= htmlspecialchars($row['guru']) ?></p>
          <p class="mb-0"><strong>Kelas:</strong> <?= htmlspecialchars($row['kelas']) ?></p>
        </div>
        <div class="col-md-6">
          <h6 class="fw-bold mb-2">
            <i class="bi bi-calendar-event me-2 text-info"></i>Informasi Kegiatan
          </h6>
          <p class="mb-1"><strong>Jenis:</strong> <span class="badge bg-primary"><?= htmlspecialchars($row['jenis']) ?></span></p>
          <p class="mb-0"><strong>Tanggal:</strong> <?= $tanggal_formatted ?></p>
        </div>
      </div>
    </div>

    <!-- Status -->
    <div class="mb-4">
      <h6 class="fw-bold mb-2">
        <i class="bi bi-check-circle me-2 text-success"></i>Status Kegiatan
      </h6>
      <span class="badge <?= $status_class ?> fs-6"><?= $status_text ?></span>
    </div>

    <!-- Laporan -->
    <div class="mb-4">
      <h6 class="fw-bold mb-3">
        <i class="bi bi-file-text me-2 text-secondary"></i>Laporan Kegiatan
      </h6>
      <div class="bg-light rounded p-3">
        <p class="mb-0"><?= nl2br(htmlspecialchars($row['laporan'])) ?></p>
      </div>
    </div>

    <!-- Additional Info -->
    <div class="row">
      <div class="col-md-6">
        <div class="bg-light rounded p-3">
          <h6 class="fw-bold mb-2">
            <i class="bi bi-info-circle me-2 text-primary"></i>Informasi Tambahan
          </h6>
          <p class="mb-1"><strong>ID Kegiatan:</strong> #<?= $row['id_kegiatan'] ?></p>
          <p class="mb-0"><strong>Tanggal Dibuat:</strong> <?= date('d M Y H:i', strtotime($row['tanggal'])) ?></p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="bg-light rounded p-3">
          <h6 class="fw-bold mb-2">
            <i class="bi bi-clock me-2 text-warning"></i>Waktu Kegiatan
          </h6>
          <p class="mb-1"><strong>Tanggal:</strong> <?= $tanggal_formatted ?></p>
          <p class="mb-0"><strong>Status:</strong> 
            <?php if (strtotime($row['tanggal']) > time()): ?>
              <span class="text-warning">Akan dilaksanakan</span>
            <?php else: ?>
              <span class="text-success">Sudah dilaksanakan</span>
            <?php endif; ?>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
