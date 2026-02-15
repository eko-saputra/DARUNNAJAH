<?php
session_start();
  if(!isset($_SESSION['pwd']))
  {
    header('location:login.php');
  }
include('includes/connection.php');

$clearTableJadwal = mysqli_query($koneksi,"TRUNCATE TABLE jadwal_tgr");
$clearNilaiTunggal = mysqli_query($koneksi,"TRUNCATE TABLE nilai_tunggal");
$clearNilaiGanda = mysqli_query($koneksi,"TRUNCATE TABLE nilai_ganda");
$clearNilaiRegu = mysqli_query($koneksi,"TRUNCATE TABLE nilai_regu");

?>

<script type="text/javascript">
	alert('Data Terhapus.');
	document.location='jadwal_partai_tgr.php';
</script>