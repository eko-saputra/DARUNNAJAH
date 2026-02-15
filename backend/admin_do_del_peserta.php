<?php
session_start();
  if(!isset($_SESSION['pwd']))
  {
    header('location:login.php');
  }
include('includes/connection.php');

$ID_peserta = mysqli_real_escape_string($koneksi, $_GET["ID_peserta"]);

$sql = "DELETE FROM peserta WHERE ID_peserta='$ID_peserta'";

if(mysqli_query($koneksi,$sql))
{
	?>
		<script type="text/javascript">
			alert('Data Berhasil Dihapus');
			document.location='index.php';
		</script>
	<?php
}
else
{
	?>
		<script type="text/javascript">
			alert('Data Gagal Dihapus!');
			document.location='index.php';
		</script>
	<?php
	die('Unable to delete record: ' .mysqli_error($koneksi));
}
?>