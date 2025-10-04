<?php 
session_start();
include 'koneksi.php'; 

$conn = $koneksi;

if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'guru' && $_SESSION['role'] != 'siswa')) {
    header("Location: login.php");
    exit();
}

// Handle AJAX request for activity detail
if (isset($_GET['ajax']) && $_GET['ajax'] === 'detail' && isset($_GET['id'])) {
    header('Content-Type: application/json');
    
    $id = intval($_GET['id']);
    $sql = "SELECT kg.*, u.nama_lengkap as guru, k.nama_kelas as kelas, j.nama_jenis as jenis
            FROM kegiatan_guru kg
            LEFT JOIN users u ON kg.id_user = u.id_user
            LEFT JOIN kelas k ON kg.id_kelas = k.id_kelas
            LEFT JOIN jenis_kegiatan j ON kg.id_jenis = j.id_jenis
            WHERE kg.id_kegiatan = $id";
    
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        echo json_encode([
            'success' => true,
            'jenis' => $data['jenis'] ?? 'N/A',
            'tanggal' => date('d F Y', strtotime($data['tanggal'])),
            'guru' => $data['guru'] ?? 'N/A',
            'kelas' => $data['kelas'] ?? 'N/A',
            'laporan' => $data['laporan'] ?? 'Tidak ada laporan'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Data kegiatan tidak ditemukan'
        ]);
    }
    exit;
}

// Assuming we have user session - for demo purposes, let's use sample data
// In real implementation, you'd get user ID from session
$user_id = $_SESSION['id_user'] ?? 1;
$user_role = $_SESSION['role'] ?? 'siswa';

// Count user's activities (if teacher) or activities they're enrolled in (if student)
if ($user_role == 'guru') {
    $q1 = mysqli_query($conn, "SELECT COUNT(*) as total FROM kegiatan_guru WHERE id_user = $user_id");
    $my_activities = ($q1 && mysqli_num_rows($q1) > 0) ? mysqli_fetch_assoc($q1)['total'] : 0;
    $activity_label = "Kegiatan Saya";
} else {
    // For students, count all activities (since we don't have user-class relationship table)
    $q1 = mysqli_query($conn, "SELECT COUNT(*) as total FROM kegiatan_guru");
    $my_activities = ($q1 && mysqli_num_rows($q1) > 0) ? mysqli_fetch_assoc($q1)['total'] : 0;
    $activity_label = "Kegiatan Tersedia";
}

// Count total classes from kelas table
$q2 = mysqli_query($conn, "SELECT COUNT(*) as total FROM kelas");
$total_classes = ($q2 && mysqli_num_rows($q2) > 0) ? mysqli_fetch_assoc($q2)['total'] : 0;

