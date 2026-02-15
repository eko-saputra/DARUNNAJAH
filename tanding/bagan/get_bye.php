<?php
include "../../backend/includes/connection.php";

header('Content-Type: application/json');

// Ambil semua data BYE
$sql = "SELECT * FROM jadwal_tanding_bye";
$res = mysqli_query($koneksi, $sql);

$bye = [];
while ($row = mysqli_fetch_assoc($res)) {
    $bye[] = $row;
}

echo json_encode([
    "status" => "ok",
    "data" => $bye
]);
