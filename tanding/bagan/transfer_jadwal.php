<?php
// simpan_jadwal.php

$host = "localhost";
$user = "eko";
$pass = "Alifzain.1988";
$db   = "skordigital";

$koneksi = new mysqli($host, $user, $pass, $db);
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// -------------------- Fungsi umum --------------------
function pindahkanData($koneksi, $sourceTable, $targetTable)
{
    $cek = $koneksi->query("SELECT COUNT(*) as cnt FROM $sourceTable");
    $row = $cek->fetch_assoc();
    if ($row['cnt'] == 0) return "Tabel $sourceTable kosong, tidak ada data untuk dipindahkan.";

    $sql = "INSERT INTO $targetTable SELECT * FROM $sourceTable";
    if ($koneksi->query($sql)) {
        // $koneksi->query("TRUNCATE TABLE $sourceTable");
        return "Data berhasil dipindahkan dari $sourceTable ke $targetTable.";
    } else {
        return "Gagal memindahkan data: " . $koneksi->error;
    }
}

// -------------------- Fungsi khusus final --------------------
function pindahkanFinal($koneksi, $sourceTable, $targetTable, $refTable)
{
    $res = $koneksi->query("SELECT MAX(id_partai) AS max_id FROM $refTable");
    $row = $res->fetch_assoc();
    $startId = $row['max_id'] ? $row['max_id'] + 1 : 1;

    $result = $koneksi->query("SELECT * FROM $sourceTable");
    if ($result->num_rows == 0) return "Tabel $sourceTable kosong, tidak ada data untuk dipindahkan.";

    $rows = [];
    while ($r = $result->fetch_assoc()) {
        $rows[] = $r;
    }

    // Urutkan: partai dengan nm_biru/merah = '-' taruh paling depan
    usort($rows, function ($a, $b) {
        $a_priority = ($a['nm_biru'] === '-' || $a['nm_merah'] === '-') ? 0 : 1;
        $b_priority = ($b['nm_biru'] === '-' || $b['nm_merah'] === '-') ? 0 : 1;
        return $a_priority <=> $b_priority; // 0 duluan
    });

    $count = 0;
    foreach ($rows as $r) {
        // Salin semua kolom terlebih dahulu
        $insertRow = $r;

        foreach (['nm_biru', 'nm_merah'] as $side) {
            $value = $r[$side];

            if (stripos($value, 'Pemenang SEMIFINAL BAGAN') !== false) {
                $bagan_semifinal = trim(str_ireplace('Pemenang SEMIFINAL BAGAN', '', $value));
                $kelas = $r['kelas'] ?? null;

                $q = $koneksi->prepare("
                    SELECT id_partai 
                    FROM jadwal_tanding 
                    WHERE bagan=? AND kelas=? 
                    LIMIT 1
                ");
                $q->bind_param("ss", $bagan_semifinal, $kelas);
                $q->execute();
                $res2 = $q->get_result();

                if ($res2->num_rows > 0) {
                    $match = $res2->fetch_assoc();
                    $value = "Pemenang Partai " . $match['id_partai'];
                } else {
                    $value = "Pemenang Partai ?";
                }

                $q->close();
            }

            // Swap biru-merah: biru di log masuk kolom merah, merah tetap di kolom biru
            if ($side == 'nm_biru') {
                $insertRow['nm_merah'] = $value;
                $insertRow['kontingen_merah'] = $r['kontingen_biru'];
            } else {
                $insertRow['nm_biru'] = $value;
                $insertRow['kontingen_biru'] = $r['kontingen_merah'];
            }
        }

        // Set id_partai baru
        $insertRow['id_partai'] = $startId;
        $insertRow['partai'] = $startId;
        $insertRow['id_bagan'] = 1;

        // Siapkan insert ke target
        $cols = implode(",", array_keys($insertRow));
        $vals = implode(",", array_map(function ($v) use ($koneksi) {
            return is_null($v) ? "NULL" : "'" . $koneksi->real_escape_string($v) . "'";
        }, $insertRow));

        $koneksi->query("INSERT INTO $targetTable ($cols) VALUES ($vals)");
        $startId++;
        $count++;
    }

    return "Berhasil memindahkan $count data dari $sourceTable ke $targetTable dengan urutan partai disesuaikan.";
}


// -------------------- Tombol ditekan --------------------
$message = "";
if (isset($_POST['simpan_jadwal'])) {
    $message = pindahkanData($koneksi, 'jadwal_tanding_log', 'jadwal_tanding');
} elseif (isset($_POST['simpan_final'])) {
    $message = pindahkanFinal($koneksi, 'jadwal_tanding_final_log', 'jadwal_tanding_final', 'jadwal_tanding');
}

$koneksi->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Simpan Jadwal Tanding</title>
    <link rel="stylesheet" href="../../assets/bootstrap/dist/css/bootstrap.min.css">
</head>

<body class="p-4 bg-light">
    <div class="container">
        <h2 class="mb-4">Pindahkan Data Jadwal Tanding</h2>

        <?php if ($message): ?>
            <div class="alert alert-info"><?= $message ?></div>
        <?php endif; ?>

        <form method="post">
            <button type="submit" name="simpan_jadwal" class="btn btn-primary mb-2">Simpan Jadwal Tanding</button>
            <button type="submit" name="simpan_final" class="btn btn-success mb-2">Simpan Jadwal Tanding Final</button>
        </form>
    </div>
</body>

</html>