
<?php
include "../backend/includes/connection.php";

//dapatkan ID jadwal pertandingan
$id_partai = mysqli_real_escape_string($koneksi, $_GET["id_partai"]);
//echo $id_partai;

//CEK dan UBAH status AKTIF PARTAI
$sqljadwalpartai = "SELECT status FROM jadwal_tanding 
					WHERE id_partai='$id_partai'";
$jadwalpartai = mysqli_query($koneksi, $sqljadwalpartai);
$status_partai = mysqli_fetch_array($jadwalpartai);
if ($status_partai === null) {
    echo "<script>alert('Partai Tersebut Belum Dilaksanakan Harap Menunggu Dengan Sabar.'); window.location.href='../index.php';</script>";
    exit;
}
//echo $status_partai['status'];

if ($status_partai['status'] == '-') {
	$update = mysqli_query($koneksi, "UPDATE jadwal_tanding SET aktif='1' WHERE id_partai='$id_partai'");
}

//Mencari data jadwal pertandingan
$sqljadwal = "SELECT * FROM jadwal_tanding 
					WHERE id_partai='$id_partai'";
$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
$jadwal = mysqli_fetch_array($jadwal_tanding);

//mengambil url
$id_jadwal = isset($_GET['id_partai']) ? intval($_GET['id_partai']) : 0;

// PERIINGATAN 1-3 sudut biru
$sqlbabak1 = "SELECT button FROM nilai_dewan WHERE id_juri = 11 AND sudut = 'BIRU' AND babak = 1 AND button IN (5, 6) AND id_jadwal = $id_jadwal";
$resultbabak1 = $koneksi->query($sqlbabak1);   

$sqlbabak2 = "SELECT nilai FROM nilai_dewan WHERE id_juri = 11 AND sudut = 'BIRU' AND babak = 2 AND button IN (5, 6) AND id_jadwal = $id_jadwal";
$resultbabak2 = $koneksi->query($sqlbabak2);

$sqlbabak3= "SELECT nilai FROM nilai_dewan WHERE id_juri = 11 AND sudut = 'BIRU' AND babak = 3 AND button IN (5, 6) AND id_jadwal = $id_jadwal";
$resultbabak3 = $koneksi->query($sqlbabak3);

//PERINGATAN merah 1-3
$sqlbabakm1 = "SELECT nilai FROM nilai_dewan WHERE id_juri = 11 AND sudut = 'MERAH' AND babak = 1 AND button IN (5, 6) AND id_jadwal = $id_jadwal";
$resultbabakm1 = $koneksi->query($sqlbabakm1);

$sqlbabakm2 = "SELECT nilai FROM nilai_dewan WHERE id_juri = 11 AND sudut = 'MERAH' AND babak = 2 AND button IN (5, 6) AND id_jadwal = $id_jadwal";
$resultbabakm2 = $koneksi->query($sqlbabakm2);

$sqlbabakm3= "SELECT nilai FROM nilai_dewan WHERE id_juri = 11 AND sudut = 'MERAH' AND babak = 3 AND button IN (5, 6) AND id_jadwal = $id_jadwal";
$resultbabakm3 = $koneksi->query($sqlbabakm3);

// query Teguran biru
$sqlbabakt1 = "SELECT nilai FROM nilai_dewan WHERE id_juri = 11 AND sudut = 'BIRU' AND babak = 1 AND button IN (3, 4) AND id_jadwal = $id_jadwal";
$resultbabakt1 = $koneksi->query($sqlbabakt1);

$sqlbabakt2 = "SELECT nilai FROM nilai_dewan WHERE id_juri = 11 AND sudut = 'BIRU' AND babak = 2 AND button IN (3, 4) AND id_jadwal = $id_jadwal";
$resultbabakt2 = $koneksi->query($sqlbabakt2);

$sqlbabakt3= "SELECT nilai FROM nilai_dewan WHERE id_juri = 11 AND sudut = 'BIRU' AND babak = 3 AND button IN (3, 4) AND id_jadwal = $id_jadwal";
$resultbabakt3 = $koneksi->query($sqlbabakt3);

// teguran 1-3 merah
$sqlbabakmt1 = "SELECT nilai FROM nilai_dewan WHERE id_juri = 11 AND sudut = 'MERAH' AND babak = 1 AND button IN (3, 4) AND id_jadwal = $id_jadwal";
$resultbabakmt1 = $koneksi->query($sqlbabakmt1);

