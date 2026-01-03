<?php
include 'validation.php';
include 'koneksi.php';

requireAdmin();

header('Content-Type: application/json');

$period = $_GET['period'] ?? 'monthly';
$jenis = $_GET['jenis'] ?? '';

// Build date condition
$date_condition = "";
switch ($period) {
    case 'daily':
        $date_condition = "AND DATE(kg.tanggal) = CURDATE()";
        break;
    case 'weekly':
        $date_condition = "AND YEARWEEK(kg.tanggal, 1) = YEARWEEK(CURDATE(), 1)";
        break;
    case 'monthly':
        $date_condition = "AND YEAR(kg.tanggal) = YEAR(CURDATE()) AND MONTH(kg.tanggal) = MONTH(CURDATE())";
        break;
    default:
        $date_condition = "";
}

// Build query
$query = "SELECT kg.*, u.nama_lengkap as nama_guru, u.username,
          j.nama_jenis as jenis_kegiatan, k.nama_kelas
          FROM kegiatan_guru kg
          LEFT JOIN users u ON kg.id_user = u.id_user
          LEFT JOIN jenis_kegiatan j ON kg.id_jenis = j.id_jenis
          LEFT JOIN kelas k ON kg.id_kelas = k.id_kelas
          WHERE 1=1 $date_condition";

if (!empty($jenis)) {
    $query .= " AND kg.id_jenis = " . intval($jenis);
}

$query .= " ORDER BY kg.tanggal DESC";

$result = mysqli_query($koneksi, $query);

$data = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>
