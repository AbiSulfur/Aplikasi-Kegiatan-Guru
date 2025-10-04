<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Manually set session for testing
$_SESSION['id_user'] = 1;
$_SESSION['username'] = 'admin';
$_SESSION['nama_lengkap'] = 'Administrator';
$_SESSION['role'] = 'admin';

echo "<h2>Manual Session Set</h2>";
echo "<p>Session has been manually set for admin user.</p>";
echo "<p><a href='debug_session.php'>Check Session Status</a></p>";
echo "<p><a href='index.php'>Try Accessing Admin Dashboard</a></p>";
?>
