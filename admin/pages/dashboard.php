<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
              WHERE nm_biru NOT LIKE 'Pemenang%' AND nm_merah NOT LIKE 'Pemenang%' AND babak='SEMIFINAL'
            ) + (
              SELECT COUNT(DISTINCT nm_biru) + COUNT(DISTINCT nm_merah) 
              FROM jadwal_tanding
              WHERE nm_biru NOT LIKE 'Pemenang%' AND nm_merah NOT LIKE 'Pemenang%' AND babak='FINAL'
            ) as total_peserta";
  $result1 = $koneksi->query($query1);
  $stats['total_peserta'] = $result1->fetch_assoc()['total_peserta'] ?? 0;

  // 2. Total Partai dari kedua tabel
  $query2 = "SELECT (
              SELECT COUNT(*) FROM jadwal_tanding WHERE babak='SEMIFINAL'
            ) + (
              SELECT COUNT(*) FROM jadwal_tanding WHERE babak='FINAL'
            ) as total_partai";
  $result2 = $koneksi->query($query2);
  $stats['total_partai'] = $result2->fetch_assoc()['total_partai'] ?? 0;

  // Total partai TGR
  $querytgr = "SELECT (
              SELECT COUNT(*) FROM jadwal_tgr WHERE babak='SEMIFINAL'
            ) + (
              SELECT COUNT(*) FROM jadwal_tgr WHERE babak='FINAL'
            ) as total_partaitgr";
  $resulttgr = $koneksi->query($querytgr);
  $stats['total_partaitgr'] = $resulttgr->fetch_assoc()['total_partaitgr'] ?? 0;

  // 3. Partai Selesai (status bukan '-') dari kedua tabel
  $query3 = "SELECT (
              SELECT COUNT(*) FROM jadwal_tanding WHERE status = 'selesai' and babak='SEMIFINAL'
            ) + (
              SELECT COUNT(*) FROM jadwal_tanding WHERE status = 'selesai' and babak='FINAL'
            ) as partai_selesai";
  $result3 = $koneksi->query($query3);
  $stats['partai_selesai'] = $result3->fetch_assoc()['partai_selesai'] ?? 0;

  // Partai selesai TGR
  $querytgr1 = "SELECT (
              SELECT COUNT(*) FROM jadwal_tgr WHERE status = 'selesai' and babak='SEMIFINAL'
            ) + (
              SELECT COUNT(*) FROM jadwal_tgr WHERE status = 'selesai' and babak='FINAL'
            ) as partai_selesaitgr1";
  $resulttgr1 = $koneksi->query($querytgr1);
  $stats['partai_selesaitgr1'] = $resulttgr1->fetch_assoc()['partai_selesaitgr1'] ?? 0;

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
              SELECT COUNT(*) FROM jadwal_tanding WHERE status = 'selesai' and babak='SEMIFINAL'
            ) + (
              SELECT COUNT(*) FROM jadwal_tanding WHERE status = 'selesai' and babak='FINAL'
            ) as partai_hari_ini";
  $result7 = $koneksi->query($query7);
  $stats['partai_hari_ini'] = $result7->fetch_assoc()['partai_hari_ini'] ?? 0;

  // 8. Partai Mendatang dari kedua tabel
  $query8 = "SELECT (
              SELECT COUNT(*) FROM jadwal_tanding WHERE tgl > CURDATE() and babak='SEMIFINAL'
            ) + (
              SELECT COUNT(*) FROM jadwal_tanding WHERE tgl > CURDATE() and babak='FINAL'
            ) as partai_mendatang";
  $result8 = $koneksi->query($query8);
  $stats['partai_mendatang'] = $result8->fetch_assoc()['partai_mendatang'] ?? 0;

  // 9. Progress Pertandingan
  if ($stats['total_partai'] > 0) {
    $stats['progress'] = round(($stats['partai_selesai'] / $stats['total_partai']) * 100, 2);
  } else {
    $stats['progress'] = 0;
  }

  if ($stats['total_partaitgr'] > 0) {
    $stats['progresstgr'] = round(($stats['partai_selesaitgr1'] / $stats['total_partaitgr']) * 100, 2);
  } else {
    $stats['progresstgr'] = 0;
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
              FROM jadwal_tanding WHERE status != 'selesai' and babak='SEMIFINAL'
              ORDER BY tgl DESC, partai ASC
              LIMIT 5";

  $result = $koneksi->query($query);

  $matches = [];
  while ($row = $result->fetch_assoc()) {
    $matches[] = $row;
  }
  return $matches;
}

