<?php
// Include connection file
require 'includes/connection.php';

// Fungsi untuk mendapatkan data statistik dari kedua tabel
function getDashboardStats($koneksi)
{
  $stats = [];

  // 1. Total Peserta (dari jadwal_tanding dan jadwal_tanding_final)
  $query1 = "SELECT (
              SELECT COUNT(DISTINCT nm_biru) + COUNT(DISTINCT nm_merah) 
              FROM jadwal_tanding 
              WHERE nm_biru NOT LIKE 'Pemenang%' AND nm_merah NOT LIKE 'Pemenang%'
            ) + (
              SELECT COUNT(DISTINCT nm_biru) + COUNT(DISTINCT nm_merah) 
              FROM jadwal_tanding_final 
              WHERE nm_biru NOT LIKE 'Pemenang%' AND nm_merah NOT LIKE 'Pemenang%'
            ) as total_peserta";
  $result1 = $koneksi->query($query1);
  $stats['total_peserta'] = $result1->fetch_assoc()['total_peserta'] ?? 0;

  // 2. Total Partai dari kedua tabel
  $query2 = "SELECT (
              SELECT COUNT(*) FROM jadwal_tanding
            ) + (
              SELECT COUNT(*) FROM jadwal_tanding_final
            ) as total_partai";
  $result2 = $koneksi->query($query2);
  $stats['total_partai'] = $result2->fetch_assoc()['total_partai'] ?? 0;

  // 3. Partai Selesai (status bukan '-') dari kedua tabel
  $query3 = "SELECT (
              SELECT COUNT(*) FROM jadwal_tanding WHERE status = 'selesai'
            ) + (
              SELECT COUNT(*) FROM jadwal_tanding_final WHERE status = 'selesai'
            ) as partai_selesai";
  $result3 = $koneksi->query($query3);
  $stats['partai_selesai'] = $result3->fetch_assoc()['partai_selesai'] ?? 0;

  // 4. Total Medali
  $query4 = "SELECT COUNT(*) as total_medali FROM medali WHERE medali IS NOT NULL";
  $result4 = $koneksi->query($query4);
  $stats['total_medali'] = $result4->fetch_assoc()['total_medali'] ?? 0;

  // 5. Distribusi Medali
  $query5 = "SELECT 
                SUM(CASE WHEN medali = 'Emas' THEN 1 ELSE 0 END) as emas,
                SUM(CASE WHEN medali = 'Perak' THEN 1 ELSE 0 END) as perak,
                SUM(CASE WHEN medali = 'Perunggu' THEN 1 ELSE 0 END) as perunggu,
                SUM(CASE WHEN medali IS NULL OR medali = '' THEN 1 ELSE 0 END) as belum_ditentukan
               FROM medali";
  $result5 = $koneksi->query($query5);
  $stats['medali_distribusi'] = $result5->fetch_assoc();

  // 6. Top Kontingen dari kedua tabel
  $query6 = "SELECT 
                kontingen,
                COUNT(*) as jumlah_peserta
               FROM (
                 SELECT kontingen_biru as kontingen FROM jadwal_tanding
                 UNION ALL
                 SELECT kontingen_merah as kontingen FROM jadwal_tanding
                 UNION ALL
                 SELECT kontingen_biru as kontingen FROM jadwal_tanding_final
                 UNION ALL
                 SELECT kontingen_merah as kontingen FROM jadwal_tanding_final
               ) as all_kontingen
               WHERE kontingen != ''
               GROUP BY kontingen
               ORDER BY jumlah_peserta DESC
               LIMIT 5";
  $result6 = $koneksi->query($query6);
  $stats['top_kontingen'] = [];
  while ($row = $result6->fetch_assoc()) {
    $stats['top_kontingen'][] = $row;
  }

  // 7. Partai Hari Ini dari kedua tabel
  $query7 = "SELECT (
              SELECT COUNT(*) FROM jadwal_tanding WHERE status = 'selesai'
            ) + (
              SELECT COUNT(*) FROM jadwal_tanding_final WHERE status = 'selesai'
            ) as partai_hari_ini";
  $result7 = $koneksi->query($query7);
  $stats['partai_hari_ini'] = $result7->fetch_assoc()['partai_hari_ini'] ?? 0;

  // 8. Partai Mendatang dari kedua tabel
  $query8 = "SELECT (
              SELECT COUNT(*) FROM jadwal_tanding WHERE tgl > CURDATE()
            ) + (
              SELECT COUNT(*) FROM jadwal_tanding_final WHERE tgl > CURDATE()
            ) as partai_mendatang";
  $result8 = $koneksi->query($query8);
  $stats['partai_mendatang'] = $result8->fetch_assoc()['partai_mendatang'] ?? 0;

  // 9. Progress Pertandingan
  if ($stats['total_partai'] > 0) {
    $stats['progress'] = round(($stats['partai_selesai'] / $stats['total_partai']) * 100, 2);
  } else {
    $stats['progress'] = 0;
  }

  return $stats;
}

