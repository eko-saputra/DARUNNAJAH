<?php
session_start();
  if(!isset($_SESSION['pwd']))
  {
    header('location:login.php');
  }
include('includes/connection.php');
$datetime = gmdate("Y-m-d H_i_s", time()+60*60*7);

	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=Hasil_Rekap_Tanding.xls");


	//$sort = mysqli_real_escape_string($koneksi, $_POST["sort"]);
	$golongan = mysqli_real_escape_string($koneksi, $_POST["golongan"]);


	//Ragam Sortir Data
	//peserta.nm_lengkap
	//peserta.perguruan_FK
	//peserta.kelas_tanding_FK

	//kueri data peserta
	$sqlpeserta ="SELECT * FROM peserta
					INNER JOIN kelastanding ON peserta.kelas_tanding_FK=kelastanding.ID_kelastanding
					WHERE peserta.kategori_tanding='Tanding'
					AND peserta.golongan LIKE '%$golongan%'
					ORDER BY peserta.jenis_kelamin,peserta.kelas_tanding_FK,peserta.kontingen ASC";
	$datapeserta = mysqli_query($koneksi, $sqlpeserta);
	
?>

<center><h1>REKAP DATA PESERTA TANDING</h1>
<h2>
	<?php
	if($golongan == '') { 
		echo ""; 
		} else {
		echo "(".strtoupper($golongan).")"; 
		}
	?>
</h2>
</center>

	<table border="1">
	<thead>
		<tr bgcolor="#99CCFF">
			<td>No.</td>
			<td>Nama</td>
			<td>Jenis Kelamin</td>
			<td>Tempat Lahir</td>
			<td>Tanggal Lahir</td>
			<td>TB</td>
			<td>BB</td>
			<td>Keterangan</td>
			<td>Kontingen</td>
			<td>Kelas Tanding</td>
			<td>Golongan</td>
			<td>Biaya Registrasi</td>
		</tr>
	</thead>
		<?php $no=0; $paid=0; while($peserta = mysqli_fetch_array($datapeserta)) { $no++; ?>
		<tr>
			<td><?php echo $no; ?></td>
			<td><?php echo $peserta['nm_lengkap']; ?></td>
			<td><?php echo $peserta['jenis_kelamin']; ?></td>
			<td><?php echo $peserta['tpt_lahir']; ?></td>
			<td><?php echo $peserta['tgl_lahir']; ?></td>
			<td><?php echo $peserta['tb']; ?></td>
			<td><?php echo $peserta['bb']; ?></td>
			<td><?php echo $peserta['asal_sekolah'];
						if($peserta['asal_sekolah']<>'') { echo ", Kelas ";}
					  echo $peserta['kelas']; ?>
			</td>
			<td><?php echo $peserta['kontingen']; ?></td>
			<td><?php echo $peserta['nm_kelastanding']; ?></td>
			<td><?php echo $peserta['golongan']; ?></td>
			<td><?php echo $peserta['status']; if($peserta['status']=='PAID') { $paid=$paid+1; } ?></td>
		</tr>
		<?php } ?>
	</table>

	<p>Generate data : Sebanyak <?php echo $no; ?> Peserta. Yang telah melunasi biaya pendaftaran sebanyak <?php echo $paid; ?> Peserta. </p>
	
	<p>Copyleft 2016 <?php echo " - ".date("Y"); ?> IPSI Kabupaten Tangerang </p>