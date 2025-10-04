<?php
include 'validation.php';
include 'koneksi.php';

requireAdmin();
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
          <!-- Display actual logged in user name and role -->
          <span class="fw-semibold text-dark" style="font-size: 0.875rem; line-height: 1.2;"><?php echo $_SESSION['nama_lengkap']; ?></span>
          <span class="text-muted" style="font-size: 0.75rem; line-height: 1.2;"><?php echo ucfirst($_SESSION['role']); ?></span>
        </div>
        <!-- Added logout dropdown -->
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
      <h2 class="fw-bold mb-1">Dashboard</h2>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
        </ol>
      </nav>
    </div>
  </div>

  <?php
  $q1 = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kegiatan_guru");
  $jml_kegiatan = mysqli_fetch_assoc($q1)['total'] ?? 0;

  $q2 = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users WHERE role='guru'");
  $jml_guru = mysqli_fetch_assoc($q2)['total'] ?? 0;

  $q3 = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users WHERE role='siswa'");
  $jml_siswa = mysqli_fetch_assoc($q3)['total'] ?? 0;

  $q4 = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kelas");
  $jml_kelas = mysqli_fetch_assoc($q4)['total'] ?? 0;
  ?>

  <div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
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
    <div class="col-lg-3 col-md-6">
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
    <div class="col-lg-3 col-md-6">
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
    <div class="col-lg-3 col-md-6">
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
  </div>

  <div class="row g-4">
    <div class="col-lg-6">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body">
          <h5 class="card-title fw-bold">Grafik Kegiatan Bulanan</h5>
          <canvas id="monthlyActivitiesChart" style="max-height: 300px;"></canvas>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body">
          <h5 class="card-title fw-bold">Aktivitas Terbaru</h5>
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
  </div>
</main>

<!-- Added Bootstrap JavaScript bundle for dropdown functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });
});
</script>

</body>
</html>
