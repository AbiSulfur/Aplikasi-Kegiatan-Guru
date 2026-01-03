<?php include 'koneksi.php'; 
include 'validation.php';
$conn = $koneksi;
requireAdmin();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kegiatan - Aplikasi Kegiatan Guru</title>
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
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
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
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            height: 100%;
            font-size: 1.3rem;
            font-weight: 700;
            color: #2c3e50;
            text-decoration: none;
            margin: 0;
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
        
        .user-section {
            display: flex;
            align-items: center;
            gap: 1rem;
            height: 100%;
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
            text-decoration: none;
            cursor: pointer;
        }
        
        .nav-link:hover, 
        .nav-link.active {
            background-color: #f1f5f9;
            color: var(--primary-color);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: calc(100vh - var(--header-height));
        }
        
        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h2 {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin: 0;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: #64748b;
        }

        .metric-card {
            background: white;
            border-radius: 0.75rem;
            padding: 2rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
            border: 1px solid #e2e8f0;
        }

        .form-control, .form-select {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            background-color: #fff;
            transition: all 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgb(99 102 241 / 0.1);
        }

        .form-label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .guide-box {
            background: linear-gradient(135deg, #f0f4ff, #f9f5ff);
            border-left: 4px solid var(--primary-color);
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-top: 0;
        }

        .guide-box h6 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .guide-box ul li {
            color: #475569;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .guide-box li i {
            color: var(--success-color);
            margin-top: 0.25rem;
            flex-shrink: 0;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, var(--primary-color), #7c3aed);
            color: white;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary-modern:hover {
            background: linear-gradient(135deg, #5856eb, #6d28d9);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
            color: white;
        }

        .btn-outline-secondary {
            border: 1px solid #e2e8f0;
            color: #64748b;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-outline-secondary:hover {
            background-color: #f8fafc;
            border-color: #cbd5e1;
            color: #64748b;
        }

        .alert {
            border-radius: 0.5rem;
            border: none;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            padding: 1rem 1.5rem;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #7f1d1d;
            padding: 1rem 1.5rem;
        }

        .alert ul li {
            color: #7f1d1d;
            margin-bottom: 0.25rem;
        }

        .footer {
            margin-left: var(--sidebar-width);
            padding: 1.5rem 2rem;
            background: white;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #9ca3af;
            font-size: 0.875rem;
            margin-top: 3rem;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .footer {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Replaced with complete navbar from kegiatan.php including dropdown functionality -->
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

    <!-- Replaced with complete sidebar from kegiatan.php with proper active state -->
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
                    <a class="nav-link active" href="kegiatan.php">
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

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Page header with breadcrumb -->
        <div class="page-header">
            <h2 class="mb-2">Tambah Kegiatan</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="kegiatan.php">Kegiatan</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </nav>
        </div>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_user = intval($_POST['id_user']);
            $id_kelas = intval($_POST['id_kelas']);
            $id_jenis = intval($_POST['id_jenis']);
            $tanggal = $_POST['tanggal'];
            $laporan = trim($_POST['laporan']);

            // Validation
            $errors = [];
            if (empty($id_user)) $errors[] = "Guru harus dipilih";
            if (empty($id_kelas)) $errors[] = "Kelas harus dipilih";
            if (empty($id_jenis)) $errors[] = "Jenis kegiatan harus dipilih";
            if (empty($tanggal)) $errors[] = "Tanggal harus diisi";
            if (empty($laporan)) $errors[] = "Laporan harus diisi";

            if (empty($errors)) {
                $stmt = mysqli_prepare($conn, "INSERT INTO kegiatan_guru (id_user, id_kelas, id_jenis, tanggal, laporan) VALUES (?,?,?,?,?)");
                mysqli_stmt_bind_param($stmt, 'iiiss', $id_user, $id_kelas, $id_jenis, $tanggal, $laporan);
                
                if (mysqli_stmt_execute($stmt)) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            <strong>Sukses!</strong> Kegiatan berhasil ditambahkan.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                          </div>';
                    echo '<script>setTimeout(function(){ window.location.href = "kegiatan.php"; }, 2000);</script>';
                } else {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Gagal!</strong> ' . mysqli_error($conn) . '
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                          </div>';
                }
            } else {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Validasi Gagal!</strong>
                        <ul class="mb-0 mt-2">';
                foreach ($errors as $error) {
                    echo '<li>' . htmlspecialchars($error) . '</li>';
                }
                echo '</ul>
                      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                      </div>';
            }
        }
        ?>

        <!-- Form Card -->
        <div class="metric-card">
            <div class="row g-4">
                <!-- Left Column: Form Fields -->
                <div class="col-lg-8">
                    <form method="post" action="" id="kegiatanForm" novalidate>
                        <!-- Guru Selection -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-person-badge" style="color: var(--primary-color);"></i>
                                    Pilih Guru
                                </label>
                                <select name="id_user" class="form-select" required>
                                    <option value="">-- Pilih Guru --</option>
                                    <?php
                                    $q = mysqli_query($conn, "SELECT id_user, nama_lengkap FROM users WHERE role='guru' ORDER BY nama_lengkap");
                                    while ($g = mysqli_fetch_assoc($q)) {
                                        $selected = (isset($_POST['id_user']) && $_POST['id_user'] == $g['id_user']) ? 'selected' : '';
                                        echo '<option value="'.$g['id_user'].'" '.$selected.'>'.htmlspecialchars($g['nama_lengkap']).'</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Kelas Selection -->
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-door-open" style="color: var(--success-color);"></i>
                                    Pilih Kelas
                                </label>
                                <select name="id_kelas" class="form-select" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php
                                    $q = mysqli_query($conn, "SELECT id_kelas, nama_kelas FROM kelas ORDER BY nama_kelas");
                                    while ($k = mysqli_fetch_assoc($q)) {
                                        $selected = (isset($_POST['id_kelas']) && $_POST['id_kelas'] == $k['id_kelas']) ? 'selected' : '';
                                        echo '<option value="'.$k['id_kelas'].'" '.$selected.'>'.htmlspecialchars($k['nama_kelas']).'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!-- Jenis Kegiatan & Date -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-tags" style="color: var(--warning-color);"></i>
                                    Jenis Kegiatan
                                </label>
                                <select name="id_jenis" class="form-select" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    <?php
                                    $q = mysqli_query($conn, "SELECT id_jenis, nama_jenis FROM jenis_kegiatan ORDER BY nama_jenis");
                                    while ($j = mysqli_fetch_assoc($q)) {
                                        $selected = (isset($_POST['id_jenis']) && $_POST['id_jenis'] == $j['id_jenis']) ? 'selected' : '';
                                        echo '<option value="'.$j['id_jenis'].'" '.$selected.'>'.htmlspecialchars($j['nama_jenis']).'</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-calendar-event" style="color: var(--primary-color);"></i>
                                    Tanggal
                                </label>
                                <input type="date" name="tanggal" class="form-control" required 
                                       value="<?= isset($_POST['tanggal']) ? htmlspecialchars($_POST['tanggal']) : '' ?>">
                            </div>
                        </div>

                        <!-- Laporan Kegiatan -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-file-text" style="color: #9ca3af;"></i>
                                Laporan Kegiatan
                            </label>
                            <textarea name="laporan" class="form-control" rows="6" required 
                                      placeholder="Deskripsikan kegiatan yang dilakukan secara detail..."><?= isset($_POST['laporan']) ? htmlspecialchars($_POST['laporan']) : '' ?></textarea>
                            <small class="form-text">Minimal 20 karakter</small>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary-modern">
                                <i class="bi bi-check-circle"></i> Simpan Kegiatan
                            </button>
                            <a href="kegiatan.php" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
                
                <!-- Right Column: Guide Box -->
                <div class="col-lg-4">
                    <div class="guide-box">
                        <h6>
                            <i class="bi bi-info-circle"></i> Panduan Pengisian
                        </h6>
                        <ul class="list-unstyled mb-0">
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                <span><strong>Pilih guru</strong> yang melakukan kegiatan</span>
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                <span><strong>Tentukan kelas</strong> yang terlibat dalam kegiatan</span>
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                <span><strong>Pilih jenis kegiatan</strong> yang sesuai dengan aktivitas</span>
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                <span><strong>Isi tanggal</strong> pelaksanaan kegiatan dengan benar</span>
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                <span><strong>Tulis laporan</strong> kegiatan secara lengkap dan detail</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        © 2025 Aplikasi Kegiatan Guru. All rights reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
