<?php
session_start();
  if(!isset($_SESSION['pwd']))
  {
    header('location:login.php');
  }
include('includes/connection.php');

$tgl = mysqli_real_escape_string($koneksi, $_POST["tgl"]);
$tanggal = date("j M y", strtotime($tgl));

  //Mencari data jadwal pertandingan
  $sqljadwaltgr = "SELECT * FROM jadwal_tgr
          WHERE kategori = 'Tunggal'
          ORDER BY id_partai ASC";
  $jadwal_tgr = mysqli_query($koneksi,$sqljadwaltgr);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>FORM NILAI TUNGGAL- IPSI Kabupaten Tangerang</title>
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
       width: 1000px;
       height: 690px;
       padding-left:5pt;
       padding-top:5pt;
       background: url("img/formnilai_tunggal.png") top no-repeat;
     }
   </style>

  <style style="text/css">
    @media print
      {
        .non-printable { display: none; }
        .printable { display: block; }
        .pagebreak { page-break-before: always; }
      }
  </style>
</head>

<body>
<div class="non-printable"><button onclick="window.print()">Cetak Halaman (A4 / Landscape)</button></div>
<?php while($jadwal = mysqli_fetch_array($jadwal_tgr)) { ?>
<div class="background">
<table border="0" width="100%">
  <tr height="57">
    <td width="23%"></td>
    <td colspan="3"><?php echo $jadwal['kategori'].' '.$jadwal['golongan']; ?></td>
  </tr>
  <tr>
    <td></td>
    <td width="19%"><?php echo $jadwal['noundian']; ?></td>
    <td width="28%"><?php echo $jadwal['nama']; ?></td>
    <td><?php echo $jadwal['kontingen']; ?></td>
  </tr>
  <tr height="460">
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td>&emsp;<?php echo $tanggal; ?></td>
    <td></td>
    <td></td>
  </tr>
</table>
</div>
<div class="pagebreak"> </div>
<?php } ?>
</body>
</html>