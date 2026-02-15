<?php
session_start();
  if(!isset($_SESSION['pwd']))
  {
    header('location:login.php');
  }
include('includes/connection.php');

$tgl = mysqli_real_escape_string($koneksi,$_POST["tgl"]);
$tanggal = date("j M y", strtotime($tgl));

  //Mencari data jadwal pertandingan
  $sqljadwaltgr = "SELECT * FROM jadwal_tgr
          WHERE kategori = 'Regu'
          ORDER BY id_partai ASC";
  $jadwal_tgr = mysqli_query($koneksi,$sqljadwaltgr);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>FORM NILAI REGU- IPSI Kabupaten Tangerang</title>
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
       background: url("img/formnilai_regu.png") top no-repeat;
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
  <tr>
    <td height="50"></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td width="23%"></td>
    <td width="24%"><?php echo strtoupper($jadwal['golongan']); ?></td>
    <td width="53%">
    <?php
      $nama = explode("<br>",$jadwal['nama']); 
          echo '1. '.strtoupper($nama[0]); 
    ?>
    </td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td>
    <?php
      echo '2. '.strtoupper($nama[1]);
    ?>
    </td>
  </tr>
  <tr>
    <td><div align="right"><strong><?php echo $jadwal['noundian']; ?></strong></div></td>
    <td></td>
    <td>
     <?php
      echo '3. '.strtoupper($nama[2]);
    ?>
    </td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td><br><br><?php echo strtoupper($jadwal['kontingen']); ?></td>
  </tr>
</table>
<table border="0" width="100%">
<tr>
  <td height="460" width="40%"></td>
  <td width="20%"></td>
  <td width="40%"></td>
</tr>
<tr>
  <td></td>
  <td>&emsp;<?php echo $tanggal; ?></td>
  <td></td>
</tr>
</table>
</div>
<div class="pagebreak"> </div>
<?php } ?>
</body>
</html>