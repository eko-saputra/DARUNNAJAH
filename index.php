<?php
include "backend/includes/connection.php";
//count jumlah peserta ALL
$sqlpesertaall = mysqli_query($koneksi, "SELECT COUNT(*) FROM peserta");
$datapesertaall = mysqli_fetch_array($sqlpesertaall);

//count jumlah peserta ALL WHERE PAID
$sqlpesertpaid = mysqli_query($koneksi, "SELECT COUNT(*) FROM peserta WHERE status='PAID' ");
$datapesertapaid = mysqli_fetch_array($sqlpesertpaid);
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

    .stats-box {
      background-color: #f8f9fa;
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .stats-number {
      font-size: 24px;
      font-weight: bold;
      color: #8B0000;
    }

    #footer {
      padding: 20px 0;
      margin-top: 40px;
      border-top: 1px solid #dee2e6;
      text-align: center;
    }

    .schedule-item {
      padding: 10px 0;
      border-bottom: 1px dashed #dee2e6;
    }

    .schedule-item:last-child {
      border-bottom: none;
    }
  </style>
</head>

<body class="bg-dark bg-gradient">

  <!-- Bootstrap JS and Popper.js -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Start Wrapper -->
  <div class="container" id="wrapper">
    <?php
    include "headmenu.php";
    ?>

    <div class="row mt-4">
      <div class="col-md-6">
        <h2 class="section-title">JADWAL KEGIATAN</h2>
        <div class="card">
          <div class="card-body">
            <div class="schedule-item">
              <strong>Pendaftaran :</strong> ...
            </div>
            <div class="schedule-item">
              <strong>Technical Meeting :</strong> ...
            </div>
            <div class="schedule-item">
              <strong>Upacara Pembukaan :</strong> ...
            </div>
            <div class="schedule-item">
              <strong>Pertandingan :</strong> ...
            </div>
            <div class="schedule-item">
              <strong>Upacara Penutupan :</strong> ...
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <h2 class="section-title">TOTAL PENDAFTAR</h2>
        <div class="stats-box">
          <p>Sampai dengan <strong><?php date_default_timezone_set("Asia/Jakarta");
                                    echo date("d/m/Y") . ", " . date("h:i A"); ?></strong>, yang telah melakukan registrasi sebanyak <span class="stats-number"><?php echo $datapesertaall[0]; ?></span> orang.</p>
          <p>Peserta yang sudah melakukan konfirmasi biaya pendaftaran dan terverifikasi datanya, sebanyak <span class="stats-number"><?php echo $datapesertapaid[0]; ?></span> orang.</p>
          <p>Klik menu <a href="pencarian.php" class="text-decoration-none">Pencarian Data</a> untuk memeriksa apakah Pesilat Anda sudah terdaftar.</p>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-12 text-center">
        <a href="mulai_pendaftaran.php" class="btn btn-daftar btn-lg">DAFTAR SEKARANG</a>
      </div>
    </div>

    <!-- start: footer -->
    <div id="footer">
      <p><?php echo '&copy;' . date("Y"); ?> Eko Saputra</p>
      <!-- end: footer -->
    </div>
    <!-- end: footer -->
  </div>
</body>

</html>