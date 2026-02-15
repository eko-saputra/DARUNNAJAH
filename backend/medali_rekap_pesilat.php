<?php
session_start();
  if(!isset($_SESSION['pwd']))
  {
    header('location:login.php');
  }
include('includes/connection.php');

	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=Medali_Rekap_Pesilat.xls");

	//query data pemenang semifinal
	$sqlwinner = "SELECT * FROM jadwal_tanding WHERE babak='SEMIFINAL' ORDER BY kelas ASC";
	$datawinner = mysqli_query($koneksi, "$sqlwinner");

	//query data pemenang final
	$sqlwinnerfinal = "SELECT * FROM jadwal_tanding WHERE babak='FINAL' ORDER BY kelas ASC";
	$datawinnerfinal = mysqli_query($koneksi, "$sqlwinnerfinal");

	//query data perolehan medali
	$sqlmedali = "SELECT * FROM medali ORDER BY kontingen ASC";
	$datamedali = mysqli_query($koneksi, "$sqlmedali");

	//query data koleksi medali
	$sqlkoleksimedali = "SELECT DISTINCT `kontingen` FROM medali";
	$datakoleksi = mysqli_query($koneksi, "$sqlkoleksimedali");
	
?>

<html>
<head>
	
</head>
<body>
<!-- Start Wrapper -->
<div id="wrapper">

<h1>Perolehan Medali (Kelas Tanding) Perorangan</h1><br>
<div align="center">
	<table>
	<thead>
	<tr bgcolor="#00FFFF" style="font-size: 16px; font-weight: bold;">
		<td><center>NO</center></td>
		<td><center>NAMA</center></td>
		<td><center>KONTINGEN</center></td>
		<td><center>KELAS</center></td>
		<td><center>MEDALI</center></td>
	</tr>
	</thead>
	<tbody>
	<?php $no=0; while($medali = mysqli_fetch_array($datamedali)) { $no++;?>
	<tr <?php if($no % 2 == 0) { echo "bgcolor=#eeeeee"; } ?> >
		<td><center><?php echo $no; ?></center></td>
		<td><center><?php echo $medali['nama']; ?></center></td>
		<td><center><?php echo $medali['kontingen']; ?></center></td>
		<td><center><?php echo $medali['kelas']; ?></center></td>
		<td><center><?php echo $medali['medali']; ?></center></td>
	</tr>
	</tbody>
	<?php } ?>
	</table>
</div>

</div>
  </body>
</html>