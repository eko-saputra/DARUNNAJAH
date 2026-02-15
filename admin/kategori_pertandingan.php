<?php
require('fpdf/fpdf.php');
include "../backend/includes/connection.php";

$id_partai = isset($_GET['id_partai']) ? intval($_GET['id_partai']) : 0;

// Ambil data jadwal
$jadwal_query = $koneksi->query("SELECT * FROM jadwal_tanding WHERE id_partai = $id_partai");
$jadwal = $jadwal_query->fetch_assoc();

class PDF extends FPDF
{
    function Header()
    {
        // Logo
        $this->Image('../backend/ipsi2.png', 10, 6, 20);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 7, 'Formulir Pertandingan Pencak Silat', 0, 1, 'C');
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 6, 'Tugas Wasit Juri', 0, 1, 'C');
        $this->Cell(0, 6, 'Kategori Pertandingan', 0, 1, 'C');
        $this->Ln(5);
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 11);

// Informasi Partai
$pdf->Cell(60, 7, "Nomor Partai: " . $jadwal['partai'], 0, 0, 'L');
$pdf->Cell(70, 7, "Kategori: " . $jadwal['kelas'], 0, 0, 'C');
$pdf->Cell(60, 7, "Tanggal: " . $jadwal['tgl'], 0, 1, 'R');
$pdf->Ln(3);

// Tabel Merah-Biru (Header)
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(220, 53, 69); // MERAH
$pdf->SetTextColor(255, 255, 255); // Putih
$pdf->Cell(95, 8, "MERAH", 1, 0, 'C', true);
$pdf->SetFillColor(0, 102, 255); // BIRU
$pdf->Cell(95, 8, "BIRU", 1, 1, 'C', true);

// Tabel Merah-Biru (Isi)
$pdf->SetFont('Arial', 'B', 10); // Bold untuk "Nama:"
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(95, 7, "Nama: " . $jadwal['nm_merah'], 1, 0, 'L');
$pdf->Cell(95, 7, "Nama: " . $jadwal['nm_biru'], 1, 1, 'L');
$pdf->Cell(95, 7, "Perguruan: " . $jadwal['kontingen_merah'], 1, 0, 'L');
$pdf->Cell(95, 7, "Perguruan: " . $jadwal['kontingen_biru'], 1, 1, 'L');
$pdf->Ln(5);

// Tabel Wasit dan Juri
$pdf->SetFont('Arial', '', 10);
// Baris 1: Dipimpin Oleh
$pdf->Cell(63.3, 7, "Dipimpin Oleh", 1, 0, 'C');
$pdf->Cell(63.3, 7, "Wasit", 1, 0, 'C');
$pdf->Cell(63.4, 7, "", 1, 1, 'C');

// Baris 2-4: Dibantu Oleh (gabung 3 baris jadi 1 cell)
$pdf->Cell(63.3, 21, "Dibantu Oleh", 1, 0, 'C'); // Tinggi 21 = 7 * 3
$pdf->Cell(63.3, 7, "Juri 1", 1, 0, 'C');
$pdf->Cell(63.4, 7, "", 1, 1, 'C');

$pdf->Cell(63.3, 7, "", 0, 0); // Kosong karena sudah digabung
$pdf->Cell(63.3, 7, "Juri 2", 1, 0, 'C');
$pdf->Cell(63.4, 7, "", 1, 1, 'C');

$pdf->Cell(63.3, 7, "", 0, 0); // Kosong karena sudah digabung
$pdf->Cell(63.3, 7, "Juri 3", 1, 0, 'C');
$pdf->Cell(63.4, 7, "", 1, 1, 'C');
$pdf->Ln(5);

$pdf->Ln(5); // Tambah jarak vertikal 5mm sebelum bagian tanda tangan

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 7, "Terferifikasi Oleh", 0, 1, 'L');

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(85, 7, "Nama Dewan: ____________________", 0, 0, 'L');
$pdf->Cell(20, 7, "", 0, 0); // spasi tengah
$pdf->Cell(85, 7, "Tanda Tangan: ________________", 0, 1, 'L');

// Tambahkan jarak antara baris tanda tangan
$pdf->Ln(8); // jarak vertikal 8mm

$pdf->Cell(85, 7, "Nama Ketua: ____________________", 0, 0, 'L');
$pdf->Cell(20, 7, "", 0, 0); // spasi tengah
$pdf->Cell(85, 7, "Tanda Tangan: ________________", 0, 1, 'L');

$pdf->Output();
?>
