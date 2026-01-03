<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to check if user is logged in
function isLoggedIn() {
    // Debug: Check what's actually in the session
    error_log("DEBUG isLoggedIn: Session data = " . print_r($_SESSION, true));
    error_log("DEBUG isLoggedIn: id_user isset = " . (isset($_SESSION['id_user']) ? 'true' : 'false'));
    
    return isset($_SESSION['id_user']) && !empty($_SESSION['id_user']);
}

// Function to check user role
function hasRole($required_role) {
    if (!isLoggedIn()) {
        return false;
    }
    return isset($_SESSION['role']) && $_SESSION['role'] == $required_role;
}

// Function to redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// Function to redirect if not admin
function requireAdmin() {
    requireLogin();
    if (!hasRole('admin')) {
        header("Location: user_dashboard.php");
        exit();
    }
}

// Function to redirect if not teacher
function requireTeacher() {
    requireLogin();
    if (!hasRole('guru')) {
        header("Location: user_dashboard.php");
        exit();
    }
}

// Function to get current user info
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['id_user'],
        'username' => $_SESSION['username'],
        'nama_lengkap' => $_SESSION['nama_lengkap'],
        'role' => $_SESSION['role']
    ];
}

// Function to logout
function logout() {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
