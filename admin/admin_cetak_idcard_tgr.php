<?php
session_start();
  if(!isset($_SESSION['pwd']))
  {
    header('location:login.php');
  }
include('includes/connection.php');

//kueri data peserta
$sqlpeserta ="SELECT * FROM peserta
				WHERE peserta.kategori_tanding <> 'Tanding'
				AND peserta.status = 'PAID'
				ORDER BY peserta.kontingen,peserta.kode_gr ASC";
				//ORDER BY peserta.nm_lengkap ASC";
$datapeserta = mysqli_query($koneksi,$sqlpeserta);

?>

<html>
 <head>
  <style type="text/css">
	 	 .background
		 {
			margin-left:5em;
		    margin-top:2em;
		    width: 10cm;
		    height: 15cm;
		    padding-left:5pt;
		    padding-top:5pt;
		    background: url("img/back_idcard.png") no-repeat;
			background-size: cover;		   
		 }
		 #body
		 {
			 margin-top:9em;
			 width: 9.5cm;
			 padding-left:5pt;
			 height:10.5cm;
			 border-radius:0px 0px 25px 25px;
		 }
		img
		{
		    -webkit-box-shadow:0 0px 7px rgba(0,0,0,0.3);
		    box-shadow:0 0 5px rgba(0,0,0,0.2);
		    padding:5px;
		    background:#fff;
		    border:1px solid #ccc;
		}
		h3
		{
		    color: purple;
		     -webkit-box-shadow:0 0px 7px rgba(0,0,0,0.3);
		    box-shadow:0 0 5px rgba(0,0,0,0.2);
		    padding:1px;
		    background:#fff;
		    border:1px solid #ccc;
		}

		@media print
	    {
	      .non-printable { display: none; }
	      .printable { display: block; }
	      .pagebreak { page-break-before: always; }
	    }
	 </style>
 </head>
 <body>
 <div class="non-printable"><button onclick="window.print()">Cetak ID Card</button></div>
<?php while($data=mysqli_fetch_array($datapeserta)) { ?>
<div class="background">
<div id="body">
<form name='peserta'>
	 <table border='0' align='center'>
	 <tr>
	 	<td align='center'><img src=../peserta_foto/<?php echo $data['foto']; ?> height='155px'></td>
	 </tr>
	 <tr>
	 	<td></td>
	 </tr>
	 <tr>
	 	<td></td>
	 </tr>
	 <tr>
	 	<td align='center'><h3><?php echo strtoupper($data['nm_lengkap']); ?></h3></td>
	 </tr>
	 <tr>
	 	<td align='center'><h3><?php echo strtoupper($data['kontingen']); ?></h3></td>
	 </tr>
	 <tr>
	 	<td align='center'>
	 	<h3>
	 	<?php
	 	echo strtoupper($data['kategori_tanding']);
	 	if($data['jenis_kelamin'] == 'Laki-laki') {
				echo " PUTRA "; 
			} else {
				echo " PUTRI ";
			}
	 	echo strtoupper($data['golongan']); 
	 	?>	
	 	</h3>
	 	</td>
	 </tr>
	 </table>
 </form>
 </div>
 </div>
 <div class="pagebreak"> </div>
 <?php } ?>
 </body>
</html>