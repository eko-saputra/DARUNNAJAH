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
	//cari data perguruan
	$sqlperguruan = "SELECT * FROM perguruan ORDER BY nm_perguruan ASC;";
	$cariperguruan = mysqli_query($koneksi, $sqlperguruan);
	$cariperguruan2 = mysqli_query($koneksi, $sqlperguruan);

	//Mulai Autocomplete Cari asal kontingen
	$sql = "SELECT DISTINCT(kontingen) FROM peserta";
	$kueri = mysqli_query($koneksi, $sql);
	while($data = mysqli_fetch_array($kueri)) {
		$arrAsalKontingen[] = $data[0];
	}
	
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Pencarian Data Peserta - Registrasi Sirkuit Pencak Silat</title>
<meta name="description" content="Registrasi Online Kejuaraan Pencak Silat">
<meta name="keywords" content="registrasi,online,pencak,silat">
<meta name="robots" content="index,follow">
<meta name="author" content="Yudha Yogasara">
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- jQuery UI for autocomplete -->
<link href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" rel="stylesheet">
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
    .btn-search {
        background-color: #8B0000;
        border-color: #8B0000;
        color: white;
        font-weight: bold;
        padding: 8px 25px;
        border-radius: 30px;
        transition: all 0.3s;
    }
    .btn-search:hover {
        background-color: #6d0000;
        border-color: #6d0000;
        transform: scale(1.05);
        color: white;
    }
    .search-box {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    #footer {
        padding: 20px 0;
        margin-top: 40px;
        border-top: 1px solid #dee2e6;
        text-align: center;
    }
    .form-control:focus {
        border-color: #8B0000;
        box-shadow: 0 0 0 0.25rem rgba(139, 0, 0, 0.25);
    }
    .ui-autocomplete {
        max-height: 200px;
        overflow-y: auto;
        overflow-x: hidden;
        z-index: 9999 !important;
    }
</style>
</head>
<body>
<!-- jQuery JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Start Wrapper -->
<div class="container" id="wrapper">
  <?php 
	include "headmenu.php";
  ?>

  <div class="row mt-4">
    <div class="col-12">
      <h2 class="section-title">PENCARIAN DATA PESERTA</h2>
      <div class="card mb-4">
        <div class="card-body">
          <p class="lead">Gunakan formulir di bawah ini untuk mencari informasi peserta yang telah terdaftar dalam kejuaraan. Anda dapat mencari berdasarkan nama peserta atau kontingen.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-4">
    <div class="col-md-6">
      <h3 class="section-title">KATEGORI TANDING</h3>
      <div class="search-box">
        <form name="CariPeserta" id="CariPeserta" method="POST" action="do_pencarian.php">
          <div class="mb-3">
            <label for="nama" class="form-label">Nama Peserta:</label>
            <input type="text" class="form-control" name="nama" id="nama" maxlength="35" placeholder="Input nama peserta / kosongkan">
          </div>
          <div class="mb-3">
            <label for="kontingen" class="form-label">Kontingen:</label>
            <input type="text" class="form-control" name="kontingen" id="kontingen" placeholder="Input Kontingen / kosongkan">
          </div>
          <div class="d-grid">
            <button type="submit" name="cari" class="btn btn-search">CARI DATA</button>
          </div>
        </form>
      </div>
    </div>
    
    <div class="col-md-6">
      <h3 class="section-title">KATEGORI TGR</h3>
      <div class="search-box">
        <form name="CariPesertaTgr" id="CariPesertaTgr" method="POST" action="do_pencarian_tgr.php">
          <div class="mb-3">
            <label for="nama2" class="form-label">Nama Peserta:</label>
            <input type="text" class="form-control" name="nama" id="nama2" maxlength="35" placeholder="Input nama peserta / kosongkan">
          </div>
          <div class="mb-3">
            <label for="kontingen2" class="form-label">Kontingen:</label>
            <input type="text" class="form-control" name="kontingen2" id="kontingen2" placeholder="Input Kontingen / kosongkan">
          </div>
          <div class="d-grid">
            <button type="submit" name="cari" class="btn btn-search">CARI DATA</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- start: footer -->
  <div id="footer">
    <p>Copyleft 2016 <?php echo " - ".date("Y"); ?> <a href="developer.php" class="text-decoration-none">IPSI Kota Dumai</a></p>
    <!-- end: footer -->
  </div>
  <!-- end: footer -->
</div>

<script>
  var arrAsalKontingen = <?php echo json_encode($arrAsalKontingen); ?>;
  $(document).ready(function() { 
    $("#kontingen").autocomplete({
      source: arrAsalKontingen
    });
    
    $("#kontingen2").autocomplete({
      source: arrAsalKontingen
    });
  });
</script>
</body>
</html>