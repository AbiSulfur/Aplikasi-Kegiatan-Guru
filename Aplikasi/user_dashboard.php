<?php include 'koneksi.php'; ?>
<?php include 'header.php'; ?>

<?php
// Assuming we have user session - for demo purposes, let's use sample data
// In real implementation, you'd get user ID from session
$user_id = 1; // This should come from session
$user_role = 'siswa'; // This should come from session

// Count user's activities (if teacher) or activities they're enrolled in (if student)
if ($user_role == 'guru') {
    $q1 = mysqli_query($conn, "SELECT COUNT(*) as total FROM kegiatan_guru WHERE id_user = $user_id");
    $my_activities = mysqli_fetch_assoc($q1)['total'] ?? 0;
    $activity_label = "Kegiatan Saya";
} else {
    // For students, count all activities (since we don't have user-class relationship table)
    $q1 = mysqli_query($conn, "SELECT COUNT(*) as total FROM kegiatan_guru");
    $my_activities = mysqli_fetch_assoc($q1)['total'] ?? 0;
    $activity_label = "Kegiatan Tersedia";
}

// Count total classes from kelas table
$q2 = mysqli_query($conn, "SELECT COUNT(*) as total FROM kelas");
$total_classes = mysqli_fetch_assoc($q2)['total'] ?? 0;

// Count completed activities this month
$q3 = mysqli_query($conn, "SELECT COUNT(*) as total FROM kegiatan_guru 
                           WHERE MONTH(tanggal) = MONTH(CURRENT_DATE()) 
                           AND YEAR(tanggal) = YEAR(CURRENT_DATE())");
$monthly_activities = mysqli_fetch_assoc($q3)['total'] ?? 0;

// Count upcoming activities (next 7 days)
$q4 = mysqli_query($conn, "SELECT COUNT(*) as total FROM kegiatan_guru 
                           WHERE tanggal BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY)");
$upcoming_activities = mysqli_fetch_assoc($q4)['total'] ?? 0;
?>

<!-- Modern welcome section -->
<div class="welcome-section">
  <div class="row align-items-center">
    <div class="col-md-8">
      <h2 class="mb-2">Welcome back, User!</h2>
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

<!-- Modern metric cards with icons and colors -->
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

<!-- Recent Activities Section -->
<div class="row g-4 mb-4">
  <div class="col-lg-8">
    <div class="metric-card">
      <div class="d-flex align-items-center justify-content-between mb-4">
        <h5 class="mb-0 fw-semibold">Kegiatan Terbaru</h5>
        <a href="/kegiatan.php" class="btn btn-primary-modern btn-modern">
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
                      <a class="btn btn-sm btn-outline-primary" href="/detail_kegiatan.php?id='.$row['id_kegiatan'].'">
                        <i class="bi bi-eye"></i>
                      </a>
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
  
  <!-- Quick Actions Sidebar -->
  <div class="col-lg-4">
    <div class="metric-card">
      <h5 class="mb-4 fw-semibold">Aksi Cepat</h5>
      
      <div class="d-grid gap-3">
        <a href="/kegiatan.php" class="btn btn-outline-primary btn-modern d-flex align-items-center">
          <i class="bi bi-list-ul me-3"></i>
          <div class="text-start">
            <div class="fw-medium">Lihat Semua Kegiatan</div>
            <small class="text-muted">Jelajahi semua aktivitas</small>
          </div>
        </a>
        
        <a href="/profile.php" class="btn btn-outline-success btn-modern d-flex align-items-center">
          <i class="bi bi-person-circle me-3"></i>
          <div class="text-start">
            <div class="fw-medium">Profil Saya</div>
            <small class="text-muted">Kelola informasi pribadi</small>
          </div>
        </a>
        
        <a href="/schedule.php" class="btn btn-outline-info btn-modern d-flex align-items-center">
          <i class="bi bi-calendar-week me-3"></i>
          <div class="text-start">
            <div class="fw-medium">Jadwal Mingguan</div>
            <small class="text-muted">Lihat jadwal kegiatan</small>
          </div>
        </a>
        
        <a href="/notifications.php" class="btn btn-outline-warning btn-modern d-flex align-items-center">
          <i class="bi bi-bell me-3"></i>
          <div class="text-start">
            <div class="fw-medium">Notifikasi</div>
            <small class="text-muted">Pesan dan pengumuman</small>
          </div>
        </a>
      </div>
    </div>
    
    <!-- Progress Card -->
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

<?php include 'footer.php'; ?>
