<?php
include 'validation.php';
include 'koneksi.php';

requireAdmin();

$error = '';
$success = '';

// Ambil ID dari URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: guru.php?error=" . urlencode("ID guru tidak valid!"));
    exit();
}

$guru_id = intval($_GET['id']);

// Ambil data guru
$stmt = mysqli_prepare($koneksi, "SELECT * FROM users WHERE id_user = ? AND role = 'guru'");
mysqli_stmt_bind_param($stmt, 'i', $guru_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$guru = mysqli_fetch_assoc($result);

if (!$guru) {
    header("Location: guru.php?error=" . urlencode("Data guru tidak ditemukan!"));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $status = $_POST['status'] ?? 'approved';
    
    // Validasi input
    if (empty($nama_lengkap) || empty($username)) {
        $error = 'Nama lengkap dan username harus diisi!';
    } elseif (!empty($password) && strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } else {
        // Cek apakah username sudah ada (kecuali untuk user yang sedang diedit)
        $check_user = mysqli_prepare($koneksi, "SELECT id_user FROM users WHERE username = ? AND id_user != ?");
        mysqli_stmt_bind_param($check_user, 'si', $username, $guru_id);
        mysqli_stmt_execute($check_user);
        $result_check = mysqli_stmt_get_result($check_user);
        
        if (mysqli_num_rows($result_check) > 0) {
            $error = 'Username sudah digunakan!';
        } else {
            // Update data guru
            if (!empty($password)) {
                // Update dengan password baru
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = mysqli_prepare($koneksi, "UPDATE users SET nama_lengkap = ?, username = ?, password = ?, status = ? WHERE id_user = ?");
                mysqli_stmt_bind_param($stmt, 'ssssi', $nama_lengkap, $username, $hashed_password, $status, $guru_id);
            } else {
                // Update tanpa mengubah password
                $stmt = mysqli_prepare($koneksi, "UPDATE users SET nama_lengkap = ?, username = ?, status = ? WHERE id_user = ?");
                mysqli_stmt_bind_param($stmt, 'sssi', $nama_lengkap, $username, $status, $guru_id);
            }
            
            if (mysqli_stmt_execute($stmt)) {
                header("Location: guru.php?msg=" . urlencode("Data guru berhasil diperbarui!"));
                exit();
            } else {
                $error = 'Gagal memperbarui data guru: ' . mysqli_stmt_error($stmt);
            }
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Guru - Aplikasi Kegiatan Guru</title>
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
      padding-top: var(--header-height);
    }
    
    .main-header {
      height: var(--header-height);
      background: white;
      border-bottom: 1px solid #e2e8f0;
      box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1050;
      transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94), 
                  box-shadow 0.3s ease-out;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 2rem;
    }
    
    .navbar-hidden {
      transform: translateY(-100%);
      box-shadow: 0 0 0 0 rgb(0 0 0 / 0);
    }
    
    .navbar-visible {
      transform: translateY(0);
      box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
    }
    
    .navbar-hover-zone {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: 80px;
      z-index: 1049;
      pointer-events: none;
    }
    
    .navbar-hover-zone.active {
      pointer-events: all;
    }
    
    .sidebar {
      width: var(--sidebar-width);
      background: white;
      border-right: 1px solid #e2e8f0;
      min-height: calc(100vh - var(--header-height));
      position: fixed;
      top: var(--header-height);
      left: 0;
      z-index: 1000;
      transition: top 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
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
      min-height: calc(100vh - var(--header-height));
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
    
    .card-modern {
      background: white;
      border-radius: 0.75rem;
      box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
      border: 1px solid #e2e8f0;
      overflow: hidden;
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
    
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
      }
      
      .main-content {
        margin-left: 0;
        padding: 1rem;
      }
    }
  </style>
</head>
<body>
<div class="navbar-hover-zone" id="navbarHoverZone"></div>

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
      
      <div class="dropdown">
        <button class="btn btn-link text-decoration-none p-0 border-0 d-flex align-items-center gap-2" 
                type="button" 
                id="userDropdown" 
                data-bs-toggle="dropdown" 
                aria-expanded="false">
          <img src="/img/avatar.png" alt="Admin" class="user-avatar">
          <div class="d-flex flex-column justify-content-center">
            <span class="fw-semibold text-dark" style="font-size: 0.875rem; line-height: 1.2;">Admin</span>
            <span class="text-muted" style="font-size: 0.75rem; line-height: 1.2;">Administrator</span>
          </div>
          <i class="bi bi-chevron-down text-muted"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="userDropdown" style="min-width: 200px;">
          <li>
            <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="profile.php">
              <i class="bi bi-person text-muted"></i>
              <span>Profile</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="settings.php">
              <i class="bi bi-gear text-muted"></i>
              <span>Settings</span>
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger" href="logout.php">
              <i class="bi bi-box-arrow-right"></i>
              <span>Logout</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>

<aside class="sidebar">
  <div class="p-4">
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link" href="index.php">
          <i class="bi bi-house-door"></i>
          Dashboard
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="kegiatan.php">
          <i class="bi bi-calendar-event"></i>
          Kegiatan
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="guru.php">
          <i class="bi bi-person-badge"></i>
          Guru
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="kelas.php">
          <i class="bi bi-door-open"></i>
          Kelas
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="user_validation.php">
          <i class="bi bi-tags"></i>
          Validation User
        </a>
      </li>
      <li class="nav-item mt-4">
        <a class="nav-link" href="settings.php">
          <i class="bi bi-gear"></i>
          Settings
        </a>
      </li>
    </ul>
  </div>
</aside>

<main class="main-content">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="fw-bold mb-1">Edit Guru</h2>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="guru.php" class="text-decoration-none">Guru</a></li>
          <li class="breadcrumb-item active">Edit</li>
        </ol>
      </nav>
    </div>
    <a href="guru.php" class="btn btn-outline-secondary btn-modern">
      <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
  </div>

  <?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="bi bi-exclamation-triangle me-2"></i>
      <?= htmlspecialchars($error) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <div class="card-modern">
    <div class="p-4">
      <div class="d-flex align-items-center mb-4">
        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
          <i class="bi bi-person-fill text-white fs-4"></i>
        </div>
        <div>
          <h5 class="mb-0"><?= htmlspecialchars($guru['nama_lengkap']) ?></h5>
          <small class="text-muted">Guru</small>
        </div>
      </div>
      
      <form method="POST" class="row g-3">
        <div class="col-md-6">
          <label for="nama_lengkap" class="form-label">
            <i class="bi bi-person me-2"></i>Nama Lengkap
          </label>
          <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                 value="<?= htmlspecialchars($_POST['nama_lengkap'] ?? $guru['nama_lengkap']) ?>" required>
        </div>
        
        <div class="col-md-6">
          <label for="username" class="form-label">
            <i class="bi bi-at me-2"></i>Username
          </label>
          <input type="text" class="form-control" id="username" name="username" 
                 value="<?= htmlspecialchars($_POST['username'] ?? $guru['username']) ?>" required>
        </div>
        
        <div class="col-md-6">
          <label for="password" class="form-label">
            <i class="bi bi-lock me-2"></i>Password Baru
          </label>
          <input type="password" class="form-control" id="password" name="password">
          <div class="form-text">Kosongkan jika tidak ingin mengubah password</div>
        </div>
        
        <div class="col-md-6">
          <label for="status" class="form-label">
            <i class="bi bi-toggle-on me-2"></i>Status Akun
          </label>
          <select class="form-select" id="status" name="status" required>
            <option value="approved" <?= ($guru['status'] === 'approved') ? 'selected' : '' ?>>Aktif</option>
            <option value="pending" <?= ($guru['status'] === 'pending') ? 'selected' : '' ?>>Pending</option>
            <option value="rejected" <?= ($guru['status'] === 'rejected') ? 'selected' : '' ?>>Ditolak</option>
          </select>
        </div>
        
        <div class="col-12">
          <hr>
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary-modern btn-modern">
              <i class="bi bi-check-lg me-2"></i>Simpan Perubahan
            </button>
            <a href="guru.php" class="btn btn-secondary btn-modern">
              <i class="bi bi-x-lg me-2"></i>Batal
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdownElementList = document.querySelectorAll('.dropdown-toggle, [data-bs-toggle="dropdown"]');
    const dropdownList = [...dropdownElementList].map(dropdownToggleEl => new bootstrap.Dropdown(dropdownToggleEl));
    
    // Navbar auto-hide functionality
    let lastScrollTop = 0;
    const navbar = document.getElementById('mainHeader');
    const navbarHoverZone = document.getElementById('navbarHoverZone');
    let isHovering = false;
    
    // Add hover event listeners
    navbarHoverZone.addEventListener('mouseenter', function() {
        isHovering = true;
        navbar.classList.remove('navbar-hidden');
        navbar.classList.add('navbar-visible');
    });
    
    navbarHoverZone.addEventListener('mouseleave', function() {
        isHovering = false;
    });
    
    // Scroll event listener
    window.addEventListener('scroll', function() {
        if (isHovering) return;
        
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > lastScrollTop && scrollTop > 100) {
            // Scrolling down
            navbar.classList.remove('navbar-visible');
            navbar.classList.add('navbar-hidden');
            navbarHoverZone.classList.add('active');
        } else {
            // Scrolling up
            navbar.classList.remove('navbar-hidden');
            navbar.classList.add('navbar-visible');
            navbarHoverZone.classList.remove('active');
        }
        
        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    });
    
    // Validasi password jika diisi
    document.getElementById('password').addEventListener('input', function() {
        if (this.value && this.value.length < 6) {
            this.setCustomValidity('Password minimal 6 karakter');
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>
</body>
</html>