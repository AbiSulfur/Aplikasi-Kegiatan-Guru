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
  <title>Manajemen Guru - Aplikasi Kegiatan Guru</title>
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
    
    .card-modern {
      background: white;
      border-radius: 0.75rem;
      box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
      border: 1px solid #e2e8f0;
      overflow: hidden;
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
    
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
      }
      
      .main-content {
        margin-left: 0;
        padding: 1rem;
      }
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
        <a class="nav-link" href="kegiatan.php">
          <i class="bi bi-calendar-event"></i>
          Kegiatan
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="guru.php">
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
      <h2 class="fw-bold mb-1">Manajemen Guru</h2>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
          <li class="breadcrumb-item active">Guru</li>
        </ol>
      </nav>
    </div>
    <a href="tambah_guru.php" class="btn btn-primary-modern btn-modern">
      <i class="bi bi-person-plus me-2"></i>Tambah Guru
    </a>
  </div>

  <?php
  // Helper function untuk status
  function getStatusBadge($status) {
    if ($status === 'approved' || $status === '1') {
      return '<span class="badge bg-success">Aktif</span>';
    } elseif ($status === 'pending') {
      return '<span class="badge bg-warning">Pending</span>';
    } elseif ($status === 'rejected') {
      return '<span class="badge bg-danger">Ditolak</span>';
    } else {
      return '<span class="badge bg-secondary">Nonaktif</span>';
    }
  }

  // Ambil data guru dari tabel users (role = 'guru')
  $sql = "SELECT * FROM users WHERE role = 'guru' ORDER BY created_at DESC";
  $res = mysqli_query($koneksi, $sql);
  ?>

  <?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($_GET['msg']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($_GET['error']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <div class="card-modern">
    <div class="p-4">
      <div class="table-responsive">
        <table class="table table-modern align-middle mb-0">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Nama Lengkap</th>
              <th scope="col">Username</th>
              <th scope="col">Status Akun</th>
              <th scope="col">Tanggal Daftar</th>
              <th scope="col">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            if ($res && mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $id = $row['id_user'];
                    $nama = htmlspecialchars($row['nama_lengkap']);
                    $username = htmlspecialchars($row['username']);
                    $status = $row['status'];
                    $created_at = date('d/m/Y', strtotime($row['created_at']));
                    
                    echo "<tr>";
                    echo "<td class='fw-semibold'>".$no++."</td>";
                    echo "<td>";
                    echo "<div class='d-flex align-items-center'>";
                    echo "<div class='bg-primary rounded-circle d-flex align-items-center justify-content-center me-3' style='width: 40px; height: 40px;'>";
                    echo "<i class='bi bi-person-fill text-white'></i>";
                    echo "</div>";
                    echo "<div>";
                    echo "<div class='fw-semibold'>$nama</div>";
                    echo "<small class='text-muted'>Guru</small>";
                    echo "</div>";
                    echo "</div>";
                    echo "</td>";
                    echo "<td><span class='badge bg-light text-dark'>$username</span></td>";
                    echo "<td>".getStatusBadge($status)."</td>";
                    echo "<td><small class='text-muted'>$created_at</small></td>";
                    echo "<td>";
                    echo "<div class='btn-group' role='group'>";
                    echo "<a href='edit_guru.php?id=$id' class='btn btn-sm btn-outline-primary' title='Edit'>";
                    echo "<i class='bi bi-pencil'></i>";
                    echo "</a>";
                    echo "<a href='detail_guru.php?id=$id' class='btn btn-sm btn-outline-info' title='Detail'>";
                    echo "<i class='bi bi-eye'></i>";
                    echo "</a>";
                    echo "<a href='hapus_guru.php?id=$id' class='btn btn-sm btn-outline-danger' title='Hapus' onclick=\"return confirm('Yakin ingin menghapus guru ini?')\">";
                    echo "<i class='bi bi-trash'></i>";
                    echo "</a>";
                    echo "</div>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo '<tr>';
                echo '<td colspan="6" class="text-center py-5">';
                echo '<div class="text-muted">';
                echo '<i class="bi bi-person-x fs-1 d-block mb-3"></i>';
                echo '<h5>Belum ada data guru</h5>';
                echo '<p class="mb-0">Silakan tambah guru baru untuk memulai.</p>';
                echo '</div>';
                echo '</td>';
                echo '</tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!--
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdownElementList = document.querySelectorAll('.dropdown-toggle, [data-bs-toggle="dropdown"]');
    const dropdownList = [...dropdownElementList].map(dropdownToggleEl => new bootstrap.Dropdown(dropdownToggleEl));
    
    // Navbar auto-hide functionality
    let lastScrollTop = 0;
    const navbar = document.getElementById('mainHeader');
    const navbarHoverZone = document.getElementById('navbarHoverZone');
    let isHovering = false;
    
    // Add hover event listeners
    if(navbarHoverZone) {
      navbarHoverZone.addEventListener('mouseenter', function() {
          isHovering = true;
          navbar.classList.remove('navbar-hidden');
          navbar.classList.add('navbar-visible');
      });
      
      navbarHoverZone.addEventListener('mouseleave', function() {
          isHovering = false;
      });
    }
    
    // Scroll event listener
    window.addEventListener('scroll', function() {
        if (isHovering) return;
        
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > lastScrollTop && scrollTop > 100) {
            // Scrolling down
            navbar.classList.remove('navbar-visible');
            navbar.classList.add('navbar-hidden');
            if(navbarHoverZone) navbarHoverZone.classList.add('active');
        } else {
            // Scrolling up
            navbar.classList.remove('navbar-hidden');
            navbar.classList.add('navbar-visible');
            if(navbarHoverZone) navbarHoverZone.classList.remove('active');
        }
        
        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    });
});
</script>
-->
</body>
</html>
