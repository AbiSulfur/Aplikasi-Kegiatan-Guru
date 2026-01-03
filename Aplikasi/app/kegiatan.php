<?php 
include 'validation.php';
include 'koneksi.php'; 
$conn = $koneksi;

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
requireAdmin();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manajemen Kegiatan - Aplikasi Kegiatan Guru</title>
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
      /*
      transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94), 
                  box-shadow 0.3s ease-out;
      */
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 2rem;
    }
    
    /*
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
    */
    
    .sidebar {
      width: var(--sidebar-width);
      background: white;
      border-right: 1px solid #e2e8f0;
      min-height: calc(100vh - var(--header-height));
      position: fixed;
      top: var(--header-height);
      left: 0;
      z-index: 1000;
      /* transition: top 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94); */
    }
    
    /*
    .navbar-hidden ~ .sidebar,
    .navbar-hidden + * .sidebar {
      top: 0;
    }
    */
    
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
  </style>
</head>
<body>
<!-- <div class="navbar-hover-zone" id="navbarHoverZone"></div> -->

<nav class="main-header" id="mainHeader">
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
          <?php 
          $profile_pic = isset($_SESSION['profile_picture']) && $_SESSION['profile_picture'] 
              ? '../uploads/profile_pictures/' . $_SESSION['profile_picture'] 
              : '/img/avatar.png';
          ?>
          <img src="<?php echo $profile_pic; ?>" alt="Admin" class="user-avatar">
          <div class="d-flex flex-column justify-content-center">
            <span class="fw-semibold text-dark" style="font-size: 0.875rem; line-height: 1.2;"><?php echo $_SESSION['nama_lengkap']; ?></span>
            <span class="text-muted" style="font-size: 0.75rem; line-height: 1.2;"><?php echo ucfirst($_SESSION['role'] ?? 'User'); ?></span>
          </div>
          <i class="bi bi-chevron-down text-muted"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="userDropdown" style="min-width: 200px;">
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
        <a class="nav-link" href="kelas.php">
          <i class="bi bi-door-open"></i>
          Kelas
        </a>
      </li>
       <li class="nav-item">
        <a class="nav-link" href="user_validation.php">
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

