<?php
include "backend/includes/connection.php";

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Registrasi Sirkuit Pencak Silat</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="css/reset.css" rel="stylesheet" type="text/css" media="all" />
  <style>
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
      max-width: 600px;
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
  </style>
</head>

<body class="bg-dark">
  <!-- Bootstrap JS and Popper.js -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Start Wrapper -->
  <div class="container" id="wrapper">
    <?php
    include "headmenu.php";
    ?>

    <div class="row mt-4">
      <div class="col-12">
        <h2 class="section-title">PENDAFTARAN PESERTA</h2>
        <div class="card">
          <div class="card-body">
            <p class="lead text-center">Silahkan pilih kategori dan golongan untuk melanjutkan proses pendaftaran</p>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-12">
        <div class="registration-form">
          <form name="InputAwal" id="InputAwal" method="POST" action="pendaftaran.php">
            <div class="form-group row mb-3">
              <label for="kategori_tanding" class="col-sm-3 col-form-label form-label">Kategori</label>
              <div class="col-sm-9">
                <select name="kategori_tanding" id="kategori_tanding" class="form-select">
                  <option value="Tanding">Tanding</option>
                  <option value="Tunggal">Tunggal</option>
                  <option value="Ganda">Ganda</option>
                  <option value="Regu">Regu</option>
                </select>
              </div>
            </div>
            <div class="form-group row mb-3">
              <label for="golongan" class="col-sm-3 col-form-label form-label">Golongan</label>
              <div class="col-sm-9">
                <select name="golongan" id="golongan" class="form-select">
                  <option value="Usia Dini">Usia Dini</option>
                  <option value="Pra Remaja">Pra Remaja</option>
                  <option value="Remaja">Remaja</option>
                  <option value="Dewasa">Dewasa</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-12 text-center">
                <input type="submit" name="daftar" value="LANJUT" class="btn btn-daftar btn-lg">
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- start: footer -->
    <div id="footer">
      <p>&copy;<?php echo date("Y"); ?> Eko Saputra</a></p>
      <!-- end: footer -->
    </div>
    <!-- end: footer -->
  </div>
</body>

</html>