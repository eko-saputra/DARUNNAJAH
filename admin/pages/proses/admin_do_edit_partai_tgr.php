<?php
// Start session jika diperlukan
session_start();

// Include koneksi database
include('../../includes/connection.php');

// Cek apakah request POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("<script type='text/javascript'>
            alert('Akses tidak valid!');
            document.location = '../../?page=tambah_jadwal_tgr';
          </script>");
}

// AMBIL VARIABEL dengan validasi
$id_partai = isset($_POST["id_partai"]) ? mysqli_real_escape_string($koneksi, $_POST["id_partai"]) : '';
$kategori = isset($_POST["kategori"]) ? mysqli_real_escape_string($koneksi, $_POST["kategori"]) : '';
$golongan = isset($_POST["golongan"]) ? mysqli_real_escape_string($koneksi, $_POST["golongan"]) : '';
$nm_merah = isset($_POST["nm_merah"]) ? mysqli_real_escape_string($koneksi, $_POST["nm_merah"]) : '';
$kontingen_merah = isset($_POST["kontingen_merah"]) ? mysqli_real_escape_string($koneksi, $_POST["kontingen_merah"]) : '';
$nm_biru = isset($_POST["nm_biru"]) ? mysqli_real_escape_string($koneksi, $_POST["nm_biru"]) : '';
$kontingen_biru = isset($_POST["kontingen_biru"]) ? mysqli_real_escape_string($koneksi, $_POST["kontingen_biru"]) : '';
$status = isset($_POST["status"]) ? mysqli_real_escape_string($koneksi, $_POST["status"]) : 'Belum Dimulai';
$pemenang = isset($_POST["pemenang"]) ? mysqli_real_escape_string($koneksi, $_POST["pemenang"]) : '';
$babak = isset($_POST["babak"]) ? mysqli_real_escape_string($koneksi, $_POST["babak"]) : 'Penyisihan';
$aktif = isset($_POST["aktif"]) ? mysqli_real_escape_string($koneksi, $_POST["aktif"]) : '0';

// Validasi data wajib
if (empty($id_partai)) {
    echo "<script type='text/javascript'>
            alert('ID Partai tidak boleh kosong!');
            window.history.back();
          </script>";
    exit();
}

// UPDATE DATA PESERTA
$sql = "UPDATE jadwal_tgr SET 
        kategori = '$kategori',
        golongan = '$golongan',
        nm_merah = '$nm_merah', 
        kontingen_merah = '$kontingen_merah',
        nm_biru = '$nm_biru', 
        kontingen_biru = '$kontingen_biru', 
        status = '$status',
        pemenang = '$pemenang', 
        babak = '$babak', 
        aktif = '$aktif'
        WHERE id_partai = '$id_partai'";

// Eksekusi query
if (mysqli_query($koneksi, $sql)) {
    // Cek apakah ada data yang terupdate
    if (mysqli_affected_rows($koneksi) > 0) {
        echo "<script type='text/javascript'>
                alert('Data partai berhasil diperbarui.');
                document.location = '../../?page=tambah_jadwal_tgr';
              </script>";
    } else {
        echo "<script type='text/javascript'>
                alert('Tidak ada perubahan data atau ID partai tidak ditemukan.');
                document.location = '../../?page=tambah_jadwal_tgr';
              </script>";
    }
} else {
    // Log error untuk debugging (hapus di production)
    error_log("MySQL Error: " . mysqli_error($koneksi));

    echo "<script type='text/javascript'>
            alert('Data gagal diupdate. Error: " . addslashes(mysqli_error($koneksi)) . "');
            window.history.back();
          </script>";
}

// Tutup koneksi
mysqli_close($koneksi);
