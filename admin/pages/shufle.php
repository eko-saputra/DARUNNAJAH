<?php
// Aktifkan error reporting hanya untuk debugging, tapi jangan tampilkan ke output
error_reporting(E_ALL);
ini_set('display_errors', 0); // Set ke 0 agar error tidak mengganggu JSON

// Perbaiki path include
include_once("includes/connection.php");

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Fungsi untuk mengirim response JSON
function sendJsonResponse($data)
{
    // Bersihkan output buffer
    while (ob_get_level()) {
        ob_end_clean();
    }
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Tangkap semua error agar tidak mengganggu JSON response
function handleError($errno, $errstr, $errfile, $errline)
{
    if (strpos($_SERVER['REQUEST_URI'], 'shufle.php') !== false && isset($_POST['action'])) {
        sendJsonResponse([
            'success' => false,
            'message' => "PHP Error: $errstr in $errfile line $errline"
        ]);
    }
    return false;
}
set_error_handler('handleError');

// Tangkap exception
function handleException($e)
{
    if (strpos($_SERVER['REQUEST_URI'], 'shufle.php') !== false && isset($_POST['action'])) {
        sendJsonResponse([
            'success' => false,
            'message' => "Exception: " . $e->getMessage()
        ]);
    }
}
set_exception_handler('handleException');

// Fungsi untuk mendapatkan semua data peserta dengan filter
function getAllPesertaByFilter($koneksi)
{
    $query = "SELECT * FROM peserta WHERE status = 'AKTIF' ORDER BY 
              CASE 
                  WHEN golongan LIKE '%Usia Dini%' THEN 1
                  WHEN golongan = 'Pra Remaja' THEN 2
                  WHEN golongan = 'Remaja' THEN 3
                  ELSE 4
              END,
              jenis_kelamin,
              kelas_tanding_FK,
              kontingen";

    $result = mysqli_query($koneksi, $query);

    if (!$result) {
        throw new Exception("Database error: " . mysqli_error($koneksi));
    }

    $peserta = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $peserta[] = $row;
    }

    return $peserta;
}

// Fungsi untuk mengelompokkan peserta berdasarkan kriteria
function kelompokkanPeserta($peserta)
{
    $kelompok = [];

    foreach ($peserta as $p) {
        $golongan = $p['golongan'];
        $kategori = $p['kategori_tanding'];
        $jenis_kelamin = $p['jenis_kelamin'];
        $kelas = $p['kelas_tanding_FK'];

        // Untuk kelas tanding, ambil huruf kelasnya saja
        preg_match('/([A-Z]+|\d+[A-Z]|UNDER)$/', $kelas, $matches);
        $kelas_huruf = isset($matches[1]) ? $matches[1] : 'UMUM';

        $key = $golongan . '|' . $kategori . '|' . $jenis_kelamin . '|' . $kelas_huruf;

        if (!isset($kelompok[$key])) {
            $kelompok[$key] = [
                'golongan' => $golongan,
                'kategori_tanding' => $kategori,
                'jenis_kelamin' => $jenis_kelamin,
                'kelas' => $kelas_huruf,
                'nama_kelas_full' => $golongan . ' - ' . $kategori . ' - ' . $jenis_kelamin . ' - Kelas ' . $kelas_huruf,
                'peserta' => []
            ];
        }

        $kelompok[$key]['peserta'][] = $p;
    }

    return $kelompok;
}

// Fungsi untuk mengacak peserta dengan mempertimbangkan kontingen
function shufflePeserta($peserta)
{
    if (empty($peserta)) return [];

    $kontingenGroups = [];
    foreach ($peserta as $p) {
        $kontingen = $p['kontingen'];
        if (!isset($kontingenGroups[$kontingen])) {
            $kontingenGroups[$kontingen] = [];
        }
        $kontingenGroups[$kontingen][] = $p;
    }

    $kontingenList = array_keys($kontingenGroups);
    shuffle($kontingenList);

    $result = [];
    $maxPerKontingen = max(array_map('count', $kontingenGroups));

    for ($i = 0; $i < $maxPerKontingen; $i++) {
        foreach ($kontingenList as $kontingen) {
            if (isset($kontingenGroups[$kontingen][$i])) {
                $result[] = $kontingenGroups[$kontingen][$i];
            }
        }
    }

    return $result;
}

