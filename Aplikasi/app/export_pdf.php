<?php
ob_start();
error_reporting(E_ERROR | E_PARSE);

include 'validation.php';
include 'koneksi.php';
requireAdmin();

require_once __DIR__ . '/libs/fpdf.php';

class PDF extends FPDF
{
    public $withWatermark = true;

    function Header()
    {
        if ($this->page > 1) {
            // Watermark di halaman isi laporan
            $watermark = __DIR__ . '/../img/watermark.png';
            if ($this->withWatermark && file_exists($watermark) && $this->page > 1) {
                $this->Image($watermark, 25, 55, 160); 
            }
            
            // Kop sekolah
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 7, 'SEKOLAH SMP BERBUDI YOGYAKARTA', 0, 1, 'C');
            $this->SetFont('Arial', '', 9);
            $this->Cell(0, 5, 'Jl. Pendidikan No. 123, Yogyakarta | Telp: (0274) 123456', 0, 1, 'C');
            $this->Ln(3);
            $this->Line(10, 32, 200, 32);
            $this->Ln(8);
        }
    }

    function Footer()
    {
        if ($this->page == 1) return; // Cover page tanpa footer

        $this->SetY(-15);
        $this->SetFont('Arial','I',9);
        $this->Cell(0, 5, 'Dicetak pada: ' . date('d-m-Y H:i'), 0, 1, 'L');
        $this->Cell(0, 5, 'Halaman ' . $this->PageNo(), 0, 0, 'R');
    }

    function RotatedText($x, $y, $txt, $angle)
    {
        $this->Rotate($angle, $x, $y);
        $this->Text($x, $y, $txt);
        $this->Rotate(0);
    }

    function Rotate($angle, $x = -1, $y = -1)
    {
        if ($x == -1)
            $x = $this->x;
        if ($y == -1)
            $y = $this->y;
        if ($angle != 0) {
            $angle *= M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf(
                'q %.3F %.3F %.3F %.3F %.3F %.3F cm',
                $c, $s, -$s, $c, $cx - $c * $cx + $s * $cy,
                $cy - $s * $cx - $c * $cy
            ));
        } else {
            $this->_out('Q');
        }
    }

    function CoverPage()
    {
        $this->AddPage();

        // Logo besar
        $logo = __DIR__ . '/../img/logo.png';

        if (file_exists($logo)) {
            $this->Image($logo, 70, 40, 70);
        } 

        // Judul
        $this->Ln(120);
        $this->SetFont('Arial','B',24);
        $this->Cell(0, 15, 'LAPORAN KEGIATAN GURU', 0, 1, 'C');

        $this->SetFont('Arial','',14);
        $this->Cell(0, 10, 'Tahun Ajaran ' . date('Y'), 0, 1, 'C');

        $this->Ln(40);
        $this->SetFont('Arial','',12);
        $this->Cell(0, 8, 'Disusun oleh:', 0, 1, 'C');
        $this->Cell(0, 8, 'Admin Kegiatan Guru', 0, 1, 'C');
        $this->Ln(20);

        $this->SetFont('Arial','',11);
        $this->Cell(0, 8, 'SMP BERBUDI YOGYAKARTA', 0, 1, 'C');
        $this->Cell(0, 8, date('d F Y'), 0, 1, 'C');
    }

    function ApprovalPage()
    {
        $this->AddPage();
        $this->SetFont('Arial','B',16);
        $this->Cell(0, 10, 'HALAMAN PENGESAHAN', 0, 1, 'C');
        $this->Ln(10);

        $this->SetFont('Arial','',12);
        $this->MultiCell(0, 7,
            "Laporan ini telah diperiksa dan disahkan oleh pihak sekolah.\n\n" .
            "Dokumen ini berisi data kegiatan guru untuk keperluan administrasi dan arsip resmi sekolah."
        );

        $this->Ln(30);

        // tanda tangan
        if (file_exists(__DIR__.'/ttd.png')) {
            $this->Image(__DIR__.'/ttd.png', 130, $this->GetY(), 50);
        }

        $this->Ln(35);
        $this->SetFont('Arial','',12);
        $this->Cell(0, 5, '_____________________________', 0, 1, 'R');
        $this->Cell(0, 7, 'Kepala Sekolah', 0, 1, 'R');
        $this->Cell(0, 7, 'SMP Berbudi Yogyakarta', 0, 1, 'R');
    }
}

// Query
$query = mysqli_query($conn, "
    SELECT kg.*, 
           u.nama_lengkap AS nama_guru,
           k.nama_kelas,
           j.nama_jenis
    FROM kegiatan_guru kg
    JOIN users u ON kg.id_user = u.id_user
    JOIN kelas k ON kg.id_kelas = k.id_kelas
    JOIN jenis_kegiatan j ON kg.id_jenis = j.id_jenis
    ORDER BY kg.tanggal DESC
");

if (!$query) {
    ob_end_clean();
    echo "Query Error.";
    exit;
}

$pdf = new PDF();
$pdf->SetMargins(10, 10, 10);

// COVER PAGE
$pdf->CoverPage();

// HALAMAN PENGESAHAN
$pdf->ApprovalPage();

// HALAMAN DATA
$pdf->AddPage();
$pdf->SetFont('Arial','B',13);
$pdf->Cell(0, 10, "DATA KEGIATAN GURU", 0, 1, 'C');
$pdf->Ln(3);

// HEAD TABLE
$pdf->SetFont('Arial','B',10);
$pdf->SetFillColor(200, 200, 255);
$pdf->Cell(10, 8, "No", 1, 0, 'C', true);
$pdf->Cell(40, 8, "Guru", 1, 0, 'C', true);
$pdf->Cell(30, 8, "Kelas", 1, 0, 'C', true);
$pdf->Cell(40, 8, "Jenis", 1, 0, 'C', true);
$pdf->Cell(50, 8, "Laporan", 1, 0, 'C', true);
$pdf->Cell(20, 8, "Tanggal", 1, 1, 'C', true);

// ROWS
$pdf->SetFont('Arial','',9);
$no = 1;

while ($row = mysqli_fetch_assoc($query)) {

    $laporan = $row['laporan'];
    if (strlen($laporan) > 60) {
        $laporan = substr($laporan, 0, 60) . "...";
    }

    $pdf->Cell(10, 8, $no++, 1);
    $pdf->Cell(40, 8, $row['nama_guru'], 1);
    $pdf->Cell(30, 8, $row['nama_kelas'], 1);
    $pdf->Cell(40, 8, $row['nama_jenis'], 1);
    $pdf->Cell(50, 8, $laporan, 1);
    $pdf->Cell(20, 8, $row['tanggal'], 1);
    $pdf->Ln();
}

ob_end_clean();
$pdf->Output('laporan_kegiatan_resmi.pdf', 'D');
exit;
