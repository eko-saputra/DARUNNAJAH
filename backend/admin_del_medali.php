<?php
session_start();
  if(!isset($_SESSION['pwd']))
  {
    header('location:login.php');
  }
include('includes/connection.php');

$id_medali = mysqli_real_escape_string($koneksi, $_GET["id_medali"]);
$id_partai = mysqli_real_escape_string($koneksi, $_GET["id_partai_FK"]);

$sql = "DELETE FROM medali WHERE id_medali='$id_medali'";


if(mysqli_query($koneksi,$sql))
{
	$updateJadwalmedali = mysqli_query($koneksi, "UPDATE jadwal_tanding SET medali='0' WHERE id_partai='$id_partai'");
	?>
		<script type="text/javascript">
			alert('Data Berhasil Dihapus');
			document.location='admin_medali.php';
		</script>
	<?php
}
else
{
	?>
		<script type="text/javascript">
			alert('Data Gagal Dihapus!');
			document.location='admin_medali.php';
		</script>
	<?php
	die('Unable to delete record: ' .mysqli_error($koneksi));
}
?>