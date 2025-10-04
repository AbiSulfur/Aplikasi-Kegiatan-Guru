<?php
// File untuk testing login admin
session_start();
include 'koneksi.php';

echo "<h2>Test Login Admin</h2>";
echo "<p>Kredensial yang benar berdasarkan database:</p>";
echo "<ul>";
echo "<li><strong>Username:</strong> admin</li>";
echo "<li><strong>Password:</strong> admin123</li>";
echo "</ul>";

// Test MD5 hash
$test_password = "admin123";
$md5_hash = md5($test_password);
echo "<p>MD5 hash dari 'admin123': <code>$md5_hash</code></p>";

// Check database
$query = "SELECT * FROM users WHERE username = 'admin'";
$result = $koneksi->query($query);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo "<h3>Data Admin di Database:</h3>";
    echo "<ul>";
    echo "<li>ID: " . $user['id_user'] . "</li>";
    echo "<li>Username: " . $user['username'] . "</li>";
    echo "<li>Password Hash: " . $user['password'] . "</li>";
    echo "<li>Nama: " . $user['nama_lengkap'] . "</li>";
    echo "<li>Role: " . $user['role'] . "</li>";
    echo "<li>Status: " . $user['status'] . "</li>";
    echo "</ul>";
    
    // Check if password matches
    if ($user['password'] === $md5_hash) {
        echo "<p style='color: green;'><strong>✓ Password hash cocok!</strong></p>";
    } else {
        echo "<p style='color: red;'><strong>✗ Password hash tidak cocok!</strong></p>";
        echo "<p>Expected: " . $user['password'] . "</p>";
        echo "<p>Got: " . $md5_hash . "</p>";
    }
} else {
    echo "<p style='color: red;'>User admin tidak ditemukan di database!</p>";
}

echo "<hr>";
echo "<h3>Test Login Form</h3>";
echo "<form method='POST' action='proses_login.php'>";
echo "<p>Username: <input type='text' name='username' value='admin' /></p>";
echo "<p>Password: <input type='password' name='password' value='admin123' /></p>";
echo "<p><button type='submit'>Test Login</button></p>";
echo "</form>";

echo "<p><a href='login.php'>Kembali ke Login</a></p>";
?>
