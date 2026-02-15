<?php 
	session_start();
	if(!isset($_SESSION['pwd']))
	{
		header('location:login.php');
	}

	include "includes/connection.php";

	//cari data peserta
	$sqlkelastanding = "SELECT * FROM kelastanding";
	$carikelastanding = mysqli_query($koneksi,$sqlkelastanding);

?>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
<center><h1>BAGAN PERTANDINGAN</h1></center>
<div align="center">
	<form name="mengundi" id="_form_bagan" method="POST" action="admin_do_bagan.php">
	<table border="0">
	<tr class="printable">
		<td>
			<select name="golongan" id="golongan">
				<option value="">--- Pilih Golongan ---</option>
				<option value="Usia Dini">Usia Dini</option>
				<option value="Pra Remaja">Pra Remaja</option>
				<option value="Remaja">Remaja</option>
				<option value="Dewasa">Dewasa</option>
			</select>
		</td>
		<td>
			<select name="jenis_kelamin" id="jenis_kelamin">
				<option value="">--- Pilih Putra/Putri ---</option>
				<option value="Laki-laki">Putra</option>
				<option value="Perempuan">Putri</option>
			</select>
		</td>
		<td>
			<select name="kelas_tanding" id="kelas_tanding">
				<option value="">--- Pilih Kelas Tanding ---</option>
				<?php while($kelastanding = mysqli_fetch_array($carikelastanding)) { ?>
				<option value="<?php echo $kelastanding[0]; ?>"><?php echo $kelastanding[1]; ?></option>
				<?php } ?>
			</select>
		</td>
	</tr>
	<tr class="non-printable">
		<td colspan="3"><input type="submit" name="bagan" value="GENERATE CHART"></td>
	</tr>
	</table>
	</form>
</div>

<button class="non-printable" onclick="window.print()">Cetak Bagan</button>
<div id="_bagan" class="printable"></div>

<style style="text/css">
	@media print
    {
    	.non-printable { display: none; }
    	.printable { display: block; }
    }
	</style>

<div id="footer">
	<p>&copy; 2016 <?php echo " - ".date("Y"); ?> IPSI Kabupaten Tangerang </p>
	<!-- end: footer -->
</div>

<script type="text/javascript" src="js/jquery.bracket-world.min.js"></script>
	<link href="css/jquery.bracket-world.css" rel="stylesheet" type="text/css" media="all" />
	<!--<link href="css/index.css" rel="stylesheet" type="text/css" media="all" />-->
<script type="text/javascript">
	$(document).ready(function(){
		$("#_form_bagan").submit(function(){
			$.ajax({
				url: $(this).attr('action'),
				data:$(this).serialize(),
				type: 'POST',
				datatype:'json',
				success: function (data) {
				jsondata=$.parseJSON(data);
				$('#_bagan').bracket(
					{
						teams:jsondata.teams,
						topOffset:35,
						scale:0.50,
						scaleDelta:0.01,
						horizontal:0,
						height:'1200px',
						icons:true,
						teamNames:jsondata.teamNames
					});
				}
				// console.log(data.teamNames);
			});
			return false;
		})
	})
</script>

</div>
</body>
</html>