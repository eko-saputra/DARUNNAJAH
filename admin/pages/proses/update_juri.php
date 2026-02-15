<?php
include '../../includes/connection.php';

header('Content-Type: application/json');

if (isset($_POST['id_juri']) && isset($_POST['nm_juri']) && isset($_POST['pass_juri'])) {
    $id_to_update = $_POST['id_juri'];
    $new_name = $_POST['nm_juri'];
    $new_password = md5(mysqli_real_escape_string($koneksi, $_POST['pass_juri'])); 

    $update_sql = "UPDATE wasit_juri SET nm_juri='$new_name', pass_juri='$new_password' WHERE id_juri='$id_to_update'";
    $update_result = mysqli_query($koneksi, $update_sql);

    if ($update_result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($koneksi)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
}
