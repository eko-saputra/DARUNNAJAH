<?php
session_start();
if (!isset($_SESSION['pwd'])) {
    header('location:login.php');
    exit;
}

require('fpdf/fpdf.php');
include('includes/connection.php');

// Ambil data dari form
$kategori = mysqli_real_escape_string($koneksi, $_POST["kategori"]);
$filter_golongan = mysqli_real_escape_string($koneksi, $_POST["golongan"] ?? '');
$tgl = $_POST["tgl"] ?? date("Y-m-d");
$tanggal = date("j F Y", strtotime($tgl));

// Class PDF
class PDF extends FPDF {
    function NbLines($w, $txt) {
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0) $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n") $nb--;
        $sep = -1;
        $i = 0; $j = 0; $l = 0; $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++; $sep = -1; $j = $i; $l = 0; $nl++;
                continue;
            }
            if ($c == ' ') $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j) $i++;
                } else $i = $sep + 1;
                $sep = -1; $j = $i; $l = 0; $nl++;
            } else {
                $i++;
            }
        }
        return $nl;
    }

    function Header() {
        global $tanggal, $kategori, $filter_golongan;

        $this->Image('dumai.png', 10, 5, 22, 25);
        $this->Image('ipsi.png', 265, 5, 22, 25);

        $this->SetY(10);
        $this->SetFont('Arial', 'B', 20);
        $this->Cell(0, 7, 'OPEN CHAMPIONSHIP PENCAK SILAT KOTA DUMAI', 0, 1, 'C');
        $this->Ln(7);

        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 7, 'JADWAL SENI ' . strtoupper($kategori), 0, 1, 'C');
        $this->Cell(0, 7, 'TAHUN 2025', 0, 1, 'C');
        $this->Ln(2);

        $this->SetFont('Arial', '', 11);
        $this->Cell(0, 7, 'Tanggal: ' . $tanggal, 0, 1, 'C');
        $this->Cell(0, 7, 'Golongan: ' . strtoupper($filter_golongan), 0, 1, 'C');
        $this->Ln(5);

        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(255, 255, 0);
        $this->SetTextColor(0);
        $this->Cell(10, 8, 'No', 1, 0, 'C', true);
        $this->Cell(20, 8, 'Partai', 1, 0, 'C', true);
        $this->Cell(25, 8, 'Babak', 1, 0, 'C', true);
        $this->Cell(25, 8, 'Golongan', 1, 0, 'C', true);

        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->Cell(50, 8, 'Sudut Merah', 1, 0, 'C', true);
        $this->Cell(50, 8, 'Kontingen Merah', 1, 0, 'C', true);

        $this->SetFillColor(0, 0, 255);
        $this->Cell(50, 8, 'Sudut Biru', 1, 0, 'C', true);
        $this->Cell(50, 8, 'Kontingen Biru', 1, 1, 'C', true);
    }
}

// Query data sesuai filter kategori dan golongan
$sql = "SELECT * FROM jadwal_tgr WHERE kategori = '$kategori'";

// Cek jika golongan dipilih
if (!empty($filter_golongan)) {
    $sql .= " AND golongan LIKE '%$filter_golongan%'";
}

$sql .= " ORDER BY id_partai ASC";

$jadwal_tanding = mysqli_query($koneksi, $sql);
if (!$jadwal_tanding) {
    die("Query Gagal: " . mysqli_error($koneksi) . "\nSQL: " . $sql);
}

// PDF
$pdf = new PDF('L', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', '', 8);

$no = 1;
while ($jadwal = mysqli_fetch_assoc($jadwal_tanding)) {
    $cols = [
        $no,
        $jadwal['partai'],
        $jadwal['babak'],
        $jadwal['golongan'],
        $jadwal['nm_merah'],
        $jadwal['kontingen_merah'],
        $jadwal['nm_biru'],
        $jadwal['kontingen_biru'],
    ];

    $widths = [10, 20, 25, 25, 50, 50, 50, 50];

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
        $align = ($i <= 3) ? 'C' : 'L';
        $pdf->MultiCell($widths[$i], 5, $cols[$i], 1, $align);
        $x += $widths[$i];
    }

    $pdf->SetY($y + $maxHeight);
    $no++;
}

$pdf->Output('I', 'Jadwal_Seni_Silat_Kota_Dumai.pdf');
?>
