<?php
session_start();
if (!isset($_SESSION['pwd'])) {
    header('location:login.php');
}
include('../../includes/connection.php');

$id_partai = mysqli_real_escape_string($koneksi, $_GET["id_partai"]);

$sql = "DELETE FROM jadwal_tgr WHERE id_partai='$id_partai'";

$sql1 = "DELETE FROM nilai_seni_tunggal WHERE id_jadwal='$id_partai'";

$sql2 = "DELETE FROM nilai_seni_regu WHERE id_jadwal='$id_partai'";

$sql3 = "DELETE FROM nilai_dewan_seni_regu WHERE id_jadwal='$id_partai'";

$sql4 = "DELETE FROM nilai_dewan_seni_tunggal WHERE id_jadwal='$id_partai'";

if (mysqli_query($koneksi, $sql) && mysqli_query($koneksi, $sql1) && mysqli_query($koneksi, $sql2) && mysqli_query($koneksi, $sql3) && mysqli_query($koneksi, $sql4)) {
?>
    <script type="text/javascript">
        document.location = '../../?page=tambah_jadwal_tgr';
    </script>
<?php
} else {
?>
    <script type="text/javascript">
        document.location = '../../?page=tambah_jadwal_tgr';
    </script>
<?php
    die('Unable to delete record: ' . mysqli_error($koneksi));
}
?>