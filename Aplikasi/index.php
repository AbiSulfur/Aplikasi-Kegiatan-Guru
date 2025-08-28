<?php include 'koneksi.php'; ?>
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
    
    /* Removed hover-zone behavior */
    
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
      min-height: 100vh;
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
<!-- navbar hover zone removed -->

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
        <a class="nav-link" href="/guru.php">
          <i class="bi bi-person-badge"></i>
          Guru
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/pages/kelas.php">
          <i class="bi bi-door-open"></i>
          Kelas
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/pages/jenis_kegiatan.php">
          <i class="bi bi-tags"></i>
          Jenis Kegiatan
        </a>
      </li>
      <li class="nav-item mt-4">
        <a class="nav-link" href="/pages/settings.php">
          <i class="bi bi-gear"></i>
          Settings
        </a>
      </li>
    </ul>
  </div>
</aside>

<!-- removed auto-hide navbar script -->

<main class="main-content">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="fw-bold mb-0">Dashboard</h2>
      <p class="text-muted mb-0">Ringkasan kegiatan guru.</p>
    </div>
  </div>

  <?php
  // Get statistics
  $q1 = mysqli_query($conn, "SELECT COUNT(*) as total FROM kegiatan_guru");
  $jml_kegiatan = mysqli_fetch_assoc($q1)['total'] ?? 0;

  $q2 = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='guru'");
  $jml_guru = mysqli_fetch_assoc($q2)['total'] ?? 0;

  $q3 = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='siswa'");
  $jml_siswa = mysqli_fetch_assoc($q3)['total'] ?? 0;

  $q4 = mysqli_query($conn, "SELECT COUNT(*) as total FROM kelas");
  $jml_kelas = mysqli_fetch_assoc($q4)['total'] ?? 0;

  // Get monthly activities data for chart
  $monthly_data = [];
  $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
  
  for ($i = 1; $i <= 12; $i++) {
    $month_start = date('Y') . '-' . sprintf('%02d', $i) . '-01';
    $month_end = date('Y') . '-' . sprintf('%02d', $i) . '-' . date('t', strtotime($month_start));
    
    $query = "SELECT COUNT(*) as count FROM kegiatan_guru 
              WHERE tanggal BETWEEN '$month_start' AND '$month_end'";
    $result = mysqli_query($conn, $query);
    $count = mysqli_fetch_assoc($result)['count'] ?? 0;
    $monthly_data[] = $count;
  }

  // Get recent activities
  $recent_activities = [];
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
      $recent_activities[] = $row;
    }
  }
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
                if (!empty($recent_activities)) {
                  foreach ($recent_activities as $row) {
                    $status = (strtotime($row['tanggal']) < time()) ? 'Selesai' : 'Berlangsung';
                    $statusClass = ($status == 'Selesai') ? 'bg-success-subtle text-success' : 'bg-primary-subtle text-primary';
                    $judul = !empty($row['jenis']) ? $row['jenis'] : substr($row['laporan'], 0, 30) . '...';
                    
                    echo '<tr>';
                    echo '<td>
                            <div class="d-flex align-items-center">
                              <div class="me-2">
                                <i class="bi bi-calendar-event text-primary"></i>
                              </div>
                              <div>
                                <div class="fw-medium">' . htmlspecialchars($judul) . '</div>
                                <small class="text-muted">' . date('d M Y', strtotime($row['tanggal'])) . '</small>
                              </div>
                            </div>
                          </td>';
                    echo '<td>
                            <div class="d-flex align-items-center">
                              <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.75rem; color: white;">
                                ' . strtoupper(substr($row['guru'], 0, 2)) . '
                              </div>
                              <div>
                                <div class="fw-medium">' . htmlspecialchars($row['guru']) . '</div>
                                <small class="text-muted">' . htmlspecialchars($row['kelas']) . '</small>
                              </div>
                            </div>
                          </td>';
                    echo '<td><span class="badge ' . $statusClass . '">' . $status . '</span></td>';
                    echo '</tr>';
                  }
                } else {
                  echo '<tr><td colspan="3" class="text-center text-muted py-4">
                          <i class="bi bi-calendar-x fs-1 text-muted mb-3 d-block"></i>
                          <p class="mb-0">Belum ada kegiatan</p>
                        </td></tr>';
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const ctx = document.getElementById('monthlyActivitiesChart');
  
  // Data from PHP
  const monthlyData = <?= json_encode($monthly_data) ?>;
  const months = <?= json_encode($months) ?>;
  
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: months,
      datasets: [{
        label: 'Jumlah Kegiatan',
        data: monthlyData,
        backgroundColor: [
          'rgba(99, 102, 241, 0.5)',
          'rgba(99, 102, 241, 0.5)',
          'rgba(99, 102, 241, 0.5)',
          'rgba(99, 102, 241, 0.5)',
          'rgba(99, 102, 241, 0.5)',
          'rgba(99, 102, 241, 0.5)',
          'rgba(99, 102, 241, 0.5)',
          'rgba(99, 102, 241, 0.5)',
          'rgba(99, 102, 241, 0.5)',
          'rgba(99, 102, 241, 0.5)',
          'rgba(99, 102, 241, 0.5)',
          'rgba(99, 102, 241, 0.5)'
        ],
        borderColor: [
          'rgb(99, 102, 241)',
          'rgb(99, 102, 241)',
          'rgb(99, 102, 241)',
          'rgb(99, 102, 241)',
          'rgb(99, 102, 241)',
          'rgb(99, 102, 241)',
          'rgb(99, 102, 241)',
          'rgb(99, 102, 241)',
          'rgb(99, 102, 241)',
          'rgb(99, 102, 241)',
          'rgb(99, 102, 241)',
          'rgb(99, 102, 241)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          position: 'top',
        },
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
          ticks: {
            stepSize: 1
          }
        },
        x: {
          ticks: {
            maxRotation: 45
          }
        }
      }
    }
  });
});
</script>

</body>
</html>
