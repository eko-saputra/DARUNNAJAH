<?php
session_start();
  if(!isset($_SESSION['pwd']))
  {
    header('location:login.php');
  }
include('includes/connection.php');

$ID_peserta = mysqli_real_escape_string($koneksi, $_GET["ID_peserta"]);


$sql = "UPDATE peserta SET status='PAID' WHERE ID_peserta='$ID_peserta'";

if(mysqli_query($koneksi,$sql))
{
	?>
		<script type="text/javascript">
			alert('Data Approved.');
			document.location='index.php';
		</script>
	<?php
}
else
{
	?>
		<script type="text/javascript">
			alert('Approve Failed!');
			document.location='index.php';
		</script>
	<?php
	die('Unable to update record: ' .mysqli_error($koneksi));
}
?>