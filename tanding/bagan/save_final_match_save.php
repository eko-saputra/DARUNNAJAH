<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
include "../../backend/includes/connection.php";

// Ambil data POST
$kelas           = $_POST['kelas'] ?? '';
$bagan           = $_POST['bagan'] ?? '';
$nm_merah        = $_POST['nm_merah'] ?? '';
$kontingen_merah = $_POST['kontingen_merah'] ?? '';
$nm_biru         = $_POST['nm_biru'] ?? '';
$kontingen_biru  = $_POST['kontingen_biru'] ?? '';
$gelanggang      = 'A';

if (!$kelas || !$bagan || !$nm_merah || !$nm_biru) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
    exit;
}

// Hapus data lama jika ada
$delete = mysqli_query($koneksi, "DELETE FROM jadwal_tanding_final 
                                  WHERE kelas='$kelas' AND bagan='$bagan' AND babak='FINAL'");

// Ambil ID terakhir
$result = mysqli_query($koneksi, "SELECT MAX(id_partai) AS last_id FROM jadwal_tanding_final");
$row = mysqli_fetch_assoc($result);
$newId = $row['last_id'] ? $row['last_id'] + 1 : 1;

// Insert data baru
$insert = mysqli_query($koneksi, "INSERT INTO jadwal_tanding_final (
    id_partai, tgl, kelas, gelanggang, partai,
    nm_biru, kontingen_biru, nm_merah, kontingen_merah,
    status, skor_biru, skor_merah, pemenang,
    babak, id_bagan, bagan, medali, aktif, grup
) VALUES (
    '$newId', CURDATE(), '$kelas', '$gelanggang', '$newId',
    '$nm_biru', '$kontingen_biru', '$nm_merah', '$kontingen_merah',
    '-', NULL, NULL, '-', 'FINAL', 1, '$bagan', '0', '1', NULL
)");

if ($insert) {
    echo json_encode(['status' => 'inserted', 'message' => 'Final berhasil disimpan.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data final.']);
}