// Fungsi untuk membentuk partai dengan sistem gugur
function bentukPartaiSistemGugur($peserta, $nama_kelas)
{
    $partai = [];
    $jumlah = count($peserta);

    if ($jumlah < 2) {
        return ['error' => 'Jumlah peserta minimal 2 orang', 'partai' => []];
    }

    $semuaPartai = [];
    $partaiCounter = 1;

    // Babak 1 (Penyisihan)
    $partaiBabak1 = floor($jumlah / 2);

    for ($i = 0; $i < $partaiBabak1; $i++) {
        $p1 = $peserta[$i * 2];
        $p2 = $peserta[$i * 2 + 1];

        // Cek apakah berasal dari kontingen sama
        if ($p1['kontingen'] == $p2['kontingen']) {
            for ($j = $i * 2 + 2; $j < $jumlah; $j++) {
                if ($peserta[$j]['kontingen'] != $p1['kontingen']) {
                    $temp = $peserta[$i * 2 + 1];
                    $peserta[$i * 2 + 1] = $peserta[$j];
                    $peserta[$j] = $temp;
                    $p2 = $peserta[$i * 2 + 1];
                    break;
                }
            }
        }

        $partai = 'P' . str_pad($partaiCounter, 2, '0', STR_PAD_LEFT);
        $semuaPartai[] = [
            'partai' => $partai,
            'babak' => 'PENYISIHAN',
            'merah' => $p1,
            'biru' => $p2
        ];

        $partaiCounter++;
    }

    // Jika jumlah ganjil, ada BYE
    $pesertaBye = [];
    if ($jumlah % 2 == 1) {
        $pesertaBye[] = $peserta[$jumlah - 1];
    }

    // Hitung peserta lolos ke semifinal
    $pesertaLolosSemifinal = $partaiBabak1 + count($pesertaBye);

    // Babak Semifinal
    if ($pesertaLolosSemifinal > 1) {
        $partaiSemifinal = floor($pesertaLolosSemifinal / 2);
        $sfCounter = 1;

        for ($i = 0; $i < $partaiSemifinal; $i++) {
            $partai = 'SF' . str_pad($sfCounter, 2, '0', STR_PAD_LEFT);

            if ($i < count($pesertaBye)) {
                $semuaPartai[] = [
                    'partai' => $partai,
                    'babak' => 'SEMIFINAL',
                    'merah' => $pesertaBye[$i],
                    'biru' => null,
                    'is_bye' => true
                ];
            } else {
                $fromPartai1 = 'P' . str_pad(($i * 2) + 1 - count($pesertaBye), 2, '0', STR_PAD_LEFT);
                $fromPartai2 = 'P' . str_pad(($i * 2) + 2 - count($pesertaBye), 2, '0', STR_PAD_LEFT);

                $semuaPartai[] = [
                    'partai' => $partai,
                    'babak' => 'SEMIFINAL',
                    'merah' => null,
                    'biru' => null,
                    'from_partai1' => $fromPartai1,
                    'from_partai2' => $fromPartai2
                ];
            }
            $sfCounter++;
        }
    }

    // Babak Final
    if ($pesertaLolosSemifinal > 1) {
        $semuaPartai[] = [
            'partai' => 'F01',
            'babak' => 'FINAL',
            'merah' => null,
            'biru' => null
        ];
    }

    return ['error' => null, 'partai' => $semuaPartai];
}

