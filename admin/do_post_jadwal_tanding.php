<?php
session_start();
  if(!isset($_SESSION['pwd']))
  {
    header('location:login.php');
  }
include('includes/connection.php');

// AMBIL VARIABEL - TANPA POST TANGGAL
$tanggal = date('Y-m-d'); // ambil tanggal server otomatis
$kelas = mysqli_real_escape_string($koneksi, $_POST["kelas"]);
$gelanggang = mysqli_real_escape_string($koneksi, $_POST["gelanggang"]);
$nopartai = mysqli_real_escape_string($koneksi, $_POST["nopartai"]);
$nm_merah = mysqli_real_escape_string($koneksi, $_POST["nm_merah"]);
$kont_merah = mysqli_real_escape_string($koneksi, $_POST["kont_merah"]);
$nm_biru = mysqli_real_escape_string($koneksi, $_POST["nm_biru"]);
$kont_biru = mysqli_real_escape_string($koneksi, $_POST["kont_biru"]);
$babak = mysqli_real_escape_string($koneksi, $_POST["babak"]);

// CEK DATA KOSONG - HAPUS CEK TANGGAL
if($kelas == '' OR $gelanggang == '' OR $nopartai == '' OR 
	$nm_merah == '' OR $kont_merah == '' OR 
    $nm_biru == '' OR $kont_biru == '' OR $babak == '') {
	?>
	<script type="text/javascript">
		alert('GAGAL ! Data masih ada yang kosong!');
		document.location='jadwal_partai_tanding.php';
	</script>
	<?php
	exit;
}

$sql = "INSERT INTO jadwal_tanding(id_partai,tgl, kelas, gelanggang, partai, 
                nm_merah, kontingen_merah, nm_biru, kontingen_biru, 
                status, pemenang, babak) 
        VALUES('$nopartai',NOW(), '$kelas', '$gelanggang', '$nopartai',
            '$nm_merah', '$kont_merah', '$nm_biru', '$kont_biru',
            '-', '-', '$babak')";

if(mysqli_query($koneksi,$sql))
{
	?>
		<script type="text/javascript">
			alert('Berhasil Diinput');
			document.location='jadwal_partai_tanding.php';
		</script>
	<?php
}
else
{
	?>
		<script type="text/javascript">
			alert('Gagal Diinput!');
			document.location='jadwal_partai_tanding.php';
		</script>
	<?php
	die('Unable to delete record: ' .mysqli_error($koneksi));
}
?>