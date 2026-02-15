<?php
session_start();
  if(!isset($_SESSION['pwd']))
  {
    header('location:login.php');
  }
include('includes/connection.php');


//AMBIL VARIABEL
$nama = mysqli_real_escape_string($koneksi, $_POST["nama"]);
$kontingen = mysqli_real_escape_string($koneksi, $_POST["kontingen"]);
$kelas = mysqli_real_escape_string($koneksi, $_POST["kelas"]);
$medali = mysqli_real_escape_string($koneksi, $_POST["medali"]);
$idjadwal = mysqli_real_escape_string($koneksi, $_POST["idjadwal"]);

if($nama == '' OR $kontingen == '' OR $kelas == '' OR $medali == '' OR $idjadwal == '')
	{
		?>
		<script type="text/javascript">
			document.location='admin_medali.php';
		</script>
		<?php
		exit;
	}

//CEK APAKAH MEDALI SUDAH ADA DI DATABASE
	$sqlcekmedali = mysqli_query($koneksi,"SELECT COUNT(*) FROM medali WHERE nama='$nama' AND kontingen='$kontingen' AND kelas='$kelas'");
	$cekmedali = mysqli_fetch_array($sqlcekmedali);
	if($cekmedali[0] >= 1) {
		?>
		<script type="text/javascript">
			document.location='admin_medali.php';
		</script>
		<?php
		exit;
	}

if($medali == "Emas") {
	$sqlvaluemedali = mysqli_query($koneksi, "SELECT medali FROM jadwal_tanding WHERE id_partai='$idjadwal'");
	$cekvaluemedali = mysqli_fetch_array($sqlvaluemedali);

	if($cekvaluemedali['medali']==0) {
		?>
		<script type="text/javascript">
			document.location='admin_medali.php';
		</script>
		<?php
		exit;
	}
}



if($medali == 'Perunggu') {
		$insertmedali = mysqli_query($koneksi, "INSERT INTO medali(nama, kontingen, kelas, medali, id_partai_FK) 
		VALUES('$nama', '$kontingen', '$kelas', '$medali', '$idjadwal')");
		$updatejadwalmedali = mysqli_query($koneksi, "UPDATE jadwal_tanding SET medali='1' WHERE id_partai='$idjadwal'");

	} elseif ($medali == 'Perak') {
		$insertmedali = mysqli_query($koneksi, "INSERT INTO medali(nama, kontingen, kelas, medali, id_partai_FK) 
		VALUES('$nama', '$kontingen', '$kelas', '$medali', '$idjadwal')");
		$updatejadwalmedali = mysqli_query($koneksi, "UPDATE jadwal_tanding SET medali='2' WHERE id_partai='$idjadwal'");

	} else {
		$insertmedali = mysqli_query($koneksi, "INSERT INTO medali(nama, kontingen, kelas, medali, id_partai_FK) 
		VALUES('$nama', '$kontingen', '$kelas', '$medali', '$idjadwal')");
		$updatejadwalmedali = mysqli_query($koneksi, "UPDATE jadwal_tanding SET medali='3' WHERE id_partai='$idjadwal'");
	}

	?>
		<script type="text/javascript">
			document.location='admin_medali.php';
		</script>
	<?php

?>