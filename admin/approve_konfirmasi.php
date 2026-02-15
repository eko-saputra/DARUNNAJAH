<?php
session_start();
  if(!isset($_SESSION['pwd']))
  {
    header('location:login.php');
  }

include('includes/connection.php');

//AMBIL VARIABEL
$ID_konfirmasi = mysqli_real_escape_string($koneksi, $_POST["ID_konfirmasi"]);

$sql = "UPDATE konfirmasi SET status='CLOSED' WHERE ID_konfirmasi='$ID_konfirmasi'";

if(mysqli_query($koneksi,$sql))
{
	?>
		<script type="text/javascript">
			alert('Berhasil disetujui.');
			document.location='admin_konfirmasi.php';
		</script>
	<?php
}
else
{
	?>
		<script type="text/javascript">
			alert('Gagal disetujui');
			document.location='admin_konfirmasi.php';
		</script>
	<?php
	die('Unable to update record: ' .mysqli_error($koneksi));
}
?>