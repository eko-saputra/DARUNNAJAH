<?php
include("../../includes/connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $nm_lengkap = mysqli_real_escape_string($koneksi, $_POST['nm_lengkap']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $tpt_lahir = mysqli_real_escape_string($koneksi, $_POST['tpt_lahir']);
    $tgl_lahir = $_POST['tgl_lahir'];
    $tb = !empty($_POST['tb']) ? (int)$_POST['tb'] : 'NULL';
    $bb = !empty($_POST['bb']) ? (int)$_POST['bb'] : 'NULL';
    $kelas = !empty($_POST['kelas']) ? "'" . mysqli_real_escape_string($koneksi, $_POST['kelas']) . "'" : 'NULL';
    $asal_sekolah = mysqli_real_escape_string($koneksi, $_POST['asal_sekolah']);
    $kategori_tanding = mysqli_real_escape_string($koneksi, $_POST['kategori_tanding']);
    $golongan = mysqli_real_escape_string($koneksi, $_POST['golongan']);
    $kode_gr = mysqli_real_escape_string($koneksi, $_POST['kode_gr']);
    $kelas_tanding_FK = mysqli_real_escape_string($koneksi, $_POST['kelas_tanding_FK']);
    $kontingen = mysqli_real_escape_string($koneksi, $_POST['kontingen']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);

    // Default values untuk file
    $default_foto = "'default.jpg'";
    $default_ktp = "'pending_ktp.jpg'";
    $default_akta = "'pending_akta.jpg'";
    $default_ijazah = "'pending_ijazah.jpg'";

    // Query insert
    $query = "INSERT INTO peserta (
        nm_lengkap, jenis_kelamin, tpt_lahir, tgl_lahir, tb, bb, kelas, 
        asal_sekolah, kategori_tanding, golongan, kode_gr, kelas_tanding_FK, 
        kontingen, foto, ktp, akta_lahir, ijazah, status
    ) VALUES (
        '$nm_lengkap', '$jenis_kelamin', '$tpt_lahir', '$tgl_lahir', 
        $tb, $bb, $kelas, '$asal_sekolah', '$kategori_tanding', '$golongan', 
        '$kode_gr', '$kelas_tanding_FK', '$kontingen', 
        $default_foto, $default_ktp, $default_akta, $default_ijazah, '$status'
    )";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>
            alert('Data peserta berhasil disimpan!');
            window.location.href = '../index.php?page=peserta';
        </script>";
    } else {
        echo "<script>
            alert('Error: " . mysqli_error($koneksi) . "');
            window.history.back();
        </script>";
    }
}
