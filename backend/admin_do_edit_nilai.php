<?php
session_start();
  if(!isset($_SESSION['pwd']))
  {
    header('location:login.php');
  }

include('includes/connection.php');

//AMBIL VARIABEL
	$id_nilaiatlet = mysqli_real_escape_string($koneksi, $_POST["id_nilaiatlet"]);
	$nama = mysqli_real_escape_string($koneksi, $_POST["nama"]);
	$kontingen = mysqli_real_escape_string($koneksi, $_POST["kontingen"]);
	$hukuman = mysqli_real_escape_string($koneksi, $_POST["hukuman"]);
	$nilai = mysqli_real_escape_string($koneksi, $_POST["nilai"]);
	

//UPDATE DATA PESERTA
	if($id_nilaiatlet == '' OR $nama == '' OR $kontingen == '')
	{
		?>
		<script type="text/javascript">
			alert('Nama Pesilat dan Kontingen harus diisi semua!');
			document.location='mvp.php';
		</script>
		<?php
		exit;
	}


$sql = "UPDATE nilai_atlet SET nama='$nama', kontingen='$kontingen', hukuman='$hukuman',
								nilai='$nilai' WHERE id_nilaiatlet='$id_nilaiatlet'";

if(mysqli_query($koneksi,$sql))
{
	?>
		<script type="text/javascript">
			alert('Data berhasil diubah.');
			document.location='mvp.php';
		</script>
	<?php
}
else
{
	?>
		<script type="text/javascript">
			alert('Data gagal diubah');
			document.location='mvp.php';
		</script>
	<?php
	die('Unable to update record: ' .mysqli_error($koneksi));
}
?>