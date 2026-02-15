<?php
include '../backend/includes/connection.php';
require('fpdf/fpdf.php'); // pastikan path fpdf sudah benar

$id_jadwal = isset($_GET['id_partai']) ? intval($_GET['id_partai']) : 0;

// Ambil data jadwal pertandingan
$stmt = $koneksi->prepare("SELECT * FROM jadwal_tanding WHERE id_partai = ?");
$stmt->bind_param("i", $id_jadwal);
$stmt->execute();
$result = $stmt->get_result();
$jadwal = $result->fetch_assoc();
$stmt->close();

class PDF extends FPDF {
    function Header() {
        // Logo
        $this->Image('../backend/ipsi2.png', 10, 8, 25); // logo di kiri
        // Judul rata tengah
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 8, 'Formulir Pertandingan Pencak Silat', 0, 1, 'C');
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 6, 'Score Sheet For Juri', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        // Footer kosong biar bersih
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 11);

// Match Info
$pdf->Cell(60, 6, "Nomor Partai : " . $jadwal['partai'], 0, 0, 'L');
$pdf->Cell(70, 6, "Kelas : " . strtoupper($jadwal['kelas']), 0, 0, 'C');
$pdf->Cell(60, 6, "Tanggal : " . $jadwal['tgl'], 0, 1, 'R');
$pdf->Ln(4);

// Nama & Perguruan
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(95, 6, "Nama : " . $jadwal['nm_merah'], 1, 0, 'L');
$pdf->Cell(95, 6, "Nama : " . $jadwal['nm_biru'], 1, 1, 'L');
$pdf->Cell(95, 6, "Perguruan: " . $jadwal['kontingen_merah'], 1, 0, 'L');
$pdf->Cell(95, 6, "Perguruan: " . $jadwal['kontingen_biru'], 1, 1, 'L');
$pdf->Ln(6);

// Table Header
$pdf->SetFont('Arial', 'B', 10);

// MERAH header
$pdf->SetFillColor(220, 53, 69); // Background MERAH
$pdf->SetTextColor(255, 255, 255); // Text putih
$pdf->Cell(63, 8, "MERAH", 1, 0, 'C', true);

// ROUND header
$pdf->SetTextColor(0, 0, 0); // Balik ke text hitam
$pdf->Cell(64, 8, "Round", 1, 0, 'C');

// BIRU header
$pdf->SetFillColor(0, 102, 255); // Background BIRU
$pdf->SetTextColor(255, 255, 255); // Text putih
$pdf->Cell(63, 8, "BIRU", 1, 1, 'C', true);

// Reset text color ke hitam lagi untuk data tabel selanjutnya
$pdf->SetTextColor(0, 0, 0);

// Sub Header
$pdf->SetFillColor(255);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(63, 6, "Score", 1, 0, 'C');
$pdf->Cell(64, 6, "", 1, 0, 'C');
$pdf->Cell(63, 6, "Score", 1, 1, 'C');

// Ambil data nilai per babak
$pdf->SetFont('Arial', '', 10);
for ($babak = 1; $babak <= 3; $babak++) {

    // Nilai MERAH
    $sql_merah = "SELECT COALESCE(SUM(nilai), 0) AS total FROM nilai_tanding WHERE sudut = 'MERAH' AND id_jadwal = ? AND babak = ?";
    $stmt = $koneksi->prepare($sql_merah);
    $stmt->bind_param("ii", $id_jadwal, $babak);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $nilai_merah = $row['total'] ?? 0;
    $stmt->close();

    // Nilai BIRU
    $sql_biru = "SELECT COALESCE(SUM(nilai), 0) AS total FROM nilai_tanding WHERE sudut = 'BIRU' AND id_jadwal = ? AND babak = ?";
    $stmt = $koneksi->prepare($sql_biru);
    $stmt->bind_param("ii", $id_jadwal, $babak);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $nilai_biru = $row['total'] ?? 0;
    $stmt->close();

    $pdf->Cell(63, 8, $nilai_merah, 1, 0, 'C');
    $pdf->Cell(64, 8, $babak, 1, 0, 'C');
    $pdf->Cell(63, 8, $nilai_biru, 1, 1, 'C');
}

// Hitung total nilai semua babak untuk MERAH
$sql_merah_total = "SELECT COALESCE(SUM(nilai), 0) AS total FROM nilai_tanding WHERE sudut = 'MERAH' AND id_jadwal = ?";
$stmt = $koneksi->prepare($sql_merah_total);
$stmt->bind_param("i", $id_jadwal);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$total_merah = $row['total'] ?? 0;
$stmt->close();

// Hitung total nilai semua babak untuk BIRU
$sql_biru_total = "SELECT COALESCE(SUM(nilai), 0) AS total FROM nilai_tanding WHERE sudut = 'BIRU' AND id_jadwal = ?";
$stmt = $koneksi->prepare($sql_biru_total);
$stmt->bind_param("i", $id_jadwal);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$total_biru = $row['total'] ?? 0;
$stmt->close();

// Tampilkan total nilai keseluruhan
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(63, 8, "Total Point : " . $total_merah, 1, 0, 'C');
$pdf->Cell(64, 8, "", 1, 0, 'C');
$pdf->Cell(63, 8, "Total Point : " . $total_biru, 1, 1, 'C');


// Match Result
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, "Match Result", 0, 1, 'L');
$pdf->Cell(40, 6, "Sudut: ___________________", 0, 0, 'L');
$pdf->Cell(50, 6, "Round: ___________________", 0, 1, 'L');
$pdf->Cell(0, 6, "Menang Dengan: Point / TKO / Mutlak / Diskualifikasi / RSC / Walk Over", 0, 1, 'L');
$pdf->Ln(2);
$pdf->Cell(60, 6, "Nomor Juri: _________________", 0, 0, 'L');
$pdf->Cell(70, 6, "Nama Juri: _________________", 0, 0, 'L');
$pdf->Cell(50, 6, "Tanda: ____________", 0, 1, 'L');

$pdf->Output('I', 'score_juri.pdf');
