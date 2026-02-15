<?php
include 'includes/connection.php';

if(isset($_POST['ip'])) {
    $ip = $_POST['ip'];
    
    $query = "INSERT INTO ip_server (ip) VALUES ('$ip')";
    $result = mysqli_query($koneksi, $query);

    if($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($koneksi)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Data IP tidak ditemukan']);
}
