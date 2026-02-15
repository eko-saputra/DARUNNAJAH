<?php
session_start();
if (!isset($_SESSION['pwd'])) {
    header('location:login.php');
    exit;
}

require('fpdf/fpdf.php');
include('includes/connection.php');

// ✅ Ambil data dari form
$kelas = isset($_POST["kelas"]) ? mysqli_real_escape_string($koneksi, $_POST["kelas"]) : "";
$gelanggang = isset($_POST["gelanggang"]) ? mysqli_real_escape_string($koneksi, $_POST["gelanggang"]) : "";
$jk = isset($_POST["jk"]) ? mysqli_real_escape_string($koneksi, $_POST["jk"]) : "";
$nama_kelas = isset($_POST["nama_kelas"]) ? mysqli_real_escape_string($koneksi, $_POST["nama_kelas"]) : "";

class PDF extends FPDF
{
    function NbLines($w, $txt)
    {
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0) $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n") $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ') $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j) $i++;
                } else $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else {
                $i++;
            }
        }
        return $nl;
    }

    function Header()
    {
        global $jk, $kelas, $nama_kelas, $gelanggang;

        $logoWidth = 22;
        $logoHeight = 24;

        $this->Image('dumai.png', 10, 5, $logoWidth, $logoHeight);
        $this->Image('ipsi.png', 265, 5, $logoWidth, $logoHeight);
        $this->Ln(1);
        $this->SetY(10);
        $this->SetFont('Arial', 'B', 20);
        $this->Cell(0, 7, 'OPEN CHAMPIONSHIP PENCAK SILAT KOTA DUMAI', 0, 1, 'C');
        $this->Ln(7);

        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 7, 'JADWAL TANDING FINAL PENCAK SILAT KOTA DUMAI', 0, 1, 'C');
        $this->Cell(0, 7, 'TAHUN 2025', 0, 1, 'C');
        $this->Ln(2);

        // 🔎 Buat teks filter
        $filterText = "Semua Kategori";
        if (!empty($jk) || !empty($kelas) || !empty($nama_kelas) || !empty($gelanggang)) {
            $filterText =
                (!empty($gelanggang) ? strtoupper($gelanggang) : ' ') . ' ' .
                (!empty($jk) ? strtoupper($jk) : ' ') . ' ' .
                (!empty($kelas) ? strtoupper($kelas) : ' ') . ' ' .
                (!empty($nama_kelas) ? strtoupper($nama_kelas) : ' ');
        }

        $this->SetFont('Arial', '', 11);
        $this->Cell(0, 7, 'Tanggal: ' . date('d-m-Y') . ' / ' . $filterText, 0, 1, 'C');
        // $this->Cell(0, 7, $filterText, 0, 1, 'C');
        $this->Ln(2);

        // Header kolom
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(255, 255, 0);
        $this->SetTextColor(0);

        $this->Cell(10, 8, 'Partai', 1, 0, 'C', true);
        $this->Cell(45, 8, 'Kelas', 1, 0, 'C', true);

        // Sudut Biru dulu (dipindah)
        $this->SetFillColor(0, 0, 255);
        $this->SetTextColor(255);
        $this->Cell(100, 8, 'Sudut Biru', 1, 0, 'C', true);
        // $this->Cell(45, 8, 'Kontingen Biru', 1, 0, 'C', true);

        // Lalu Sudut Merah (dipindah)
        $this->SetFillColor(255, 0, 0);
        $this->Cell(100, 8, 'Sudut Merah', 1, 0, 'C', true);
        // $this->Cell(45, 8, 'Kontingen Merah', 1, 0, 'C', true);

        // Grup
        $this->SetFillColor(255, 255, 0);
        $this->SetTextColor(0);
        $this->Cell(20, 8, 'Grup', 1, 1, 'C', true);
    }
}

// ✅ Query dinamis
$sqljadwal = "SELECT * FROM jadwal_tanding_final WHERE 1=1";

if (!empty($jk)) {
    $sqljadwal .= " AND kelas LIKE '%$jk%'";
}
if (!empty($kelas)) {
    $sqljadwal .= " AND kelas LIKE '%$kelas%'";
}
if (!empty($nama_kelas)) {
    $sqljadwal .= " AND kelas LIKE '%$nama_kelas%'";
}
if (!empty($gelanggang)) {
    $sqljadwal .= " AND gelanggang = '$gelanggang'";
}

$sqljadwal .= " ORDER BY id_partai ASC";
$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);

// ✅ Cetak PDF
$pdf = new PDF('L', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', '', 8);

$no = 1;
while ($jadwal = mysqli_fetch_assoc($jadwal_tanding)) {
    // ✅ Sudut Biru dan Merah sudah ditukar di sini
    $cols = [
        strtoupper($jadwal['partai']),
        strtoupper($jadwal['kelas']),
        strtoupper($jadwal['nm_biru']),

        strtoupper($jadwal['nm_merah']),

        strtoupper($jadwal['bagan'] . ' ' . $jadwal['babak']),
    ];
    $widths = [10, 45, 100, 100, 20];

    $maxHeight = 0;
    foreach ($cols as $i => $text) {
        $lines = $pdf->NbLines($widths[$i], $text);
        $height = 6 * $lines;
        if ($height > $maxHeight) $maxHeight = $height;
    }

    if ($pdf->GetY() + $maxHeight > 190) {
        $pdf->AddPage();
    }

    $x = $pdf->GetX();
    $y = $pdf->GetY();

    for ($i = 0; $i < count($cols); $i++) {
        $pdf->SetXY($x, $y);
        $align = ($i <= 4) ? 'L' : 'L';
        $pdf->MultiCell($widths[$i], 5, $cols[$i], 1, $align);
        $x += $widths[$i];
    }

    $pdf->SetY($y + $maxHeight);
    $no++;
}

$pdf->Output('I', 'Jadwal_Tanding_Silat_Dumai.pdf');
