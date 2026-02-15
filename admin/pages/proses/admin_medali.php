<?php
error_reporting(E_ALL);
ini_set("", 1);
session_start();
// if (!isset($_SESSION['pwd'])) {
//     header('location:login.php');
// }
include('../../includes/connection.php');


//AMBIL VARIABEL
$nama = mysqli_real_escape_string($koneksi, $_POST["nama"]);
$kontingen = mysqli_real_escape_string($koneksi, $_POST["kontingen"]);
$kelas = mysqli_real_escape_string($koneksi, $_POST["kelas"]);
$medali = mysqli_real_escape_string($koneksi, $_POST["medali"]);
$idjadwal = mysqli_real_escape_string($koneksi, $_POST["idjadwal"]);
echo $medali;
if ($medali == 'Perunggu') {
    mysqli_query($koneksi, "INSERT INTO medali(nama, kontingen, kelas, medali, id_partai_FK) 
		VALUES('$nama', '$kontingen', '$kelas', '$medali', '$idjadwal')");
    mysqli_query($koneksi, "UPDATE jadwal_tanding SET medali='1' WHERE id_partai='$idjadwal'");
}
if ($medali == 'Perak') {
    mysqli_query($koneksi, "INSERT INTO medali(nama, kontingen, kelas, medali, id_partai_FK) 
		VALUES('$nama', '$kontingen', '$kelas', '$medali', '$idjadwal')");
    mysqli_query($koneksi, "UPDATE jadwal_tanding_final SET medali='2' WHERE id_partai='$idjadwal'");
}
if ($medali == "Emas") {
    mysqli_query($koneksi, "INSERT INTO medali(nama, kontingen, kelas, medali, id_partai_FK) 
		VALUES('$nama', '$kontingen', '$kelas', '$medali', '$idjadwal')");
    mysqli_query($koneksi, "UPDATE jadwal_tanding_final SET medali=3 WHERE id_partai=14");
}
?>
<script type="text/javascript">
    document.location = '../../?page=perolehan_medali';
</script>
<?php

?>