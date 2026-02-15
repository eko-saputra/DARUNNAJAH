<?php
session_start();
  if(!isset($_SESSION['pwd']))
  {
    header('location:login.php');
  }

include('includes/connection.php');

//AMBIL VARIABEL
	$id_partai = mysqli_real_escape_string($koneksi, $_POST["id_partai"]);
	$tgl = mysqli_real_escape_string($koneksi, $_POST["tgl"]);
	$kelas = mysqli_real_escape_string($koneksi, $_POST["kelas"]);
	$gelanggang = mysqli_real_escape_string($koneksi, $_POST["gelanggang"]);
	$partai = mysqli_real_escape_string($koneksi, $_POST["partai"]);
	$nm_merah = mysqli_real_escape_string($koneksi, $_POST["nm_merah"]);
	$kontingen_merah = mysqli_real_escape_string($koneksi, $_POST["kontingen_merah"]);
	$nm_biru = mysqli_real_escape_string($koneksi, $_POST["nm_biru"]);
	$kontingen_biru = mysqli_real_escape_string($koneksi, $_POST["kontingen_biru"]);
	$status = mysqli_real_escape_string($koneksi, $_POST["status"]);
	$pemenang = mysqli_real_escape_string($koneksi, $_POST["pemenang"]);
	$babak = mysqli_real_escape_string($koneksi, $_POST["babak"]);
	$aktif = mysqli_real_escape_string($koneksi, $_POST["aktif"]);

//UPDATE DATA PESERTA
	if($id_partai == '' OR $tgl == '' OR $kelas == '' OR $partai == '' OR $nm_merah == '' OR $nm_biru == '' OR $aktif == '')
	{
		?>
		<script type="text/javascript">
			alert('Data harus diisi semua!');
			document.location='jadwal_partai_tanding.php';
		</script>
		<?php
		exit;
	}


$sql = "UPDATE jadwal_tanding SET tgl='$tgl', kelas='$kelas', gelanggang='$gelanggang',
								partai='$partai', nm_merah='$nm_merah', kontingen_merah='$kontingen_merah',
								nm_biru='$nm_biru', kontingen_biru='$kontingen_biru', status='$status',
								pemenang='$pemenang', babak='$babak', aktif='$aktif'
								WHERE id_partai='$id_partai'";

if(mysqli_query($koneksi,$sql))
{
	?>
		<script type="text/javascript">
			alert('Data berhasil diubah.');
			document.location='jadwal_partai_tanding.php';
		</script>
	<?php
}
else
{
	?>
		<script type="text/javascript">
			alert('Data gagal diubah');
			document.location='jadwal_partai_tanding.php';
		</script>
	<?php
	die('Unable to update record: ' .mysqli_error($koneksi));
}
?>