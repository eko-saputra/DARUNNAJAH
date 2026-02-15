<?php
session_start();
  if(!isset($_SESSION['pwd']))
  {
    header('location:login.php');
  }
include('includes/connection.php');

$gelanggang = mysqli_real_escape_string($koneksi, $_POST["gelanggang"]);
$tgl = mysqli_real_escape_string($koneksi, $_POST["tgl"]);
$tanggal = date("j F Y", strtotime($tgl));

  //Mencari data jadwal pertandingan
  $sqljadwal = "SELECT * FROM jadwal_tanding 
          WHERE tgl = '$tgl'
          AND gelanggang = '$gelanggang'
          ORDER BY id_partai ASC";
  $jadwal_tanding = mysqli_query($koneksi,$sqljadwal);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>FORM NILAI - IPSI Kabupaten Tangerang</title>
<style type="text/css">
<!--
.style2 {color: #FFFFFF; font-weight: bold; }
-->
</style>
</head>

<body>
<div class="non-printable"><button onclick="window.print()">Cetak Halaman A4/Portrait</button></div>
  <style style="text/css">
    @media print
      {
        .non-printable { display: none; }
        .printable { display: block; }
        .pagebreak { page-break-before: always; }
      }
  </style>
<?php while($jadwal = mysqli_fetch_array($jadwal_tanding)) { ?>
<h2 align="center">PERTANDINGAN PENCAK SILAT</h2>
<h2 align="center">DATA NILAI</h2>
<h2 align="center">KATEGORI TANDING</h2>
<table width="100%" border="0">
  <tr>
    <td colspan="4"><div align="center"><strong>GELANGGANG : <?php echo $jadwal['gelanggang']; ?></strong></div></td>
  </tr>
  <tr>
    <td width="16%">Tanggal</td>
    <td width="40%">: <?php echo $tanggal; ?></td>
    <td width="11%">Babak</td>
    <td width="33%">: <?php echo $jadwal['babak']; ?></td>
  </tr>
  <tr>
    <td>KELAS</td>
    <td>: <?php echo $jadwal['kelas']; ?></td>
    <td>PARTAI</td>
    <td>: <?php echo $jadwal['partai']; ?></td>
  </tr>
  <tr>
    <td colspan="4"><div align="center"><strong>WASIT : </strong>.........................................................</div></td>
  </tr>
  <tr>
    <td>Nama</td>
    <td>: <?php echo $jadwal['nm_merah']; ?></td>
    <td>Nama</td>
    <td>: <?php echo $jadwal['nm_biru']; ?></td>
  </tr>
  <tr>
    <td>Kontingen</td>
    <td>: <?php echo $jadwal['kontingen_merah']; ?></td>
    <td>Kontingen</td>
    <td>: <?php echo $jadwal['kontingen_biru']; ?></td>
  </tr>
</table>

<table width="100%" border="1">
  <tr>
    <td colspan="3" bgcolor="#FF0000"><div align="center" class="style2">SUDUT MERAH</div></td>
    <td rowspan="2"><div align="center"><strong>BABAK</strong></div></td>
    <td colspan="3" bgcolor="#0000FF"><div align="center" class="style2">SUDUT BIRU</div></td>
  </tr>
  <tr>
    <td><div align="center"><strong>JML. NILAI</strong></div></td>
    <td><div align="center"><strong>HUK.</strong></div></td>
    <td><div align="center"><strong>NILAI</strong></div></td>
    <td><div align="center"><strong>NILAI</strong></div></td>
    <td><div align="center"><strong>HUK.</strong></div></td>
    <td><div align="center"><strong>JML. NILAI</strong></div></td>
  </tr>
  <tr>
    <td height="80">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="center"><strong>I</strong></div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td height="80">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="center"><strong>II</strong></div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="80">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="center"><strong>III</strong></div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="80" bgcolor="#CCCCCC">&nbsp;</td>
    <td colspan="2" bgcolor="#CCCCCC"><div align="center"><strong>JUMLAH NILAI</strong></div></td>
    <td><div align="center"></div></td>
    <td colspan="2" bgcolor="#CCCCCC"><div align="center"><strong>JUMLAH NILAI</strong></div></td>
    <td bgcolor="#CCCCCC">&nbsp;</td>
  </tr>
</table>

<br><br>
<div>
<table width="100%" border="0">
  <tr>
    <td colspan="2"><strong>Keputusan Pemenang :</strong></td>
    <td colspan="2"><strong>Juri Nomor :</strong></td>
  </tr>
  <tr>
    <td width="12%">N a m a</td>
    <td width="42%">: ........................................</td>
    <td width="14%">Nama Juri</td>
    <td width="32%">: ........................................</td>
  </tr>
  <tr>
    <td>S u d u t</td>
    <td>: ........................................</td>
    <td>Tanda Tangan</td>
    <td>: ........................................</td>
  </tr>
</table>
</div>

<p>Catatan:</p>
<table width="100%" border="1">
  <tr>
    <td height="80">&nbsp;</td>
  </tr>
</table>

<br>
<div align="center">
    <table width="60%" border="1">
      <tr>
        <td><div align="center"><strong>ANGKA</strong></div></td>
        <td><div align="center"><strong>TEKNIK</strong></div></td>
        <td><div align="center"><strong>MUTLAK</strong></div></td>
        <td><div align="center"><strong>DISK.</strong></div></td>
        <td><div align="center"><strong>W.M.P</strong></div></td>
        <td><div align="center"><strong>U.D</strong></div></td>
      </tr>
    </table>
</div>

<div align="center">
    <table width="40%" border="1">
      <tr>
        <td><div align="center"><strong>BABAK</strong></div></td>
        <td><div align="center"><strong>I</strong></div></td>
        <td><div align="center"><strong>II</strong></div></td>
        <td><div align="center"><strong>III</strong></div></td>
      </tr>
    </table>
</div>
<div class="pagebreak"> </div>
<?php } ?>

</body>
</html>