<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!file_exists('koneksi.php')) {
    die("File koneksi.php tidak ditemukan. Pastikan file koneksi.php ada di direktori yang sama.");
}

include 'koneksi.php';

if (!isset($koneksi) || $koneksi->connect_error) {
    die("Koneksi database gagal. Pastikan database 'app_kegiatan_guru' sudah dibuat dan XAMPP MySQL berjalan.");
}

$message = '';

if (isset($_GET['error'])) {
    $error_message = urldecode($_GET['error']);
    // Selalu sembunyikan informasi debug apapun yang mungkin ikut terkirim
    // Ambil hanya pesan error utama sebelum penanda "DEBUG INFO"
    $parts = explode("\n\nDEBUG INFO:", $error_message, 2);
    $main_error = trim($parts[0]);
    $message = "<div class='alert alert-danger'>" . htmlspecialchars($main_error) . "</div>";
}

// All login processing is handled by proses_login.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Kegiatan Guru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            height: 100svh; /* Support for newer browsers */
            display: flex;
            overflow: hidden;
            margin: 0;
            padding: 0;
        }
        
        .left-side {
            flex: 1;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-y: auto;
        }
        
        .right-side {
            flex: 1;
            background: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2071&q=80') center/cover;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .right-side::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(138, 43, 226, 0.8), rgba(75, 0, 130, 0.8));
        }
        
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            backdrop-filter: blur(10px);
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .logo h1 {
            color: #4A90E2;
            font-size: 2.2rem;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .logo .logo-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #4A90E2, #357ABD);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.6rem;
            font-weight: bold;
            margin-bottom: 12px;
        }
        
        .welcome-text {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .welcome-text h2 {
            color: #333;
            font-size: 1.6rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .welcome-text p {
            color: #666;
            font-size: 0.9rem;
        }
        
        .form-group {
            margin-bottom: 15px;
            position: relative;
        }
        
        .form-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            z-index: 2;
        }
        
        .form-control {
            padding-left: 45px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            height: 50px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #4A90E2;
            box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
        }
        
        .form-control::placeholder {
            color: #999;
        }
        
        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
        }
        
        .remember-me input[type="checkbox"] {
            margin-right: 8px;
            transform: scale(1.2);
        }
        
        .remember-me label {
            color: #666;
            font-size: 0.9rem;
        }
        
        .forgot-password {
            color: #4A90E2;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }
        
        .forgot-password:hover {
            color: #357ABD;
        }
        
        .btn-login {
            width: 100%;
            height: 50px;
            background: linear-gradient(135deg, #4A90E2, #357ABD);
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(74, 144, 226, 0.3);
        }
        
        .social-login {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .btn-social {
            flex: 1;
            height: 45px;
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-facebook {
            background: #3B5998;
        }
        
        .btn-google {
            background: #DB4437;
        }
        
        .btn-social:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .signup-link {
            text-align: center;
            color: #666;
        }
        
        .signup-link a {
            color: #4A90E2;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        
        .signup-link a:hover {
            color: #357ABD;
        }
        
        .copyright {
            position: absolute;
            bottom: 20px;
            right: 20px;
            color: white;
            font-size: 0.8rem;
            opacity: 0.8;
        }
        
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            
            .right-side {
                display: none;
            }
            
            .left-side {
                flex: none;
                height: 100vh;
            }
        }
    </style>
</head>
<body>
    <div class="left-side">
        <div class="login-container">
            <div class="logo">
                <div class="logo-icon">M</div>
                <h1>Majestic</h1>
            </div>
            
            <div class="welcome-text">
                <h2>Welcome back!</h2>
                <p>Happy to see you again!</p>
            </div>
            
            <?php echo $message; ?>
            
            <form method="POST" action="proses_login.php">
                <div class="form-group">
                    <i class="fas fa-user"></i>
                <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
            
                <div class="form-group">
                    <i class="fas fa-lock"></i>
                <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
                
                <div class="options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember">
                        <label for="remember">Keep me signed in</label>
                    </div>
                    <a href="#" class="forgot-password">Forgot password?</a>
            </div>
            
                <button class="btn btn-login" type="submit">LOGIN</button>
                
                <div class="social-login">
                    <button type="button" class="btn btn-social btn-facebook">
                        <i class="fab fa-facebook-f"></i>
                        Facebook
                    </button>
                    <button type="button" class="btn btn-social btn-google">
                        <i class="fab fa-google"></i>
                        Google
                    </button>
                </div>
                
                <div class="signup-link">
                    Don't have an account? <a href="register.php">Create</a>
                </div>
        </form>
        </div>
    </div>
    
    <div class="right-side">
        <div class="copyright">
            Copyright © 2020 All rights reserved.
        </div>
    </div>
</body>
</html>