// Fungsi untuk mendapatkan jadwal SEMIFINAL terbaru
function getSemifinalMatches($koneksi)
{
  $query = "SELECT 
                partai,
                kelas,
                nm_biru,
                nm_merah,
                status,
                tgl,
                DATE_FORMAT(tgl, '%d %b %Y') as tanggal
              FROM jadwal_tanding WHERE status != 'selesai'
              ORDER BY tgl DESC, partai ASC
              LIMIT 5";

  $result = $koneksi->query($query);

  $matches = [];
  while ($row = $result->fetch_assoc()) {
    $matches[] = $row;
  }
  return $matches;
}

// Fungsi untuk mendapatkan jadwal FINAL terbaru
function getFinalMatches($koneksi)
{
  $query = "SELECT 
                partai,
                kelas,
                nm_biru,
                nm_merah,
                status,
                tgl,
                DATE_FORMAT(tgl, '%d %b %Y') as tanggal
              FROM jadwal_tanding_final WHERE status != 'selesai'
              ORDER BY tgl DESC, partai ASC
              LIMIT 5";

  $result = $koneksi->query($query);

  $matches = [];
  while ($row = $result->fetch_assoc()) {
    $matches[] = $row;
  }
  return $matches;
}

// Fungsi untuk mendapatkan peringkat kontingen dari kedua tabel
// Fungsi untuk mendapatkan peringkat kontingen dari kedua tabel - DIREVISI
function getKontingenRanking($koneksi)
{
  $query = "SELECT 
                k.kontingen,
                k.total_peserta,
                COALESCE(m.total_medali, 0) as total_medali,
                COALESCE(m.emas, 0) as emas,
                COALESCE(m.perak, 0) as perak,
                COALESCE(m.perunggu, 0) as perunggu
              FROM (
                -- Hitung total peserta per kontingen (hapus kontingen kosong)
                SELECT 
                  kontingen,
                  COUNT(*) as total_peserta
                FROM (
                  SELECT kontingen_biru as kontingen FROM jadwal_tanding WHERE kontingen_biru != '' AND kontingen_biru != '-'
                  UNION ALL
                  SELECT kontingen_merah as kontingen FROM jadwal_tanding WHERE kontingen_merah != '' AND kontingen_merah != '-'
                  UNION ALL
                  SELECT kontingen_biru as kontingen FROM jadwal_tanding_final WHERE kontingen_biru != '' AND kontingen_biru != '-'
                  UNION ALL
                  SELECT kontingen_merah as kontingen FROM jadwal_tanding_final WHERE kontingen_merah != '' AND kontingen_merah != '-'
                ) as all_kontingen
                WHERE kontingen IS NOT NULL AND kontingen != ''
                GROUP BY kontingen
              ) as k
              LEFT JOIN (
                -- Hitung medali per kontingen (sesuaikan dengan nilai medali sebenarnya)
                SELECT 
                  kontingen,
                  COUNT(*) as total_medali,
                  SUM(CASE WHEN medali = 'Emas' THEN 1 ELSE 0 END) as emas,
                  SUM(CASE WHEN medali = 'Perak' THEN 1 ELSE 0 END) as perak,
                  SUM(CASE WHEN medali = 'Perunggu' THEN 1 ELSE 0 END) as perunggu
                FROM medali 
                WHERE medali IS NOT NULL 
                  AND medali != '' 
                  AND kontingen IS NOT NULL 
                  AND kontingen != ''
                GROUP BY kontingen
              ) as m ON k.kontingen = m.kontingen
              ORDER BY 
                total_medali DESC, 
                emas DESC, 
                perak DESC, 
                perunggu DESC,
                total_peserta DESC";

  $result = $koneksi->query($query);

  $ranking = [];
  $rank = 1;
  while ($row = $result->fetch_assoc()) {
    $row['rank'] = $rank++;

    // Filter kontingen yang valid
    if (!empty($row['kontingen']) && $row['kontingen'] != '-' && $row['kontingen'] != '') {
      $ranking[] = $row;
    }
  }
  return $ranking;
}

