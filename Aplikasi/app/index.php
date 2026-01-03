<?php
include 'validation.php';
include 'koneksi.php';

requireAdmin();

// Get total kegiatan count
$q_total_kegiatan = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kegiatan_guru");
$jml_kegiatan = mysqli_fetch_assoc($q_total_kegiatan)['total'] ?? 0;

// Get kegiatan this month
$q_month = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kegiatan_guru WHERE MONTH(tanggal) = MONTH(CURRENT_DATE()) AND YEAR(tanggal) = YEAR(CURRENT_DATE())");
$jml_month = mysqli_fetch_assoc($q_month)['total'] ?? 0;

// Get kegiatan today
$q_today = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kegiatan_guru WHERE tanggal = CURRENT_DATE()");
$jml_today = mysqli_fetch_assoc($q_today)['total'] ?? 0;

// Get total guru count
$q_total_guru = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users WHERE role='guru'");
$jml_guru = mysqli_fetch_assoc($q_total_guru)['total'] ?? 0;

// Get total siswa count
$q_total_siswa = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users WHERE role='siswa'");
$jml_siswa = mysqli_fetch_assoc($q_total_siswa)['total'] ?? 0;

// Get total kelas count
$q_total_kelas = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kelas");
$jml_kelas = mysqli_fetch_assoc($q_total_kelas)['total'] ?? 0;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Aplikasi Kegiatan Guru - Admin</title>
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

    /* Added metric icon styling for status cards */
    .metric-icon.icon-danger {
      background: linear-gradient(135deg, #ef4444, #dc2626);
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
        <a class="nav-link active" href="index.php">
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


<main class="main-content">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="fw-bold mb-1">Dashboard</h2>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Metric Cards - Symmetrical 3x2 Layout -->
  <!-- Reorganized into 3-column layout for better symmetry -->
  <div class="row g-4 mb-4">
    <div class="col-lg-4 col-md-6">
      <div class="metric-card d-flex align-items-center gap-3">
        <div class="metric-icon icon-purple">
          <i class="bi bi-calendar-event"></i>
        </div>
        <div>
          <h4 class="fw-bold mb-0"><?= $jml_kegiatan ?></h4>
          <span class="text-muted">Total Kegiatan</span>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-6">
      <div class="metric-card d-flex align-items-center gap-3">
        <div class="metric-icon icon-green">
          <i class="bi bi-person-badge"></i>
        </div>
        <div>
          <h4 class="fw-bold mb-0"><?= $jml_guru ?></h4>
          <span class="text-muted">Total Guru</span>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-6">
      <div class="metric-card d-flex align-items-center gap-3">
        <div class="metric-icon icon-blue">
          <i class="bi bi-people"></i>
        </div>
        <div>
          <h4 class="fw-bold mb-0"><?= $jml_siswa ?></h4>
          <span class="text-muted">Total Siswa</span>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4 mb-4">
    <div class="col-lg-4 col-md-6">
      <div class="metric-card d-flex align-items-center gap-3">
        <div class="metric-icon icon-orange">
          <i class="bi bi-door-open"></i>
        </div>
        <div>
          <h4 class="fw-bold mb-0"><?= $jml_kelas ?></h4>
          <span class="text-muted">Total Kelas</span>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-6">
      <div class="metric-card d-flex align-items-center gap-3">
        <div class="metric-icon icon-green">
          <i class="bi bi-check-circle"></i>
        </div>
        <div>
          <h4 class="fw-bold mb-0"><?= $jml_month ?></h4>
          <span class="text-muted">Kegiatan Bulan Ini</span>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-6">
      <div class="metric-card d-flex align-items-center gap-3">
        <div class="metric-icon icon-orange">
          <i class="bi bi-clock"></i>
        </div>
        <div>
          <h4 class="fw-bold mb-0"><?= $jml_today ?></h4>
          <span class="text-muted">Kegiatan Hari Ini</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Added export buttons and filter section -->
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white">
      <div class="row g-3 align-items-center">
        <div class="col-lg-6">
          <h5 class="card-title fw-bold mb-0">
            <i class="bi bi-file-earmark-text text-primary me-2"></i>
            Laporan Kegiatan Bulan Ini
          </h5>
        </div>
        <div class="col-lg-6">
          <div class="d-flex gap-2 justify-content-end">
            <button class="btn btn-success btn-sm" onclick="exportToExcel()">
              <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
            </button>
            <button class="btn btn-danger btn-sm" onclick="exportToPDF()">
              <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="row g-3 mb-3">
        <div class="col-lg-3">
          <label class="form-label fw-semibold">Periode</label>
          <select class="form-select" id="periodFilter">
            <option value="monthly" selected>Bulan Ini</option>
            <option value="weekly">Minggu Ini</option>
            <option value="daily">Hari Ini</option>
            <option value="all">Semua</option>
          </select>
        </div>
        <div class="col-lg-3">
          <label class="form-label fw-semibold">Jenis Kegiatan</label>
          <select class="form-select" id="jenisFilter">
            <option value="">Semua Jenis</option>
            <?php
            $jenis_q = mysqli_query($koneksi, "SELECT * FROM jenis_kegiatan ORDER BY nama_jenis");
            while ($j = mysqli_fetch_assoc($jenis_q)) {
              echo '<option value="'.$j['id_jenis'].'">'.$j['nama_jenis'].'</option>';
            }
            ?>
          </select>
        </div>
        <div class="col-lg-3">
          <label class="form-label">&nbsp;</label>
          <button class="btn btn-primary w-100" onclick="loadReportData()">
            <i class="bi bi-search me-2"></i>Tampilkan
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4 mb-4">
    <div class="col-lg-6">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body">
          <h5 class="card-title fw-bold mb-3">
            <i class="bi bi-bar-chart-fill text-primary me-2"></i>
            Grafik Kegiatan Bulanan
          </h5>
          <canvas id="monthlyActivitiesChart" style="max-height: 300px;"></canvas>
        </div>
      </div>
    </div>
    
    <div class="col-lg-6">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body">
          <h5 class="card-title fw-bold mb-3">
            <i class="bi bi-pie-chart-fill text-success me-2"></i>
            Distribusi Jenis Kegiatan
          </h5>
          <canvas id="activityTypeChart" style="max-height: 300px;"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4 mb-4">
    <div class="col-lg-8">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body">
          <h5 class="card-title fw-bold mb-3">
            <i class="bi bi-graph-up text-info me-2"></i>
            Top 5 Guru Paling Aktif
          </h5>
          <canvas id="topTeachersChart" style="max-height: 300px;"></canvas>
        </div>
      </div>
    </div>
    
    <div class="col-lg-4">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body">
          <h5 class="card-title fw-bold mb-3">
            <i class="bi bi-door-closed-fill text-warning me-2"></i>
            Kegiatan per Kelas
          </h5>
          <canvas id="classDistributionChart" style="max-height: 300px;"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-lg-6">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body">
          <h5 class="card-title fw-bold">
            <i class="bi bi-clock-history text-primary me-2"></i>
            Aktivitas Terbaru
          </h5>
          <div class="table-responsive">
            <table class="table table-modern align-middle">
              <thead>
                <tr>
                  <th>Judul Kegiatan</th>
                  <th>Guru</th>
                  <th>Status</th>
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

                $res = mysqli_query($koneksi, $sql);
                if ($res && mysqli_num_rows($res) > 0) {
                  while ($row = mysqli_fetch_assoc($res)) {
                    $status = (strtotime($row['tanggal']) < time()) ? 'Selesai' : 'Berlangsung';
                    $statusClass = ($status == 'Selesai') ? 'bg-success-subtle text-success' : 'bg-primary-subtle text-primary';
                    echo '<tr>';
                    echo '<td>'.htmlspecialchars(substr($row['laporan'],0,30)).'...</td>';
                    echo '<td>'.$row['guru'].'</td>';
                    echo '<td><span class="badge '.$statusClass.'">'.$status.'</span></td>';
                    echo '</tr>';
                  }
                } else {
                  echo '<tr><td colspan="3" class="text-center text-muted py-4">Belum ada kegiatan</td></tr>';
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-lg-6">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body">
          <h5 class="card-title fw-bold">
            <i class="bi bi-trophy text-warning me-2"></i>
            Statistik Guru
          </h5>
          <div class="table-responsive">
            <table class="table table-modern align-middle">
              <thead>
                <tr>
                  <th>Nama Guru</th>
                  <th>Total Kegiatan</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sql_guru = "SELECT u.nama_lengkap, COUNT(kg.id_kegiatan) as total,
                            u.status as user_status
                            FROM users u
                            LEFT JOIN kegiatan_guru kg ON u.id_user = kg.id_user
                            WHERE u.role='guru'
                            GROUP BY u.id_user
                            ORDER BY total DESC
                            LIMIT 5";

                $res_guru = mysqli_query($koneksi, $sql_guru);
                if ($res_guru && mysqli_num_rows($res_guru) > 0) {
                  while ($row = mysqli_fetch_assoc($res_guru)) {
                    $statusClass = ($row['user_status'] == 'active') ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning';
                    $statusText = ($row['user_status'] == 'active') ? 'Aktif' : ucfirst($row['user_status']);
                    echo '<tr>';
                    echo '<td>'.$row['nama_lengkap'].'</td>';
                    echo '<td><span class="badge bg-primary">'.$row['total'].'</span></td>';
                    echo '<td><span class="badge '.$statusClass.'">'.$statusText.'</span></td>';
                    echo '</tr>';
                  }
                } else {
                  echo '<tr><td colspan="3" class="text-center text-muted py-4">Belum ada data guru</td></tr>';
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
let currentReportData = [];

document.addEventListener('DOMContentLoaded', function() {
    var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });
    
    initializeCharts();
    loadReportData();
});

function initializeCharts() {
    <?php
    // Monthly activities chart data
    $monthly_query = "SELECT 
        DATE_FORMAT(tanggal, '%Y-%m') as bulan,
        DATE_FORMAT(tanggal, '%M %Y') as bulan_nama,
        COUNT(*) as jumlah
        FROM kegiatan_guru
        WHERE tanggal <= CURDATE()
        GROUP BY DATE_FORMAT(tanggal, '%Y-%m')
        ORDER BY bulan ASC
        LIMIT 12";
    
    $monthly_result = mysqli_query($koneksi, $monthly_query);
    $labels = [];
    $data = [];
    
    if ($monthly_result && mysqli_num_rows($monthly_result) > 0) {
        while ($row = mysqli_fetch_assoc($monthly_result)) {
            $labels[] = $row['bulan_nama'];
            $data[] = $row['jumlah'];
        }
    }
    
    if (empty($labels)) {
        $labels = ['January 2025', 'February 2025', 'March 2025'];
        $data = [0, 0, 0];
    }
    
    // Activity type distribution
    $type_query = "SELECT j.nama_jenis, COUNT(kg.id_kegiatan) as jumlah
                   FROM jenis_kegiatan j
                   LEFT JOIN kegiatan_guru kg ON j.id_jenis = kg.id_jenis
                   GROUP BY j.id_jenis
                   ORDER BY jumlah DESC";
    $type_result = mysqli_query($koneksi, $type_query);
    $type_labels = [];
    $type_data = [];
    
    if ($type_result && mysqli_num_rows($type_result) > 0) {
        while ($row = mysqli_fetch_assoc($type_result)) {
            $type_labels[] = $row['nama_jenis'];
            $type_data[] = $row['jumlah'];
        }
    }
    
    // Top teachers
    $teacher_query = "SELECT u.nama_lengkap, COUNT(kg.id_kegiatan) as jumlah
                      FROM users u
                      LEFT JOIN kegiatan_guru kg ON u.id_user = kg.id_user
                      WHERE u.role='guru'
                      GROUP BY u.id_user
                      ORDER BY jumlah DESC
                      LIMIT 5";
    $teacher_result = mysqli_query($koneksi, $teacher_query);
    $teacher_labels = [];
    $teacher_data = [];
    
    if ($teacher_result && mysqli_num_rows($teacher_result) > 0) {
        while ($row = mysqli_fetch_assoc($teacher_result)) {
            $teacher_labels[] = $row['nama_lengkap'];
            $teacher_data[] = $row['jumlah'];
        }
    }
    
    // Class distribution
    $class_query = "SELECT k.nama_kelas, COUNT(kg.id_kegiatan) as jumlah
                    FROM kelas k
                    LEFT JOIN kegiatan_guru kg ON k.id_kelas = kg.id_kelas
                    GROUP BY k.id_kelas
                    ORDER BY jumlah DESC";
    $class_result = mysqli_query($koneksi, $class_query);
    $class_labels = [];
    $class_data = [];
    
    if ($class_result && mysqli_num_rows($class_result) > 0) {
        while ($row = mysqli_fetch_assoc($class_result)) {
            $class_labels[] = $row['nama_kelas'];
            $class_data[] = $row['jumlah'];
        }
    }
    ?>
    
    // Monthly Activities Chart
    const ctx1 = document.getElementById('monthlyActivitiesChart');
    if (ctx1) {
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Kegiatan',
                    data: <?php echo json_encode($data); ?>,
                    backgroundColor: 'rgba(99, 102, 241, 0.8)',
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 1,
                    borderRadius: 8,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: true },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Kegiatan: ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    }
    
    // Activity Type Chart
    const ctx2 = document.getElementById('activityTypeChart');
    if (ctx2) {
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($type_labels); ?>,
                datasets: [{
                    data: <?php echo json_encode($type_data); ?>,
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
    
    // Top Teachers Chart
    const ctx3 = document.getElementById('topTeachersChart');
    if (ctx3) {
        new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($teacher_labels); ?>,
                datasets: [{
                    label: 'Jumlah Kegiatan',
                    data: <?php echo json_encode($teacher_data); ?>,
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    }
    
    // Class Distribution Chart
    const ctx4 = document.getElementById('classDistributionChart');
    if (ctx4) {
        new Chart(ctx4, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($class_labels); ?>,
                datasets: [{
                    data: <?php echo json_encode($class_data); ?>,
                    backgroundColor: [
                        '#f59e0b', '#8b5cf6', '#ec4899', '#14b6a6', '#f97316', '#06b6d4'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
}

function loadReportData() {
    const period = document.getElementById('periodFilter').value;
    const jenis = document.getElementById('jenisFilter').value;
    
    const params = new URLSearchParams({
        period: period,
        jenis: jenis
    });
    
    fetch('get_report_data.php?' + params.toString())
        .then(response => response.json())
        .then(data => {
            currentReportData = data;
            console.log('[v0] Report data loaded:', data.length + ' records');
        })
        .catch(error => {
            console.error('[v0] Error loading report data:', error);
            currentReportData = [];
        });
}

function exportToExcel() {
    const period = document.getElementById('periodFilter').value;
    const jenis = document.getElementById('jenisFilter').value;
    
    const params = new URLSearchParams({
        period: period,
        jenis: jenis
    });
    
    window.location.href = 'export_excel.php?' + params.toString();
}

function exportToPDF() {
    const period = document.getElementById('periodFilter').value;
    const jenis = document.getElementById('jenisFilter').value;
    
    const params = new URLSearchParams({
        period: period,
        jenis: jenis
    });
    
    window.open('export_pdf.php?' + params.toString(), '_blank');
}
</script>

</body>
</html>
