<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'app_kegiatan_guru'; // Updated database name to match the actual database

// Create connection
$koneksi = new mysqli($host, $username, $password, $database);

// Check connection
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Set charset to utf8mb4
$koneksi->set_charset("utf8mb4");

$conn = $koneksi;
?>
