<?php
session_start();
if (!isset($_SESSION['pwd'])) {
    header('location:login.php');
}
include('../../includes/connection.php');

$id_partai = mysqli_real_escape_string($koneksi, $_GET["id_partai"]);

$sql = "DELETE FROM jadwal_tanding WHERE id_partai='$id_partai'";

if (mysqli_query($koneksi, $sql)) {
?>
    <script type="text/javascript">
        document.location = '../../?page=tambah_jadwal';
    </script>
<?php
} else {
?>
    <script type="text/javascript">
        document.location = '../../?page=tambah_jadwal';
    </script>
<?php
    die('Unable to delete record: ' . mysqli_error($koneksi));
}
?>