$sqlbabakmt2 = "SELECT nilai FROM nilai_dewan WHERE id_juri = 11 AND sudut = 'MERAH' AND babak = 2 AND button IN (3, 4) AND id_jadwal = $id_jadwal";
$resultbabakmt2 = $koneksi->query($sqlbabakmt2);

$sqlbabakmt3= "SELECT nilai FROM nilai_dewan WHERE id_juri =  11 AND sudut = 'MERAH' AND babak = 3 AND button IN (3, 4) AND id_jadwal = $id_jadwal";
$resultbabakmt3 = $koneksi->query($sqlbabakmt3);

//jatuhanbiru babak 1-3
$sqldewan1 = "SELECT nilai FROM nilai_dewan WHERE id_juri = 11 AND sudut = 'BIRU' AND babak = 1 AND button = 1  AND id_jadwal = $id_jadwal";
$resultdewan1 = $koneksi->query($sqldewan1);

$sqldewan2 = "SELECT nilai FROM nilai_dewan WHERE id_juri = 11 AND sudut = 'BIRU' AND babak = 2 AND button = 1  AND id_jadwal = $id_jadwal";
$resultdewan2 = $koneksi->query($sqldewan2);

$sqldewan3 = "SELECT nilai FROM nilai_dewan WHERE id_juri = 11 AND sudut = 'BIRU' AND babak = 3 AND button = 1  AND id_jadwal = $id_jadwal";
$resultdewan3 = $koneksi->query($sqldewan3);
//jatuhanmerah babak 1-3
$sqldewanm1 = "SELECT nilai FROM nilai_dewan WHERE id_juri = 11 AND sudut = 'MERAH' AND babak = 1 AND button = 1  AND id_jadwal = $id_jadwal";
$resultdewanm1 = $koneksi->query($sqldewanm1);

$sqldewanm2 = "SELECT nilai FROM nilai_dewan WHERE id_juri = 11 AND sudut = 'MERAH' AND babak = 2 AND button = 1  AND id_jadwal = $id_jadwal";
$resultdewanm2 = $koneksi->query($sqldewanm2);

$sqldewanm3 = "SELECT nilai FROM nilai_dewan WHERE id_juri = 11 AND sudut = 'MERAH' AND babak = 3 AND button = 1  AND id_jadwal = $id_jadwal";
$resultdewanm3 = $koneksi->query($sqldewanm3);

//binaan MERAH
$sqlbabakteguran1= "SELECT button FROM nilai_dewan WHERE id_juri = 11 AND button = 2 AND sudut = 'MERAH' AND babak = 1  AND id_jadwal = $id_jadwal";
$resultbabakteguran1 = $koneksi->query($sqlbabakteguran1);

$sqlbabakteguran2= "SELECT button FROM nilai_dewan WHERE id_juri = 11 AND button = 2 AND sudut = 'MERAH' AND babak = 2  AND id_jadwal = $id_jadwal";
$resultbabakteguran2 = $koneksi->query($sqlbabakteguran2);

$sqlbabakteguran3= "SELECT button FROM nilai_dewan  WHERE id_juri = 11 AND button = 2 AND sudut = 'MERAH' AND babak = 3  AND id_jadwal = $id_jadwal";
$resultbabakteguran3 = $koneksi->query($sqlbabakteguran3);

//binaan BIRU
$sqlbabakteguranb1= "SELECT button FROM nilai_dewan WHERE id_juri = 11 AND button = 2 AND sudut = 'BIRU' AND babak = 1  AND id_jadwal = $id_jadwal";
$resultbabakteguranb1 = $koneksi->query($sqlbabakteguranb1);

$sqlbabakteguranb2= "SELECT button FROM nilai_dewan WHERE id_juri = 11 AND button = 2 AND sudut = 'BIRU' AND babak = 2  AND id_jadwal = $id_jadwal";
$resultbabakteguranb2 = $koneksi->query($sqlbabakteguranb2);

$sqlbabakteguranb3= "SELECT button FROM nilai_dewan WHERE id_juri = 11 AND button = 2 AND sudut = 'BIRU' AND babak = 3  AND id_jadwal = $id_jadwal";
$resultbabakteguranb3 = $koneksi->query($sqlbabakteguranb3);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dewan Match Score Keeping</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style media="print">
    body {
      overflow: hidden;
    }
    table {
      width: 100%;
      table-layout: fixed;
      word-wrap: break-word;
    }
    @page {
      size: A4;
      margin: 20mm;
    }
    ::-webkit-scrollbar {
      display: none;
    }
  </style>
  <style>
    @media print {
      .bg-danger {
        background-color: #dc3545 !important;
        color: white !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
      }
      .bg-primary {
        background-color: #0d6efd !important;
        color: white !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
      }
      .no-print {
        display: none !important;
      }
    }
  </style>
