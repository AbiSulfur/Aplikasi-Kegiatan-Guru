<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kegiatan - Aplikasi Kegiatan Guru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 250px;
            --navbar-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }

        /* Navbar Styles */
        .main-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--navbar-height);
            background: white;
            border-bottom: 1px solid #e9ecef;
            z-index: 1030;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .main-header.navbar-hidden {
            transform: translateY(-100%);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2c3e50;
            text-decoration: none;
        }

        .navbar-search {
            flex: 1;
            max-width: 400px;
            margin: 0 2rem;
        }

        .search-input {
            width: 100%;
            padding: 0.5rem 1rem;
            border: 1px solid #e9ecef;
            border-radius: 25px;
            background-color: #f8f9fa;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            border-radius: 25px;
            background-color: #f8f9fa;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--navbar-height));
            background: white;
            border-right: 1px solid #e9ecef;
            z-index: 1020;
            overflow-y: auto;
            transition: margin-top 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .sidebar.navbar-hidden {
            margin-top: calc(-1 * var(--navbar-height));
            height: 100vh;
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: #6c757d;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: #e3f2fd;
            color: #1976d2;
            border-right: 3px solid #1976d2;
        }

        .sidebar-menu i {
            width: 20px;
            margin-right: 0.75rem;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            padding: 2rem;
            min-height: calc(100vh - var(--navbar-height));
            transition: margin-top 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .main-content.navbar-hidden {
            margin-top: 0;
            min-height: 100vh;
        }

        /* Card Styles */
        .metric-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border: 1px solid #e9ecef;
        }

        /* Button Styles */
        .btn-primary-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-modern {
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-modern:hover {
            transform: translateY(-1px);
        }

        /* Form Styles */
        .form-control, .form-select {
            border-radius: 0.5rem;
            border: 1px solid #e9ecef;
            padding: 0.75rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        /* Breadcrumb */
        .breadcrumb {
            background: none;
            padding: 0;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
            color: #6c757d;
        }

        /* Footer */
        .footer {
            margin-left: var(--sidebar-width);
            padding: 1rem 2rem;
            background: white;
            border-top: 1px solid #e9ecef;
            text-align: center;
            color: #6c757d;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <header class="main-header" id="navbar">
        <a href="index.php" class="navbar-brand">Aplikasi Kegiatan Guru</a>
        
        <div class="navbar-search">
            <input type="text" class="search-input" placeholder="Search now">
        </div>
        
        <div class="navbar-actions">
            <div class="position-relative">
                <i class="bi bi-bell fs-5"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">3</span>
            </div>
            <div class="user-profile">
                <img src="img/avatar.png" alt="Admin" width="32" height="32" class="rounded-circle">
                <div class="d-flex flex-column">
                    <small class="fw-semibold">Admin</small>
                    <small class="text-muted" style="font-size: 0.7rem;">Administrator</small>
                </div>
                <i class="bi bi-chevron-down"></i>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-menu">
            <a href="index.php">
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </a>
            <a href="kegiatan.php" class="active">
                <i class="bi bi-calendar-check"></i>
                Kegiatan
            </a>
            <a href="guru.php">
                <i class="bi bi-person-badge"></i>
                Guru
            </a>
            <a href="#" onclick="alert('Fitur dalam pengembangan')">
                <i class="bi bi-door-open"></i>
                Kelas
            </a>
            <a href="#" onclick="alert('Fitur dalam pengembangan')">
                <i class="bi bi-tags"></i>
                Jenis Kegiatan
            </a>
            <a href="#" onclick="alert('Fitur dalam pengembangan')">
                <i class="bi bi-gear"></i>
                Settings
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Updated page header structure -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="mb-1 fw-bold">Tambah Kegiatan</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="kegiatan.php" class="text-decoration-none">Kegiatan</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                </nav>
            </div>
        </div>

        <?php
        // ... existing PHP code for form processing ...
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
                            Kegiatan berhasil ditambahkan!
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                          </div>';
                    echo '<script>setTimeout(function(){ window.location.href = "kegiatan.php"; }, 2000);</script>';
                } else {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Gagal menambahkan kegiatan: ' . mysqli_error($conn) . '
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                          </div>';
                }
            } else {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <ul class="mb-0">';
                foreach ($errors as $error) {
                    echo '<li>' . $error . '</li>';
                }
                echo '</ul>
                      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                      </div>';
            }
        }
        ?>

        <!-- Form content remains the same but now properly positioned -->
        <div class="metric-card">
            <div class="row">
                <div class="col-lg-8">
                    <form method="post" action="" id="kegiatanForm">
                        <!-- ... existing form content ... -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person-badge me-2 text-primary"></i>Guru
                                </label>
                                <select name="id_user" class="form-select" required style="border-radius: 0.5rem;">
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

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-door-open me-2 text-success"></i>Kelas
                                </label>
                                <select name="id_kelas" class="form-select" required style="border-radius: 0.5rem;">
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

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-tags me-2 text-warning"></i>Jenis Kegiatan
                                </label>
                                <select name="id_jenis" class="form-select" required style="border-radius: 0.5rem;">
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

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-calendar-event me-2 text-info"></i>Tanggal
                                </label>
                                <input type="date" name="tanggal" class="form-control" required style="border-radius: 0.5rem;" 
                                       value="<?= isset($_POST['tanggal']) ? $_POST['tanggal'] : '' ?>">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-file-text me-2 text-secondary"></i>Laporan Kegiatan
                            </label>
                            <textarea name="laporan" class="form-control" rows="6" required style="border-radius: 0.5rem;" 
                                      placeholder="Deskripsikan kegiatan yang dilakukan..."><?= isset($_POST['laporan']) ? htmlspecialchars($_POST['laporan']) : '' ?></textarea>
                            <div class="form-text">Minimal 20 karakter</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary-modern btn-modern">
                                <i class="bi bi-check-circle me-2"></i>Simpan Kegiatan
                            </button>
                            <a href="kegiatan.php" class="btn btn-outline-secondary btn-modern">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </form>
                </div>
                
                <div class="col-lg-4">
                    <div class="bg-light rounded p-4">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-info-circle me-2 text-primary"></i>Panduan Pengisian
                        </h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="bi bi-check text-success me-2"></i>
                                <small>Pilih guru yang melakukan kegiatan</small>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check text-success me-2"></i>
                                <small>Tentukan kelas yang terlibat</small>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check text-success me-2"></i>
                                <small>Pilih jenis kegiatan yang sesuai</small>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check text-success me-2"></i>
                                <small>Isi tanggal pelaksanaan</small>
                            </li>
                            <li>
                                <i class="bi bi-check text-success me-2"></i>
                                <small>Tulis laporan kegiatan secara detail</small>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let hideTimer;
        let isScrolling = false;

        function startHideTimer() {
            clearTimeout(hideTimer);
            hideTimer = setTimeout(() => {
                if (!isScrolling) {
                    document.getElementById('navbar').classList.add('navbar-hidden');
                    document.getElementById('sidebar').classList.add('navbar-hidden');
                    document.getElementById('mainContent').classList.add('navbar-hidden');
                }
            }, 3000);
        }

        function showNavbar() {
            document.getElementById('navbar').classList.remove('navbar-hidden');
            document.getElementById('sidebar').classList.remove('navbar-hidden');
            document.getElementById('mainContent').classList.remove('navbar-hidden');
            startHideTimer();
        }

        window.addEventListener('scroll', () => {
            isScrolling = true;
            showNavbar();
            clearTimeout(hideTimer);
            setTimeout(() => {
                isScrolling = false;
                startHideTimer();
            }, 150);
        });

        document.getElementById('navbar').addEventListener('mouseenter', showNavbar);
        document.addEventListener('mousemove', (e) => {
            if (e.clientY <= 100) {
                showNavbar();
            }
        });

        startHideTimer();

        // Form validation and textarea auto-resize
        document.getElementById('kegiatanForm').addEventListener('submit', function(e) {
            const laporan = document.querySelector('textarea[name="laporan"]').value;
            if (laporan.length < 20) {
                e.preventDefault();
                alert('Laporan harus minimal 20 karakter');
                return false;
            }
        });

        document.querySelector('textarea[name="laporan"]').addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    </script>
</body>
</html>
