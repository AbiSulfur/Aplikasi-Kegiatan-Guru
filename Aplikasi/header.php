<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Aplikasi Kegiatan Guru - Admin</title>
  <!-- Bootstrap CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Custom CSS -->
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
    
    /* Simplified metric card CSS for equal heights without breaking layout */
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
    
    <div class="dropdown">
      <button class="btn btn-link d-flex align-items-center gap-2 text-decoration-none p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <?php
        if (isset($_SESSION['id_user'])) {
          include_once 'koneksi.php';
          $user_id = $_SESSION['id_user'];
          $profile_query = mysqli_query($koneksi, "SELECT profile_picture FROM users WHERE id_user = $user_id");
          $profile_data = mysqli_fetch_assoc($profile_query);
          
          $profile_pic_path = isset($profile_data['profile_picture']) && $profile_data['profile_picture'] 
                             ? "../uploads/profile_pictures/" . $profile_data['profile_picture'] 
                             : "../aplikasi-kegiatan-admin-module/img/avatar.png";
        } else {
          $profile_pic_path = "../aplikasi-kegiatan-admin-module/img/avatar.png";
        }
        ?>
        <img src="<?php echo $profile_pic_path; ?>" alt="Admin" class="user-avatar">
        <div class="d-flex flex-column justify-content-center">
          <span class="fw-semibold text-dark" style="font-size: 0.875rem; line-height: 1.2;"><?php echo $_SESSION['nama_lengkap'] ?? 'Admin'; ?></span>
          <span class="text-muted" style="font-size: 0.75rem; line-height: 1.2;"><?php echo ucfirst($_SESSION['role'] ?? 'Administrator'); ?></span>
        </div>
        <i class="bi bi-chevron-down text-muted"></i>
      </button>
      <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="min-width: 200px;">
        <li>
          <a class="dropdown-item d-flex align-items-center gap-2" href="#">
            <i class="bi bi-person-circle"></i>
            Profile
          </a>
        </li>
        <li>
          <a class="dropdown-item d-flex align-items-center gap-2" href="#">
            <i class="bi bi-gear"></i>
            Settings
          </a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
          <a class="dropdown-item d-flex align-items-center gap-2 text-danger" href="logout.php">
            <i class="bi bi-box-arrow-right"></i>
            Logout
          </a>
        </li>
      </ul>
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

<<<<<<<< HEAD:Aplikasi/admin/header.php
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });
    
    const navbar = document.getElementById('mainHeader');
    const hoverZone = document.getElementById('navbarHoverZone');
    const sidebar = document.getElementById('sidebar');
    
    let lastScrollTop = 0;
    let hideTimeout;
    let isScrolling = false;
    let isHovered = false;
    
    function startHideTimer() {
        clearTimeout(hideTimeout);
        hideTimeout = setTimeout(() => {
            if (!isHovered && !isScrolling) {
                hideNavbar();
            }
        }, 4000);
    }
    
    function showNavbar() {
        navbar.classList.remove('navbar-hidden');
        navbar.classList.add('navbar-visible');
        hoverZone.classList.remove('active');
        document.body.style.paddingTop = '70px';
        if (sidebar) {
            sidebar.style.top = '70px';
        }
    }
    
    function hideNavbar() {
        navbar.classList.remove('navbar-visible');
        navbar.classList.add('navbar-hidden');
        hoverZone.classList.add('active');
        document.body.style.paddingTop = '0';
        if (sidebar) {
            sidebar.style.top = '0';
        }
    }
    
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        isScrolling = true;
        clearTimeout(scrollTimeout);
        
        showNavbar();
        
        scrollTimeout = setTimeout(() => {
            isScrolling = false;
            startHideTimer();
        }, 200);
        
        lastScrollTop = window.pageYOffset;
    });
    
    hoverZone.addEventListener('mouseenter', function() {
        isHovered = true;
        showNavbar();
        clearTimeout(hideTimeout);
    });
    
    navbar.addEventListener('mouseenter', function() {
        isHovered = true;
        clearTimeout(hideTimeout);
    });
    
    navbar.addEventListener('mouseleave', function() {
        isHovered = false;
        startHideTimer();
    });
    
    startHideTimer();
    
    navbar.addEventListener('click', function() {
        clearTimeout(hideTimeout);
        startHideTimer();
    });
    
    navbar.addEventListener('focus', function() {
        clearTimeout(hideTimeout);
    }, true);
    
    navbar.addEventListener('blur', function() {
        startHideTimer();
    }, true);
});
</script>
========
<!-- removed auto-hide navbar script -->
>>>>>>>> 295c4edbe6931c5adbb63141e0d260ee6bca5d5e:Aplikasi/header.php

<main class="main-content">
</main>
</body>
</html>
