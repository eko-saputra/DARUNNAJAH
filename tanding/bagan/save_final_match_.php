<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
include "../../backend/includes/connection.php";

// Ambil data POST
$kelas           = $_POST['kelas'] ?? '';
$bagan           = $_POST['bagan'] ?? '';
$id_bagan        = $_POST['id_bagan'] ?? 1; // default 1 jika tidak dikirim
$nm_merah        = $_POST['nm_merah'] ?? '';
$kontingen_merah = $_POST['kontingen_merah'] ?? '';
$nm_biru         = $_POST['nm_biru'] ?? '';
$kontingen_biru  = $_POST['kontingen_biru'] ?? '';
$gelanggang      = 'A';

if (!$kelas || !$bagan || !$nm_merah || !$nm_biru || !$id_bagan) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
    exit;
}

// Cek apakah data FINAL dengan kelas, bagan, id_bagan sudah ada
$checkQuery = "SELECT id_partai FROM jadwal_tanding_final 
               WHERE kelas='$kelas' AND bagan='$bagan' AND id_bagan='$id_bagan' 
               AND babak='FINAL' LIMIT 1";
$checkResult = mysqli_query($koneksi, $checkQuery);

if (mysqli_num_rows($checkResult) > 0) {
    // Data sudah ada → UPDATE
    $row = mysqli_fetch_assoc($checkResult);
    $id_partai = $row['id_partai'];

    $update = mysqli_query($koneksi, "UPDATE jadwal_tanding_final SET 
        nm_biru='$nm_biru',
        kontingen_biru='$kontingen_biru',
        nm_merah='$nm_merah',
        kontingen_merah='$kontingen_merah',
        tgl=CURDATE()
        WHERE id_partai='$id_partai'
    ");

    if ($update) {
        echo json_encode(['status' => 'updated', 'message' => 'Data final berhasil diperbarui.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui data final.']);
    }
} else {
    // Data belum ada → INSERT
    $result = mysqli_query($koneksi, "SELECT MAX(id_partai) AS last_id FROM jadwal_tanding_final");
    $row = mysqli_fetch_assoc($result);
    $newId = $row['last_id'] ? $row['last_id'] + 1 : 1;

    $insert = mysqli_query($koneksi, "INSERT INTO jadwal_tanding_final (
        id_partai, tgl, kelas, gelanggang, partai,
        nm_biru, kontingen_biru, nm_merah, kontingen_merah,
        status, skor_biru, skor_merah, pemenang,
        babak, id_bagan, bagan, medali, aktif, grup
    ) VALUES (
        '$newId', CURDATE(), '$kelas', '$gelanggang', '$newId',
        '$nm_biru', '$kontingen_biru', '$nm_merah', '$kontingen_merah',
        '-', NULL, NULL, '-', 'FINAL', '$id_bagan', '$bagan', '0', '1', NULL
    )");

    if ($insert) {
        echo json_encode(['status' => 'inserted', 'message' => 'Final berhasil disimpan.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data final.']);
    }
}
