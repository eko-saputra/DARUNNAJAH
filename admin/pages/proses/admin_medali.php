<?php
session_start();
include('../../includes/connection.php');

// AMBIL VARIABEL
$nama = mysqli_real_escape_string($koneksi, $_POST["nama"]);
$kontingen = mysqli_real_escape_string($koneksi, $_POST["kontingen"]);
$kelas = mysqli_real_escape_string($koneksi, $_POST["kelas"]);
$medali = mysqli_real_escape_string($koneksi, $_POST["medali"]);
$idjadwal = mysqli_real_escape_string($koneksi, $_POST["idjadwal"]);

// Untuk debugging (opsional, hapus setelah selesai)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Mulai transaksi
mysqli_begin_transaction($koneksi);

try {
  // Insert ke tabel medali
  $insert_query = "INSERT INTO medali(nama, kontingen, kelas, medali, id_partai_FK) 
                     VALUES('$nama', '$kontingen', '$kelas', '$medali', '$idjadwal')";

  if (!mysqli_query($koneksi, $insert_query)) {
    throw new Exception("Error insert: " . mysqli_error($koneksi));
  }

  // Update berdasarkan jenis medali
  if ($medali == 'Perunggu') {
    $update_query = "UPDATE jadwal_tanding SET medali='1' WHERE id_partai='$idjadwal'";
  } else if ($medali == 'Perak') {
    $update_query = "UPDATE jadwal_tanding SET medali='2' WHERE id_partai='$idjadwal'";
  } else if ($medali == "Emas") {
    // Perbaiki: gunakan $idjadwal, bukan nilai hardcoded 14
    $update_query = "UPDATE jadwal_tanding SET medali='3' WHERE id_partai='$idjadwal'";
  }

  if (isset($update_query)) {
    if (!mysqli_query($koneksi, $update_query)) {
      throw new Exception("Error update: " . mysqli_error($koneksi));
    }
  }

  // Commit transaksi
  mysqli_commit($koneksi);
} catch (Exception $e) {
  // Rollback jika ada error
  mysqli_rollback($koneksi);

  // Log error
  error_log($e->getMessage());

  // Tampilkan pesan error (untuk debugging)
  die("Error: " . $e->getMessage());
}

// Redirect
echo "<script type='text/javascript'>
        document.location = '../../?page=perolehan_medali';
      </script>";