// Count completed activities this month
$q3 = mysqli_query($conn, "SELECT COUNT(*) as total FROM kegiatan_guru 
                           WHERE MONTH(tanggal) = MONTH(CURRENT_DATE()) 
                           AND YEAR(tanggal) = YEAR(CURRENT_DATE())");
$monthly_activities = ($q3 && mysqli_num_rows($q3) > 0) ? mysqli_fetch_assoc($q3)['total'] : 0;

// Count upcoming activities (next 7 days)
$q4 = mysqli_query($conn, "SELECT COUNT(*) as total FROM kegiatan_guru 
                           WHERE tanggal BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY)");
$upcoming_activities = ($q4 && mysqli_num_rows($q4) > 0) ? mysqli_fetch_assoc($q4)['total'] : 0;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Aplikasi Kegiatan Guru - User Dashboard</title>
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
    
    .welcome-section {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border-radius: 1rem;
      padding: 2rem;
      margin-bottom: 2rem;
    }
    
    .metric-icon {
      width: 48px;
      height: 48px;
      border-radius: 0.75rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.25rem;
      color: white;
    }
    
    .icon-purple {
      background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    }
    
    .icon-green {
      background: linear-gradient(135deg, #10b981, #059669);
    }
    
    .icon-blue {
      background: linear-gradient(135deg, #3b82f6, #2563eb);
    }
    
    .icon-orange {
      background: linear-gradient(135deg, #f59e0b, #d97706);
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
    
    .modal-content {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .modal-header {
      border-bottom: 1px solid #e2e8f0;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border-radius: 1rem 1rem 0 0;
    }
    
    .modal-header .btn-close {
      filter: invert(1);
    }
    
    .modal-body {
      padding: 2rem;
    }
    
    .modal-footer {
      border-top: 1px solid #e2e8f0;
      padding: 1rem 2rem;
    }
    
    .activity-report {
      background: white;
      border-radius: 0.5rem;
      padding: 1rem;
      border-left: 4px solid #10b981;
      min-height: 100px;
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
        <?php
        $profile_query = mysqli_query($conn, "SELECT profile_picture FROM users WHERE id_user = $user_id");
        $profile_data = mysqli_fetch_assoc($profile_query);
        
        $profile_pic_path = isset($profile_data['profile_picture']) && $profile_data['profile_picture'] 
                           ? "../uploads/profile_pictures/" . $profile_data['profile_picture'] 
                           : "../aplikasi-kegiatan-admin-module/img/avatar.png";
        ?>
        <img src="<?php echo $profile_pic_path; ?>" alt="User" class="user-avatar">
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
        <a class="nav-link active" href="user_dashboard.php">
          <i class="bi bi-house-door"></i>
          Dashboard
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="kegiatan_user.php">
          <i class="bi bi-calendar-event"></i>
          Kegiatan
        </a>
      </li>
      <?php if ($user_role == 'guru'): ?>
      <li class="nav-item">
        <a class="nav-link" href="my_activities.php">
          <i class="bi bi-journal-bookmark"></i>
          Kegiatan Saya
        </a>
      </li>
      <?php endif; ?>
      <li class="nav-item">
        <a class="nav-link" href="schedule.php">
          <i class="bi bi-calendar-week"></i>
          Jadwal
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="profile.php">
          <i class="bi bi-person-circle"></i>
          Profil
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

<!-- <script>
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
</script> -->

<main class="main-content">
  <div class="welcome-section">
    <div class="row align-items-center">
      <div class="col-md-8">
        <h2 class="mb-2">Welcome back, <?php echo $_SESSION['nama_lengkap']; ?>!</h2>
        <p class="mb-0 opacity-90">Your personal activity dashboard</p>
      </div>
      <div class="col-md-4 text-end">
        <button class="btn btn-light btn-modern">
          <i class="bi bi-calendar-plus me-2"></i>
          My Schedule
        </button>
      </div>
    </div>
  </div>

  <div class="row g-4 mb-5">
    <div class="col-md-3">
      <div class="metric-card">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <p class="text-muted mb-1" style="font-size: 0.875rem;"><?= $activity_label ?></p>
            <h3 class="mb-0 fw-bold"><?= number_format($my_activities); ?></h3>
            <small class="text-success">
              <i class="bi bi-arrow-up"></i> 15% dari bulan lalu
            </small>
          </div>
          <div class="metric-icon icon-purple">
            <i class="bi bi-journal-bookmark"></i>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-md-3">
      <div class="metric-card">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <p class="text-muted mb-1" style="font-size: 0.875rem;">Kegiatan Bulan Ini</p>
            <h3 class="mb-0 fw-bold"><?= number_format($monthly_activities); ?></h3>
            <small class="text-success">
              <i class="bi bi-arrow-up"></i> 8% dari bulan lalu
            </small>
          </div>
          <div class="metric-icon icon-green">
            <i class="bi bi-calendar-check"></i>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-md-3">
      <div class="metric-card">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <p class="text-muted mb-1" style="font-size: 0.875rem;">Kegiatan Mendatang</p>
            <h3 class="mb-0 fw-bold"><?= number_format($upcoming_activities); ?></h3>
            <small class="text-info">
              <i class="bi bi-clock"></i> 7 hari ke depan
            </small>
          </div>
          <div class="metric-icon icon-blue">
            <i class="bi bi-calendar-event"></i>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-md-3">
      <div class="metric-card">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <p class="text-muted mb-1" style="font-size: 0.875rem;">Total Kelas Aktif</p>
            <h3 class="mb-0 fw-bold"><?= number_format($total_classes); ?></h3>
            <small class="text-success">
              <i class="bi bi-arrow-up"></i> 2% dari bulan lalu
            </small>
          </div>
          <div class="metric-icon icon-orange">
            <i class="bi bi-mortarboard"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4 mb-4">
    <div class="col-lg-8">
      <div class="metric-card">
        <div class="d-flex align-items-center justify-content-between mb-4">
          <h5 class="mb-0 fw-semibold">Kegiatan Terbaru</h5>
          <a href="kegiatan_user.php" class="btn btn-primary-modern btn-modern">
            <i class="bi bi-eye me-2"></i>
            Lihat Semua
          </a>
        </div>
        
        <div class="table-responsive">
          <table class="table table-modern mb-0">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Guru</th>
                <th>Kelas</th>
                <th>Jenis</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
            <?php
            $sql = "SELECT kg.id_kegiatan, kg.tanggal, kg.laporan,
                    u.nama_lengkap as guru, k.nama_kelas as kelas, j.nama_jenis as jenis
                    FROM kegiatan_guru kg
                    LEFT JOIN users u ON kg.id_user = u.id_user
                    LEFT JOIN kelas k ON kg.id_kelas = k.id_kelas
                    LEFT JOIN jenis_kegiatan j ON kg.id_jenis = j.id_jenis
                    ORDER BY kg.tanggal DESC
                    LIMIT 5";

            $res = mysqli_query($conn, $sql);
            if ($res && mysqli_num_rows($res) > 0) {
              while ($row = mysqli_fetch_assoc($res)) {
                $status = strtotime($row['tanggal']) > time() ? 'Mendatang' : 'Selesai';
                $status_class = strtotime($row['tanggal']) > time() ? 'bg-warning' : 'bg-success';
                
                echo '<tr>';
                echo '<td><span class="fw-medium">'.date('d M Y', strtotime($row['tanggal'])).'</span></td>';
                echo '<td>'.$row['guru'].'</td>';
                echo '<td><span class="badge bg-light text-dark">'.$row['kelas'].'</span></td>';
                echo '<td><span class="badge bg-primary">'.$row['jenis'].'</span></td>';
                echo '<td><span class="badge '.$status_class.'">'.$status.'</span></td>';
                echo '<td>
                        <button class="btn btn-sm btn-outline-primary" onclick="showActivityDetail('.$row['id_kegiatan'].')">
                          <i class="bi bi-eye"></i>
                        </button>
                      </td>';
                echo '</tr>';
              }
            } else {
              echo '<tr><td colspan="6" class="text-center text-muted py-4">Belum ada kegiatan</td></tr>';
            }
            ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    
    <div class="col-lg-4">
      <div class="metric-card">
        <h5 class="mb-4 fw-semibold">Aksi Cepat</h5>
        
        <div class="d-grid gap-3">
          <a href="kegiatan_user.php" class="btn btn-outline-primary btn-modern d-flex align-items-center">
            <i class="bi bi-list-ul me-3"></i>
            <div class="text-start">
              <div class="fw-medium">Lihat Semua Kegiatan</div>
              <small class="text-muted">Jelajahi semua aktivitas</small>
            </div>
          </a>
          
          <a href="profile.php" class="btn btn-outline-success btn-modern d-flex align-items-center">
            <i class="bi bi-person-circle me-3"></i>
            <div class="text-start">
              <div class="fw-medium">Profil Saya</div>
              <small class="text-muted">Kelola informasi pribadi</small>
            </div>
          </a>
          
          <a href="schedule.php" class="btn btn-outline-info btn-modern d-flex align-items-center">
            <i class="bi bi-calendar-week me-3"></i>
            <div class="text-start">
              <div class="fw-medium">Jadwal Mingguan</div>
              <small class="text-muted">Lihat jadwal kegiatan</small>
            </div>
          </a>
          
          <a href="notifications.php" class="btn btn-outline-warning btn-modern d-flex align-items-center">
            <i class="bi bi-bell me-3"></i>
            <div class="text-start">
              <div class="fw-medium">Notifikasi</div>
              <small class="text-muted">Pesan dan pengumuman</small>
            </div>
          </a>
        </div>
      </div>
      
      <div class="metric-card mt-4">
        <h6 class="mb-3 fw-semibold">Progress Bulan Ini</h6>
        
        <div class="mb-3">
          <div class="d-flex justify-content-between mb-1">
            <small class="text-muted">Kegiatan Selesai</small>
            <small class="fw-medium">75%</small>
          </div>
          <div class="progress" style="height: 6px;">
            <div class="progress-bar bg-success" style="width: 75%"></div>
          </div>
        </div>
        
        <div class="mb-3">
          <div class="d-flex justify-content-between mb-1">
            <small class="text-muted">Partisipasi</small>
            <small class="fw-medium">90%</small>
          </div>
          <div class="progress" style="height: 6px;">
            <div class="progress-bar bg-primary" style="width: 90%"></div>
          </div>
        </div>
        
        <div class="mb-0">
          <div class="d-flex justify-content-between mb-1">
            <small class="text-muted">Target Bulanan</small>
            <small class="fw-medium">60%</small>
          </div>
          <div class="progress" style="height: 6px;">
            <div class="progress-bar bg-warning" style="width: 60%"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Modal Detail Kegiatan -->
<div class="modal fade" id="activityDetailModal" tabindex="-1" aria-labelledby="activityDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="activityDetailModalLabel">
          <i class="bi bi-calendar-event me-2"></i>Detail Kegiatan
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="activityDetailContent">
          <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Memuat detail kegiatan...</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="bi bi-x-lg me-2"></i>Tutup
        </button>
        <button type="button" class="btn btn-primary" onclick="window.open('kegiatan.php', '_blank')">
          <i class="bi bi-eye me-2"></i>Lihat Semua Kegiatan
        </button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Function to show activity detail modal
function showActivityDetail(activityId) {
    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('activityDetailModal'));
    modal.show();
    
    // Reset content to loading state
    document.getElementById('activityDetailContent').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Memuat detail kegiatan...</p>
        </div>
    `;
    
    // Fetch activity details via AJAX
    fetch(`?ajax=detail&id=${activityId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayActivityDetail(data);
            } else {
                displayError(data.message || 'Gagal memuat detail kegiatan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            displayError('Terjadi kesalahan saat memuat detail kegiatan');
        });
}

// Function to display activity detail
function displayActivityDetail(data) {
    const content = `
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="card-title text-primary mb-3">
                            <i class="bi bi-info-circle me-2"></i>Informasi Kegiatan
                        </h6>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">Jenis Kegiatan</label>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-primary me-2">${data.jenis}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">Tanggal</label>
                            <p class="mb-0 fw-medium">${data.tanggal}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">Guru</label>
                            <p class="mb-0 fw-medium">${data.guru}</p>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold text-muted">Kelas</label>
                            <p class="mb-0 fw-medium">${data.kelas}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="card-title text-success mb-3">
                            <i class="bi bi-journal-text me-2"></i>Laporan Kegiatan
                        </h6>
                        <div class="activity-report">
                            <p class="mb-0">${data.laporan}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body text-white">
                        <h6 class="card-title mb-3">
                            <i class="bi bi-lightbulb me-2"></i>Catatan Penting
                        </h6>
                        <p class="mb-0 opacity-90">
                            Pastikan untuk selalu memperhatikan detail kegiatan dan melaporkan setiap perkembangan yang terjadi.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('activityDetailContent').innerHTML = content;
}

// Function to display error message
function displayError(message) {
    const content = `
        <div class="text-center py-4">
            <div class="text-danger mb-3">
                <i class="bi bi-exclamation-triangle fs-1"></i>
            </div>
            <h6 class="text-danger mb-2">Terjadi Kesalahan</h6>
            <p class="text-muted mb-0">${message}</p>
            <button class="btn btn-outline-primary btn-sm mt-3" onclick="showActivityDetail(document.querySelector('[onclick*=showActivityDetail]').onclick.toString().match(/\d+/)[0])">
                <i class="bi bi-arrow-clockwise me-2"></i>Coba Lagi
            </button>
        </div>
    `;
    
    document.getElementById('activityDetailContent').innerHTML = content;
}
</script>
</body>
</html>
