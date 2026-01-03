<?php 
session_start();
include 'koneksi.php'; 

$conn = $koneksi;

if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'guru' && $_SESSION['role'] != 'siswa')) {
    header("Location: login.php");
    exit();
}

// AJAX detail
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
        echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
    }
    exit;
}

// Ambil data 3 bulan mundur & 3 bulan ke depan untuk kalender/timeline
$startDate = date('Y-m-01', strtotime('-1 month'));
$endDate = date('Y-m-t', strtotime('+2 months'));

$events = [];
if ($conn) {
  $sql = "SELECT kg.id_kegiatan, kg.tanggal, kg.laporan, u.nama_lengkap as guru, k.nama_kelas as kelas, j.nama_jenis as jenis
          FROM kegiatan_guru kg
          LEFT JOIN users u ON kg.id_user = u.id_user
          LEFT JOIN kelas k ON kg.id_kelas = k.id_kelas
          LEFT JOIN jenis_kegiatan j ON kg.id_jenis = j.id_jenis
          WHERE DATE(kg.tanggal) BETWEEN '$startDate' AND '$endDate'
          ORDER BY kg.tanggal ASC";
  $res = mysqli_query($conn, $sql);
  if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
      $events[] = $row;
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Jadwal - Dashboard Pengguna</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root { --sidebar-width: 280px; --header-height: 70px; --primary-color: #6366f1; }
    body { background-color: #f1f5f9; padding-top: var(--header-height); font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
    .main-header { height: var(--header-height); background: white; border-bottom: 1px solid #e2e8f0; position: fixed; top: 0; left: 0; right: 0; z-index: 1050; display: flex; align-items: center; justify-content: space-between; padding: 0 2rem; }
    .main-content { margin-left: var(--sidebar-width); padding: 2rem; min-height: calc(100vh - var(--header-height)); }
    .sidebar { width: var(--sidebar-width); background: white; border-right: 1px solid #e2e8f0; min-height: calc(100vh - var(--header-height)); position: fixed; top: var(--header-height); left: 0; }
    .nav-link { color: #64748b; padding: 0.75rem 1.5rem; border-radius: 0.5rem; margin: 0.25rem 0; display: flex; align-items: center; gap: 0.75rem; }
    .nav-link.active, .nav-link:hover { background-color: #f1f5f9; color: var(--primary-color); }
    .timeline { position: relative; margin-left: 1rem; }
    .timeline:before { content: ''; position: absolute; left: 16px; top: 0; bottom: 0; width: 2px; background: #e5e7eb; }
    .timeline-item { position: relative; padding-left: 48px; margin-bottom: 1rem; }
    .timeline-item .dot { position: absolute; left: 8px; top: 12px; width: 18px; height: 18px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 0 2px #e5e7eb; }
    .card-event { border: 1px solid #e2e8f0; }
    .card-upcoming { background: #fff; }
    .card-done { background: #f3f4f6; color: #374151; }
    .badge-done { background-color: #10b981; }
    .badge-upcoming { background-color: #f59e0b; }
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
        <a class="nav-link" href="kegiatan_user.php">
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
        <a class="nav-link active" href="schedule.php">
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
      <h2 class="mb-1 fw-bold">Jadwal Kegiatan</h2>
      <div class="text-muted">Lini masa dan kalender kegiatan mendatang dan yang telah selesai</div>
    </div>
  </div>

  <!-- Ringkasan bulan -->
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="metric-card">
        <?php
        $upcoming = 0; $done = 0; $today = strtotime(date('Y-m-d'));
        foreach ($events as $e) { $ts = strtotime($e['tanggal']); if ($ts >= $today) $upcoming++; else $done++; }
        ?>
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted">Mendatang</div>
            <div class="fw-bold fs-4"><?php echo $upcoming; ?></div>
          </div>
          <span class="badge badge-upcoming">Mendatang</span>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="metric-card">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted">Selesai</div>
            <div class="fw-bold fs-4"><?php echo $done; ?></div>
          </div>
          <span class="badge badge-done">Selesai</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Timeline -->
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="mb-0 fw-semibold">Timeline Kegiatan</h5>
      </div>
      <div class="timeline">
        <?php
        if (empty($events)) {
          echo '<div class="text-center text-muted py-4">Belum ada jadwal dalam rentang waktu ini</div>';
        } else {
          foreach ($events as $e) {
            $ts = strtotime($e['tanggal']);
            $isDone = $ts < strtotime(date('Y-m-d'));
            $statusClass = $isDone ? 'card-done' : 'card-upcoming';
            $dotColor = $isDone ? '#10b981' : '#f59e0b';
            echo '<div class="timeline-item">';
            echo '<span class="dot" style="background: '.$dotColor.'"></span>';
            echo '<div class="card card-event '.$statusClass.' mb-2">';
            echo '<div class="card-body d-flex flex-column flex-md-row justify-content-between gap-3">';
            echo '<div>';
            echo '<div class="fw-semibold">'.date('D, d M Y', $ts).'</div>';
            echo '<div class="text-muted">'.htmlspecialchars($e['jenis']).' • Kelas '.htmlspecialchars($e['kelas']).'</div>';
            echo '<div class="small">Guru: '.htmlspecialchars($e['guru']).'</div>';
            echo '</div>';
            echo '<div class="text-md-end">';
            echo $isDone ? '<span class="badge bg-success">Selesai</span>' : '<span class="badge bg-warning text-dark">Mendatang</span>';
            echo '<div class="mt-2"><button class="btn btn-sm btn-outline-primary" onclick="showDetail('.$e['id_kegiatan'].')"><i class="bi bi-eye me-1"></i>Detail</button></div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
          }
        }
        ?>
      </div>
    </div>
  </div>

  <!-- Kalender sederhana per bulan (grid) -->
  <?php
  // Buat kalender bulan berjalan
  $monthStart = strtotime(date('Y-m-01'));
  $daysInMonth = date('t', $monthStart);
  $firstWeekday = date('N', $monthStart); // 1 (Mon) - 7 (Sun)
  $eventsByDate = [];
  foreach ($events as $e) { $d = date('Y-m-d', strtotime($e['tanggal'])); $eventsByDate[$d][] = $e; }
  ?>
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="mb-0 fw-semibold">Kalender Bulan Ini (<?php echo date('F Y'); ?>)</h5>
      </div>
      <div class="row g-2">
        <?php
        for ($i = 1; $i < $firstWeekday; $i++) {
          echo '<div class="col-12 col-sm-6 col-md-4 col-lg-2"><div class="p-3 border rounded bg-light" style="visibility:hidden">0</div></div>';
        }
        for ($day = 1; $day <= $daysInMonth; $day++) {
          $dateStr = date('Y-m-', $monthStart) . str_pad($day, 2, '0', STR_PAD_LEFT);
          $list = $eventsByDate[$dateStr] ?? [];
          echo '<div class="col-12 col-sm-6 col-md-4 col-lg-2">';
          echo '<div class="p-2 border rounded" style="min-height:140px; background:#fff">';
          echo '<div class="d-flex justify-content-between align-items-center mb-1">';
          echo '<span class="fw-semibold">'.$day.'</span>';
          echo '</div>';
          if (empty($list)) {
            echo '<div class="text-muted small">-</div>';
          } else {
            foreach ($list as $ev) {
              $isDone = strtotime($ev['tanggal']) < strtotime(date('Y-m-d'));
              $cls = $isDone ? 'bg-secondary text-white' : 'bg-info text-dark';
              echo '<div class="small mb-1 badge '.$cls.' w-100 text-start" style="white-space:normal">'.htmlspecialchars($ev['jenis']).' ('.htmlspecialchars($ev['kelas']).')</div>';
            }
          }
          echo '</div>';
          echo '</div>';
        }
        ?>
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
  fetch('schedule.php?ajax=detail&id=' + id)
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


