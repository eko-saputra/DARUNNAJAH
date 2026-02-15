<?php
session_start();
  if(!isset($_SESSION['pwd']))
  {
    header('location:login.php');
  }
include('includes/connection.php');

//AMBIL VARIABEL
	$tgl              = mysqli_real_escape_string($koneksi, $_POST["tgl"]);
	$partai           = mysqli_real_escape_string($koneksi, $_POST["partai"]);
	$kategori         = mysqli_real_escape_string($koneksi, $_POST["kategori"]);
	$golongan         = mysqli_real_escape_string($koneksi, $_POST["golongan"]);
	$nm_merah1         = mysqli_real_escape_string($koneksi, $_POST["nm_merah1"]);
	$nm_merah2        = mysqli_real_escape_string($koneksi, $_POST["nm_merah2"]);
	$nm_merah3        = mysqli_real_escape_string($koneksi, $_POST["nm_merah3"]);
	$nm_merah         = $nm_merah1.",".$nm_merah2.",".$nm_merah3;
	$kontingen_merah  = mysqli_real_escape_string($koneksi, $_POST["kontingen_merah"]);
	$nm_biru1          = mysqli_real_escape_string($koneksi, $_POST["nm_biru1"]);
	$nm_biru2          = mysqli_real_escape_string($koneksi, $_POST["nm_biru2"]);
	$nm_biru3          = mysqli_real_escape_string($koneksi, $_POST["nm_biru3"]);
	$nm_biru            = $nm_biru1.",".$nm_biru2.",".$nm_biru3;
	$kontingen_biru   = mysqli_real_escape_string($koneksi, $_POST["kontingen_biru"]);
	$babak            = mysqli_real_escape_string($koneksi, $_POST["babak"]);


//CEK DATA KOSONG
	if(
    $tgl == '' ||
    $partai == '' ||
    $kategori == '' ||
    $golongan == '' ||
    $nm_merah == '' ||
    $kontingen_merah == '' ||
    $nm_biru == '' ||
    $kontingen_biru == '' ||
    $babak == ''
)
	{
		?>
		<script type="text/javascript">
			alert('GAGAL ! Data masih ada yangkosong!');
			document.location='jadwal_partai_tgr.php';
		</script>
		<?php
		exit;
	}

$sql = "INSERT INTO jadwal_tgr(tgl, partai, kategori, golongan, 
					nm_merah, kontingen_merah, nm_biru, kontingen_biru, 
					status, pemenang, babak) 
					VALUES('$tgl', '$partai', 'REGU', '$golongan',
						'$nm_merah', '$kontingen_merah', '$nm_biru', '$kontingen_biru',
						'-', '-','$babak')";

if(mysqli_query($koneksi,$sql))
{
	?>
		<script type="text/javascript">
			alert('Berhasil Diinput');
			document.location='jadwal_partai_tgr.php';
		</script>
	<?php
}
else
{
	?>
		<script type="text/javascript">
			alert('Gagal Diinput!');
			document.location='jadwal_partai_tgr.php';
		</script>
	<?php
	die('Unable to delete record: ' .mysqli_error($koneksi));
}
?>