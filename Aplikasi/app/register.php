<?php
include 'koneksi.php';

// Handle messages from URL parameters
$message = '';
if (isset($_GET['success'])) {
    $message = "<div class='alert alert-success'>" . htmlspecialchars($_GET['success']) . "</div>";
} else if (isset($_GET['error'])) {
    $message = "<div class='alert alert-danger'>" . htmlspecialchars($_GET['error']) . "</div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Akun - Aplikasi Kegiatan Guru</title>
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
            background: url('https://images.unsplash.com/photo-1512453979798-5ea266f8880c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80') center/cover;
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
        
        .register-container {
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
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
        
        .form-select {
            padding-left: 45px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            height: 50px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-select:focus {
            border-color: #4A90E2;
            box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
        }
        
        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        
        .terms-checkbox input[type="checkbox"] {
            margin-right: 10px;
            margin-top: 3px;
            transform: scale(1.2);
        }
        
        .terms-checkbox label {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.4;
        }
        
        .terms-checkbox a {
            color: #4A90E2;
            text-decoration: none;
        }
        
        .terms-checkbox a:hover {
            color: #357ABD;
        }
        
        .btn-signup {
            width: 100%;
            height: 50px;
            background: linear-gradient(135deg, #4A90E2, #357ABD);
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .btn-signup:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(74, 144, 226, 0.3);
        }
        
        .login-link {
            text-align: center;
            color: #666;
        }
        
        .login-link a {
            color: #4A90E2;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        
        .login-link a:hover {
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
        <div class="register-container">
            <div class="logo">
                <div class="logo-icon">M</div>
                <h1>Majestic</h1>
            </div>
            
            <div class="welcome-text">
                <h2>New here?</h2>
                <p>Join us today! It takes only few steps</p>
            </div>
            
            <?php echo $message; ?>
            
            <form method="POST" action="proses_register.php">
                <div class="form-group">
                    <i class="fas fa-user"></i>
                    <input type="text" class="form-control" name="nama_lengkap" placeholder="Nama Lengkap" required>
                </div>
                
                <div class="form-group">
                    <i class="fas fa-user"></i>
                    <input type="text" class="form-control" name="username" placeholder="Username" required minlength="3">
                </div>
                
                <div class="form-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" class="form-control" name="password" placeholder="Password" required minlength="6">
                </div>
                
                <div class="form-group">
                    <i class="fas fa-user-tag"></i>
                    <select name="role" class="form-select" required>
                        <option value="">Pilih Role</option>
                        <option value="guru">Saya adalah Guru</option>
                        <option value="siswa">Saya adalah Siswa</option>
                    </select>
                </div>
                
                <div class="terms-checkbox">
                    <input type="checkbox" id="terms" required>
                    <label for="terms">I agree to all <a href="#">Terms & Conditions</a></label>
                </div>
                
                <button class="btn btn-signup" type="submit">SIGN UP</button>
                
                <div class="login-link">
                    Already have an account? <a href="login.php">Login</a>
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
