<?php
// Mulai session
session_start();

// Periksa jika request adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $user = mysqli_real_escape_string($koneksi, $_POST["username"]);
    $pwd = md5($_POST['password']);
    
    // Include koneksi database
    include 'includes/connection.php';
    
    // Query untuk memeriksa user
    $sql = "SELECT * FROM admin WHERE username='$user' AND password='$pwd'";
    $result = mysqli_query($koneksi, $sql);
    
    // Hitung jumlah baris yang ditemukan
    $num = mysqli_num_rows($result);
    
    if ($num == 1) {
        // Login berhasil
        $_SESSION['username'] = $user;
        $_SESSION['logged_in'] = true;
        
        // Set session timeout (opsional)
        $_SESSION['last_activity'] = time();
        
        // Kirim response 'success' untuk AJAX
        echo "success";
        exit();
    } else {
        // Login gagal
        echo "error";
        exit();
    }
} else {
    // Jika bukan POST request, kirim error
    echo "error";
    exit();
}
?>