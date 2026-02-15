<?php
session_start();
  if(!isset($_SESSION['pwd']))
  {
    header('location:login.php');
  }
include('includes/connection.php');

$id_undiantgr = mysqli_real_escape_string($koneksi,$_GET["id_undiantgr"]);

$sql = "DELETE FROM undian_tgr WHERE id_undiantgr='$id_undiantgr'";

if(mysqli_query($koneksi,$sql))
{
	?>
		<script type="text/javascript">
			alert('Data Berhasil Dihapus');
			document.location='admin_undian_tgr.php';
		</script>
	<?php
}
else
{
	?>
		<script type="text/javascript">
			alert('Data Gagal Dihapus!');
			document.location='admin_undian_tgr.php';
		</script>
	<?php
	die('Unable to delete record: ' .mysqli_error($koneksi));
}
?>