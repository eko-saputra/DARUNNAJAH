<?php
include('../../includes/connection.php');

mysqli_query($koneksi, "TRUNCATE TABLE jadwal_tanding");
mysqli_query($koneksi, "TRUNCATE TABLE nilai_tanding");
mysqli_query($koneksi, "TRUNCATE TABLE nilai_tanding_log");
mysqli_query($koneksi, "TRUNCATE TABLE nilai_dewan");

?>

<script type="text/javascript">
    document.location = '../../?page=tambah_jadwal_tanding';
</script>