<?php
include 'includes/connection.php';

if(isset($_GET['delete_id'])) {
    $id_to_delete = $_GET['delete_id'];
    
    $get_name_sql = "SELECT nm_juri FROM wasit_juri WHERE id_juri = '$id_to_delete'";
    $name_result = mysqli_query($koneksi, $get_name_sql);
    $juri_data = mysqli_fetch_assoc($name_result);
    $juri_name = $juri_data['nm_juri'];
    
    $delete_sql = "DELETE FROM wasit_juri WHERE id_juri = '$id_to_delete'";
    $delete_result = mysqli_query($koneksi, $delete_sql);
    
    if($delete_result) {
        $status = "success";
        $message = "Berhasil menghapus juri $juri_name";
    } else {
        $status = "error";
        $message = "Gagal menghapus juri!";
    }
    
    header("Location: juri.php?status=$status&message=" . urlencode($message));
    exit();
}

header("Location: juri.php");
exit();
?>