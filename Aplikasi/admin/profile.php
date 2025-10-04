<?php 
session_start();
include 'koneksi.php'; 

$conn = $koneksi;

if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'guru' && $_SESSION['role'] != 'siswa')) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id_user'];
$user_role = $_SESSION['role'];

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $phone = mysqli_real_escape_string($conn, $_POST['phone'] ?? '');
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat'] ?? '');
    $kelas_id = isset($_POST['kelas_id']) ? intval($_POST['kelas_id']) : null;
    
    // Check if username already exists (excluding current user)
    $check_username = mysqli_query($conn, "SELECT id_user FROM users WHERE username = '$username' AND id_user != $user_id");
    
    if (mysqli_num_rows($check_username) > 0) {
        $error_message = "Username sudah digunakan oleh pengguna lain!";
    } else {
        // First, check if additional profile fields exist in users table
        $check_columns = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'email'");
        $has_additional_fields = mysqli_num_rows($check_columns) > 0;
        
        if (!$has_additional_fields) {
            // Add additional columns to users table if they don't exist
            mysqli_query($conn, "ALTER TABLE users ADD COLUMN email VARCHAR(100) DEFAULT NULL");
            mysqli_query($conn, "ALTER TABLE users ADD COLUMN phone VARCHAR(20) DEFAULT NULL");
            mysqli_query($conn, "ALTER TABLE users ADD COLUMN alamat TEXT DEFAULT NULL");
            mysqli_query($conn, "ALTER TABLE users ADD COLUMN kelas_id INT(11) DEFAULT NULL");
            mysqli_query($conn, "ALTER TABLE users ADD COLUMN profile_picture VARCHAR(255) DEFAULT NULL");
            mysqli_query($conn, "ALTER TABLE users ADD FOREIGN KEY (kelas_id) REFERENCES kelas(id_kelas) ON DELETE SET NULL");
        } else {
            // Check if profile_picture column exists
            $check_profile_pic = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'profile_picture'");
            if (mysqli_num_rows($check_profile_pic) == 0) {
                mysqli_query($conn, "ALTER TABLE users ADD COLUMN profile_picture VARCHAR(255) DEFAULT NULL");
            }
        }
        
        // Handle profile picture upload
        $profile_picture_update = "";
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $file_type = $_FILES['profile_picture']['type'];
            $file_size = $_FILES['profile_picture']['size'];
            
            if (in_array($file_type, $allowed_types) && $file_size <= 5000000) { // 5MB max
                $upload_dir = '../uploads/profile_pictures/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
                $new_filename = 'profile_' . $user_id . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;
                
                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_path)) {
                    // Delete old profile picture if exists
                    $old_pic_query = mysqli_query($conn, "SELECT profile_picture FROM users WHERE id_user = $user_id");
                    $old_pic = mysqli_fetch_assoc($old_pic_query)['profile_picture'];
                    if ($old_pic && file_exists('../uploads/profile_pictures/' . $old_pic)) {
                        unlink('../uploads/profile_pictures/' . $old_pic);
                    }
                    
                    $profile_picture_update = ", profile_picture = '$new_filename'";
                } else {
                    $error_message = "Gagal mengupload foto profil!";
                }
            } else {
                $error_message = "File foto profil tidak valid! Gunakan format JPG, PNG, atau GIF dengan ukuran maksimal 5MB.";
            }
        }
        
        if (!isset($error_message)) {
            // Build update query only for changed fields
            $update_fields = [];
            
            // Get current user data to compare
            $current_data_query = mysqli_query($conn, "SELECT * FROM users WHERE id_user = $user_id");
            $current_data = mysqli_fetch_assoc($current_data_query);
            
            // Only update fields that have changed
            if ($current_data['nama_lengkap'] != $nama_lengkap) {
                $update_fields[] = "nama_lengkap = '$nama_lengkap'";
            }
            if ($current_data['username'] != $username) {
                $update_fields[] = "username = '$username'";
            }
            if (($current_data['email'] ?? '') != $email) {
                $update_fields[] = "email = '$email'";
            }
            if (($current_data['phone'] ?? '') != $phone) {
                $update_fields[] = "phone = '$phone'";
            }
            if (($current_data['alamat'] ?? '') != $alamat) {
                $update_fields[] = "alamat = '$alamat'";
            }
            if ($user_role == 'siswa' && $kelas_id && ($current_data['kelas_id'] ?? 0) != $kelas_id) {
                $update_fields[] = "kelas_id = $kelas_id";
            }
            
            // Add profile picture update if uploaded
            if ($profile_picture_update) {
                $update_fields[] = substr($profile_picture_update, 2); // Remove ", " prefix
            }
            
            // Only run update if there are changes
            if (!empty($update_fields)) {
                $update_query = "UPDATE users SET " . implode(', ', $update_fields) . " WHERE id_user = $user_id";
                
                if (mysqli_query($conn, $update_query)) {
                    $_SESSION['nama_lengkap'] = $nama_lengkap;
                    $success_message = "Profil berhasil diperbarui!";
                } else {
                    $error_message = "Gagal memperbarui profil: " . mysqli_error($conn);
                }
            } else {
                $success_message = "Tidak ada perubahan yang disimpan.";
            }
        }
    }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Get current password from database
    $user_query = mysqli_query($conn, "SELECT password FROM users WHERE id_user = $user_id");
    $user_data = mysqli_fetch_assoc($user_query);
    
    if (md5($current_password) != $user_data['password']) {
        $password_error = "Password saat ini tidak benar!";
    } elseif ($new_password != $confirm_password) {
        $password_error = "Konfirmasi password tidak cocok!";
    } elseif (strlen($new_password) < 6) {
        $password_error = "Password baru minimal 6 karakter!";
    } else {
        $hashed_password = md5($new_password);
        if (mysqli_query($conn, "UPDATE users SET password = '$hashed_password' WHERE id_user = $user_id")) {
            $password_success = "Password berhasil diubah!";
        } else {
            $password_error = "Gagal mengubah password!";
        }
    }
}

