<?php 
	/*
	*---------------------------------------------------------------
	* E-REGISTRASI PENCAK SILAT
	*---------------------------------------------------------------
	* This program is free software; you can redistribute it and/or
	* modify it under the terms of the GNU General Public License
	* as published by the Free Software Foundation; either version 2
	* of the License, or (at your option) any later version.
	*
	* This program is distributed in the hope that it will be useful,
	* but WITHOUT ANY WARRANTY; without even the implied warranty of
	* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	* GNU General Public License for more details.
	*
	* You should have received a copy of the GNU General Public License
	* along with this program; if not, write to the Free Software
	* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
	*
	* @Author Yudha Yogasara
	* yudha.yogasara@gmail.com
	* @Contributor Sofyan Hadi, Satria Salam
	*
	* IPSI KABUPATEN TANGERANG
	* SALAM OLAHRAGA
	*/
	include "backend/includes/connection.php";

	$nama = mysqli_real_escape_string($koneksi, $_POST["nama"]);
	$kontingen = mysqli_real_escape_string($koneksi, $_POST["kontingen"]);

	/*
	if($nama == '') {
		?>
		<script type="text/javascript">
			alert('GAGAL! Nama Pesilat masih kosong, masbro.');
			document.location='pencarian.php';
		</script>
		<?php
		exit;
	}
	*/

	//kueri data peserta
	$sqlpeserta ="SELECT * FROM peserta
					INNER JOIN kelastanding ON peserta.kelas_tanding_FK=kelastanding.ID_kelastanding
					WHERE peserta.nm_lengkap LIKE '%$nama%'
					AND peserta.kontingen LIKE '%$kontingen%'
					AND peserta.kategori_tanding='Tanding'
					ORDER BY peserta.kontingen ASC";
	$datapeserta = mysqli_query($koneksi, $sqlpeserta);
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Registrasi Pencak Silat</title>
<!-- CSS Files -->
    <link href="css/reset.css" rel="stylesheet" type="text/css" media="all" />
    <link href="css/stylesheet.css" rel="stylesheet" type="text/css" media="all" />
	
</head>
<body>
<!-- Start Wrapper -->
<div id="wrapper">
  <?php 
	include "headmenu.php";
  ?>

<h1>Hasil Pencarian Data Peserta Tanding</h1>
<div class="non-printable"><button onclick="window.print()">Cetak Halaman</button></div>
	
	<style style="text/css">
	@media print
    {
    	.non-printable { display: none; }
    	.printable { display: block; }
    }
	</style>

<div align="center">
	<table>
		<tr bgcolor="#99CCFF">
			<td>No.</td>
			<td>Nama</td>
			<td>Jenis Kelamin</td>
			<td>TB</td>
			<td>BB</td>
			<td>Keterangan</td>
			<td>Kontingen</td>
			<td>Kelas Tanding</td>
			<td>Golongan</td>
			<td>STATUS</td>
		</tr>
		<?php $no=0 ; while($peserta = mysqli_fetch_array($datapeserta)) { $no++; ?>
		<tr bgcolor="#eeeeee">
			<td><?php echo $no; ?></td>
			<td><?php echo $peserta['nm_lengkap']; ?></td>
			<td><?php echo $peserta['jenis_kelamin']; ?></td>
			<td><?php echo $peserta['tb']; ?></td>
			<td><?php echo $peserta['bb']; ?></td>
			<td><?php echo $peserta['asal_sekolah'];
						if($peserta['asal_sekolah']<>'') { echo ", Kelas ";}
					  echo $peserta['kelas']; ?>
			</td>
			<td><?php echo $peserta['kontingen']; ?></td>
			<td><?php echo $peserta['nm_kelastanding']; ?></td>
			<td><?php echo $peserta['golongan']; ?></td>
			<td><?php if($peserta['status']=='PAID') { echo "<IMG SRC=images/check.png></IMG>"; } ?></td>
		</tr>
		<?php } ?>
	</table>
	</div>


<!-- start: footer -->
<div id="footer">
	<p>Copyleft 2016 <?php echo " - ".date("Y"); ?> <a href="developer.php">IPSI Kabupaten Tangerang</a> </p>
	<!-- end: footer -->
</div>
<!-- end: footer -->
</div>
  </body>
</html>