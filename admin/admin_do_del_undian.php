<?php
session_start();
  if(!isset($_SESSION['pwd']))
  {
    header('location:login.php');
  }
include('includes/connection.php');

$id_undian = mysqli_real_escape_string($koneksi,$_GET["id_undian"]);

$sql = "DELETE FROM undian WHERE id_undian='$id_undian'";

if(mysqli_query($koneksi,$sql))
{
	?>
		<script type="text/javascript">
			alert('Data Berhasil Dihapus');
			document.location='admin_undian_tanding.php';
		</script>
	<?php
}
else
{
	?>
		<script type="text/javascript">
			alert('Data Gagal Dihapus!');
			document.location='admin_undian_tanding.php';
		</script>
	<?php
	die('Unable to delete record: ' .mysqli_error($koneksi));
}
?>