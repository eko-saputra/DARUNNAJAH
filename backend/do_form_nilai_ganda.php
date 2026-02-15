<?php
session_start();
  if(!isset($_SESSION['pwd']))
  {
    header('location:login.php');
  }
include('includes/connection.php');

$nm_kejuaraan = mysqli_real_escape_string($koneksi,$_POST["nm_kejuaraan"]);
$tgl = mysqli_real_escape_string($koneksi,$_POST["tgl"]);
$tanggal = date("j M y", strtotime($tgl));

  //Mencari data jadwal pertandingan
  $sqljadwaltgr = "SELECT * FROM jadwal_tgr
          WHERE kategori = 'Ganda'
          ORDER BY id_partai ASC";
  $jadwal_tgr = mysqli_query($koneksi,$sqljadwaltgr);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>FORM NILAI GANDA- IPSI Kabupaten Tangerang</title>
<style type="text/css">
<!--
.style2 {color: #FFFFFF; font-weight: bold; }
-->
</style>
   <style type="text/css">
     .background
     {
       margin-left:1em;
       margin-top:1em;
       height: 950px;
       padding-left:5pt;
       padding-top:5pt;
       background: url("img/formnilai_ganda.png") top no-repeat;
     }
   </style>

  <style style="text/css">
    @media print
      {
        .non-printable { display: none; }
        .printable { display: block; }
        .pagebreak { page-break-before: always; }
  </style>
</head>

<body>
<div class="non-printable"><button onclick="window.print()">Cetak Halaman (A4/Portrait)</button></div>
<?php while($jadwal = mysqli_fetch_array($jadwal_tgr)) { ?>
<div class="background">
<table border="0" width="100%">
  <tr height="35">
    <td colspan="3"></td>
  </tr>
  <tr>
    <td width="10%"></td>
    <td width="55%"><center><?php echo $nm_kejuaraan; ?></center></td>
    <td width="35%"><center><?php echo strtoupper($jadwal['golongan']); ?></center></td>
  </tr>
</table>

<table border="0" width="100%">
  <tr height="25">
    <td colspan="3"></td>
  </tr>
  <tr>
    <td width="20%"></td>
    <td width="55%">
        <?php 
          $nama = explode("<br>",$jadwal['nama']); 
          echo '1. '.$nama[0].'<br> 2. '.$nama[1]; 
        ?>
    </td>
    <td width="25%"><center><?php echo $jadwal['noundian']; ?></center></td>
  </tr>
</table>

<table border="0" width="100%">
  <tr height="15">
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td width="20%"></td>
    <td width="60%"><?php echo $jadwal['kontingen']; ?></td>
    <td width="20%"><center><?php echo $tanggal; ?></center></td>
  </tr>
</table>
</div>
<div class="pagebreak"> </div>
<?php } ?>
</body>
</html>