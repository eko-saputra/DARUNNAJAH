<?php
session_start();
  if(!isset($_SESSION['pwd']))
  {
    header('location:login.php');
  }
include('includes/connection.php');

$clearTableJadwal = mysqli_query($koneksi,"TRUNCATE TABLE jadwal_tanding");
$clearNilaiTanding = mysqli_query($koneksi,"TRUNCATE TABLE nilai_tanding");

?>

<script type="text/javascript">
	alert('Data Terhapus.');
	document.location='jadwal_partai_tanding.php';
</script>