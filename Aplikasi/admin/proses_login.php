<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php';

$response = ['success' => false, 'message' => '', 'redirect' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    $debug_info = [];
    $debug_info[] = "Login attempt - Username: " . $username;
    $debug_info[] = "Password length: " . strlen($password);
    
    // Basic validation
    if (empty($username) || empty($password)) {
        $response['message'] = 'Username dan password harus diisi!';
    } else {
        $query = "SELECT id_user, username, password, nama_lengkap, role, status FROM users WHERE username = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $stored_password = $user['password'];
            
            $debug_info[] = "User found - Status: " . $user['status'] . ", Role: " . $user['role'];
            $debug_info[] = "Stored password: " . $stored_password;
            $debug_info[] = "Input password: " . $password;
            $debug_info[] = "MD5 of input: " . md5($password);
            
            $password_match = false;
            if ($stored_password === $password) {
                // Plain text match
                $password_match = true;
                $debug_info[] = "Password matched as plain text";
            } elseif ($stored_password === md5($password)) {
                // MD5 hash match (for existing hashed passwords)
                $password_match = true;
                $debug_info[] = "Password matched as MD5 hash";
            } else {
                $debug_info[] = "Password did not match either plain text or MD5";
            }
            
            if ($password_match && $user['status'] == 'approved') {
                $_SESSION['id_user'] = $user['id_user'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['role'] = $user['role'];
                
                $debug_info[] = "Login successful!";
                
                $response['success'] = true;
                $response['message'] = 'Login berhasil!';
                
                // Set redirect based on role
                if ($user['role'] == 'admin') {
                    $response['redirect'] = 'index.php';
                } else {
                    $response['redirect'] = 'user_dashboard.php';
                }
            } elseif ($password_match && $user['status'] == 'pending') {
                $response['message'] = 'Akun Anda masih menunggu persetujuan dari Administrator.';
            } elseif ($password_match && $user['status'] == 'rejected') {
                $response['message'] = 'Akun Anda telah ditolak oleh Administrator.';
            } else {
                $response['message'] = 'Username atau password salah!';
                $debug_info[] = "Login failed - Password match: " . ($password_match ? 'true' : 'false') . ", Status: " . $user['status'];
            }
        } else {
            $response['message'] = 'Username tidak ditemukan!';
            $debug_info[] = "Username not found in database";
        }
    }
} else {
    $response['message'] = 'Method tidak diizinkan!';
}

$response['debug'] = $debug_info;

// Return JSON response for AJAX calls
if (isset($_POST['ajax'])) {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

if ($response['success']) {
    header("Location: " . $response['redirect']);
} else {
    $error_message = $response['message'];
    if (!empty($debug_info)) {
        $error_message .= "\n\nDEBUG INFO:\n" . implode("\n", $debug_info);
    }
    header("Location: login.php?error=" . urlencode($error_message) . "&debug=1");
}
exit();
?>
