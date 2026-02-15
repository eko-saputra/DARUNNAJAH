<?php
session_start();
  if(!isset($_SESSION['pwd']))
  {
    header('location:login.php');
  }

include('includes/connection.php');

//AMBIL VARIABEL
	$ID_peserta = mysqli_real_escape_string($koneksi, $_POST["ID_peserta"]);
	$nama = mysqli_real_escape_string($koneksi, $_POST["nama"]);
	$jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST["jenis_kelamin"]);
	$golongan = mysqli_real_escape_string($koneksi, $_POST["golongan"]);
	$kelas_tanding = mysqli_real_escape_string($koneksi, $_POST["kelas_tanding"]);
	$kontingen = mysqli_real_escape_string($koneksi, strtoupper($_POST["kontingen"]));



$sql = "UPDATE peserta SET nm_lengkap='$nama', jenis_kelamin='$jenis_kelamin',
									golongan='$golongan', kelas_tanding_FK='$kelas_tanding', 
									kontingen='$kontingen'
									WHERE ID_peserta='$ID_peserta'";

if(mysqli_query($koneksi,$sql))
{
	?>
		<script type="text/javascript">
			alert('Data berhasil diubah.');
			document.location='index.php';
		</script>
	<?php
}
else
{
	?>
		<script type="text/javascript">
			alert('Data gagal diubah');
			document.location='index.php';
		</script>
	<?php
	die('Unable to update record: ' .mysqli_error($koneksi));
}
?>