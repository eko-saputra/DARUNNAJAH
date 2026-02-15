<?php
session_start();
  if(!isset($_SESSION['pwd']))
  {
    header('location:login.php');
  }
include('includes/connection.php');

$sql = "TRUNCATE TABLE undian";

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