// Get user profile data - first check if kelas_id column exists
$check_kelas_column = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'kelas_id'");
$has_kelas_column = mysqli_num_rows($check_kelas_column) > 0;

if ($has_kelas_column) {
    $profile_query = "SELECT u.*, k.nama_kelas 
                      FROM users u 
                      LEFT JOIN kelas k ON u.kelas_id = k.id_kelas 
                      WHERE u.id_user = $user_id";
} else {
    $profile_query = "SELECT u.*, NULL as nama_kelas 
                      FROM users u 
                      WHERE u.id_user = $user_id";
}
$profile_result = mysqli_query($conn, $profile_query);
$profile = mysqli_fetch_assoc($profile_result);

// Get all classes for dropdown (for students)
$kelas_query = mysqli_query($conn, "SELECT * FROM kelas ORDER BY nama_kelas");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Aplikasi Kegiatan Guru - Profil</title>
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
    
    .profile-card {
      background: white;
      border-radius: 0.75rem;
      padding: 2rem;
      box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
      border: 1px solid #e2e8f0;
      margin-bottom: 2rem;
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
    
    .profile-avatar {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      border: 4px solid #e2e8f0;
      object-fit: cover;
    }
    
    /* Added styles for profile picture upload */
    .profile-picture-container {
      position: relative;
      display: inline-block;
    }
    
    .profile-picture-overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.5);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: opacity 0.3s;
      cursor: pointer;
    }
    
    .profile-picture-container:hover .profile-picture-overlay {
      opacity: 1;
    }
    
    .profile-picture-overlay i {
      color: white;
      font-size: 1.5rem;
    }
    
    .notification-icon {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
    }
    
    .page-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border-radius: 1rem;
      padding: 2rem;
      margin-bottom: 2rem;
    }
    
    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
    }
    
    .btn-primary-modern {
      background: var(--primary-color);
      border: none;
      border-radius: 0.5rem;
      padding: 0.75rem 1.5rem;
      font-weight: 500;
      transition: all 0.2s;
    }
    
    .btn-primary-modern:hover {
      background: #5856eb;
      transform: translateY(-1px);
    }
    
    .btn-outline-primary-modern {
      border: 2px solid var(--primary-color);
      color: var(--primary-color);
      border-radius: 0.5rem;
      padding: 0.75rem 1.5rem;
      font-weight: 500;
      transition: all 0.2s;
      background: transparent;
    }
    
    .btn-outline-primary-modern:hover {
      background: var(--primary-color);
      color: white;
      transform: translateY(-1px);
    }
    
    .alert-modern {
      border: none;
      border-radius: 0.75rem;
      padding: 1rem 1.5rem;
      margin-bottom: 1.5rem;
    }
    
    .stats-card {
      background: white;
      border-radius: 0.75rem;
      padding: 1.5rem;
      box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
      border: 1px solid #e2e8f0;
      text-align: center;
      transition: transform 0.2s;
    }
    
    .stats-card:hover {
      transform: translateY(-2px);
    }
    
    .stats-icon {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1rem;
      font-size: 1.5rem;
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
  </style>
</head>
<body>

<nav class="main-header">
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
        <?php 
        $profile_pic_path = isset($profile['profile_picture']) && $profile['profile_picture'] 
                           ? "../uploads/profile_pictures/" . $profile['profile_picture'] 
                           : "../aplikasi-kegiatan-admin-module/img/avatar.png";
        ?>
        <img src="<?php echo $profile_pic_path; ?>" alt="User" class="user-avatar">
        <div class="d-flex flex-column justify-content-center">
          <span class="fw-semibold text-dark" style="font-size: 0.875rem; line-height: 1.2;"><?php echo $_SESSION['nama_lengkap']; ?></span>
          <span class="text-muted" style="font-size: 0.75rem; line-height: 1.2;"><?php echo ucfirst($_SESSION['role']); ?></span>
        </div>
        <div class="dropdown">
          <i class="bi bi-chevron-down text-muted dropdown-toggle" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</nav>

<aside class="sidebar">
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
      <?php if ($user_role == 'guru'): ?>
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
        <a class="nav-link active" href="profile.php">
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
  <div class="page-header">
    <div class="row align-items-center">
      <div class="col-md-8">
        <h2 class="mb-2">Profil Pengguna</h2>
        <p class="mb-0 opacity-90">Kelola informasi pribadi dan pengaturan akun Anda</p>
      </div>
      <div class="col-md-4 text-end">
        <button class="btn btn-light" onclick="window.location.href='user_dashboard.php'">
          <i class="bi bi-arrow-left me-2"></i>
          Kembali ke Dashboard
        </button>
      </div>
    </div>
  </div>

  <?php if (isset($success_message)): ?>
    <div class="alert alert-success alert-modern">
      <i class="bi bi-check-circle me-2"></i><?php echo $success_message; ?>
    </div>
  <?php endif; ?>

  <?php if (isset($error_message)): ?>
    <div class="alert alert-danger alert-modern">
      <i class="bi bi-exclamation-triangle me-2"></i><?php echo $error_message; ?>
    </div>
  <?php endif; ?>

  <?php if (isset($password_success)): ?>
    <div class="alert alert-success alert-modern">
      <i class="bi bi-check-circle me-2"></i><?php echo $password_success; ?>
    </div>
  <?php endif; ?>

  <?php if (isset($password_error)): ?>
    <div class="alert alert-danger alert-modern">
      <i class="bi bi-exclamation-triangle me-2"></i><?php echo $password_error; ?>
    </div>
  <?php endif; ?>

  <div class="row g-4">
    <div class="col-lg-8">
      <div class="profile-card">
        <div class="d-flex align-items-center mb-4">
          <div class="profile-picture-container me-4">
            <?php 
            $profile_pic_path = isset($profile['profile_picture']) && $profile['profile_picture'] 
                               ? "../uploads/profile_pictures/" . $profile['profile_picture'] 
                               : "../aplikasi-kegiatan-admin-module/img/avatar.png";
            ?>
            <img src="<?php echo $profile_pic_path; ?>" alt="Profile" class="profile-avatar" id="profileImage">
            <div class="profile-picture-overlay" onclick="document.getElementById('profilePictureInput').click()">
              <i class="bi bi-camera"></i>
            </div>
          </div>
          <div>
            <h4 class="mb-1"><?php echo $profile['nama_lengkap']; ?></h4>
            <p class="text-muted mb-2"><?php echo ucfirst($profile['role']); ?></p>
            <span class="badge bg-success">Aktif</span>
          </div>
        </div>

        <form method="POST" action="" enctype="multipart/form-data">
          <input type="file" id="profilePictureInput" name="profile_picture" accept="image/*" style="display: none;" onchange="previewImage(this)">
          
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label fw-semibold">
                <i class="bi bi-camera me-2"></i>Foto Profil
              </label>
              <div class="d-flex align-items-center gap-3">
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('profilePictureInput').click()">
                  <i class="bi bi-upload me-2"></i>Pilih Foto
                </button>
                <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 5MB.</small>
              </div>
              <div id="imagePreview" class="mt-2" style="display: none;">
                <img id="previewImg" src="/placeholder.svg" alt="Preview" style="max-width: 150px; max-height: 150px; border-radius: 8px; border: 2px solid #e2e8f0;">
              </div>
            </div>
            <div class="col-md-6">
              <label for="nama_lengkap" class="form-label fw-semibold">Nama Lengkap</label>
              <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                     value="<?php echo htmlspecialchars($profile['nama_lengkap']); ?>" required>
            </div>
            <div class="col-md-6">
              <label for="username" class="form-label fw-semibold">Username</label>
              <input type="text" class="form-control" id="username" name="username" 
                     value="<?php echo htmlspecialchars($profile['username']); ?>" required>
            </div>
            <div class="col-md-6">
              <label for="email" class="form-label fw-semibold">Email</label>
              <input type="email" class="form-control" id="email" name="email" 
                     value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>" 
                     placeholder="contoh@email.com">
            </div>
            <div class="col-md-6">
              <label for="phone" class="form-label fw-semibold">Nomor Telepon</label>
              <input type="tel" class="form-control" id="phone" name="phone" 
                     value="<?php echo htmlspecialchars($profile['phone'] ?? ''); ?>" 
                     placeholder="08xxxxxxxxxx">
            </div>
            <?php if ($user_role == 'siswa'): ?>
            <div class="col-md-6">
              <label for="kelas_id" class="form-label fw-semibold">Kelas</label>
              <select class="form-select" id="kelas_id" name="kelas_id">
                <option value="">Pilih Kelas</option>
                <?php while ($kelas = mysqli_fetch_assoc($kelas_query)): ?>
                  <option value="<?php echo $kelas['id_kelas']; ?>" 
                          <?php echo ($profile['kelas_id'] == $kelas['id_kelas']) ? 'selected' : ''; ?>>
                    <?php echo $kelas['nama_kelas']; ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </div>
            <?php endif; ?>
            <div class="col-12">
              <label for="alamat" class="form-label fw-semibold">Alamat</label>
              <textarea class="form-control" id="alamat" name="alamat" rows="3" 
                        placeholder="Masukkan alamat lengkap"><?php echo htmlspecialchars($profile['alamat'] ?? ''); ?></textarea>
            </div>
          </div>
          
          <div class="d-flex gap-3 mt-4">
            <button type="submit" name="update_profile" class="btn btn-primary-modern">
              <i class="bi bi-check-lg me-2"></i>Simpan Perubahan
            </button>
            <button type="reset" class="btn btn-outline-primary-modern" onclick="resetForm()">
              <i class="bi bi-arrow-clockwise me-2"></i>Reset
            </button>
          </div>
        </form>
      </div>

      <div class="profile-card">
        <h5 class="mb-4">
          <i class="bi bi-shield-lock me-2"></i>Ubah Password
        </h5>
        
        <form method="POST" action="">
          <div class="row g-3">
            <div class="col-12">
              <label for="current_password" class="form-label fw-semibold">Password Saat Ini</label>
              <input type="password" class="form-control" id="current_password" name="current_password" required>
            </div>
            <div class="col-md-6">
              <label for="new_password" class="form-label fw-semibold">Password Baru</label>
              <input type="password" class="form-control" id="new_password" name="new_password" 
                     minlength="6" required>
              <div class="form-text">Minimal 6 karakter</div>
            </div>
            <div class="col-md-6">
              <label for="confirm_password" class="form-label fw-semibold">Konfirmasi Password Baru</label>
              <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                     minlength="6" required>
            </div>
          </div>
          
          <div class="mt-4">
            <button type="submit" name="change_password" class="btn btn-warning">
              <i class="bi bi-key me-2"></i>Ubah Password
            </button>
          </div>
        </form>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="row g-3">
        <?php
        // Get user statistics
        if ($user_role == 'guru') {
          $my_activities_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM kegiatan_guru WHERE id_user = $user_id");
          $my_activities_count = mysqli_fetch_assoc($my_activities_query)['total'];
          
          $monthly_activities_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM kegiatan_guru 
                                                          WHERE id_user = $user_id 
                                                          AND MONTH(tanggal) = MONTH(CURRENT_DATE()) 
                                                          AND YEAR(tanggal) = YEAR(CURRENT_DATE())");
          $monthly_activities_count = mysqli_fetch_assoc($monthly_activities_query)['total'];
        } else {
          $my_activities_count = 0;
          $monthly_activities_count = 0;
        }
        
        $total_activities_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM kegiatan_guru");
        $total_activities_count = mysqli_fetch_assoc($total_activities_query)['total'];
        
        $account_age_query = mysqli_query($conn, "SELECT DATEDIFF(CURRENT_DATE(), created_at) as days FROM users WHERE id_user = $user_id");
        $account_age = mysqli_fetch_assoc($account_age_query)['days'];
        ?>
        
        <div class="col-12">
          <div class="stats-card">
            <div class="stats-icon icon-purple">
              <i class="bi bi-person-check"></i>
            </div>
            <h4 class="mb-1"><?php echo $account_age; ?> Hari</h4>
            <p class="text-muted mb-0">Bergabung Sejak</p>
          </div>
        </div>
        
        <?php if ($user_role == 'guru'): ?>
        <div class="col-12">
          <div class="stats-card">
            <div class="stats-icon icon-green">
              <i class="bi bi-journal-bookmark"></i>
            </div>
            <h4 class="mb-1"><?php echo $my_activities_count; ?></h4>
            <p class="text-muted mb-0">Total Kegiatan</p>
          </div>
        </div>
        
        <div class="col-12">
          <div class="stats-card">
            <div class="stats-icon icon-blue">
              <i class="bi bi-calendar-month"></i>
            </div>
            <h4 class="mb-1"><?php echo $monthly_activities_count; ?></h4>
            <p class="text-muted mb-0">Kegiatan Bulan Ini</p>
          </div>
        </div>
        <?php else: ?>
        <div class="col-12">
          <div class="stats-card">
            <div class="stats-icon icon-green">
              <i class="bi bi-calendar-event"></i>
            </div>
            <h4 class="mb-1"><?php echo $total_activities_count; ?></h4>
            <p class="text-muted mb-0">Total Kegiatan Tersedia</p>
          </div>
        </div>
        
        <div class="col-12">
          <div class="stats-card">
            <div class="stats-icon icon-blue">
              <i class="bi bi-mortarboard"></i>
            </div>
            <h4 class="mb-1"><?php echo $profile['nama_kelas'] ?? 'Belum Dipilih'; ?></h4>
            <p class="text-muted mb-0">Kelas</p>
          </div>
        </div>
        <?php endif; ?>
        
        <div class="col-12">
          <div class="stats-card">
            <div class="stats-icon icon-orange">
              <i class="bi bi-shield-check"></i>
            </div>
            <h4 class="mb-1">Aktif</h4>
            <p class="text-muted mb-0">Status Akun</p>
          </div>
        </div>
      </div>
      
      <div class="profile-card mt-4">
        <h6 class="mb-3 fw-semibold">Aksi Cepat</h6>
        
        <div class="d-grid gap-2">
          <a href="user_dashboard.php" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-house-door me-2"></i>Dashboard
          </a>
          <a href="kegiatan_user.php" class="btn btn-outline-success btn-sm">
            <i class="bi bi-calendar-event me-2"></i>Lihat Kegiatan
          </a>
          <a href="schedule.php" class="btn btn-outline-info btn-sm">
            <i class="bi bi-calendar-week me-2"></i>Jadwal
          </a>
          <a href="settings.php" class="btn btn-outline-warning btn-sm">
            <i class="bi bi-gear me-2"></i>Pengaturan
          </a>
        </div>
      </div>
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Profile picture preview function
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
            document.getElementById('profileImage').src = e.target.result;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Reset form function
function resetForm() {
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('profilePictureInput').value = '';
    // Reset profile image to original
    <?php 
    $original_pic_path = isset($profile['profile_picture']) && $profile['profile_picture'] 
                        ? "../uploads/profile_pictures/" . $profile['profile_picture'] 
                        : "../aplikasi-kegiatan-admin-module/img/avatar.png";
    ?>
    document.getElementById('profileImage').src = '<?php echo $original_pic_path; ?>';
}

// Password confirmation validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = this.value;
    
    if (newPassword !== confirmPassword) {
        this.setCustomValidity('Password tidak cocok');
    } else {
        this.setCustomValidity('');
    }
});

// Form validation feedback
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
});
</script>
</body>
</html>
