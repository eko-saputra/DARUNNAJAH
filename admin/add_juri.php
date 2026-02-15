<?php
include 'includes/connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nm_juri']) && isset($_POST['pass_juri'])) {
        $nm_juri = mysqli_real_escape_string($koneksi, $_POST['nm_juri']);
        $pass_juri = md5(mysqli_real_escape_string($koneksi, $_POST['pass_juri'])); 
        
        $check_query = "SELECT COUNT(*) as count FROM wasit_juri WHERE nm_juri = '$nm_juri'";
        $check_result = mysqli_query($koneksi, $check_query);
        $row = mysqli_fetch_assoc($check_result);
        
        if ($row['count'] > 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Nama juri sudah digunakan!'
            ]);
            exit;
        }
        
        $insert_query = "INSERT INTO wasit_juri (nm_juri, pass_juri) VALUES ('$nm_juri', '$pass_juri')";
        $result = mysqli_query($koneksi, $insert_query);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Berhasil menambah juri ' . $nm_juri
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . mysqli_error($koneksi)
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Data juri tidak lengkap!'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed!'
    ]);
}
?>