<?php
session_start();
  if(!isset($_SESSION['pwd']))
  {
    header('location:login.php');
  }
include('includes/connection.php');

	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=Medali_Rekap_Kontingen.xls");


	//query data pemenang semifinal
	$sqlwinner = "SELECT * FROM jadwal_tanding WHERE babak='SEMIFINAL' ORDER BY kelas ASC";
	$datawinner = mysqli_query($koneksi,"$sqlwinner");

	//query data pemenang final
	$sqlwinnerfinal = "SELECT * FROM jadwal_tanding WHERE babak='FINAL' ORDER BY kelas ASC";
	$datawinnerfinal = mysqli_query($koneksi,"$sqlwinnerfinal");

	//query data perolehan medali
	$sqlmedali = "SELECT * FROM medali ORDER BY kontingen ASC";
	$datamedali = mysqli_query($koneksi,"$sqlmedali");

	//query data koleksi medali
	$sqlkoleksimedali = "SELECT DISTINCT `kontingen` FROM medali";
	$datakoleksi = mysqli_query($koneksi,"$sqlkoleksimedali");
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BACKEND REGISTRASI SILAT</title>
	
</head>
<body>
<!-- Start Wrapper -->
<div id="wrapper">
 
<h1>Perolehan Medali (Kelas Tanding) Kontingen</h1>
<div align="center">
	<table>
		<tr bgcolor="#00FFFF" style="font-size: 16px; font-weight: bold;">
			<td><center>KONTINGEN</center></td>
			<td><center>EMAS</center></td>
			<td><center>PERAK</center></td>
			<td><center>PERUNGGU</center></td>
		</tr>
		<?php while($koleksimedali = mysqli_fetch_array($datakoleksi)) { ;?>
		<tr>
			<td><?php echo $koleksimedali['kontingen']; ?></td>
			<td>
				<center>
				<?php
				$sqlcountemas = mysqli_query($koneksi,"SELECT COUNT(*) FROM medali 
											WHERE kontingen='$koleksimedali[kontingen]'
											AND medali='emas'");
				$countemas = mysqli_fetch_array($sqlcountemas);
				echo $countemas[0];
				?>
				</center>
			</td>
			<td>
				<center>
				<?php
				$sqlcountperak = mysqli_query($koneksi,"SELECT COUNT(*) FROM medali 
											WHERE kontingen='$koleksimedali[kontingen]'
											AND medali='perak'");
				$countperak = mysqli_fetch_array($sqlcountperak);
				echo $countperak[0];
				?>
				</center>
			</td>
			<td>
				<center>
				<?php
				$sqlcountperunggu = mysqli_query($koneksi,"SELECT COUNT(*) FROM medali 
											WHERE kontingen='$koleksimedali[kontingen]'
											AND medali='perunggu'");
				$countperunggu = mysqli_fetch_array($sqlcountperunggu);
				echo $countperunggu[0];
				?>
				</center>
			</td>
		</tr>
		<?php } ?>
	</table>
</div>
  
</div>
  </body>
</html>