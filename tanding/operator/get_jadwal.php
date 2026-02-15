<?php
include "../../backend/includes/connection.php";

$status_filter = isset($_GET['status']) ? ($_GET['status'] == '' ? '-' : $_GET['status']) : '';
$kelas_filter = isset($_GET['kelas']) ? $_GET['kelas'] : '';

// Bangun query
$sqljadwal = "SELECT * FROM jadwal_tanding WHERE status='$status_filter' AND nm_merah != '-' AND nm_biru != '-' AND babak='SEMIFINAL'";

if ($kelas_filter != '') {
    $sqljadwal .= " AND kelas='$kelas_filter'";
}

$sqljadwal .= " ORDER BY id_partai ASC";

$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);

$rows = [];
while ($jadwal = mysqli_fetch_assoc($jadwal_tanding)) {
    $rows[] = $jadwal;
}

// kembalikan data JSON
header('Content-Type: application/json');
echo json_encode($rows);
