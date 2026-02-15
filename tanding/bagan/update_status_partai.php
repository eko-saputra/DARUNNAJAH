<?php
include "../../backend/includes/connection.php";

// Aktifkan error reporting sementara untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ambil id_partai dari POST
if (!isset($_POST['id_partai']) || empty($_POST['id_partai'])) {
    http_response_code(400); // Bad Request
    echo "id_partai tidak dikirim!";
    exit;
}

$id_partai = (int)$_POST['id_partai'];

// Update status partai menjadi 'selesai'
$sql_update = "UPDATE jadwal_tanding SET status_babak = 'FINAL/BYE' WHERE id_partai = $id_partai";

if (mysqli_query($koneksi, $sql_update)) {
    echo "Status partai ID $id_partai berhasil diperbarui menjadi 'selesai'.";
} else {
    http_response_code(500); // Internal Server Error
    echo "Error update: " . mysqli_error($koneksi);
}
