<?php include 'koneksi.php'; ?>
<?php include 'header.php'; ?>

<?php
// Count activities
$q1 = mysqli_query($conn, "SELECT COUNT(*) as total FROM kegiatan_guru");
$jml_kegiatan = mysqli_fetch_assoc($q1)['total'] ?? 0;

// Count gurus (users with role 'guru')
$q2 = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='guru'");
$jml_guru = mysqli_fetch_assoc($q2)['total'] ?? 0;

// Count students (role 'siswa')
$q3 = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='siswa'");
$jml_siswa = mysqli_fetch_assoc($q3)['total'] ?? 0;

// Count kelas
$q4 = mysqli_query($conn, "SELECT COUNT(*) as total FROM kelas");
$jml_kelas = mysqli_fetch_assoc($q4)['total'] ?? 0;
?>

<!-- Modern welcome section -->
<div class="welcome-section">
  <div class="row align-items-center">
    <div class="col-md-8">
      <h2 class="mb-2">Welcome back, Admin!</h2>
      <p class="mb-0 opacity-90">Your teacher activity management dashboard</p>
    </div>
    <div class="col-md-4 text-end">
      <button class="btn btn-light btn-modern">
        <i class="bi bi-download me-2"></i>
        Download report
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
          <p class="text-muted mb-1" style="font-size: 0.875rem;">Total Kegiatan</p>
          <h3 class="mb-0 fw-bold"><?= number_format($jml_kegiatan); ?></h3>
          <small class="text-success">
            <i class="bi bi-arrow-up"></i> 12% dari bulan lalu
          </small>
        </div>
        <div class="metric-icon icon-purple">
          <i class="bi bi-calendar-event"></i>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-md-3">
    <div class="metric-card">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <p class="text-muted mb-1" style="font-size: 0.875rem;">Total Guru</p>
          <h3 class="mb-0 fw-bold"><?= number_format($jml_guru); ?></h3>
          <small class="text-success">
            <i class="bi bi-arrow-up"></i> 5% dari bulan lalu
          </small>
        </div>
        <div class="metric-icon icon-green">
          <i class="bi bi-person-badge"></i>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-md-3">
    <div class="metric-card">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <p class="text-muted mb-1" style="font-size: 0.875rem;">Total Siswa</p>
          <h3 class="mb-0 fw-bold"><?= number_format($jml_siswa); ?></h3>
          <small class="text-success">
            <i class="bi bi-arrow-up"></i> 8% dari bulan lalu
          </small>
        </div>
        <div class="metric-icon icon-blue">
          <i class="bi bi-people"></i>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-md-3">
    <div class="metric-card">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <p class="text-muted mb-1" style="font-size: 0.875rem;">Total Kelas</p>
          <h3 class="mb-0 fw-bold"><?= number_format($jml_kelas); ?></h3>
          <small class="text-success">
            <i class="bi bi-arrow-up"></i> 3% dari bulan lalu
          </small>
        </div>
        <div class="metric-icon icon-orange">
          <i class="bi bi-door-open"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modern table with better styling -->
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
          <th>Laporan</th>
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
              LIMIT 10";

      $res = mysqli_query($conn, $sql);
      if ($res && mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
          echo '<tr>';
          echo '<td><span class="fw-medium">'.date('d M Y', strtotime($row['tanggal'])).'</span></td>';
          echo '<td>'.$row['guru'].'</td>';
          echo '<td><span class="badge bg-light text-dark">'.$row['kelas'].'</span></td>';
          echo '<td><span class="badge bg-primary">'.$row['jenis'].'</span></td>';
          echo '<td>'.htmlspecialchars(substr($row['laporan'],0,60)).'...</td>';
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

<?php include 'footer.php'; ?>
