<?php
// Mulai session
session_start();

// Periksa jika request adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include koneksi database TERLEBIH DAHULU
    include '../../includes/connection.php';

    // Ambil data dari form
    $user = mysqli_real_escape_string($koneksi, $_POST["username"] ?? '');
    $pwd = md5($_POST['password'] ?? '');

    // Debug: Lihat apa yang diterima
    // file_put_contents('debug.txt', "User: $user, Hash: $pwd\n", FILE_APPEND);

    // Query untuk memeriksa user
    $sql = "SELECT * FROM admin WHERE username='$user' AND password='$pwd'";
    $result = mysqli_query($koneksi, $sql);

    // Cek error query
    if (!$result) {
        echo "error|" . mysqli_error($koneksi);
        exit();
    }

    // Hitung jumlah baris yang ditemukan
    $num = mysqli_num_rows($result);

    if ($num == 1) {
        // Login berhasil
        $_SESSION['username'] = $user;
        $_SESSION['logged_in'] = true;
        $_SESSION['last_activity'] = time();

        // Kirim response 'success' untuk AJAX
        echo "success";
        exit();
    } else {
        // Login gagal
        echo "error|User not found or password incorrect";
        exit();
    }
} else {
    // Jika bukan POST request, kirim error
    echo "error|Invalid request method";
    exit();
}
