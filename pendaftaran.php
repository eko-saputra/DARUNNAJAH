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

$today = date("Y-m-d");

//POST FROM MULAI PENDAFTARAN Page
$kategori_tanding = mysqli_real_escape_string($koneksi, $_POST["kategori_tanding"]);
$golongan = mysqli_real_escape_string($koneksi, $_POST["golongan"]);

if ($kategori_tanding == '' or $golongan == '') {
?>
  <script type="text/javascript">
    alert('Kategori dan Golongan Pertandingan Harus diisi terlebih dahulu.');
    document.location = 'mulai_pendaftaran.php';
  </script>
<?php
  exit;
}

//cari data kelas tanding
$sqlkelastanding = "SELECT * FROM kelastanding ORDER BY nm_kelastanding ASC;";
$carikelas = mysqli_query($koneksi, $sqlkelastanding);

//Mulai Autocomplete Cari asal kontingen
$sqlkontingen = "SELECT DISTINCT(kontingen) FROM peserta";
$kueri = mysqli_query($koneksi, $sqlkontingen);
while ($data = mysqli_fetch_array($kueri)) {
  $arrAsalKontingen[] = $data[0];
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Registrasi Sirkuit Pencak Silat</title>
  <meta name="description" content="Registrasi Online Kejuaraan Pencak Silat">
  <meta name="keywords" content="registrasi,online,pencak,silat">
  <meta name="robots" content="index,follow">
  <meta name="author" content="Yudha Yogasara">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="css/reset.css" rel="stylesheet" type="text/css" media="all" />
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f8f9fa;
      background-image: url('https://i.imgur.com/jOP8Rzn.png');
      background-attachment: fixed;
      background-size: cover;
      background-position: center;
      background-blend-mode: overlay;
    }

    #wrapper {
      background-color: rgba(255, 255, 255, 0.9);
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      padding: 20px;
      border-radius: 8px;
      margin-top: 20px;
      margin-bottom: 20px;
    }

    .section-title {
      color: #8B0000;
      border-bottom: 2px solid #8B0000;
      padding-bottom: 10px;
      margin-bottom: 20px;
      font-weight: 700;
      text-transform: uppercase;
    }

    .card {
      border: none;
      border-left: 4px solid #8B0000;
      margin-bottom: 20px;
    }

    .card-header {
      background-color: #8B0000;
      color: white;
      font-weight: bold;
    }

    .btn-daftar {
      background-color: #8B0000;
      border-color: #8B0000;
      color: white;
      font-weight: bold;
      padding: 10px 30px;
      border-radius: 30px;
      transition: all 0.3s;
    }

    .btn-daftar:hover {
      background-color: #6d0000;
      border-color: #6d0000;
      transform: scale(1.05);
      color: white;
    }

    #footer {
      padding: 20px 0;
      margin-top: 40px;
      border-top: 1px solid #dee2e6;
      text-align: center;
    }

    /* Form Styling */
    .registration-form {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-label {
      font-weight: 600;
      color: #212529;
    }

    .form-select,
    .form-control {
      border-radius: 5px;
      border: 1px solid #ced4da;
      padding: 10px;
    }

    .form-select:focus,
    .form-control:focus {
      border-color: #8B0000;
      box-shadow: 0 0 0 0.25rem rgba(139, 0, 0, 0.25);
    }

    .category-info {
      background-color: #f8f9fa;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 20px;
      border-left: 4px solid #8B0000;
    }

    .category-info p {
      margin-bottom: 5px;
      font-size: 16px;
    }

    .category-info strong {
      color: #8B0000;
    }
  </style>
</head>

<body>
  <!-- Bootstrap JS and Popper.js -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Start Wrapper -->
  <div class="container" id="wrapper">
    <?php
    include "headmenu.php";
    ?>

    <div class="row mt-4">
      <div class="col-12">
        <h2 class="section-title">FORMULIR PENDAFTARAN</h2>
        <div class="category-info">
          <p><strong>Kategori:</strong> <?php echo $kategori_tanding; ?></p>
          <p><strong>Golongan:</strong> <?php echo $golongan; ?></p>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12">
        <?php
        if ($kategori_tanding == 'Tanding') {
          include "form_tanding.php";
        } elseif ($kategori_tanding == 'Tunggal') {
          include "form_tunggal.php";
        } elseif ($kategori_tanding == 'Ganda') {
          include "form_ganda.php";
        } else {
          include "form_regu.php";
        }
        ?>
      </div>
    </div>

    <!-- start: footer -->
    <div id="footer">
      <p>Copyleft 2016 <?php echo " - " . date("Y"); ?> <a href="developer.php" class="text-decoration-none">IPSI Kota Dumai</a></p>
      <!-- end: footer -->
    </div>
    <!-- end: footer -->
  </div>
</body>

</html>