function getSemifinalMatchestgr($koneksi)
{
  $query = "SELECT 
                partai,
                kategori,
                golongan,
                nm_biru,
                nm_merah,
                status,
                tgl,
                DATE_FORMAT(tgl, '%d %b %Y') as tanggal
              FROM jadwal_tgr WHERE status != 'selesai' and babak='SEMIFINAL'
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
              FROM jadwal_tanding WHERE status != 'selesai' and babak='FINAL'
              ORDER BY tgl DESC, partai ASC
              LIMIT 5";

  $result = $koneksi->query($query);

  $matches = [];
  while ($row = $result->fetch_assoc()) {
    $matches[] = $row;
  }
  return $matches;
}

function getFinalMatchestgr($koneksi)
{
  $query = "SELECT 
                partai,
                kategori,
                golongan,
                nm_biru,
                nm_merah,
                status,
                tgl,
                DATE_FORMAT(tgl, '%d %b %Y') as tanggal
              FROM jadwal_tgr WHERE status != 'selesai' and babak='FINAL'
              ORDER BY tgl DESC, partai ASC
              LIMIT 5";

  $result = $koneksi->query($query);

  $matches = [];
  while ($row = $result->fetch_assoc()) {
    $matches[] = $row;
  }
  return $matches;
}

// ==================== FUNGSI RANKING KONTINGEN DENGAN POIN ====================
// Ranking berdasarkan poin: Emas=100, Perak=75, Perunggu=50
function getKontingenRanking($koneksi)
{
  $query = "SELECT 
                k.kontingen,
                k.total_peserta,
                COALESCE(m.total_medali, 0) as total_medali,
                COALESCE(m.emas, 0) as emas,
                COALESCE(m.perak, 0) as perak,
                COALESCE(m.perunggu, 0) as perunggu,
                COALESCE(m.total_poin, 0) as total_poin
              FROM (
                -- Hitung total peserta per kontingen
                SELECT 
                  kontingen,
                  COUNT(*) as total_peserta
                FROM (
                  SELECT kontingen_biru as kontingen FROM jadwal_tanding WHERE kontingen_biru != '' AND kontingen_biru != '-'
                  UNION ALL
                  SELECT kontingen_merah as kontingen FROM jadwal_tanding WHERE kontingen_merah != '' AND kontingen_merah != '-'
                ) as all_kontingen
                WHERE kontingen IS NOT NULL AND kontingen != ''
                GROUP BY kontingen
              ) as k
              LEFT JOIN (
                -- Hitung medali per kontingen dengan POIN
                SELECT 
                  kontingen,
                  COUNT(*) as total_medali,
                  SUM(CASE WHEN medali = 'Emas' THEN 1 ELSE 0 END) as emas,
                  SUM(CASE WHEN medali = 'Perak' THEN 1 ELSE 0 END) as perak,
                  SUM(CASE WHEN medali = 'Perunggu' THEN 1 ELSE 0 END) as perunggu,
                  (SUM(CASE WHEN medali = 'Emas' THEN 100 ELSE 0 END) +
                   SUM(CASE WHEN medali = 'Perak' THEN 75 ELSE 0 END) +
                   SUM(CASE WHEN medali = 'Perunggu' THEN 50 ELSE 0 END)) as total_poin
                FROM medali 
                WHERE medali IS NOT NULL 
                  AND medali != '' 
                  AND kontingen IS NOT NULL 
                  AND kontingen != ''
                GROUP BY kontingen
              ) as m ON k.kontingen = m.kontingen
              ORDER BY 
                total_poin DESC,      -- Urutkan berdasarkan poin tertinggi
                emas DESC,            -- Jika poin sama, berdasarkan emas terbanyak
                perak DESC,           -- Jika masih sama, berdasarkan perak terbanyak
                perunggu DESC,        -- Jika masih sama, berdasarkan perunggu terbanyak
                total_peserta DESC";

  $result = $koneksi->query($query);

  $ranking = [];
  $rank = 1;
  $prevPoin = null;
  $rankOffset = 0;

  while ($row = $result->fetch_assoc()) {
    // Handle peringkat yang sama (jika poin sama)
    if ($prevPoin === $row['total_poin']) {
      $rankOffset++;
      $displayRank = $rank - $rankOffset;
    } else {
      $rankOffset = 0;
      $displayRank = $rank;
    }
    $prevPoin = $row['total_poin'];

    $row['rank'] = $displayRank;
    $row['rank_number'] = $rank; // Untuk keperluan internal

    // Filter kontingen yang valid
    if (!empty($row['kontingen']) && $row['kontingen'] != '-' && $row['kontingen'] != '') {
      $ranking[] = $row;
    }
    $rank++;
  }
  return $ranking;
}

