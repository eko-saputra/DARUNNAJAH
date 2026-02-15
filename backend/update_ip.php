<?php
include 'includes/connection.php';

header('Content-Type: application/json');

if (isset($_POST['id_juri']) && isset($_POST['ip'])) {
    $id_to_update = $_POST['id_juri'];
    $new_ip = $_POST['ip'];
    
    // Sesuaikan nama tabel dan kolom dengan struktur tabel ip_server
    $update_sql = "UPDATE ip_server SET ip='$new_ip' WHERE id_ip='$id_to_update'";
    $update_result = mysqli_query($koneksi, $update_sql);
    
    if ($update_result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($koneksi)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
}
?>