<!--
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdownElementList = document.querySelectorAll('.dropdown-toggle, [data-bs-toggle="dropdown"]');
    const dropdownList = [...dropdownElementList].map(dropdownToggleEl => new bootstrap.Dropdown(dropdownToggleEl));
    
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
        if(hoverZone) hoverZone.classList.remove('active');
        document.body.style.paddingTop = '70px';
        if (sidebar) {
            sidebar.style.top = '70px';
        }
    }
    
    function hideNavbar() {
        navbar.classList.remove('navbar-visible');
        navbar.classList.add('navbar-hidden');
        if(hoverZone) hoverZone.classList.add('active');
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
    
    if(hoverZone) {
        hoverZone.addEventListener('mouseenter', function() {
            isHovered = true;
            showNavbar();
            clearTimeout(hideTimeout);
        });
    }
    
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
-->

<main class="main-content">
  <!-- Updated header section with proper alignment -->
  <div class="d-flex align-items-center justify-content-between mb-4">
    <div>
      <h2 class="mb-1 fw-bold">Manajemen Kegiatan</h2>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
          <li class="breadcrumb-item active">Kegiatan</li>
        </ol>
      </nav>
    </div>
    <a href="tambah_kegiatan.php" class="btn btn-primary-modern btn-modern">
      <i class="bi bi-plus-circle me-2"></i>
      Tambah Kegiatan
    </a>
  </div>

  <!-- Updated statistics cards with proper grid structure -->
  <div class="row g-4 mb-4">
    <?php
    $total_kegiatan = 0;
    $kegiatan_hari_ini = 0;
    $kegiatan_minggu_ini = 0;
    $kegiatan_selesai = 0;
    
    if ($conn) {
      $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM kegiatan_guru");
      if ($result) {
        $total_kegiatan = mysqli_fetch_assoc($result)['total'] ?? 0;
      }
      
      $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM kegiatan_guru WHERE DATE(tanggal) = CURDATE()");
      if ($result) {
        $kegiatan_hari_ini = mysqli_fetch_assoc($result)['total'] ?? 0;
      }
      
      $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM kegiatan_guru WHERE WEEK(tanggal) = WEEK(NOW())");
      if ($result) {
        $kegiatan_minggu_ini = mysqli_fetch_assoc($result)['total'] ?? 0;
      }
      
      $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM kegiatan_guru WHERE tanggal < NOW()");
      if ($result) {
        $kegiatan_selesai = mysqli_fetch_assoc($result)['total'] ?? 0;
      }
    }
    ?>
    
    <div class="col-lg-3 col-md-6">
      <div class="metric-card d-flex align-items-center gap-3">
        <div class="metric-icon icon-purple">
          <i class="bi bi-calendar-event"></i>
        </div>
        <div>
          <h4 class="fw-bold mb-0"><?= $total_kegiatan ?></h4>
          <span class="text-muted">Total Kegiatan</span>
        </div>
      </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
      <div class="metric-card d-flex align-items-center gap-3">
        <div class="metric-icon icon-blue">
          <i class="bi bi-calendar-check"></i>
        </div>
        <div>
          <h4 class="fw-bold mb-0"><?= $kegiatan_hari_ini ?></h4>
          <span class="text-muted">Hari Ini</span>
        </div>
      </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
      <div class="metric-card d-flex align-items-center gap-3">
        <div class="metric-icon icon-orange">
          <i class="bi bi-calendar-week"></i>
        </div>
        <div>
          <h4 class="fw-bold mb-0"><?= $kegiatan_minggu_ini ?></h4>
          <span class="text-muted">Minggu Ini</span>
        </div>
      </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
      <div class="metric-card d-flex align-items-center gap-3">
        <div class="metric-icon icon-green">
          <i class="bi bi-check-circle"></i>
        </div>
        <div>
          <h4 class="fw-bold mb-0"><?= $kegiatan_selesai ?></h4>
          <span class="text-muted">Selesai</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Updated table section with proper card structure -->
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
        <h5 class="mb-0 fw-semibold">Daftar Semua Kegiatan</h5>
        <div class="d-flex flex-column flex-sm-row gap-2">
          <div class="position-relative">
            <i class="bi bi-search position-absolute" style="left: 12px; top: 50%; transform: translateY(-50%); color: #64748b; z-index: 10;"></i>
            <input type="text" id="searchInput" class="form-control" placeholder="Cari kegiatan..." style="padding-left: 2.5rem; border-radius: 0.5rem; min-width: 200px;">
          </div>
          <select id="filterJenis" class="form-select" style="border-radius: 0.5rem; min-width: 150px;">
            <option value="">Semua Jenis</option>
            <?php
            if ($conn) {
              $jenis_result = mysqli_query($conn, "SELECT DISTINCT nama_jenis FROM jenis_kegiatan ORDER BY nama_jenis");
              if ($jenis_result) {
                while ($jenis = mysqli_fetch_assoc($jenis_result)) {
                  echo '<option value="'.$jenis['nama_jenis'].'">'.$jenis['nama_jenis'].'</option>';
                }
              }
            }
            ?>
          </select>
          <select id="filterStatus" class="form-select" style="border-radius: 0.5rem; min-width: 150px;">
            <option value="">Semua Status</option>
            <option value="Terjadwal">Terjadwal</option>
            <option value="Selesai">Selesai</option>
          </select>
        </div>
      </div>
      
      <div class="table-responsive">
        <table class="table table-modern mb-0" id="kegiatanTable">
          <thead>
            <tr>
              <th style="width: 60px;">#</th>
              <th style="width: 120px;">Tanggal</th>
              <th style="width: 180px;">Guru</th>
              <th style="width: 100px;">Kelas</th>
              <th style="width: 120px;">Jenis</th>
              <th>Laporan</th>
              <th style="width: 100px;">Status</th>
              <th style="width: 120px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1;
          if ($conn) {
            $sql = "SELECT kg.id_kegiatan, kg.tanggal, kg.laporan, u.nama_lengkap as guru, k.nama_kelas as kelas, j.nama_jenis as jenis
                      FROM kegiatan_guru kg
                      LEFT JOIN users u ON kg.id_user = u.id_user
                      LEFT JOIN kelas k ON kg.id_kelas = k.id_kelas
                      LEFT JOIN jenis_kegiatan j ON kg.id_jenis = j.id_jenis
                      ORDER BY kg.tanggal DESC";
            $res = mysqli_query($conn, $sql);
            
            if ($res && mysqli_num_rows($res) > 0) {
              while ($row = mysqli_fetch_assoc($res)) {
                $status_class = (strtotime($row['tanggal']) > time()) ? 'bg-warning' : 'bg-success';
                $status_text = (strtotime($row['tanggal']) > time()) ? 'Terjadwal' : 'Selesai';
                
                echo '<tr>';
                echo '<td><span class="fw-medium text-muted">'.$no++.'</span></td>';
                echo '<td><span class="fw-medium">'.date('d M Y', strtotime($row['tanggal'])).'</span></td>';
                echo '<td>
                        <div class="d-flex align-items-center gap-2">
                          <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.75rem; color: white;">
                            '.strtoupper(substr($row['guru'] ?? 'N/A', 0, 2)).'
                          </div>
                          <span class="text-truncate">'.htmlspecialchars($row['guru'] ?? 'N/A').'</span>
                        </div>
                      </td>';
                echo '<td><span class="badge bg-light text-dark">'.htmlspecialchars($row['kelas'] ?? 'N/A').'</span></td>';
                echo '<td><span class="badge bg-primary">'.htmlspecialchars($row['jenis'] ?? 'N/A').'</span></td>';
                echo '<td><span class="text-truncate d-inline-block" style="max-width: 300px;" title="'.htmlspecialchars($row['laporan'] ?? '').'">'.htmlspecialchars(substr($row['laporan'] ?? '',0,60)).'...</span></td>';
                echo '<td><span class="badge '.$status_class.'">'.$status_text.'</span></td>';
                echo '<td>
                        <div class="btn-group" role="group">
                          <button class="btn btn-sm btn-outline-primary" onclick="showDetailModal('.$row['id_kegiatan'].')" title="Lihat Detail">
                            <i class="bi bi-eye"></i>
                          </button>
                          <a class="btn btn-sm btn-outline-warning" href="edit_kegiatan.php?id='.$row['id_kegiatan'].'" title="Edit">
                            <i class="bi bi-pencil"></i>
                          </a>
                          <a class="btn btn-sm btn-outline-danger" href="hapus_kegiatan.php?id='.$row['id_kegiatan'].'" onclick="return confirm(\'Hapus kegiatan ini?\')" title="Hapus">
                            <i class="bi bi-trash"></i>
                          </a>
                        </div>
                      </td>';
                echo '</tr>';
              }
            } else {
              echo '<tr><td colspan="8" class="text-center text-muted py-5">
                      <i class="bi bi-calendar-x fs-1 text-muted mb-3 d-block"></i>
                      <p class="mb-0">Belum ada kegiatan yang tersedia</p>
                      <a href="tambah_kegiatan.php" class="btn btn-primary-modern btn-modern mt-3">
                        <i class="bi bi-plus-circle me-2"></i>
                        Tambah Kegiatan Pertama
                      </a>
                    </td></tr>';
            }
          } else {
            echo '<tr><td colspan="8" class="text-center text-danger py-5">
                    <i class="bi bi-exclamation-triangle fs-1 text-danger mb-3 d-block"></i>
                    <p class="mb-0">Koneksi database bermasalah</p>
                  </td></tr>';
          }
          ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>

<!-- Added modal for detail kegiatan -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailModalLabel">Detail Kegiatan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="detailModalBody">
        <div class="text-center py-4">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <p class="mt-2 text-muted">Memuat detail kegiatan...</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterJenis = document.getElementById('filterJenis');
    const filterStatus = document.getElementById('filterStatus');
    const table = document.getElementById('kegiatanTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const jenisFilter = filterJenis.value.toLowerCase();
        const statusFilter = filterStatus.value.toLowerCase();

        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const cells = row.getElementsByTagName('td');
            
            if (cells.length > 1) {
                const guru = cells[2].textContent.toLowerCase();
                const kelas = cells[3].textContent.toLowerCase();
                const jenis = cells[4].textContent.toLowerCase();
                const laporan = cells[5].textContent.toLowerCase();
                const status = cells[6].textContent.toLowerCase();
                
                const matchesSearch = guru.includes(searchTerm) || 
                                    kelas.includes(searchTerm) || 
                                    laporan.includes(searchTerm);
                const matchesJenis = jenisFilter === '' || jenis.includes(jenisFilter);
                const matchesStatus = statusFilter === '' || status.includes(statusFilter);
                
                if (matchesSearch && matchesJenis && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }
    }

    searchInput.addEventListener('keyup', filterTable);
    filterJenis.addEventListener('change', filterTable);
    filterStatus.addEventListener('change', filterTable);
});