// Fungsi untuk menyimpan ke database
function simpanSemuaJadwal($koneksi, $semuaKelompok)
{
    $tgl = date('Y-m-d');
    $totalInserted = 0;
    $totalErrors = 0;
    $allErrors = [];
    $allResults = [];

    // Hapus semua data jadwal lama
    mysqli_query($koneksi, "DELETE FROM jadwal_tanding");

    foreach ($semuaKelompok as $key => $kelompok) {
        $peserta = $kelompok['peserta'];
        $nama_kelas_full = $kelompok['nama_kelas_full'];

        if (count($peserta) < 2) {
            $allErrors[] = "Kelompok $nama_kelas_full: Jumlah peserta kurang dari 2";
            continue;
        }

        // Acak peserta
        $acak = shufflePeserta($peserta);

        // Bentuk partai sistem gugur
        $result = bentukPartaiSistemGugur($acak, $nama_kelas_full);

        if ($result['error']) {
            $allErrors[] = "Kelompok $nama_kelas_full: " . $result['error'];
            continue;
        }

        $partaiList = $result['partai'];
        $gelanggang = 'A';

        foreach ($partaiList as $p) {
            $partai = mysqli_real_escape_string($koneksi, $p['partai']);
            $babak = mysqli_real_escape_string($koneksi, $p['babak']);
            $kelas = mysqli_real_escape_string($koneksi, $nama_kelas_full);

            if ($babak == 'PENYISIHAN') {
                $nm_merah = mysqli_real_escape_string($koneksi, $p['merah']['nm_lengkap']);
                $kontingen_merah = mysqli_real_escape_string($koneksi, $p['merah']['kontingen']);
                $nm_biru = mysqli_real_escape_string($koneksi, $p['biru']['nm_lengkap']);
                $kontingen_biru = mysqli_real_escape_string($koneksi, $p['biru']['kontingen']);

                $query = "INSERT INTO jadwal_tanding (
                    tgl, kelas, gelanggang, partai, nm_merah, kontingen_merah, 
                    nm_biru, kontingen_biru, status, babak
                ) VALUES (
                    '$tgl', '$kelas', '$gelanggang', '$partai', 
                    '$nm_merah', '$kontingen_merah', '$nm_biru', '$kontingen_biru', 
                    '-', '$babak'
                )";
            } else if ($babak == 'SEMIFINAL') {
                if (isset($p['is_bye']) && $p['is_bye']) {
                    $nm_merah = mysqli_real_escape_string($koneksi, $p['merah']['nm_lengkap']);
                    $kontingen_merah = mysqli_real_escape_string($koneksi, $p['merah']['kontingen']);

                    $query = "INSERT INTO jadwal_tanding (
                        tgl, kelas, gelanggang, partai, nm_merah, kontingen_merah, 
                        nm_biru, kontingen_biru, status, babak
                    ) VALUES (
                        '$tgl', '$kelas', '$gelanggang', '$partai', 
                        '$nm_merah', '$kontingen_merah', 'BYE', 'BYE', 
                        '-', '$babak'
                    )";
                } else {
                    $nm_merah = "Pemenang " . $p['from_partai1'];
                    $nm_biru = "Pemenang " . $p['from_partai2'];

                    $query = "INSERT INTO jadwal_tanding (
                        tgl, kelas, gelanggang, partai, nm_merah, kontingen_merah, 
                        nm_biru, kontingen_biru, status, babak
                    ) VALUES (
                        '$tgl', '$kelas', '$gelanggang', '$partai', 
                        '$nm_merah', '-', '$nm_biru', '-', 
                        '-', '$babak'
                    )";
                }
            } else if ($babak == 'FINAL') {
                $query = "INSERT INTO jadwal_tanding (
                    tgl, kelas, gelanggang, partai, nm_merah, kontingen_merah, 
                    nm_biru, kontingen_biru, status, babak
                ) VALUES (
                    '$tgl', '$kelas', '$gelanggang', '$partai', 
                    'Pemenang SF01', '-', 'Pemenang SF02', '-', 
                    '-', '$babak'
                )";
            }

            if (isset($query) && mysqli_query($koneksi, $query)) {
                $totalInserted++;
            } else if (isset($query)) {
                $totalErrors++;
                $allErrors[] = "Gagal simpan $nama_kelas_full partai $partai: " . mysqli_error($koneksi);
            }
        }

        $allResults[] = [
            'kelompok' => $nama_kelas_full,
            'peserta' => count($peserta),
            'partai' => count($partaiList)
        ];
    }

    return [
        'success' => $totalErrors == 0,
        'inserted' => $totalInserted,
        'errors' => $totalErrors,
        'error_messages' => $allErrors,
        'results' => $allResults
    ];
}

// Fungsi untuk mendapatkan statistik
function getStatistikPeserta($koneksi)
{
    $query = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN jenis_kelamin = 'LAKI-LAKI' THEN 1 ELSE 0 END) as laki,
                SUM(CASE WHEN jenis_kelamin = 'PEREMPUAN' THEN 1 ELSE 0 END) as perempuan,
                COUNT(DISTINCT CONCAT(golongan, '-', kategori_tanding, '-', jenis_kelamin, '-', kelas_tanding_FK)) as total_kelompok
              FROM peserta 
              WHERE status = 'AKTIF'";

    $result = mysqli_query($koneksi, $query);
    return mysqli_fetch_assoc($result);
}

