<?php include 'koneksi.php'; ?>
<?php include 'header.php'; ?>

<!-- Wrap main content and add proper margin for sidebar offset -->
<div class="main-content">
  <!-- Modern page header -->
  <div class="d-flex align-items-center justify-content-between mb-4">
    <div>
      <h2 class="mb-1 fw-bold">Edit Kegiatan</h2>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="kegiatan.php" class="text-decoration-none">Kegiatan</a></li>
          <li class="breadcrumb-item active">Edit</li>
        </ol>
      </nav>
    </div>
  </div>

  <?php
  $id = intval($_GET['id'] ?? 0);
  if (!$id) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            ID kegiatan tidak valid.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>';
    echo '<a href="kegiatan.php" class="btn btn-primary-modern">Kembali ke Daftar Kegiatan</a>';
    include 'footer.php';
    exit;
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = intval($_POST['id_user']);
    $id_kelas = intval($_POST['id_kelas']);
    $id_jenis = intval($_POST['id_jenis']);
    $tanggal = $_POST['tanggal'];
    $laporan = trim($_POST['laporan']);

    // Validation
    $errors = [];
    if (empty($id_user)) $errors[] = "Guru harus dipilih";
    if (empty($id_kelas)) $errors[] = "Kelas harus dipilih";
    if (empty($id_jenis)) $errors[] = "Jenis kegiatan harus dipilih";
    if (empty($tanggal)) $errors[] = "Tanggal harus diisi";
    if (empty($laporan)) $errors[] = "Laporan harus diisi";

    if (empty($errors)) {
      $stmt = mysqli_prepare($conn, "UPDATE kegiatan_guru SET id_user=?, id_kelas=?, id_jenis=?, tanggal=?, laporan=? WHERE id_kegiatan=?");
      mysqli_stmt_bind_param($stmt, 'iiissi', $id_user, $id_kelas, $id_jenis, $tanggal, $laporan, $id);
      
      if (mysqli_stmt_execute($stmt)) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                Kegiatan berhasil diperbarui!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>';
        echo '<script>setTimeout(function(){ window.location.href = "kegiatan.php"; }, 2000);</script>';
      } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Gagal memperbarui kegiatan: ' . mysqli_error($conn) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>';
      }
    } else {
      echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="bi bi-exclamation-triangle me-2"></i>
              <ul class="mb-0">';
      foreach ($errors as $error) {
        echo '<li>' . $error . '</li>';
      }
      echo '</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>';
    }
  }

  // Get existing data
  $q = mysqli_query($conn, "SELECT kg.*, u.nama_lengkap as guru_nama, k.nama_kelas, j.nama_jenis 
                            FROM kegiatan_guru kg
                            LEFT JOIN users u ON kg.id_user = u.id_user
                            LEFT JOIN kelas k ON kg.id_kelas = k.id_kelas
                            LEFT JOIN jenis_kegiatan j ON kg.id_jenis = j.id_jenis
                            WHERE kg.id_kegiatan = $id");
  $row = mysqli_fetch_assoc($q);

  if (!$row) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Data kegiatan tidak ditemukan.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>';
    echo '<a href="kegiatan.php" class="btn btn-primary-modern">Kembali ke Daftar Kegiatan</a>';
    include 'footer.php';
    exit;
  }
  ?>

  <!-- Modern form card -->
  <div class="metric-card">
    <div class="row">
      <div class="col-lg-8">
        <!-- Current data info -->
        <div class="bg-light rounded p-3 mb-4">
          <h6 class="fw-bold mb-2">
            <i class="bi bi-info-circle me-2 text-primary"></i>Data Saat Ini
          </h6>
          <div class="row text-sm">
            <div class="col-md-6">
              <small class="text-muted">Guru:</small> <strong><?= htmlspecialchars($row['guru_nama']) ?></strong><br>
              <small class="text-muted">Kelas:</small> <strong><?= htmlspecialchars($row['nama_kelas']) ?></strong>
            </div>
            <div class="col-md-6">
              <small class="text-muted">Jenis:</small> <strong><?= htmlspecialchars($row['nama_jenis']) ?></strong><br>
              <small class="text-muted">Tanggal:</small> <strong><?= date('d M Y', strtotime($row['tanggal'])) ?></strong>
            </div>
          </div>
        </div>

        <form method="post" action="" id="editKegiatanForm">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">
                <i class="bi bi-person-badge me-2 text-primary"></i>Guru
              </label>
              <select name="id_user" class="form-select" required style="border-radius: 0.5rem;">
                <?php
                $q = mysqli_query($conn, "SELECT id_user, nama_lengkap FROM users WHERE role='guru' ORDER BY nama_lengkap");
                while ($g = mysqli_fetch_assoc($q)) {
                  $selected = ($g['id_user'] == $row['id_user']) ? 'selected' : '';
                  echo '<option value="'.$g['id_user'].'" '.$selected.'>'.htmlspecialchars($g['nama_lengkap']).'</option>';
                }
                ?>
              </select>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">
                <i class="bi bi-door-open me-2 text-success"></i>Kelas
              </label>
              <select name="id_kelas" class="form-select" required style="border-radius: 0.5rem;">
                <?php
                $q = mysqli_query($conn, "SELECT id_kelas, nama_kelas FROM kelas ORDER BY nama_kelas");
                while ($k = mysqli_fetch_assoc($q)) {
                  $selected = ($k['id_kelas'] == $row['id_kelas']) ? 'selected' : '';
                  echo '<option value="'.$k['id_kelas'].'" '.$selected.'>'.htmlspecialchars($k['nama_kelas']).'</option>';
                }
                ?>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">
                <i class="bi bi-tags me-2 text-warning"></i>Jenis Kegiatan
              </label>
              <select name="id_jenis" class="form-select" required style="border-radius: 0.5rem;">
                <?php
                $q = mysqli_query($conn, "SELECT id_jenis, nama_jenis FROM jenis_kegiatan ORDER BY nama_jenis");
                while ($j = mysqli_fetch_assoc($q)) {
                  $selected = ($j['id_jenis'] == $row['id_jenis']) ? 'selected' : '';
                  echo '<option value="'.$j['id_jenis'].'" '.$selected.'>'.htmlspecialchars($j['nama_jenis']).'</option>';
                }
                ?>
              </select>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">
                <i class="bi bi-calendar-event me-2 text-info"></i>Tanggal
              </label>
              <input type="date" name="tanggal" value="<?= $row['tanggal'] ?>" class="form-control" required style="border-radius: 0.5rem;">
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold">
              <i class="bi bi-file-text me-2 text-secondary"></i>Laporan Kegiatan
            </label>
            <textarea name="laporan" class="form-control" rows="6" required style="border-radius: 0.5rem;" 
                      placeholder="Deskripsikan kegiatan yang dilakukan..."><?= htmlspecialchars($row['laporan']) ?></textarea>
            <div class="form-text">Minimal 20 karakter</div>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary-modern btn-modern">
              <i class="bi bi-check-circle me-2"></i>Perbarui Kegiatan
            </button>
            <a href="kegiatan.php" class="btn btn-outline-secondary btn-modern">
              <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
            <a href="detail_kegiatan.php?id=<?= $id ?>" class="btn btn-outline-info btn-modern">
              <i class="bi bi-eye me-2"></i>Lihat Detail
            </a>
          </div>
        </form>
      </div>
      
      <div class="col-lg-4">
        <div class="guide-box">
          <h6>
            <i class="bi bi-lightbulb me-2"></i>Tips Edit
          </h6>
          <ul class="list-unstyled mb-0">
            <li>
              <i class="bi bi-check-circle"></i>
              <small>Periksa kembali data yang akan diubah</small>
            </li>
            <li>
              <i class="bi bi-check-circle"></i>
              <small>Pastikan tanggal sesuai dengan pelaksanaan</small>
            </li>
            <li>
              <i class="bi bi-check-circle"></i>
              <small>Update laporan jika ada perubahan</small>
            </li>
            <li>
              <i class="bi bi-check-circle"></i>
              <small>Simpan perubahan setelah selesai</small>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <script>
  document.getElementById('editKegiatanForm').addEventListener('submit', function(e) {
    const laporan = document.querySelector('textarea[name="laporan"]').value;
    if (laporan.length < 20) {
      e.preventDefault();
      alert('Laporan harus minimal 20 karakter');
      return false;
    }
  });

  // Auto-resize textarea
  document.querySelector('textarea[name="laporan"]').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = this.scrollHeight + 'px';
  });
  </script>
</div>

<?php include 'footer.php'; ?>
