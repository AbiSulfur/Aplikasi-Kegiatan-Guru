<?php 
include 'koneksi.php'; 
session_start();
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
    
    /* hover-zone removed */
    
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
        <a class="nav-link" href="index.php">
          <i class="bi bi-house-door"></i>
          Dashboard
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="/kegiatan.php">
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
  <!-- Session Messages -->
  <?php
  if (isset($_SESSION['message'])) {
    $message_type = $_SESSION['message_type'] ?? 'info';
    $icon = ($message_type === 'success') ? 'check-circle' : (($message_type === 'danger') ? 'exclamation-triangle' : 'info-circle');
    echo '<div class="alert alert-' . $message_type . ' alert-dismissible fade show" role="alert">
            <i class="bi bi-' . $icon . ' me-2"></i>
            ' . htmlspecialchars($_SESSION['message']) . '
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>';
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
  }
  ?>

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
    $total_kegiatan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM kegiatan_guru"))['total'] ?? 0;
    $kegiatan_hari_ini = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM kegiatan_guru WHERE DATE(tanggal) = CURDATE()"))['total'] ?? 0;
    $kegiatan_minggu_ini = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM kegiatan_guru WHERE WEEK(tanggal) = WEEK(NOW())"))['total'] ?? 0;
    $kegiatan_selesai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM kegiatan_guru WHERE tanggal < NOW()"))['total'] ?? 0;
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
            $jenis_result = mysqli_query($conn, "SELECT DISTINCT nama_jenis FROM jenis_kegiatan ORDER BY nama_jenis");
            while ($jenis = mysqli_fetch_assoc($jenis_result)) {
              echo '<option value="'.$jenis['nama_jenis'].'">'.$jenis['nama_jenis'].'</option>';
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
                          '.strtoupper(substr($row['guru'], 0, 2)).'
                        </div>
                        <span class="text-truncate">'.htmlspecialchars($row['guru']).'</span>
                      </div>
                    </td>';
              echo '<td><span class="badge bg-light text-dark">'.htmlspecialchars($row['kelas']).'</span></td>';
              echo '<td><span class="badge bg-primary">'.htmlspecialchars($row['jenis']).'</span></td>';
              echo '<td><span class="text-truncate d-inline-block" style="max-width: 300px;" title="'.htmlspecialchars($row['laporan']).'">'.htmlspecialchars(substr($row['laporan'],0,60)).'...</span></td>';
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
          ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>

<!-- Modal Detail Kegiatan -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailModalLabel">
          <i class="bi bi-eye me-2 text-primary"></i>Detail Kegiatan
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modalBody">
        <!-- Content will be loaded here -->
        <div class="text-center">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <p class="mt-2 text-muted">Memuat data...</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary" id="editButton" style="display: none;">
          <i class="bi bi-pencil me-2"></i>Edit Kegiatan
        </button>
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

// Function to show detail modal
function showDetailModal(id) {
    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
    const modalBody = document.getElementById('modalBody');
    const editButton = document.getElementById('editButton');
    
    // Show loading state
    modalBody.innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Memuat data...</p>
        </div>
    `;
    
    // Show modal
    modal.show();
    
    // Fetch data via AJAX
    fetch(`detail_kegiatan.php?id=${id}`)
        .then(response => response.text())
        .then(data => {
            // Check if response contains error
            if (data.includes('alert-danger')) {
                modalBody.innerHTML = data;
                return;
            }
            
            // Extract content from the response
            const parser = new DOMParser();
            const doc = parser.parseFromString(data, 'text/html');
            const content = doc.querySelector('.row, .card, .container');
            
            if (content) {
                modalBody.innerHTML = content.innerHTML;
                editButton.style.display = 'inline-block';
                editButton.onclick = () => {
                    window.location.href = `edit_kegiatan.php?id=${id}`;
                };
            } else {
                // If no specific content found, try to use the whole body
                const bodyContent = doc.querySelector('body');
                if (bodyContent) {
                    modalBody.innerHTML = bodyContent.innerHTML;
                    editButton.style.display = 'inline-block';
                    editButton.onclick = () => {
                        window.location.href = `edit_kegiatan.php?id=${id}`;
                    };
                } else {
                    modalBody.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Gagal memuat data kegiatan.
                        </div>
                    `;
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            modalBody.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Terjadi kesalahan saat memuat data.
                </div>
            `;
        });
}
</script>

</body>
</html>