// PROSES AJAX REQUEST - HARUS PALING ATAS SEBELUM OUTPUT HTML
if (isset($_POST['action'])) {
    try {
        if ($_POST['action'] == 'shuffle_all') {
            $semuaPeserta = getAllPesertaByFilter($koneksi);

            if (empty($semuaPeserta)) {
                sendJsonResponse([
                    'success' => false,
                    'message' => 'Tidak ada peserta aktif dalam database'
                ]);
            }

            $kelompokPeserta = kelompokkanPeserta($semuaPeserta);

            if (empty($kelompokPeserta)) {
                sendJsonResponse([
                    'success' => false,
                    'message' => 'Tidak dapat mengelompokkan peserta'
                ]);
            }

            $result = simpanSemuaJadwal($koneksi, $kelompokPeserta);

            if ($result['success']) {
                $message = "✅ BERHASIL membuat " . $result['inserted'] . " partai pertandingan\n\n";
                $message .= "📊 RINCIAN PER KELOMPOK:\n";
                $message .= str_repeat("─", 50) . "\n";

                foreach ($result['results'] as $r) {
                    $message .= "📌 {$r['kelompok']}\n";
                    $message .= "   ├─ Peserta: {$r['peserta']} orang\n";
                    $message .= "   └─ Partai: {$r['partai']} partai\n\n";
                }

                $stats = getStatistikPeserta($koneksi);
                $message .= str_repeat("─", 50) . "\n";
                $message .= "📈 STATISTIK KESELURUHAN:\n";
                $message .= "   ├─ Total Peserta: {$stats['total']} orang\n";
                $message .= "   ├─ Laki-laki: {$stats['laki']} orang\n";
                $message .= "   ├─ Perempuan: {$stats['perempuan']} orang\n";
                $message .= "   └─ Total Kelompok: {$stats['total_kelompok']} kelompok\n";

                sendJsonResponse([
                    'success' => true,
                    'message' => $message,
                    'results' => $result['results'],
                    'total_inserted' => $result['inserted']
                ]);
            } else {
                $errorMsg = "❌ GAGAL dengan $result[errors] error:\n";
                $errorMsg .= str_repeat("─", 50) . "\n";
                $errorMsg .= implode("\n", array_slice($result['error_messages'], 0, 5));

                sendJsonResponse([
                    'success' => false,
                    'message' => $errorMsg
                ]);
            }
        }
    } catch (Exception $e) {
        sendJsonResponse([
            'success' => false,
            'message' => "Exception: " . $e->getMessage()
        ]);
    }
}

// AMBIL DATA UNTUK DITAMPILKAN (HANYA JIKA BUKAN AJAX REQUEST)
$semuaPeserta = getAllPesertaByFilter($koneksi);
$kelompokPeserta = kelompokkanPeserta($semuaPeserta);
$totalPeserta = count($semuaPeserta);
$totalKelompok = count($kelompokPeserta);
$statistik = getStatistikPeserta($koneksi);
?>

<!-- jQuery -->
<script src="../assets/jquery/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="../assets/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="assets/js/sweetalert2.js"></script>