function showDetailModal(id) {
    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
    const modalBody = document.getElementById('detailModalBody');
    
    // Show loading state
    modalBody.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Memuat detail kegiatan...</p>
        </div>
    `;
    
    modal.show();
    
    fetch('kegiatan.php?ajax=detail&id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                modalBody.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-muted">Jenis Kegiatan</label>
                                <p class="mb-0">${data.jenis}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-muted">Tanggal</label>
                                <p class="mb-0">${data.tanggal}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-muted">Guru</label>
                                <p class="mb-0">${data.guru}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-muted">Kelas</label>
                                <p class="mb-0">${data.kelas}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-muted">Laporan Kegiatan</label>
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-0" style="white-space: pre-wrap;">${data.laporan}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                modalBody.innerHTML = `
                    <div class="text-center py-4">
                        <i class="bi bi-exclamation-triangle fs-1 text-warning mb-3 d-block"></i>
                        <p class="mb-0 text-muted">${data.message || 'Gagal memuat detail kegiatan'}</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            modalBody.innerHTML = `
                <div class="text-center py-4">
                    <i class="bi bi-exclamation-triangle fs-1 text-danger mb-3 d-block"></i>
                    <p class="mb-0 text-muted">Terjadi kesalahan saat memuat data</p>
                </div>
            `;
        });
}
</script>

</body>
</html>
