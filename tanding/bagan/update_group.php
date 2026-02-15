<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../../backend/includes/connection.php";

if (isset($_POST['id_partai'], $_POST['group'])) {
    $id_partai = intval($_POST['id_partai']);
    $group = mysqli_real_escape_string($koneksi, $_POST['group']);

    // pakai backtick kalau nama kolomnya 'group'
    $sql = "UPDATE jadwal_tanding SET `grup` = '$group' WHERE id_partai = $id_partai";

    if (mysqli_query($koneksi, $sql)) {
        echo "OK";
    } else {
        http_response_code(500);
        echo "Error SQL: " . mysqli_error($koneksi);
    }
} else {
    http_response_code(400);
    echo "Invalid parameters";
}
