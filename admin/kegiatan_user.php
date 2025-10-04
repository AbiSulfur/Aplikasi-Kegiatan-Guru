<?php 
session_start();
include 'koneksi.php'; 

$conn = $koneksi;

if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'guru' && $_SESSION['role'] != 'siswa')) {
    header("Location: login.php");
    exit();
}

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
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kegiatan - Dashboard Pengguna</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root {
      --sidebar-width: 280px;
      --header-height: 70px;
      --primary-color: #6366f1;
    }
    body { background-color: #f1f5f9; padding-top: var(--header-height); font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
    .main-header { height: var(--header-height); background: white; border-bottom: 1px solid #e2e8f0; position: fixed; top: 0; left: 0; right: 0; z-index: 1050; display: flex; align-items: center; justify-content: space-between; padding: 0 2rem; }
    .main-content { margin-left: var(--sidebar-width); padding: 2rem; min-height: calc(100vh - var(--header-height)); }
    .sidebar { width: var(--sidebar-width); background: white; border-right: 1px solid #e2e8f0; min-height: calc(100vh - var(--header-height)); position: fixed; top: var(--header-height); left: 0; }
    .nav-link { color: #64748b; padding: 0.75rem 1.5rem; border-radius: 0.5rem; margin: 0.25rem 0; display: flex; align-items: center; gap: 0.75rem; }
    .nav-link.active, .nav-link:hover { background-color: #f1f5f9; color: var(--primary-color); }
    .table-modern th { background-color: #f8fafc; border: none; font-weight: 600; color: #475569; padding: 1rem; }
    .table-modern td { border: none; padding: 1rem; vertical-align: middle; }
    .metric-card { background: white; border-radius: 0.75rem; padding: 1.25rem; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1); border: 1px solid #e2e8f0; }
    .btn-modern { border-radius: 0.5rem; font-weight: 500; padding: 0.5rem 1rem; border: none; }
    .btn-primary-modern { background: var(--primary-color); color: white; }
  </style>
</head>
<body>
<nav class="main-header">
  <div class="navbar-brand">
    <h5 class="mb-0 fw-bold text-dark">Aplikasi Kegiatan Guru</h5>
  </div>
  <div></div>
</nav>

<aside class="sidebar" id="sidebar">
  <div class="p-4">
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link" href="user_dashboard.php">
          <i class="bi bi-house-door"></i>
          Dashboard
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="kegiatan_user.php">
          <i class="bi bi-calendar-event"></i>
          Kegiatan
        </a>
      </li>
      <?php if (($_SESSION['role'] ?? '') == 'guru'): ?>
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

<main class="main-content">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <div>
      <h2 class="mb-1 fw-bold">Daftar Kegiatan</h2>
      <div class="text-muted">Lihat aktivitas yang sedang berlangsung, mendatang, dan selesai</div>
    </div>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body">
      <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-4">
        <div class="d-flex gap-2">
          <div class="position-relative">
            <i class="bi bi-search position-absolute" style="left: 12px; top: 50%; transform: translateY(-50%); color: #64748b; z-index: 10;"></i>
            <input type="text" id="searchInput" class="form-control" placeholder="Cari guru, kelas, atau jenis" style="padding-left: 2.5rem; border-radius: 0.5rem; min-width: 260px;">
          </div>
          <select id="filterStatus" class="form-select" style="border-radius: 0.5rem; min-width: 160px;">
            <option value="">Semua Status</option>
            <option value="Sedang Berlangsung">Sedang Berlangsung</option>
            <option value="Mendatang">Mendatang</option>
            <option value="Selesai">Selesai</option>
          </select>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-modern mb-0" id="kegiatanTable">
          <thead>
            <tr>
              <th style="width: 60px;">#</th>
              <th style="width: 140px;">Tanggal</th>
              <th style="width: 120px;">Status</th>
              <th style="width: 180px;">Guru</th>
              <th style="width: 100px;">Kelas</th>
              <th style="width: 140px;">Jenis</th>
              <th>Laporan</th>
              <th style="width: 90px;">Aksi</th>
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
                $tanggal = strtotime($row['tanggal']);
                $today = strtotime(date('Y-m-d'));
                if ($tanggal == $today) {
                  $status_text = 'Sedang Berlangsung';
                  $status_class = 'bg-info';
                } elseif ($tanggal > $today) {
                  $status_text = 'Mendatang';
                  $status_class = 'bg-warning';
                } else {
                  $status_text = 'Selesai';
                  $status_class = 'bg-success';
                }
                echo '<tr>';
                echo '<td><span class="fw-medium text-muted">'.$no++.'</span></td>';
                echo '<td><span class="fw-medium">'.date('d M Y', $tanggal).'</span></td>';
                echo '<td><span class="badge '.$status_class.'">'.$status_text.'</span></td>';
                echo '<td>'.htmlspecialchars($row['guru'] ?? 'N/A').'</td>';
                echo '<td><span class="badge bg-light text-dark">'.htmlspecialchars($row['kelas'] ?? 'N/A').'</span></td>';
                echo '<td><span class="badge bg-primary">'.htmlspecialchars($row['jenis'] ?? 'N/A').'</span></td>';
                $lap = trim($row['laporan'] ?? '');
                $short = $lap !== '' ? htmlspecialchars(mb_substr($lap, 0, 70)).(mb_strlen($lap) > 70 ? '…' : '') : '<span class="text-muted">Tidak ada</span>';
                echo '<td><span class="d-inline-block text-truncate" style="max-width: 320px;" title="'.htmlspecialchars($lap).'">'.$short.'</span></td>';
                echo '<td><button class="btn btn-sm btn-outline-primary" onclick="showDetail('.$row['id_kegiatan'].')"><i class="bi bi-eye"></i></button></td>';
                echo '</tr>';
              }
            } else {
              echo '<tr><td colspan="8" class="text-center text-muted py-5">Belum ada kegiatan</td></tr>';
            }
          }
          ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailModalLabel"><i class="bi bi-calendar-event me-2"></i>Detail Kegiatan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="detailBody">
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
function showDetail(id) {
  const modal = new bootstrap.Modal(document.getElementById('detailModal'));
  const body = document.getElementById('detailBody');
  body.innerHTML = `
    <div class="text-center py-4">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <p class="mt-2 text-muted">Memuat detail kegiatan...</p>
    </div>
  `;
  modal.show();
  fetch('kegiatan_user.php?ajax=detail&id=' + id)
    .then(r => r.json())
    .then(data => {
      if (!data.success) { body.innerHTML = '<div class="text-center text-muted py-4">' + (data.message || 'Gagal memuat') + '</div>'; return; }
      body.innerHTML = `
        <div class="row g-3">
          <div class="col-md-6">
            <div class="card border-0 bg-light"><div class="card-body">
              <div class="mb-2"><span class="text-muted">Jenis</span><div class="fw-semibold">${data.jenis}</div></div>
              <div class="mb-2"><span class="text-muted">Tanggal</span><div class="fw-semibold">${data.tanggal}</div></div>
              <div class="mb-0"><span class="text-muted">Guru</span><div class="fw-semibold">${data.guru}</div></div>
            </div></div>
          </div>
          <div class="col-md-6">
            <div class="card border-0 bg-light"><div class="card-body">
              <div class="mb-2"><span class="text-muted">Kelas</span><div class="fw-semibold">${data.kelas}</div></div>
              <div class="mb-0"><span class="text-muted">Laporan</span><div class="fw-semibold" style="white-space: pre-wrap;">${data.laporan}</div></div>
            </div></div>
          </div>
        </div>
      `;
    })
    .catch(() => { body.innerHTML = '<div class="text-center text-danger py-4">Terjadi kesalahan</div>'; });
}
</script>

</body>
</html>


