<?php
session_start();
  if(!isset($_SESSION['pwd']))
  {
    header('location:login.php');
    exit();
  }
  
$pw = md5($_POST['txtpassword']);

include('includes/connection.php');

$sql = "UPDATE admin SET password='$pw' WHERE userID='1'";

if(mysqli_query($koneksi,$sql))
{
	header('location:logout.php');
}
else
{
	?>
		<script type="text/javascript">
			alert('Password gagal diubah');
			document.location='index.php';
		</script>
	<?php
	die('Unable to update record: ' .mysqli_error($koneksi));
}
?>