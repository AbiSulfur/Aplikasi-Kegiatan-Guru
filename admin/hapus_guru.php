<?php
include 'validation.php';
include 'koneksi.php';

requireAdmin();

// Ambil ID dari URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: guru.php?error=" . urlencode("ID guru tidak valid!"));
    exit();
}

$guru_id = intval($_GET['id']);

// Cek apakah guru ada
$stmt = mysqli_prepare($koneksi, "SELECT nama_lengkap FROM users WHERE id_user = ? AND role = 'guru'");
mysqli_stmt_bind_param($stmt, 'i', $guru_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$guru = mysqli_fetch_assoc($result);

if (!$guru) {
    header("Location: guru.php?error=" . urlencode("Data guru tidak ditemukan!"));
    exit();
}

// Cek apakah guru memiliki kegiatan
$check_kegiatan = mysqli_prepare($koneksi, "SELECT COUNT(*) as total FROM kegiatan_guru WHERE id_user = ?");
mysqli_stmt_bind_param($check_kegiatan, 'i', $guru_id);
mysqli_stmt_execute($check_kegiatan);
$kegiatan_result = mysqli_stmt_get_result($check_kegiatan);
$kegiatan_count = mysqli_fetch_assoc($kegiatan_result)['total'];

if ($kegiatan_count > 0) {
    header("Location: guru.php?error=" . urlencode("Guru tidak dapat dihapus karena memiliki " . $kegiatan_count . " kegiatan!"));
    exit();
}

// Hapus guru
$stmt = mysqli_prepare($koneksi, "DELETE FROM users WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, 'i', $guru_id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: guru.php?msg=" . urlencode("Guru " . $guru['nama_lengkap'] . " berhasil dihapus!"));
    exit();
} else {
    header("Location: guru.php?error=" . urlencode("Gagal menghapus guru: " . mysqli_stmt_error($stmt)));
    exit();
}
?>