<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-dark text-light border-secondary">
                <div class="card-header bg-gradient py-3" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                    <h5 class="mb-0 text-white">
                        <i class="bi bi-shuffle me-2"></i>
                        SHUFFLE PESERTA - PEMBUATAN PARTAI SISTEM GUGUR
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Info Cards -->
                    <div class="row mb-4 g-3">
                        <div class="col-md-3">
                            <div class="bg-gradient p-3 rounded text-white"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-people-fill fs-1 me-3"></i>
                                    <div>
                                        <h3 class="mb-0 fw-bold"><?php echo $statistik['total']; ?></h3>
                                        <small>Total Peserta Aktif</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-gradient p-3 rounded text-white"
                                style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-gender-male fs-1 me-3"></i>
                                    <div>
                                        <h3 class="mb-0 fw-bold"><?php echo $statistik['laki']; ?></h3>
                                        <small>Laki-laki</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-gradient p-3 rounded text-white"
                                style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-gender-female fs-1 me-3"></i>
                                    <div>
                                        <h3 class="mb-0 fw-bold"><?php echo $statistik['perempuan']; ?></h3>
                                        <small>Perempuan</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-gradient p-3 rounded text-white"
                                style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-grid-3x3-gap-fill fs-1 me-3"></i>
                                    <div>
                                        <h3 class="mb-0 fw-bold"><?php echo $statistik['total_kelompok']; ?></h3>
                                        <small>Total Kelompok</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shuffle Button -->
                    <div class="text-center mb-4">
                        <button id="shuffleAll" class="btn btn-lg px-5 py-3"
                            style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); 
                                       border: none; 
                                       color: white; 
                                       font-size: 24px; 
                                       font-weight: 600;
                                       border-radius: 15px;
                                       box-shadow: 0 10px 20px rgba(0,0,0,0.3);
                                       transition: all 0.3s;
                                       cursor: pointer;">
                            <i class="bi bi-shuffle me-2"></i>
                            SHUFFLE SEMUA PESERTA
                        </button>
                        <p class="text-muted mt-2">
                            <i class="bi bi-info-circle me-1"></i>
                            Sistem akan membuat partai sistem gugur untuk setiap kelompok
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Result Modal -->
<div class="modal fade" id="resultModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark text-light border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="resultModalTitle"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <pre id="resultMessage" style="background: #1e1e1e; padding: 15px; border-radius: 10px; font-family: monospace; max-height: 400px; overflow-y: auto; white-space: pre-wrap;"></pre>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Di bagian bawah file shufle.php, ganti script yang ada dengan ini: -->
<script>
    $(document).ready(function() {
        console.log('Document ready - Shuffle page loaded');

        $('#shuffleAll').click(function() {
            console.log('Shuffle button clicked');

            Swal.fire({
                title: 'Konfirmasi Shuffle',
                html: `
                <div style="text-align: left;">
                    <p class="mb-2">Anda akan melakukan shuffle untuk <strong>SELURUH PESERTA AKTIF</strong>:</p>
                    <ul style="list-style: none; padding-left: 0;">
                        <li class="mb-2">
                            <i class="bi bi-people-fill text-primary me-2"></i>
                            Total Peserta: <strong><?php echo $totalPeserta; ?></strong> orang
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-grid-3x3-gap-fill text-success me-2"></i>
                            Total Kelompok: <strong><?php echo $totalKelompok; ?></strong> kelompok
                        </li>
                    </ul>
                    <p class="text-danger mt-3 mb-0">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        PERHATIAN: Semua jadwal pertandingan yang ada akan dihapus!
                    </p>
                </div>
            `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1e3c72',
                cancelButtonColor: '#d33',
                confirmButtonText: '<i class="bi bi-shuffle me-2"></i>Ya, Shuffle Semua!',
                cancelButtonText: '<i class="bi bi-x-circle me-2"></i>Batal',
                background: '#2d2d2d',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: 'pages/proses/proses_shuffle.php', // Panggil file khusus
                        type: 'POST',
                        data: {
                            action: 'shuffle_all'
                        },
                        dataType: 'json',
                        timeout: 60000,
                        success: function(response) {

                            if (response.success) {
                                $('#resultModalTitle').html('<i class="bi bi-check-circle text-success me-2"></i>Shuffle Berhasil!');
                                $('#resultMessage').text(response.message).css('color', '#0f0');
                                $('#lihatJadwalBtn').show();
                            } else {
                                $('#resultModalTitle').html('<i class="bi bi-exclamation-triangle text-danger me-2"></i>Shuffle Gagal!');
                                $('#resultMessage').text(response.message).css('color', '#ff6b6b');
                                $('#lihatJadwalBtn').hide();
                            }
                            $('#resultModal').modal('show');
                        },
                        error: function(xhr, status, error) {

                            let errorText = 'Error: ' + error + '\n';
                            errorText += 'Status: ' + status + '\n';
                            errorText += 'Response: ' + xhr.responseText;

                            $('#resultModalTitle').html('<i class="bi bi-exclamation-triangle text-danger me-2"></i>Error!');
                            $('#resultMessage').text(errorText).css('color', '#ff6b6b');
                            $('#lihatJadwalBtn').hide();
                            $('#resultModal').modal('show');
                        }
                    });
                }
            });
        });
    });
</script>