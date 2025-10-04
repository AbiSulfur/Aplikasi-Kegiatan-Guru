<?php
session_start();
include 'validation.php';
include 'koneksi.php';

requireAdmin();

$message = '';

// Handle user approval/rejection
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $action = $_POST['action'];
    
    if ($action == 'approve') {
        $query = "UPDATE users SET status = 'approved' WHERE id_user = ?";
        $success_msg = "User berhasil disetujui!";
    } else if ($action == 'reject') {
        $query = "UPDATE users SET status = 'rejected' WHERE id_user = ?";
        $success_msg = "User berhasil ditolak!";
    }
    
    if (isset($query)) {
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("i", $user_id);
        
        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>$success_msg</div>";
        } else {
            $message = "<div class='alert alert-danger'>Terjadi kesalahan!</div>";
        }
    }
}

// Get pending users
$pending_users = mysqli_query($koneksi, "SELECT * FROM users WHERE status = 'pending' ORDER BY created_at DESC");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Validasi User - Aplikasi Kegiatan Guru</title>
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
    
    .table-modern {
      border: none;
    }
    
    .table-modern th {
      background-color: #f8fafc;
      border: none;
      font-weight: 600;
      color: #475569;
      padding: 1rem;
    }
    
    .table-modern td {
      border: none;
      padding: 1rem;
      vertical-align: middle;
    }
    
    .table-modern tbody tr {
      border-bottom: 1px solid #f1f5f9;
    }
    
    .table-modern tbody tr:hover {
      background-color: #f8fafc;
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
      
      <div class="d-flex align-items-center gap-2">
        <img src="/img/avatar.png" alt="Admin" class="user-avatar">
        <div class="d-flex flex-column justify-content-center">
          <span class="fw-semibold text-dark" style="font-size: 0.875rem; line-height: 1.2;"><?php echo $_SESSION['nama_lengkap']; ?></span>
          <span class="text-muted" style="font-size: 0.75rem; line-height: 1.2;"><?php echo ucfirst($_SESSION['role']); ?></span>
        </div>
        <div class="dropdown">
          <i class="bi bi-chevron-down text-muted dropdown-toggle" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
          </ul>
        </div>
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
        <a class="nav-link" href="kegiatan.php">
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
        <a class="nav-link" href="kelas.php">
          <i class="bi bi-door-open"></i>
          Kelas
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="user_validation.php">
          <i class="bi bi-person-check"></i>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.getElementById('mainHeader');
    const hoverZone = document.getElementById('navbarHoverZone');
    const sidebar = document.getElementById('sidebar');
    
    let lastScrollTop = 0;
    let hideTimeout;
    let isScrolling = false;
    let isHovered = false;
    
    function startHideTimer() {
        clearTimeout(hideTimeout);
        hideTimeout = setTimeout(() => {
            if (!isHovered && !isScrolling) {
                hideNavbar();
            }
        }, 4000);
    }
    
    function showNavbar() {
        navbar.classList.remove('navbar-hidden');
        navbar.classList.add('navbar-visible');
        hoverZone.classList.remove('active');
        document.body.style.paddingTop = '70px';
        if (sidebar) {
            sidebar.style.top = '70px';
        }
    }
    
    function hideNavbar() {
        navbar.classList.remove('navbar-visible');
        navbar.classList.add('navbar-hidden');
        hoverZone.classList.add('active');
        document.body.style.paddingTop = '0';
        if (sidebar) {
            sidebar.style.top = '0';
        }
    }
    
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        isScrolling = true;
        clearTimeout(scrollTimeout);
        
        showNavbar();
        
        scrollTimeout = setTimeout(() => {
            isScrolling = false;
            startHideTimer();
        }, 200);
        
        lastScrollTop = window.pageYOffset;
    });
    
    hoverZone.addEventListener('mouseenter', function() {
        isHovered = true;
        showNavbar();
        clearTimeout(hideTimeout);
    });
    
    navbar.addEventListener('mouseenter', function() {
        isHovered = true;
        clearTimeout(hideTimeout);
    });
    
    navbar.addEventListener('mouseleave', function() {
        isHovered = false;
        startHideTimer();
    });
    
    startHideTimer();
    
    navbar.addEventListener('click', function() {
        clearTimeout(hideTimeout);
        startHideTimer();
    });
    
    navbar.addEventListener('focus', function() {
        clearTimeout(hideTimeout);
    }, true);
    
    navbar.addEventListener('blur', function() {
        startHideTimer();
    }, true);
});
</script>

<main class="main-content">
  <!-- Updated header section with breadcrumb navigation to match other admin pages -->
  <div class="d-flex align-items-center justify-content-between mb-4">
    <div>
      <h2 class="mb-1 fw-bold">Validasi User Baru</h2>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
          <li class="breadcrumb-item active">Validation User</li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Updated card structure to match modern design -->
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <div class="d-flex align-items-center gap-3 mb-4">
        <div class="d-flex align-items-center justify-content-center bg-primary rounded-circle" style="width: 48px; height: 48px;">
          <i class="bi bi-person-check text-white fs-5"></i>
        </div>
        <div>
          <h5 class="mb-0 fw-semibold">Daftar User Menunggu Persetujuan</h5>
          <p class="text-muted mb-0 small">Kelola persetujuan registrasi user baru</p>
        </div>
      </div>
      
      <?php echo $message; ?>
      
      <div class="table-responsive">
        <!-- Updated table to use modern styling -->
        <table class="table table-modern mb-0">
          <thead>
            <tr>
              <th>Nama Lengkap</th>
              <th>Username</th>
              <th>Role</th>
              <th>Tanggal Daftar</th>
              <th style="width: 200px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (mysqli_num_rows($pending_users) > 0) {
                while ($user = mysqli_fetch_assoc($pending_users)) {
                    echo '<tr>';
                    echo '<td>
                            <div class="d-flex align-items-center gap-2">
                              <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.75rem; color: white;">
                                '.strtoupper(substr($user['nama_lengkap'], 0, 2)).'
                              </div>
                              <span>'.htmlspecialchars($user['nama_lengkap']).'</span>
                            </div>
                          </td>';
                    echo '<td><span class="fw-medium">'.htmlspecialchars($user['username']).'</span></td>';
                    echo '<td><span class="badge bg-info">'.ucfirst($user['role']).'</span></td>';
                    echo '<td><span class="text-muted">'.date('d M Y H:i', strtotime($user['created_at'])).'</span></td>';
                    echo '<td>';
                    echo '<div class="d-flex gap-2">';
                    echo '<form method="POST" class="d-inline">';
                    echo '<input type="hidden" name="user_id" value="'.$user['id_user'].'">';
                    echo '<input type="hidden" name="action" value="approve">';
                    echo '<button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check-lg me-1"></i>Setujui</button>';
                    echo '</form>';
                    echo '<form method="POST" class="d-inline">';
                    echo '<input type="hidden" name="user_id" value="'.$user['id_user'].'">';
                    echo '<input type="hidden" name="action" value="reject">';
                    echo '<button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-x-lg me-1"></i>Tolak</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="5" class="text-center text-muted py-5">
                        <i class="bi bi-person-check fs-1 text-muted mb-3 d-block"></i>
                        <p class="mb-0">Tidak ada user yang menunggu validasi</p>
                      </td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
      
      <!-- Updated back button styling -->
      <div class="mt-4 pt-3 border-top">
        <a href="index.php" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
        </a>
      </div>
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
