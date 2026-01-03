<?php
include 'validation.php';
include 'koneksi.php';

requireAdmin();

$period = $_GET['period'] ?? 'monthly';
$jenis = $_GET['jenis'] ?? '';

// Build date condition
$date_condition = "";
$period_name = "Semua Periode";
switch ($period) {
    case 'daily':
        $date_condition = "AND DATE(kg.tanggal) = CURDATE()";
        $period_name = "Hari Ini";
        break;
    case 'weekly':
        $date_condition = "AND YEARWEEK(kg.tanggal, 1) = YEARWEEK(CURDATE(), 1)";
        $period_name = "Minggu Ini";
        break;
    case 'monthly':
        $date_condition = "AND YEAR(kg.tanggal) = YEAR(CURDATE()) AND MONTH(kg.tanggal) = MONTH(CURDATE())";
        $period_name = "Bulan Ini";
        break;
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

// Set headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=laporan_kegiatan_' . date('Y-m-d') . '.csv');

// Create output stream
$output = fopen('php://output', 'w');

// Add BOM for UTF-8
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Add headers
fputcsv($output, ['Laporan Kegiatan Guru - ' . $period_name]);
fputcsv($output, ['Tanggal Export: ' . date('d-m-Y H:i:s')]);
fputcsv($output, []);
fputcsv($output, ['No', 'Nama Guru', 'Username', 'Tanggal', 'Jenis Kegiatan', 'Kelas', 'Laporan']);

// Add data
$no = 1;
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, [
            $no++,
            $row['nama_guru'],
            $row['username'],
            date('d-m-Y', strtotime($row['tanggal'])),
            $row['jenis_kegiatan'],
            $row['nama_kelas'] ?? '-',
            $row['laporan']
        ]);
    }
} else {
    fputcsv($output, ['', 'Tidak ada data', '', '', '', '', '']);
}

fclose($output);
exit;
?>
