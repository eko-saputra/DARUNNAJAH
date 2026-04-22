<?php
include('../../includes/connection.php');

mysqli_query($koneksi, "TRUNCATE TABLE jadwal_tgr");
mysqli_query($koneksi, "TRUNCATE TABLE nilai_seni_tunggal");
mysqli_query($koneksi, "TRUNCATE TABLE nilai_dewan_seni_tunggal");

?>

<script type="text/javascript">
    document.location = '../../?page=tambah_jadwal_tgr';
</script>