<?php
include('../../includes/connection.php');

$clearTableJadwal = mysqli_query($koneksi, "TRUNCATE TABLE jadwal_tgr");
$clearNilaiTanding = mysqli_query($koneksi, "TRUNCATE TABLE nilai_tgr");

?>

<script type="text/javascript">
    document.location = '../../?page=tambah_tgr';
</script>