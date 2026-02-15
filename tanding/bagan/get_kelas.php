<?php
include "../../backend/includes/connection.php";

header('Content-Type: application/json');

// Cek koneksi
if (!$koneksi) {
    echo json_encode(['success' => false, 'error' => 'Koneksi database gagal']);
    exit;
}

$query = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM jadwal_tanding ORDER BY id_partai ASC");
if (!$query) {
    echo json_encode(['success' => false, 'error' => mysqli_error($koneksi)]);
    exit;
}

$kelasList = [];

while ($row = mysqli_fetch_assoc($query)) {
    $kelasList[] = $row['kelas'];
}

echo json_encode([
    'success' => true,
    'kelas' => $kelasList
]);