// Ambil data dari database
$stats = getDashboardStats($koneksi);
$semifinalMatches = getSemifinalMatches($koneksi);
$finalMatches = getFinalMatches($koneksi);
$semifinalMatchestgr = getSemifinalMatchestgr($koneksi);
$finalMatchestgr = getFinalMatchestgr($koneksi);
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
        <h6 class="text-muted font-weight-normal">Total Peserta</h6>
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
              <p class="text-info ms-2 mb-0 font-weight-medium">Tanding</p>/
              <h3 class="mb-0"><?php echo $stats['total_partaitgr']; ?></h3>
              <p class="text-info ms-2 mb-0 font-weight-medium">TGR</p>
            </div>
          </div>
          <div class="col-3">
            <div class="icon icon-box-info">
              <span class="mdi mdi-calendar-check icon-item"></span>
            </div>
          </div>
        </div>
        <h6 class="text-muted font-weight-normal">Total Partai Tanding/TGR</h6>
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
              </p>/
              <h3 class="mb-0"><?php echo $stats['partai_selesaitgr1']; ?></h3>
              <p class="<?php echo ($stats['progresstgr'] > 0) ? 'text-success' : 'text-warning'; ?> ms-2 mb-0 font-weight-medium">
                <?php echo $stats['progresstgr']; ?>%
              </p>
            </div>
          </div>
          <div class="col-3">
            <div class="icon icon-box-<?php echo ($stats['progress'] > 0) ? 'success' : 'warning'; ?>">
              <span class="mdi mdi-check-circle icon-item"></span>
            </div>
          </div>
        </div>
        <h6 class="text-muted font-weight-normal">Partai Selesai Tanding/TGR</h6>
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
        <h6 class="text-muted font-weight-normal">Total Medali</h6>
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

  <!-- Jadwal Partai Terbaru TANDING -->
  <div class="col-md-4 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex flex-row justify-content-between">
          <h4 class="card-title mb-1">Jadwal Tanding 5 Partai Terbaru</h4>
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
                        <h6 class="preview-subject">Partai <?php echo htmlspecialchars($match['partai']); ?> <br> <?php echo htmlspecialchars($match['kelas']); ?></h6>
                      </div>
                      <div class="me-auto text-sm-right pt-2 pt-sm-0">
                        <p class="text-muted mb-0">
                          <span class="badge text-dark badge-<?php echo ($match['status'] != '-') ? 'success' : 'warning'; ?>">
                            <?php echo ($match['status'] != '-') ? 'Selesai' : 'Menunggu'; ?>
                          </span>
                        </p>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
                <?php if (count($semifinalMatches) > 0): ?>
                  <div class="text-center mt-3">
                    <a href="?page=tambah_jadwal_tanding" class="btn btn-outline-primary btn-sm">Lihat Semua Jadwal Tanding</a>
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
                          Partai <?php echo htmlspecialchars($match['partai']); ?> <br> <?php echo htmlspecialchars($match['kelas']); ?>
                        </h6>
                      </div>
                      <div class="me-auto text-sm-right pt-2 pt-sm-0">
                        <p class="text-muted mb-0">
                          <span class="badge text-dark badge-<?php echo ($match['status'] != '-') ? 'success' : 'warning'; ?>">
                            <?php echo ($match['status'] != '-') ? 'Selesai' : 'Menunggu'; ?>
                          </span>
                        </p>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
                <?php if (count($finalMatches) > 0): ?>
                  <div class="text-center mt-3">
                    <a href="?page=tambah_jadwal_tanding" class="btn btn-outline-warning btn-sm">Lihat Semua Jadwal Tanding</a>
                  </div>
                <?php endif; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Jadwal Partai Terbaru TGR -->
  <div class="col-md-4 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex flex-row justify-content-between">
          <h4 class="card-title mb-1">Jadwal TGR 5 Partai Terbaru</h4>
        </div>

        <!-- Tab Navigation -->
        <ul class="nav nav-tabs" id="jadwalTabTgr" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="semifinal-tab1" data-bs-toggle="tab" href="#semifinal1" role="tab" aria-controls="semifinal1" aria-selected="true">
              <i class="mdi mdi-sword-cross me-1"></i> SEMIFINAL
              <span class="badge bg-primary ms-2"><?php echo count($semifinalMatchestgr); ?></span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="final-tab1" data-bs-toggle="tab" href="#final1" role="tab" aria-controls="final1" aria-selected="false">
              <i class="mdi mdi-trophy me-1"></i> FINAL
              <span class="badge bg-warning ms-2"><?php echo count($finalMatchestgr); ?></span>
            </a>
          </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content pt-3" id="jadwalTabContentTgr">
          <!-- Tab SEMIFINAL -->
          <div class="tab-pane fade show active" id="semifinal1" role="tabpanel" aria-labelledby="semifinal-tab1">
            <div class="preview-list">
              <?php if (empty($semifinalMatchestgr)): ?>
                <div class="text-center py-4">
                  <p class="text-muted">Tidak ada jadwal semifinal</p>
                </div>
              <?php else: ?>
                <?php foreach ($semifinalMatchestgr as $match): ?>
                  <div class="preview-item border-bottom">
                    <div class="preview-thumbnail">
                      <div class="preview-icon <?php echo ($match['status'] != '-') ? 'bg-success' : 'bg-info'; ?>">
                        <i class="mdi mdi-sword-cross"></i>
                      </div>
                    </div>
                    <div class="preview-item-content d-sm-flex flex-grow">
                      <div class="flex-grow">
                        <h6 class="preview-subject">Partai <?php echo htmlspecialchars($match['partai']); ?> <br> <?php echo htmlspecialchars($match['kategori'] . " - " . $match['golongan']); ?></h6>
                      </div>
                      <div class="me-auto text-sm-right pt-2 pt-sm-0">
                        <p class="text-muted mb-0">
                          <span class="badge text-dark badge-<?php echo ($match['status'] != '-') ? 'success' : 'warning'; ?>">
                            <?php echo ($match['status'] != '-') ? 'Selesai' : 'Menunggu'; ?>
                          </span>
                        </p>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
                <?php if (count($semifinalMatchestgr) > 0): ?>
                  <div class="text-center mt-3">
                    <a href="?page=tambah_jadwal_tgr" class="btn btn-outline-primary btn-sm">Lihat Semua Jadwal TGR</a>
                  </div>
                <?php endif; ?>
              <?php endif; ?>
            </div>
          </div>

          <!-- Tab FINAL -->
          <div class="tab-pane fade" id="final1" role="tabpanel" aria-labelledby="final-tab1">
            <div class="preview-list">
              <?php if (empty($finalMatchestgr)): ?>
                <div class="text-center py-4">
                  <p class="text-muted">Tidak ada jadwal final</p>
                </div>
              <?php else: ?>
                <?php foreach ($finalMatchestgr as $match): ?>
                  <div class="preview-item border-bottom">
                    <div class="preview-thumbnail">
                      <div class="preview-icon bg-warning">
                        <i class="mdi mdi-trophy"></i>
                      </div>
                    </div>
                    <div class="preview-item-content d-sm-flex flex-grow">
                      <div class="flex-grow">
                        <h6 class="preview-subject">
                          Partai <?php echo htmlspecialchars($match['partai']); ?> <br> <?php echo htmlspecialchars($match['kategori'] . " - " . $match['golongan']); ?>
                        </h6>
                      </div>
                      <div class="me-auto text-sm-right pt-2 pt-sm-0">
                        <p class="text-muted mb-0">
                          <span class="badge text-dark badge-<?php echo ($match['status'] != '-') ? 'success' : 'warning'; ?>">
                            <?php echo ($match['status'] != '-') ? 'Selesai' : 'Menunggu'; ?>
                          </span>
                        </p>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
                <?php if (count($finalMatchestgr) > 0): ?>
                  <div class="text-center mt-3">
                    <a href="?page=tambah_jadwal_tgr" class="btn btn-outline-warning btn-sm">Lihat Semua Jadwal TGR</a>
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
        <h5>Top Kontingen (Terbanyak Peserta)</h5>
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
  <!-- ==================== PERINGKAT KONTINGEN BERDASARKAN POIN ==================== -->
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h4 class="card-title mb-0">🏆 Peringkat Kontingen (Berdasarkan Poin)</h4>
          <div class="alert alert-info py-2 px-3 mb-0">
            <small>📊 Poin: 🥇 Emas = 100 | 🥈 Perak = 75 | 🥉 Perunggu = 50</small>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="table-dark">
              <tr>
                <th width="5%">Peringkat</th>
                <th width="20%">Kontingen</th>
                <th width="10%" class="text-center">Jumlah Peserta</th>
                <th width="10%" class="text-center">Total Medali</th>
                <th width="10%" class="text-center text-warning">🥇 Emas</th>
                <th width="10%" class="text-center text-secondary">🥈 Perak</th>
                <th width="10%" class="text-center text-danger">🥉 Perunggu</th>
                <th width="10%" class="text-center">⭐ Total Poin</th>
                <th width="15%" class="text-center">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($kontingenRanking)): ?>
                <tr>
                  <td colspan="9" class="text-center py-4">
                    <i class="mdi mdi-emoticon-sad fs-1"></i>
                    <p class="mt-2">Belum ada data peringkat kontingen</p>
                  </td>
                </tr>
              <?php else: ?>
                <?php
                $rankColors = ['🥇', '🥈', '🥉'];
                foreach ($kontingenRanking as $index => $ranking):
                  $isTop3 = $ranking['rank'] <= 3;
                  $rowClass = '';
                  if ($ranking['rank'] == 1) $rowClass = 'table-warning';
                  elseif ($ranking['rank'] == 2) $rowClass = 'table-secondary';
                  elseif ($ranking['rank'] == 3) $rowClass = 'table-danger';
                ?>
                  <tr class="<?php echo $rowClass; ?>">
                    <td class="text-center">
                      <?php if ($isTop3): ?>
                        <span class="fs-2">
                          <?php echo $rankColors[$ranking['rank'] - 1]; ?>
                        </span>
                        <br>
                        <span class="badge bg-<?php echo ($ranking['rank'] == 1) ? 'warning' : (($ranking['rank'] == 2) ? 'secondary' : 'danger'); ?> text-dark">
                          #<?php echo $ranking['rank']; ?>
                        </span>
                      <?php else: ?>
                        <span class="badge bg-dark">
                          #<?php echo $ranking['rank']; ?>
                        </span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <div class="d-flex align-items-center">
                        <?php if ($isTop3): ?>
                          <span class="mdi mdi-trophy fs-4 me-2 
                            <?php echo ($ranking['rank'] == 1) ? 'text-warning' : (($ranking['rank'] == 2) ? 'text-secondary' : 'text-danger'); ?>">
                          </span>
                        <?php endif; ?>
                        <span class="fw-bold fs-5"><?php echo htmlspecialchars($ranking['kontingen']); ?></span>
                      </div>
                    </td>
                    <td class="text-center">
                      <span class="badge bg-info text-dark px-3 py-2">
                        <i class="mdi mdi-account"></i> <?php echo $ranking['total_peserta']; ?>
                      </span>
                    </td>
                    <td class="text-center">
                      <span class="badge bg-primary px-3 py-2">
                        <i class="mdi mdi-medal"></i> <?php echo $ranking['total_medali']; ?>
                      </span>
                    </td>
                    <td class="text-center">
                      <span class="badge bg-warning text-dark px-3 py-2 fs-6">
                        🥇 <?php echo $ranking['emas']; ?>
                      </span>
                    </td>
                    <td class="text-center">
                      <span class="badge bg-secondary px-3 py-2 fs-6">
                        🥈 <?php echo $ranking['perak']; ?>
                      </span>
                    </td>
                    <td class="text-center">
                      <span class="badge bg-danger px-3 py-2 fs-6">
                        🥉 <?php echo $ranking['perunggu']; ?>
                      </span>
                    </td>
                    <td class="text-center">
                      <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-size: 1rem; min-width: 80px; padding: 8px 12px;">
                        ⭐ <?php echo number_format($ranking['total_poin']); ?>
                      </span>
                    </td>
                    <td class="text-center">
                      <?php if ($ranking['rank'] == 1): ?>
                        <span class="badge bg-warning text-dark px-3 py-2">
                          <i class="mdi mdi-crown"></i> JUARA 1
                        </span>
                      <?php elseif ($ranking['rank'] == 2): ?>
                        <span class="badge bg-secondary px-3 py-2">
                          <i class="mdi mdi-trophy"></i> JUARA 2
                        </span>
                      <?php elseif ($ranking['rank'] == 3): ?>
                        <span class="badge bg-danger px-3 py-2">
                          <i class="mdi mdi-trophy"></i> JUARA 3
                        </span>
                      <?php else: ?>
                        <span class="badge bg-dark px-3 py-2">
                          Peringkat <?php echo $ranking['rank']; ?>
                        </span>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
            <?php
            // Hitung total keseluruhan untuk footer
            $sqlTotalPoin = "SELECT 
                              SUM(CASE WHEN medali = 'Emas' THEN 100 ELSE 0 END) +
                              SUM(CASE WHEN medali = 'Perak' THEN 75 ELSE 0 END) +
                              SUM(CASE WHEN medali = 'Perunggu' THEN 50 ELSE 0 END) as grand_total_poin,
                              COUNT(*) as grand_total_medali,
                              SUM(CASE WHEN medali = 'Emas' THEN 1 ELSE 0 END) as grand_emas,
                              SUM(CASE WHEN medali = 'Perak' THEN 1 ELSE 0 END) as grand_perak,
                              SUM(CASE WHEN medali = 'Perunggu' THEN 1 ELSE 0 END) as grand_perunggu
                            FROM medali";
            $totalResult = $koneksi->query($sqlTotalPoin);
            $totalRow = $totalResult->fetch_assoc();
            ?>
            <tfoot class="table-dark">
              <tr>
                <td colspan="2" class="fw-bold text-end">GRAND TOTAL :</td>
                <td class="text-center">-</td>
                <td class="text-center fw-bold"><?php echo $totalRow['grand_total_medali']; ?></td>
                <td class="text-center fw-bold text-warning">🥇 <?php echo $totalRow['grand_emas']; ?></td>
                <td class="text-center fw-bold text-secondary">🥈 <?php echo $totalRow['grand_perak']; ?></td>
                <td class="text-center fw-bold text-danger">🥉 <?php echo $totalRow['grand_perunggu']; ?></td>
                <td class="text-center fw-bold">
                  <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-size: 0.9rem;">
                    ⭐ <?php echo number_format($totalRow['grand_total_poin']); ?>
                  </span>
                </td>
                <td class="text-center">-</td>
              </tr>
            </tfoot>
          </table>
        </div>

        <!-- Keterangan -->
        <div class="alert alert-light mt-3 mb-0">
          <small>
            <i class="mdi mdi-information-outline"></i>
            <strong>Sistem Peringkat:</strong> Kontingen diurutkan berdasarkan TOTAL POIN tertinggi.
            Jika poin sama, maka diurutkan berdasarkan jumlah medali EMAS terbanyak,
            kemudian PERAK, kemudian PERUNGGU.
          </small>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
  // Inisialisasi Bootstrap tabs
  document.addEventListener('DOMContentLoaded', function() {
    // Tab untuk Jadwal Tanding
    var triggerTabList = [].slice.call(document.querySelectorAll('#jadwalTab a'))
    triggerTabList.forEach(function(triggerEl) {
      var tabTrigger = new bootstrap.Tab(triggerEl)
      triggerEl.addEventListener('click', function(event) {
        event.preventDefault()
        tabTrigger.show()
      })
    });

    // Tab untuk Jadwal TGR
    var triggerTabListTgr = [].slice.call(document.querySelectorAll('#jadwalTabTgr a'))
    triggerTabListTgr.forEach(function(triggerEl) {
      var tabTrigger = new bootstrap.Tab(triggerEl)
      triggerEl.addEventListener('click', function(event) {
        event.preventDefault()
        tabTrigger.show()
      })
    });
  });

  // Chart.js untuk Distribusi Medali
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeChart);
  } else {
    initializeChart();
  }

  function initializeChart() {
    var canvas = document.getElementById('medaliChart');
    if (!canvas) return;

    var ctx = canvas.getContext('2d');

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
            display: false,
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                let label = context.label || '';
                let value = context.raw || 0;
                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                let percentage = total > 0 ? Math.round((value / total) * 100) : 0;
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