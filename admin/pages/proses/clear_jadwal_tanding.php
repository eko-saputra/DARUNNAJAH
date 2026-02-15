<?php
include('../../includes/connection.php');

$clearTableJadwal = mysqli_query($koneksi, "TRUNCATE TABLE jadwal_tanding");
$clearNilaiTanding = mysqli_query($koneksi, "TRUNCATE TABLE nilai_tanding");

?>

<script type="text/javascript">
    document.location = '../../?page=tambah_jadwal';
</script>