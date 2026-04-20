<?php
// proses_shuffle.php
error_reporting(E_ALL);
ini_set('display_errors', 0);

header('Content-Type: application/json');

try {
    require_once("../../includes/connection.php");

    if (!$koneksi) {
        throw new Exception("Koneksi database gagal");
    }

    if (!isset($_POST['action']) || $_POST['action'] != 'shuffle_all') {
        throw new Exception("Invalid action");
    }

    // 1. Ambil semua peserta aktif
    $query = "SELECT * FROM peserta WHERE status = 'AKTIF'";
    $result = mysqli_query($koneksi, $query);

    if (!$result) {
        throw new Exception("Query error: " . mysqli_error($koneksi));
    }

    $peserta = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $peserta[] = $row;
    }

    if (empty($peserta)) {
        throw new Exception("Tidak ada peserta aktif");
    }

    // 2. Reset Data Lama
    mysqli_query($koneksi, "DELETE FROM jadwal_tanding");
    mysqli_query($koneksi, "DELETE FROM jadwal_tgr");
    mysqli_query($koneksi, "ALTER TABLE jadwal_tanding AUTO_INCREMENT = 1");
    mysqli_query($koneksi, "ALTER TABLE jadwal_tgr AUTO_INCREMENT = 1");

    // 3. Kelompokkan Peserta
    $kelompok = [];
    foreach ($peserta as $p) {
        preg_match('/([A-Z]+)$/', $p['kelas_tanding_FK'], $matches);
        $kelas_huruf = isset($matches[1]) ? $matches[1] : 'UMUM';
        $putra_putri = ($p['jenis_kelamin'] == 'LAKI-LAKI') ? 'Putra' : 'Putri';

        $key = $p['golongan'] . '|' . $p['kategori_tanding'] . '|' . $p['jenis_kelamin'] . '|' . $kelas_huruf;

        if (!isset($kelompok[$key])) {
            $kelompok[$key] = [
                'golongan'      => $p['golongan'],
                'kategori'      => $p['kategori_tanding'],
                'jenis_kelamin' => $p['jenis_kelamin'],
                'nama_kelas'    => $p['golongan'] . ' ' . $putra_putri . ' Kelas ' . $kelas_huruf,
                'peserta'       => []
            ];
        }
        $kelompok[$key]['peserta'][] = $p;
    }

    $partaiCounterTanding = 1;
    $partaiCounterTgr = 1;
    $resultsTanding = [];
    $resultsTgr = [];

    /**
     * FUNGSI GENERATE BAGAN SISTEM GUGUR
     */
    function generateBagan($koneksi, &$counter, $pesertaGroup, $isTgr, $infoKelas)
    {
        $jml = count($pesertaGroup);
        if ($jml < 2) return [];

        shuffle($pesertaGroup);
        $antrian = $pesertaGroup;
        $semuaPartaiKelasIni = [];

        // Selama masih ada lebih dari 1 orang/pemenang yang perlu bertanding
        while (count($antrian) > 1) {
            $pemenangBabakIni = [];
            $currentSize = count($antrian);

            // Tentukan Nama Babak
            if ($currentSize <= 2) $babak = "FINAL";
            elseif ($currentSize <= 4) $babak = "SEMIFINAL";
            elseif ($currentSize <= 8) $babak = "PEREMPAT FINAL";
            else $babak = "PENYISIHAN";

            // Pasangkan peserta dalam antrian
            while (count($antrian) >= 2) {
                $p1 = array_shift($antrian);
                $p2 = array_shift($antrian);

                $nm1 = mysqli_real_escape_string($koneksi, $p1['nm_lengkap']);
                $kt1 = mysqli_real_escape_string($koneksi, $p1['kontingen']);
                $nm2 = mysqli_real_escape_string($koneksi, $p2['nm_lengkap']);
                $kt2 = mysqli_real_escape_string($koneksi, $p2['kontingen']);

                $noPartai = $counter++;

                if (!$isTgr) {
                    $q = "INSERT INTO jadwal_tanding (tgl, kelas, gelanggang, partai, nm_merah, kontingen_merah, nm_biru, kontingen_biru, status, babak) 
                          VALUES (CURDATE(), '{$infoKelas['nama_kelas']}', 'A', '$noPartai', '$nm1', '$kt1', '$nm2', '$kt2', '-', '$babak')";
                } else {
                    $q = "INSERT INTO jadwal_tgr (tgl, partai, kategori, golongan, nm_merah, kontingen_merah, nm_biru, kontingen_biru, status, babak) 
                          VALUES (CURDATE(), '$noPartai', '{$infoKelas['kategori']}', '{$infoKelas['nama_kelas']}', '$nm1', '$kt1', '$nm2', '$kt2', '-', '$babak')";
                }

                if (mysqli_query($koneksi, $q)) {
                    $res = [
                        'partai' => $noPartai,
                        'babak' => $babak,
                        'nm_merah' => $nm1,
                        'nm_biru' => $nm2
                    ];
                    $semuaPartaiKelasIni[] = $res;
                    // Pemenang lanjut ke ronde berikutnya
                    $pemenangBabakIni[] = [
                        'nm_lengkap' => "Pemenang Partai $noPartai",
                        'kontingen' => '-'
                    ];
                }
            }

            // Jika ada yang BYE (ganjil), langsung lolos ke ronde berikutnya
            if (count($antrian) == 1) {
                $pemenangBabakIni[] = array_shift($antrian);
            }

            // Pindahkan semua pemenang ronde ini kembali ke antrian untuk ronde selanjutnya
            $antrian = $pemenangBabakIni;
        }
        return $semuaPartaiKelasIni;
    }

    // 4. Proses Eksekusi per Kelompok
    foreach ($kelompok as $grp) {
        if ($grp['kategori'] == 'TANDING') {
            $awal = $partaiCounterTanding;
            $list = generateBagan($koneksi, $partaiCounterTanding, $grp['peserta'], false, $grp);
            if (!empty($list)) {
                $resultsTanding[] = [
                    'kelas' => $grp['nama_kelas'],
                    'peserta' => count($grp['peserta']),
                    'partai_awal' => $awal,
                    'partai_akhir' => $partaiCounterTanding - 1,
                    'total_partai' => count($list),
                    'partai_list' => $list
                ];
            }
        } else {
            $awal = $partaiCounterTgr;
            $list = generateBagan($koneksi, $partaiCounterTgr, $grp['peserta'], true, $grp);
            if (!empty($list)) {
                $resultsTgr[] = [
                    'golongan' => $grp['nama_kelas'],
                    'kategori' => $grp['kategori'],
                    'peserta' => count($grp['peserta']),
                    'partai_awal' => $awal,
                    'partai_akhir' => $partaiCounterTgr - 1,
                    'total_partai' => count($list),
                    'partai_list' => $list
                ];
            }
        }
    }

    // 5. Hitung Statistik Akhir untuk Response
    $totalTanding = $partaiCounterTanding - 1;
    $totalTgr = $partaiCounterTgr - 1;

    $statResult = mysqli_query($koneksi, "SELECT COUNT(*) as total, 
        SUM(CASE WHEN jenis_kelamin = 'LAKI-LAKI' THEN 1 ELSE 0 END) as laki,
        SUM(CASE WHEN jenis_kelamin = 'PEREMPUAN' THEN 1 ELSE 0 END) as perempuan FROM peserta WHERE status = 'AKTIF'");
    $stat = mysqli_fetch_assoc($statResult);

    // Build Output Message (Sesuai format awal Anda)
    $message = "✅ BERHASIL MEMBUAT PARTAI PERTANDINGAN\n\n";
    $message .= "📊 STATISTIK KESELURUHAN:\n" . str_repeat("─", 50) . "\n";
    $message .= "   👥 Total Peserta: {$stat['total']} orang\n";
    $message .= "   👦 Laki-laki: {$stat['laki']} orang\n";
    $message .= "   👧 Perempuan: {$stat['perempuan']} orang\n\n";
    $message .= "   ✅ Total Partai TANDING: $totalTanding\n";
    $message .= "   ✅ Total Partai TGR: $totalTgr\n\n";

    echo json_encode([
        'success' => true,
        'message' => $message,
        'results_tanding' => $resultsTanding,
        'results_tgr' => $resultsTgr
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => "Error: " . $e->getMessage()]);
}
