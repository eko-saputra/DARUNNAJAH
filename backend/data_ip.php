<?php
include 'includes/connection.php';

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $query = "DELETE FROM ip_server WHERE id_ip = $id";

    if (mysqli_query($koneksi, $query)) {
        header("Location: ipserver.php?status=success&message=Data berhasil dihapus");
    } else {
        header("Location: ipserver.php?status=error&message=Gagal menghapus data");
    }
} else {
    header("Location: ipserver.php");
}
?>