</head>
<body>
  <div id="printArea">
    <div class="container py-4">
      <div class="d-flex align-items-center mb-3">
        <img src="ipsi2.png" alt="Logo" style="width:100px; margin-right: 20px;">
        <div class="text-center w-100">
          <h5 class="fw-bold mb-1">Formulir Pertandingan Pencak Silat</h5>
          <h6 class="fw-bold mb-1">Penilaian Score Dewan</h6>
        </div>
      </div>
    <table width="100%" border="0" style="margin-top:10px;">
    <tr>
        <td><b>Nomor Partai:</b> <?php echo $jadwal['partai']; ?> <br><b>Nama:</b> <?php echo $jadwal['nm_merah']; ?></td>
        <td align="right"><b>Kategori:</b> <?php echo $jadwal['kelas']; ?><br><b>Nama:</b> <?php echo $jadwal['nm_biru']; ?></td>
    </tr>
</table>
<br>
      <div class="table-responsive">
        <table class="text-center table table-bordered" width="100%" cellspacing="0" cellpadding="5">
          <thead>
            <tr>
              <th colspan="4" class="bg-danger text-white">MERAH</th>
              <th rowspan="2">Round</th>
              <th colspan="4" class="bg-primary text-white">BIRU</th>
            </tr>
            <tr>
              <th>Peringatan</th>
              <th>Teguran</th>
              <th>Binaan</th>
              <th>Jatuhan</th>
              <th>Peringatan</th>
              <th>Binaan</th>
              <th>Teguran</th>
              <th>Jatuhan</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="py-4">
              <?php
                if ($resultbabakm1 && $resultbabakm1->num_rows > 0) {
                      $output = [];
                      while($row = $resultbabakm1->fetch_assoc()) {
                          if ($row["button"] == 5) {
                              $output[] = "1";
                          } elseif ($row["button"] == 6) {
                              $output[] = "2";
                          }
                      }
                      echo "<strong>" . implode(" ", $output) . "</strong>";
                  } else {
                      echo "<strong>0</strong>";
                  }
              ?>
              </td>
                <td valign="middle">
                <?php
                  if ($resultbabakmt1 && $resultbabakmt1->num_rows > 0) {
                  $nilaiArray = [];
                  while($row = $resultbabakmt1->fetch_assoc()) {
                  $nilaiArray[] = $row["nilai"];
                    }
                  echo "<strong>" . implode(" ", $nilaiArray) . "</strong>"; // Gabungkan semua nilai jadi satu string
                  } else {
                  echo "<strong>0</strong>";
                  }
              ?>
              </td>
              <td class="py-4">
            <?php
              $resultbabakteguran1 = $koneksi->query($sqlbabakteguran1);

              // Hitung jumlah baris hasil query
              $jumlah = $resultbabakteguran1->num_rows;

              // Tampilkan jumlahnya
              echo "<strong>$jumlah</strong>";
              ?>
              </td>
              <td class="py-4">
                <?php
                  if ($resultdewanm1 && $resultdewanm1->num_rows > 0) {
                  $nilaiArray = [];
                  while($row = $resultdewanm1->fetch_assoc()) {
                  $nilaiArray[] = $row["nilai"];
                    }
                  echo "<strong>" . implode(" ", $nilaiArray) . "</strong>";
                  } else {
                echo "<strong>0</strong>";
                  }
              ?>
              </td>
              <td class="py-4">1</td>
              <td class="py-4">
                <?php
                  if ($resultbabak1 && $resultbabak1->num_rows > 0) {
                      $output = [];
                      while($row = $resultbabak1->fetch_assoc()) {
                          if ($row["button"] == 5) {
                              $output[] = "1";
                          } elseif ($row["button"] == 6) {
                              $output[] = "2";
                          }
                      }
                      echo "<strong>" . implode(" ", $output) . "</strong>";
                  } else {
                      echo "<strong>0</strong>";
                  }
                ?>
              </td>
              <td class="py-4">
          <?php
            $resultbabakteguranb1 = $koneksi->query($sqlbabakteguranb1);
            // Hitung jumlah baris hasil query
            $jumlah = $resultbabakteguranb1->num_rows;
            // Tampilkan jumlahnya
            echo "<strong>$jumlah</strong>";
          ?>
              </td>
                <td valign="middle">
              <?php
                  if ($resultbabakt1 && $resultbabakt1->num_rows > 0) {
                  $nilaiArray = [];
                  while($row = $resultbabakt1->fetch_assoc()) {
                  $nilaiArray[] = $row["nilai"];
                    }
                  echo "<strong>" . implode(" ", $nilaiArray) . "</strong>"; // Gabungkan semua nilai jadi satu string
                  } else {
                  echo "<strong>0</strong>";
                  }
              ?>
              </td>
              <td class="py-4">
                  <?php
                  if ($resultdewan1 && $resultdewan1->num_rows > 0) {
                  $nilaiArray = [];
                  while($row = $resultdewan1->fetch_assoc()) {
                  $nilaiArray[] = $row["nilai"];
                    }
                  echo "<strong>" . implode(" ", $nilaiArray) . "</strong>";
                  } else {
                echo "<strong>0</strong>";
                  }
              ?>
              </td>
            </tr>
            <tr>
              <td class="py-4">
                <?php
                    if ($resultbabakm2 && $resultbabakm2->num_rows > 0) {
                        $output = [];
                        while($row = $resultbabakm2->fetch_assoc()) {
                            if ($row["button"] == 5) {
                                $output[] = "1";
                            } elseif ($row["button"] == 6) {
                                $output[] = "2";
                            }
                        }
                        echo "<strong>" . implode(" ", $output) . "</strong>";
                    } else {
                        echo "<strong>0</strong>";
                    }
                ?>
              </td>
                <td valign="middle">
                <?php
                  if ($resultbabakmt2 && $resultbabakmt2->num_rows > 0) {
                  $nilaiArray = [];
                  while($row = $resultbabakmt2->fetch_assoc()) {
                  $nilaiArray[] = $row["nilai"];
                    }
                  echo "<strong>" . implode(" ", $nilaiArray) . "</strong>"; // Gabungkan semua nilai jadi satu string
                  } else {
                  echo "<strong>0</strong>";
                  }
              ?>
              </td>
              <td class="py-4">
          <?php
            $resultbabakteguran2 = $koneksi->query($sqlbabakteguran2);
            // Hitung jumlah baris hasil query
            $jumlah = $resultbabakteguran2->num_rows;
            // Tampilkan jumlahnya
            echo "<strong>$jumlah</strong>";
          ?>
              </td>
              <td class="py-4">
                <?php
                  if ($resultdewanm2 && $resultdewanm2->num_rows > 0) {
                  $nilaiArray = [];
                  while($row = $resultdewanm2->fetch_assoc()) {
                  $nilaiArray[] = $row["nilai"];
                    }
                  echo "<strong>" . implode(" ", $nilaiArray) . "</strong>";
                  } else {
                echo "<strong>0</strong>";
                  }
              ?>
              </td>
              <td class="py-4">2</td>
              <td class="py-4">
              <?php
                  if ($resultbabak2 && $resultbabak2->num_rows > 0) {
                      $output = [];
                      while($row = $resultbabak2->fetch_assoc()) {
                          if ($row["button"] == 5) {
                              $output[] = "1";
                          } elseif ($row["button"] == 6) {
                              $output[] = "2";
                          }
                      }
                      echo "<strong>" . implode(" ", $output) . "</strong>";
                  } else {
                      echo "<strong>0</strong>";
                  }
              ?>
              </td>
              <td class="py-4">
          <?php
            $resultbabakteguranb2 = $koneksi->query($sqlbabakteguranb2);
            // Hitung jumlah baris hasil query
            $jumlah = $resultbabakteguranb2->num_rows;
            // Tampilkan jumlahnya
            echo "<strong>$jumlah</strong>";
          ?>
              </td>
                <td valign="middle">
                <?php
                  if ($resultbabakt2 && $resultbabakt2->num_rows > 0) {
                  $nilaiArray = [];
                  while($row = $resultbabakt2->fetch_assoc()) {
                  $nilaiArray[] = $row["nilai"];
                    }
                  echo "<strong>" . implode(" ", $nilaiArray) . "</strong>"; // Gabungkan semua nilai jadi satu string
                  } else {
                  echo "<strong>0</strong>";
                  }
              ?>
              </td>
              <td class="py-4">
                <?php
                  if ($resultdewan2 && $resultdewan2->num_rows > 0) {
                  $nilaiArray = [];
                  while($row = $resultdewan2->fetch_assoc()) {
                  $nilaiArray[] = $row["nilai"];
                    }
                  echo "<strong>" . implode(" ", $nilaiArray) . "</strong>";
                  } else {
                echo "<strong>0</strong>";
                  }
              ?>
              </td>
            </tr>
            <tr>
              <td class="py-4">
              <?php
                  if ($resultbabakm3 && $resultbabakm3->num_rows > 0) {
                    $output = [];
                    while($row = $resultbabakm3->fetch_assoc()) {
                        if ($row["button"] == 5) {
                            $output[] = "1";
                        } elseif ($row["button"] == 6) {
                            $output[] = "2";
                        }
                    }
                    echo "<strong>" . implode(" ", $output) . "</strong>";
                } else {
                    echo "<strong>0</strong>";
                }
              ?>
              </td>
                <td valign="middle">
                <?php
                  if ($resultbabakmt3 && $resultbabakmt3->num_rows > 0) {
                  $nilaiArray = [];
                  while($row = $resultbabakmt3->fetch_assoc()) {
                  $nilaiArray[] = $row["nilai"];
                    }
                  echo "<strong>" . implode(" ", $nilaiArray) . "</strong>"; // Gabungkan semua nilai jadi satu string
                  } else {
                  echo "<strong>0</strong>";
                  }
              ?>
              </td>
              <td class="py-4">
          <?php
            $resultbabakteguran3 = $koneksi->query($sqlbabakteguran3);
            // Hitung jumlah baris hasil query
            $jumlah = $resultbabakteguran3->num_rows;
            // Tampilkan jumlahnya
            echo "<strong>$jumlah</strong>";
          ?>
              </td>
              <td class="py-4">
                <?php
                  if ($resultdewanm3 && $resultdewanm3->num_rows > 0) {
                  $nilaiArray = [];
                  while($row = $resultdewanm3->fetch_assoc()) {
                  $nilaiArray[] = $row["nilai"];
                    }
                  echo "<strong>" . implode(" ", $nilaiArray) . "</strong>";
                  } else {
                echo "<strong>0</strong>";
                  }
              ?>
              </td>
              <td class="py-4">3</td>
              <td class="py-4">
                <?php
                  if ($resultbabak3 && $resultbabak3->num_rows > 0) {
                      $output = [];
                      while($row = $resultbabak3->fetch_assoc()) {
                          if ($row["button"] == 5) {
                              $output[] = "1";
                          } elseif ($row["button"] == 6) {
                              $output[] = "2";
                          }
                      }
                      echo "<strong>" . implode(" ", $output) . "</strong>";
                  } else {
                      echo "<strong>0</strong>";
                  }
                ?>
              </td>
              <td class="py-4">
            <?php
              $resultbabakteguranb3 = $koneksi->query($sqlbabakteguranb3);
              // Hitung jumlah baris hasil query
              $jumlah = $resultbabakteguranb3->num_rows;
              // Tampilkan jumlahnya
              echo "<strong>$jumlah</strong>";
            ?>
              </td>
              <td valign="middle">
                <?php
                  if ($resultbabakt3 && $resultbabakt3->num_rows > 0) {
                  $nilaiArray = [];
                  while($row = $resultbabakt3->fetch_assoc()) {
                  $nilaiArray[] = $row["nilai"];
                    }
                  echo "<strong>" . implode(" ", $nilaiArray) . "</strong>"; // Gabungkan semua nilai jadi satu string
                  } else {
                  echo "<strong>0</strong>";
                  }
              ?>
              </td>
              <td class="py-4">
                    <?php
                  if ($resultdewan3 && $resultdewan3->num_rows > 0) {
                  $nilaiArray = [];
                  while($row = $resultdewan3->fetch_assoc()) {
                  $nilaiArray[] = $row["nilai"];
                    }
                  echo "<strong>" . implode(" ", $nilaiArray) . "</strong>";
                  } else {
                echo "<strong>0</strong>";
                  }
              ?>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="mt-3"><br>
        <p><strong>Catatan:</strong></p>
      </div>
    </div>
  </div>

</body>
</html>
