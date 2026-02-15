<?php
session_start();
  if(!isset($_SESSION['pwd']))
  {
    header('location:login.php');
  }
include('includes/connection.php');

$id_nilaiatlet = mysqli_real_escape_string($koneksi, $_GET["id_nilaiatlet"]);

//hapus data nilai atlet
$sql = "DELETE FROM nilai_atlet WHERE id_nilaiatlet='$id_nilaiatlet'";

if(mysqli_query($koneksi,$sql))
{
	?>
		<script type="text/javascript">
			alert('Data Berhasil Dihapus');
			document.location='mvp.php';
		</script>
	<?php
}
else
{
	?>
		<script type="text/javascript">
			alert('Data Gagal Dihapus!');
			document.location='mvp.php';
		</script>
	<?php
	die('Unable to delete record: ' .mysqli_error($koneksi));
}
?>