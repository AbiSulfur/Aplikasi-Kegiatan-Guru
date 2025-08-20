<?php include 'koneksi.php'; ?>
<?php include 'header.php'; ?>

<!-- Modern page header with breadcrumb -->
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h2 class="mb-1 fw-bold">Daftar Kegiatan</h2>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/index.php" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active">Kegiatan</li>
      </ol>
    </nav>
  </div>
  <a href="tambah_kegiatan.php" class="btn btn-primary-modern btn-modern">
    <i class="bi bi-plus-circle me-2"></i>
    Tambah Kegiatan
  </a>
</div>

<!-- Modern table card -->
<div class="metric-card">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h5 class="mb-0 fw-semibold">Semua Kegiatan</h5>
    <div class="d-flex gap-2">
      <div class="position-relative">
        <i class="bi bi-search position-absolute" style="left: 12px; top: 50%; transform: translateY(-50%); color: #64748b;"></i>
        <input type="text" class="form-control" placeholder="Cari kegiatan..." style="padding-left: 2.5rem; border-radius: 0.5rem;">
      </div>
      <select class="form-select" style="border-radius: 0.5rem; width: auto;">
        <option>Semua Jenis</option>
        <option>Mengajar</option>
        <option>Rapat</option>
        <option>Pelatihan</option>
      </select>
    </div>
  </div>
  
  <div class="table-responsive">
    <table class="table table-modern mb-0">
      <thead>
        <tr>
          <th>#</th>
          <th>Tanggal</th>
          <th>Guru</th>
          <th>Kelas</th>
          <th>Jenis</th>
          <th>Laporan</th>
          <th>Status</th>
          <th>Aksi</th>
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
          echo '<td><span class="fw-medium">'.$no++.'</span></td>';
          echo '<td><span class="fw-medium">'.date('d M Y', strtotime($row['tanggal'])).'</span></td>';
          echo '<td>
                  <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.75rem; color: white;">
                      '.strtoupper(substr($row['guru'], 0, 2)).'
                    </div>
                    '.$row['guru'].'
                  </div>
                </td>';
          echo '<td><span class="badge bg-light text-dark">'.$row['kelas'].'</span></td>';
          echo '<td><span class="badge bg-primary">'.$row['jenis'].'</span></td>';
          echo '<td>'.htmlspecialchars(substr($row['laporan'],0,80)).'...</td>';
          echo '<td><span class="badge '.$status_class.'">'.$status_text.'</span></td>';
          echo '<td>
                  <div class="btn-group" role="group">
                    <a class="btn btn-sm btn-outline-primary" href="detail_kegiatan.php?id='.$row['id_kegiatan'].'" title="Lihat Detail">
                      <i class="bi bi-eye"></i>
                    </a>
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

<?php include 'footer.php'; ?>
