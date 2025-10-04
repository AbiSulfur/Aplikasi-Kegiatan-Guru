<?php include 'koneksi.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Kegiatan - Aplikasi Kegiatan Guru</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root {
      --sidebar-bg: #f8f9fa;
      --sidebar-width: 280px;
      --header-height: 70px;
      --primary-color: #6366f1;
      --success-color: #10b981;
      --warning-color: #f59e0b;
      --danger-color: #ef4444;
    }
    
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background-color: #f1f5f9;
      padding-top: 0;
    }
    
    .main-header {
      height: var(--header-height);
      background: white;
      border-bottom: 1px solid #e2e8f0;
      box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 2rem;
      position: sticky;
      top: 0;
      z-index: 1050;
    }
    
    .navbar-hidden {
      transform: translateY(-100%);
      box-shadow: 0 0 0 0 rgb(0 0 0 / 0);
    }
    
    .navbar-visible {
      transform: translateY(0);
      box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
    }
    
    .sidebar {
      width: var(--sidebar-width);
      background: white;
      border-right: 1px solid #e2e8f0;
      min-height: 100vh;
      position: fixed;
      top: var(--header-height);
      left: 0;
      z-index: 1000;
    }
    
    .navbar-hidden ~ .sidebar,
    .navbar-hidden + * .sidebar {
      top: 0;
    }
    
    .navbar-brand {
      display: flex;
      align-items: center;
      height: 100%;
    }
    
    .navbar-actions {
      display: flex;
      align-items: center;
      gap: 1.5rem;
      height: 100%;
    }
    
    .search-container {
      display: flex;
      align-items: center;
      position: relative;
    }
    
    .user-section {
      display: flex;
      align-items: center;
      gap: 1rem;
      height: 100%;
    }

    .main-content {
      margin-left: var(--sidebar-width);
      padding: 2rem;
      min-height: 100vh;
    }
    
    .nav-link {
      color: #64748b;
      padding: 0.75rem 1.5rem;
      border-radius: 0.5rem;
      margin: 0.25rem 0;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }
    
    .nav-link:hover, .nav-link.active {
      background-color: #f1f5f9;
      color: var(--primary-color);
    }
    
    .metric-card {
      background: white;
      border-radius: 0.75rem;
      padding: 1.5rem;
      box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
      border: 1px solid #e2e8f0;
      transition: transform 0.2s;
      min-height: 140px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    
    .metric-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }
    
    .search-box {
      background: #f1f5f9;
      border: 1px solid #e2e8f0;
      border-radius: 0.5rem;
      padding: 0.5rem 1rem;
      width: 300px;
      height: 40px;
      display: flex;
      align-items: center;
    }
    
    .search-box:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgb(99 102 241 / 0.1);
    }
    
    .user-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      border: 2px solid #e2e8f0;
    }
    
    .notification-icon {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
    }
    
    .btn-modern {
      border-radius: 0.5rem;
      font-weight: 500;
      padding: 0.5rem 1rem;
      border: none;
      transition: all 0.2s;
    }
    
    .btn-primary-modern {
      background: var(--primary-color);
      color: white;
    }
    
    .btn-primary-modern:hover {
      background: #5856eb;
      transform: translateY(-1px);
    }

    .form-control, .form-select {
      border-radius: 0.5rem;
      border: 1px solid #e2e8f0;
      padding: 0.75rem;
    }

    .form-control:focus, .form-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgb(99 102 241 / 0.1);
    }

    .breadcrumb {
      background: none;
      padding: 0;
    }

    .breadcrumb-item + .breadcrumb-item::before {
      content: ">";
      color: #6c757d;
    }
  </style>
</head>
<body>
<nav class="main-header navbar-visible" id="mainHeader">
  <div class="navbar-brand">
    <h5 class="mb-0 fw-bold text-dark">Aplikasi Kegiatan Guru</h5>
  </div>
  
  <div class="navbar-actions">
    <div class="search-container">
      <i class="bi bi-search position-absolute" style="left: 12px; color: #64748b; z-index: 1;"></i>
      <input type="text" class="search-box" placeholder="Search now" style="padding-left: 2.5rem;">
    </div>
    
    <div class="user-section">
      <div class="notification-icon">
        <i class="bi bi-bell text-muted fs-5"></i>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">3</span>
      </div>
      
      <div class="d-flex align-items-center gap-2">
        <img src="img/avatar.png" alt="Admin" class="user-avatar">
        <div class="d-flex flex-column justify-content-center">
          <span class="fw-semibold text-dark" style="font-size: 0.875rem; line-height: 1.2;">Admin</span>
          <span class="text-muted" style="font-size: 0.75rem; line-height: 1.2;">Administrator</span>
        </div>
        <i class="bi bi-chevron-down text-muted"></i>
      </div>
    </div>
  </div>
