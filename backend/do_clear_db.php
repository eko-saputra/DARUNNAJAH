<?php
session_start();
  if(!isset($_SESSION['pwd']))
  {
    header('location:login.php');
  }
include('includes/connection.php');

//TRUNCATE ALL DATABASE except Table admin , kelastanding & wasit_juri
	$clearJadwalTanding = mysqli_query($koneksi, "TRUNCATE TABLE jadwal_tanding");
	$clearJadwalTGR = mysqli_query($koneksi, "TRUNCATE TABLE jadwal_tgr");

	$clearKonfirmasi = mysqli_query($koneksi, "TRUNCATE TABLE konfirmasi");
	
	$clearNilaiTanding = mysqli_query($koneksi, "TRUNCATE TABLE nilai_tanding");
	$clearNilaiTunggal = mysqli_query($koneksi, "TRUNCATE TABLE nilai_tunggal");
	$clearNilaiGanda = mysqli_query($koneksi, "TRUNCATE TABLE nilai_ganda");
	$clearNilaiRegu = mysqli_query($koneksi, "TRUNCATE TABLE nilai_regu");
	
	$clearPeserta = mysqli_query($koneksi, "TRUNCATE TABLE peserta");
	$clearUndianTanding = mysqli_query($koneksi, "TRUNCATE TABLE undian");
	$clearUndiangTGR = mysqli_query($koneksi, "TRUNCATE TABLE undian_tgr");

	$clearMedali = mysqli_query($koneksi, "TRUNCATE TABLE medali");

	$clearATLET_TERBAIK = mysqli_query($koneksi, "TRUNCATE TABLE nilai_atlet");
// END TRUNCATE

//Hapus file gambar dari folder  peserta_akta
	$filesakta = glob('../peserta_akta/*'); //get all file names
	foreach($filesakta as $fileakta) {
    	if(is_file($fileakta))
    	unlink($fileakta); //delete file
	}

	//Hapus file gambar dari folder  peserta_foto
	$filesfoto = glob('../peserta_foto/*'); //get all file names
	foreach($filesfoto as $filefoto) {
    	if(is_file($filefoto))
    	unlink($filefoto); //delete file
	}

	//Hapus file gambar dari folder  peserta_ijazah
	$filesijazah = glob('../peserta_ijazah/*'); //get all file names
	foreach($filesijazah as $fileijazah) {
    	if(is_file($fileijazah))
    	unlink($fileijazah); //delete file
	}

	//Hapus file gambar dari folder  peserta_ktp
	$filesktp = glob('../peserta_ktp/*'); //get all file names
	foreach($filesktp as $filektp) {
    	if(is_file($filektp))
    	unlink($filektp); //delete file
	}

	//Hapus file gambar dari folder buktibayar
	$filesbuktibayar = glob('../buktibayar/*'); //get all file names
	foreach($filesbuktibayar as $filebuktibayar) {
    	if(is_file($filebuktibayar))
    	unlink($filebuktibayar); //delete file
	}

?>

<script type="text/javascript">
	alert('Database berhasil terhapus seluruhnya');
	document.location='index.php';
</script>