<?php
session_start();
if (!isset($_SESSION['pwd'])) {
    header('location:login.php');
}
include('../../includes/connection.php');

$id_partai = mysqli_real_escape_string($koneksi, $_GET["id_partai"]);

$sql = "DELETE FROM jadwal_tanding WHERE id_partai='$id_partai'";

$sql1 = "DELETE FROM nilai_tanding WHERE id_jadwal='$id_partai'";

$sql2 = "DELETE FROM nilai_tanding_log WHERE id_jadwal='$id_partai'";

$sql3 = "DELETE FROM nilai_dewan WHERE id_jadwal='$id_partai'";

if (mysqli_query($koneksi, $sql) && mysqli_query($koneksi, $sql1) && mysqli_query($koneksi, $sql2) && mysqli_query($koneksi, $sql3)) {
?>
    <script type="text/javascript">
        document.location = '../../?page=tambah_jadwal_tanding';
    </script>
<?php
} else {
?>
    <script type="text/javascript">
        document.location = '../../?page=tambah_jadwal_tanding';
    </script>
<?php
    die('Unable to delete record: ' . mysqli_error($koneksi));
}
?>