</nav>

<aside class="sidebar" id="sidebar">
  <div class="p-4">
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link" href="index.php">
          <i class="bi bi-house-door"></i>
          Dashboard
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="kegiatan.php">
          <i class="bi bi-calendar-event"></i>
          Kegiatan
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="guru.php">
          <i class="bi bi-person-badge"></i>
          Guru
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#" onclick="alert('Fitur dalam pengembangan')">
          <i class="bi bi-door-open"></i>
          Kelas
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#" onclick="alert('Fitur dalam pengembangan')">
          <i class="bi bi-tags"></i>
          Jenis Kegiatan
        </a>
      </li>
      <li class="nav-item mt-4">
        <a class="nav-link" href="#" onclick="alert('Fitur dalam pengembangan')">
          <i class="bi bi-gear"></i>
          Settings
        </a>
      </li>
    </ul>
  </div>
</aside>

<main class="main-content">
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
  echo '</main></body></html>';
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
  echo '</main></body></html>';
  exit;
}
?>

<!-- Modern form card -->
<div class="card shadow-sm border-0">
  <div class="card-body">
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
              <select name="id_user" class="form-select" required>
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
              <select name="id_kelas" class="form-select" required>
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
              <select name="id_jenis" class="form-select" required>
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
              <input type="date" name="tanggal" value="<?= $row['tanggal'] ?>" class="form-control" required>
            </div>
        </div>

        <div class="mb-4">
          <label class="form-label fw-semibold">
            <i class="bi bi-file-text me-2 text-secondary"></i>Laporan Kegiatan
          </label>
            <textarea name="laporan" class="form-control" rows="6" required 
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
        </div>
      </form>
    </div>
    
    <div class="col-lg-4">
      <div class="bg-light rounded p-4">
        <h6 class="fw-bold mb-3">
          <i class="bi bi-lightbulb me-2 text-warning"></i>Tips Edit
        </h6>
        <ul class="list-unstyled mb-0">
          <li class="mb-2">
            <i class="bi bi-check text-success me-2"></i>
            <small>Periksa kembali data yang akan diubah</small>
          </li>
          <li class="mb-2">
            <i class="bi bi-check text-success me-2"></i>
            <small>Pastikan tanggal sesuai dengan pelaksanaan</small>
          </li>
          <li class="mb-2">
            <i class="bi bi-check text-success me-2"></i>
            <small>Update laporan jika ada perubahan</small>
          </li>
          <li>
            <i class="bi bi-check text-success me-2"></i>
            <small>Simpan perubahan setelah selesai</small>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
</div>

</main>

<!-- Modern footer -->
<footer class="text-center py-4" style="margin-left: var(--sidebar-width); background: white; border-top: 1px solid #e2e8f0;">
  <p class="text-muted mb-0">&copy; 2025 Aplikasi Kegiatan Guru. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Active navigation highlighting
  const currentPath = window.location.pathname;
  const navLinks = document.querySelectorAll('.nav-link');
  
  navLinks.forEach(link => {
    link.classList.remove('active');
    if (link.getAttribute('href') === currentPath || 
        (currentPath.includes(link.getAttribute('href')) && link.getAttribute('href') !== '/')) {
      link.classList.add('active');
    }
  });
  
  // Search functionality placeholder
  const searchInput = document.querySelector('.search-box');
  if (searchInput) {
    searchInput.addEventListener('input', function() {
      // Add search functionality here
      console.log('Searching for:', this.value);
    });
  }

  // Form validation and textarea auto-resize
  const form = document.getElementById('editKegiatanForm');
  if (form) {
    form.addEventListener('submit', function(e) {
  const laporan = document.querySelector('textarea[name="laporan"]').value;
  if (laporan.length < 20) {
    e.preventDefault();
    alert('Laporan harus minimal 20 karakter');
    return false;
  }
});
  }

  const textarea = document.querySelector('textarea[name="laporan"]');
  if (textarea) {
    textarea.addEventListener('input', function() {
  this.style.height = 'auto';
  this.style.height = this.scrollHeight + 'px';
    });
  }
});
</script>
</body>
</html>