// Ambil data dari database
$stats = getDashboardStats($koneksi);
$semifinalMatches = getSemifinalMatches($koneksi);
$finalMatches = getFinalMatches($koneksi);
$kontingenRanking = getKontingenRanking($koneksi);

// Data untuk grafik medali
$medaliEmas = $stats['medali_distribusi']['emas'] ?? 0;
$medaliPerak = $stats['medali_distribusi']['perak'] ?? 0;
$medaliPerunggu = $stats['medali_distribusi']['perunggu'] ?? 0;
$belumDitentukan = $stats['medali_distribusi']['belum_ditentukan'] ?? 0;
?>

<div class="row">
  <!-- Statistik Utama -->
  <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-9">
            <div class="d-flex align-items-center align-self-start">
              <h3 class="mb-0"><?php echo $stats['total_peserta']; ?></h3>
              <p class="text-success ms-2 mb-0 font-weight-medium">+<?php echo $stats['partai_hari_ini']; ?> partai hari ini</p>
            </div>
          </div>
          <div class="col-3">
            <div class="icon icon-box-success">
              <span class="mdi mdi-account-group icon-item"></span>
            </div>
          </div>
        </div>
        <h6 class="text-muted font-weight-normal">Sisa Partai</h6>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-9">
            <div class="d-flex align-items-center align-self-start">
              <h3 class="mb-0"><?php echo $stats['total_partai']; ?></h3>
              <p class="text-info ms-2 mb-0 font-weight-medium">Partai</p>
            </div>
          </div>
          <div class="col-3">
            <div class="icon icon-box-info">
              <span class="mdi mdi-calendar-check icon-item"></span>
            </div>
          </div>
        </div>
        <h6 class="text-muted font-weight-normal">Total Partai</h6>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-9">
            <div class="d-flex align-items-center align-self-start">
              <h3 class="mb-0"><?php echo $stats['partai_selesai']; ?></h3>
              <p class="<?php echo ($stats['progress'] > 0) ? 'text-success' : 'text-warning'; ?> ms-2 mb-0 font-weight-medium">
                <?php echo $stats['progress']; ?>%
              </p>
            </div>
          </div>
          <div class="col-3">
            <div class="icon icon-box-<?php echo ($stats['progress'] > 0) ? 'success' : 'warning'; ?>">
              <span class="mdi mdi-check-circle icon-item"></span>
            </div>
          </div>
        </div>
        <h6 class="text-muted font-weight-normal">Partai Selesai</h6>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-9">
            <div class="d-flex align-items-center align-self-start">
              <h3 class="mb-0"><?php echo $stats['total_medali']; ?></h3>
              <p class="text-primary ms-2 mb-0 font-weight-medium">
                Medali
              </p>
            </div>
          </div>
          <div class="col-3">
            <div class="icon icon-box-primary">
              <span class="mdi mdi-medal icon-item"></span>
            </div>
          </div>
        </div>
        <h6 class="text-muted font-weight-normal">Total Medaldsadsai</h6>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Grafik Distribusi Medali -->
  <div class="col-md-4 grid-margin stretch-card">
    <div class="card">
      <div class="card-body" style="position: relative; overflow: visible;">
        <h4 class="card-title">Distribusi Medali</h4>

        <!-- Container untuk chart dengan fixed height -->
        <div style="position: relative; height: 250px; margin: 0 auto;">
          <canvas id="medaliChart" style="display: block;"></canvas>
        </div>

        <!-- Statistik Medali dalam Teks -->
        <div class="row mt-4">
          <div class="col-6 mb-3">
            <div class="d-flex align-items-center">
              <div class="rounded-circle p-2 me-3" style="background-color: #FFD700; width: 20px; height: 20px;"></div>
              <div>
                <h6 class="mb-0">Emas</h6>
                <p class="text-muted mb-0"><?php echo $medaliEmas; ?> medali</p>
              </div>
            </div>
          </div>
          <div class="col-6 mb-3">
            <div class="d-flex align-items-center">
              <div class="rounded-circle p-2 me-3" style="background-color: #C0C0C0; width: 20px; height: 20px;"></div>
              <div>
                <h6 class="mb-0">Perak</h6>
                <p class="text-muted mb-0"><?php echo $medaliPerak; ?> medali</p>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="d-flex align-items-center">
              <div class="rounded-circle p-2 me-3" style="background-color: #CD7F32; width: 20px; height: 20px;"></div>
              <div>
                <h6 class="mb-0">Perunggu</h6>
                <p class="text-muted mb-0"><?php echo $medaliPerunggu; ?> medali</p>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="d-flex align-items-center">
              <div class="rounded-circle p-2 me-3" style="background-color: #6c757d; width: 20px; height: 20px;"></div>
              <div>
                <h6 class="mb-0">Belum Ditentukan</h6>
                <p class="text-muted mb-0"><?php echo $belumDitentukan; ?> medali</p>
              </div>
            </div>
          </div>
        </div>
        <!-- Ringkasan Total -->
        <div class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">
          <div class="text-md-center text-xl-left">
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Jadwal Partai Terbaru dengan Tab -->
  <div class="col-md-8 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex flex-row justify-content-between">
          <h4 class="card-title mb-1">Jadwal 5 Partai Terbaru</h4>
        </div>

        <!-- Tab Navigation -->
        <ul class="nav nav-tabs" id="jadwalTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="semifinal-tab" data-bs-toggle="tab" href="#semifinal" role="tab" aria-controls="semifinal" aria-selected="true">
              <i class="mdi mdi-sword-cross me-1"></i> SEMIFINAL
              <span class="badge bg-primary ms-2"><?php echo count($semifinalMatches); ?></span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="final-tab" data-bs-toggle="tab" href="#final" role="tab" aria-controls="final" aria-selected="false">
              <i class="mdi mdi-trophy me-1"></i> FINAL
              <span class="badge bg-warning ms-2"><?php echo count($finalMatches); ?></span>
            </a>
          </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content pt-3" id="jadwalTabContent">
          <!-- Tab SEMIFINAL -->
          <div class="tab-pane fade show active" id="semifinal" role="tabpanel" aria-labelledby="semifinal-tab">
            <div class="preview-list">
              <?php if (empty($semifinalMatches)): ?>
                <div class="text-center py-4">
                  <p class="text-muted">Tidak ada jadwal semifinal</p>
                </div>
              <?php else: ?>
                <?php foreach ($semifinalMatches as $match): ?>
                  <div class="preview-item border-bottom">
                    <div class="preview-thumbnail">
                      <div class="preview-icon <?php echo ($match['status'] != '-') ? 'bg-success' : 'bg-info'; ?>">
                        <i class="mdi mdi-sword-cross"></i>
                      </div>
                    </div>
                    <div class="preview-item-content d-sm-flex flex-grow">
                      <div class="flex-grow">
                        <h6 class="preview-subject">Partai <?php echo htmlspecialchars($match['partai']); ?> - <?php echo htmlspecialchars($match['kelas']); ?></h6>
                        <p class="text-muted mb-0">
                          <span class="fw-bold text-primary"><?php echo htmlspecialchars($match['nm_biru']); ?></span>
                          vs
                          <span class="fw-bold text-danger"><?php echo htmlspecialchars($match['nm_merah']); ?></span>
                        </p>
                      </div>
                      <div class="me-auto text-sm-right pt-2 pt-sm-0">
                        <p class="text-muted mb-1"><?php echo $match['tanggal']; ?></p>
                        <p class="text-muted mb-0">
                          <span class="badge badge-<?php echo ($match['status'] != '-') ? 'success' : 'warning'; ?>">
                            <?php echo ($match['status'] != '-') ? 'Selesai' : 'Menunggu'; ?>
                          </span>
                        </p>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
                <?php if (count($semifinalMatches) > 0): ?>
                  <div class="text-center mt-3">
                    <!-- <a href="jadwal_semifinal.php" class="btn btn-outline-primary btn-sm">Lihat Semua Jadwal Semifinal</a> -->
                  </div>
                <?php endif; ?>
              <?php endif; ?>
            </div>
          </div>

          <!-- Tab FINAL -->
          <div class="tab-pane fade" id="final" role="tabpanel" aria-labelledby="final-tab">
            <div class="preview-list">
              <?php if (empty($finalMatches)): ?>
                <div class="text-center py-4">
                  <p class="text-muted">Tidak ada jadwal final</p>
                </div>
              <?php else: ?>
                <?php foreach ($finalMatches as $match): ?>
                  <div class="preview-item border-bottom">
                    <div class="preview-thumbnail">
                      <div class="preview-icon bg-warning">
                        <i class="mdi mdi-trophy"></i>
                      </div>
                    </div>
                    <div class="preview-item-content d-sm-flex flex-grow">
                      <div class="flex-grow">
                        <h6 class="preview-subject">
                          <span class="badge badge-warning me-2">FINAL</span>
                          Partai <?php echo htmlspecialchars($match['partai']); ?> - <?php echo htmlspecialchars($match['kelas']); ?>
                        </h6>
                        <p class="text-muted mb-0">
                          <span class="fw-bold text-primary"><?php echo htmlspecialchars($match['nm_biru']); ?></span>
                          vs
                          <span class="fw-bold text-danger"><?php echo htmlspecialchars($match['nm_merah']); ?></span>
                        </p>
                      </div>
                      <div class="me-auto text-sm-right pt-2 pt-sm-0">
                        <p class="text-muted mb-1"><?php echo $match['tanggal']; ?></p>
                        <p class="text-muted mb-0">
                          <span class="badge badge-<?php echo ($match['status'] != '-') ? 'success' : 'warning'; ?>">
                            <?php echo ($match['status'] != '-') ? 'Selesai' : 'Menunggu'; ?>
                          </span>
                        </p>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
                <?php if (count($finalMatches) > 0): ?>
                  <div class="text-center mt-3">
                    <!-- <a href="jadwal_final.php" class="btn btn-outline-warning btn-sm">Lihat Semua Jadwal Final</a> -->
                  </div>
                <?php endif; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Progress Pertandingan -->
  <div class="col-sm-4 grid-margin">
    <div class="card">
      <div class="card-body">
        <h5>Progress Pertandingan</h5>
        <div class="row">
          <div class="col-8 col-sm-12 col-xl-8 my-auto">
            <div class="d-flex d-sm-block d-md-flex align-items-center">
              <h2 class="mb-0"><?php echo $stats['progress']; ?>%</h2>
              <p class="text-success ms-2 mb-0 font-weight-medium"><?php echo $stats['partai_selesai']; ?> dari <?php echo $stats['total_partai']; ?></p>
            </div>
            <h6 class="text-muted font-weight-normal">
              <?php echo $stats['partai_hari_ini']; ?> partai hari ini
            </h6>
          </div>
          <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
            <i class="icon-lg mdi mdi-chart-line text-primary ms-auto"></i>
          </div>
        </div>
        <div class="progress mt-3">
          <div class="progress-bar bg-success" role="progressbar"
            style="width: <?php echo $stats['progress']; ?>%"
            aria-valuenow="<?php echo $stats['progress']; ?>"
            aria-valuemin="0"
            aria-valuemax="100"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Top Kontingen -->
  <div class="col-sm-4 grid-margin">
    <div class="card">
      <div class="card-body">
        <h5>Top Kontingen</h5>
        <div class="row">
          <div class="col-8 col-sm-12 col-xl-8 my-auto">
            <?php if (!empty($stats['top_kontingen'])): ?>
              <?php foreach ($stats['top_kontingen'] as $index => $kontingen): ?>
                <div class="d-flex justify-content-between mb-2">
                  <div class="d-flex align-items-center">
                    <span class="badge badge-<?php echo ($index == 0) ? 'warning' : (($index == 1) ? 'secondary' : (($index == 2) ? 'danger' : 'info')); ?> me-2">
                      <?php echo $index + 1; ?>
                    </span>
                    <h6 class="mb-0"><?php echo htmlspecialchars($kontingen['kontingen']); ?></h6>
                  </div>
                  <p class="text-success ms-2 mb-0 font-weight-medium"><?php echo $kontingen['jumlah_peserta']; ?> peserta</p>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="text-muted">Tidak ada data kontingen</p>
            <?php endif; ?>
          </div>
          <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
            <i class="icon-lg mdi mdi-flag text-danger ms-auto"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Ringkasan Medali -->
  <div class="col-sm-4 grid-margin">
    <div class="card">
      <div class="card-body">
        <h5>Ringkasan Medali</h5>
        <div class="row">
          <div class="col-8 col-sm-12 col-xl-8 my-auto">
            <div class="d-flex justify-content-between mb-2">
              <div class="d-flex align-items-center">
                <span class="mdi mdi-medal text-warning me-2"></span>
                <h6 class="mb-0">Emas</h6>
              </div>
              <p class="text-warning ms-2 mb-0 font-weight-bold"><?php echo $medaliEmas; ?></p>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <div class="d-flex align-items-center">
                <span class="mdi mdi-medal text-secondary me-2"></span>
                <h6 class="mb-0">Perak</h6>
              </div>
              <p class="text-secondary ms-2 mb-0 font-weight-bold"><?php echo $medaliPerak; ?></p>
            </div>
            <div class="d-flex justify-content-between">
              <div class="d-flex align-items-center">
                <span class="mdi mdi-medal text-danger me-2"></span>
                <h6 class="mb-0">Perunggu</h6>
              </div>
              <p class="text-danger ms-2 mb-0 font-weight-bold"><?php echo $medaliPerunggu; ?></p>
            </div>
          </div>
          <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
            <i class="icon-lg mdi mdi-medal-outline text-success ms-auto"></i>
          </div>
        </div>
        <div class="mt-3 pt-3 border-top">
          <div class="d-flex justify-content-between">
            <h6 class="mb-0">Total Medali</h6>
            <p class="text-success mb-0 font-weight-bold"><?php echo $stats['total_medali']; ?></p>
          </div>
          <p class="text-muted mb-0 small"><?php echo $belumDitentukan; ?> medali belum ditentukan</p>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Peringkat Kontingen -->
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Peringkat Kontingen</h4>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Peringkat</th>
                <th>Nama Kontingen</th>
                <th>Jumlah Peserta</th>
                <th>Total Medali</th>
                <th class="text-warning">Emas</th>
                <th class="text-secondary">Perak</th>
                <th class="text-danger">Perunggu</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($kontingenRanking)): ?>
                <tr>
                  <td colspan="8" class="text-center">Tidak ada data peringkat</td>
                </tr>
              <?php else: ?>
                <?php foreach ($kontingenRanking as $ranking): ?>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <?php if ($ranking['rank'] <= 3): ?>
                          <span class="mdi mdi-trophy me-2 
                            <?php echo ($ranking['rank'] == 1) ? 'text-warning' : (($ranking['rank'] == 2) ? 'text-secondary' : 'text-danger'); ?>">
                          </span>
                        <?php endif; ?>
                        <span class="fw-bold"><?php echo $ranking['rank']; ?></span>
                      </div>
                    </td>
                    <td>
                      <span class="ps-2 fw-bold"><?php echo htmlspecialchars($ranking['kontingen']); ?></span>
                    </td>
                    <td>
                      <span class="badge badge-info"><?php echo $ranking['total_peserta']; ?></span>
                    </td>
                    <td>
                      <span class="fw-bold"><?php echo $ranking['total_medali']; ?></span>
                    </td>
                    <td class="text-warning fw-bold">
                      <?php echo $ranking['emas']; ?>
                    </td>
                    <td class="text-secondary">
                      <?php echo isset($ranking['perak']) ? $ranking['perak'] : '0'; ?>
                    </td>
                    <td class="text-danger">
                      <?php echo isset($ranking['perunggu']) ? $ranking['perunggu'] : '0'; ?>
                    </td>
                    <td>
                      <div class="badge badge-outline-<?php echo ($ranking['rank'] == 1) ? 'warning' : (($ranking['rank'] == 2) ? 'secondary' : (($ranking['rank'] == 3) ? 'danger' : 'success')); ?>">
                        <?php if ($ranking['rank'] == 1): ?>
                          Juara 1
                        <?php elseif ($ranking['rank'] == 2): ?>
                          Juara 2
                        <?php elseif ($ranking['rank'] == 3): ?>
                          Juara 3
                        <?php else: ?>
                          Peringkat <?php echo $ranking['rank']; ?>
                        <?php endif; ?>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Inisialisasi Bootstrap tabs
  document.addEventListener('DOMContentLoaded', function() {
    var triggerTabList = [].slice.call(document.querySelectorAll('#jadwalTab a'))
    triggerTabList.forEach(function(triggerEl) {
      var tabTrigger = new bootstrap.Tab(triggerEl)
      triggerEl.addEventListener('click', function(event) {
        event.preventDefault()
        tabTrigger.show()
      })
    });
  });

  // Chart.js untuk Distribusi Medali
  // Tunggu sampai DOM sepenuhnya dimuat
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeChart);
  } else {
    initializeChart();
  }

  function initializeChart() {
    var canvas = document.getElementById('medaliChart');
    if (!canvas) return;

    var ctx = canvas.getContext('2d');

    // Cek apakah chart sudah ada sebelumnya, jika ya hancurkan
    if (window.medaliChartInstance) {
      window.medaliChartInstance.destroy();
    }

    window.medaliChartInstance = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Emas', 'Perak', 'Perunggu', 'Belum Ditentukan'],
        datasets: [{
          data: [
            <?php echo $medaliEmas; ?>,
            <?php echo $medaliPerak; ?>,
            <?php echo $medaliPerunggu; ?>,
            <?php echo $belumDitentukan; ?>
          ],
          backgroundColor: [
            '#FFD700',
            '#C0C0C0',
            '#CD7F32',
            '#6c757d'
          ],
          borderWidth: 2,
          borderColor: '#fff'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false, // Nonaktifkan legend karena sudah ada di teks
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                let label = context.label || '';
                let value = context.raw || 0;
                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                let percentage = Math.round((value / total) * 100);
                return `${label}: ${value} medali (${percentage}%)`;
              }
            }
          }
        },
        cutout: '65%',
        animation: {
          animateScale: true,
          animateRotate: true
        }
      }
    });
  }

  // Pastikan chart di-redraw saat window di-resize
  let resizeTimer;
  window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function() {
      if (window.medaliChartInstance) {
        window.medaliChartInstance.resize();
      }
    }, 250);
  });
</script>

<!-- Include Bootstrap JS untuk tabs -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>