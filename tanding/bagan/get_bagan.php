<?php
header('Content-Type: application/json');
include '../../backend/includes/connection.php'; // koneksi ke database

$kelas = $_GET['kelas'] ?? '';

// Ambil jadwal_tanding + jadwal_tanding_final
$sql = "SELECT * FROM jadwal_tanding WHERE kelas = ? 
        UNION ALL 
        SELECT * FROM jadwal_tanding_final WHERE kelas = ? 
        ORDER BY bagan, id_partai";

$stmt = $koneksi->prepare($sql);
$stmt->bind_param('ss', $kelas, $kelas);
$stmt->execute();
$result = $stmt->get_result();

$groups = [];

while ($row = $result->fetch_assoc()) {
    $huruf = $row['bagan'];
    if (!isset($groups[$huruf])) {
        $groups[$huruf] = [
            'huruf' => $huruf,
            'judul' => $row['babak'],
            'teams' => [],
            'results' => []
        ];
    }

    // Nama peserta, null jika mengandung "Pemenang"
    $nm_biru = stripos($row['nm_biru'], 'pemenang') !== false ? null : $row['nm_biru'] . ' (' . $row['kontingen_biru'] . ')';
    $nm_merah = stripos($row['nm_merah'], 'pemenang') !== false ? null : $row['nm_merah'] . ' (' . $row['kontingen_merah'] . ')';

    $groups[$huruf]['teams'][] = [$nm_biru, $nm_merah];
    $groups[$huruf]['results'][] = [[(float)($row['skor_biru'] ?? 0), (float)($row['skor_merah'] ?? 0)]];
}

echo json_encode(['groups' => array_values($groups)]);
