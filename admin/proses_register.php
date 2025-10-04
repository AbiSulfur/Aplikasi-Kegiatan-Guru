<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    // Basic validation
    if (empty($nama_lengkap) || empty($username) || empty($password) || empty($role)) {
        $response['message'] = 'Semua field harus diisi!';
    } else if (strlen($username) < 3) {
        $response['message'] = 'Username minimal 3 karakter!';
    } else if (strlen($password) < 6) {
        $response['message'] = 'Password minimal 6 karakter!';
    } else if (!in_array($role, ['guru', 'siswa'])) {
        $response['message'] = 'Role tidak valid!';
    } else {
        // Check for duplicate username
        $check = $koneksi->prepare("SELECT id_user FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        
        if ($check->get_result()->num_rows > 0) {
            $response['message'] = 'Username sudah digunakan!';
        } else {
            // Insert new user
            $query = "INSERT INTO users (nama_lengkap, username, password, role, status) VALUES (?, ?, ?, ?, 'pending')";
            $stmt = $koneksi->prepare($query);
            $stmt->bind_param("ssss", $nama_lengkap, $username, $password, $role);
            
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Registrasi berhasil! Silakan tunggu persetujuan dari Administrator.';
            } else {
                $response['message'] = 'Terjadi kesalahan saat menyimpan data!';
            }
        }
    }
} else {
    $response['message'] = 'Method tidak diizinkan!';
}

// Return JSON response for AJAX calls
if (isset($_POST['ajax'])) {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// For regular form submission, redirect back with message
if ($response['success']) {
    header("Location: register.php?success=" . urlencode($response['message']));
} else {
    header("Location: register.php?error=" . urlencode($response['message']));
}
exit();
?>
