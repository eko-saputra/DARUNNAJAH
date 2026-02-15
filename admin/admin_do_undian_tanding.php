<?php
session_start();
if (!isset($_SESSION['pwd'])) {
    header('location:login.php');
    exit;
}
include('includes/connection.php');

$golongan = mysqli_real_escape_string($koneksi, $_POST["golongan"]);
$jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST["jenis_kelamin"]);
$kelas_tanding = mysqli_real_escape_string($koneksi, $_POST["kelas_tanding"]);

sleep(2);

if ($golongan == '' || $jenis_kelamin == '' || $kelas_tanding == '') {
    echo "<script>alert('GAGAL. Filter Data Tidak Lengkap!');document.location='admin_undian_tanding.php';</script>";
    exit;
}

$sqlcaripeserta = "SELECT * FROM undian
    INNER JOIN peserta ON undian.id_peserta=peserta.ID_peserta
    WHERE golongan = '$golongan' 
    AND jenis_kelamin = '$jenis_kelamin'
    AND kelas_tanding_FK = '$kelas_tanding'";
$caripeserta = mysqli_query($koneksi, $sqlcaripeserta);

if (mysqli_num_rows($caripeserta) > 0) {
    echo "<script>alert('GAGAL. Ditemukan data peserta sudah pernah diundi.');document.location='admin_undian_tanding.php';</script>";
    exit;
}

$sqlcount = "SELECT * FROM peserta 
    WHERE golongan = '$golongan' 
    AND jenis_kelamin = '$jenis_kelamin'
    AND kelas_tanding_FK = '$kelas_tanding' 
    AND status = 'PAID'";
$countresult = mysqli_query($koneksi, $sqlcount);
$jumlah = mysqli_num_rows($countresult);

if ($jumlah == 0) {
    echo "<script>alert('GAGAL. Peserta tidak ditemukan pada kelompok tersebut.');document.location='admin_undian_tanding.php';</script>";
    exit;
}

// Ambil peserta dan acak urutan
$sqlpeserta = "SELECT * FROM peserta
    WHERE golongan = '$golongan' 
    AND jenis_kelamin = '$jenis_kelamin'
    AND kelas_tanding_FK = '$kelas_tanding'
    AND status = 'PAID'
    ORDER BY RAND()";
$pesertaquery = mysqli_query($koneksi, $sqlpeserta);

$numbers = range(1, $jumlah);
shuffle($numbers);

$urutan = 0;
$peserta_array = [];

while ($peserta = mysqli_fetch_array($pesertaquery)) {
    $no_undian = $numbers[$urutan];
    mysqli_query($koneksi, "INSERT INTO undian(ID_peserta, no_undian) VALUES('{$peserta['ID_peserta']}', '$no_undian')");

    $peserta_array[] = [
        'nm_lengkap' => $peserta['nm_lengkap'],
        'kontingen' => $peserta['kontingen'],
        'no_undian' => $no_undian
    ];

    $urutan++;
}

// Urutkan berdasarkan no undian
usort($peserta_array, function ($a, $b) {
    return $a['no_undian'] <=> $b['no_undian'];
});

// Ambil nama kelas
$sqlkelas = mysqli_query($koneksi, "SELECT nm_kelastanding FROM kelastanding WHERE ID_kelastanding = '$kelas_tanding'");
$kelastanding = mysqli_fetch_assoc($sqlkelas);
$kelas_str = "$jenis_kelamin $golongan {$kelastanding['nm_kelastanding']}";

// Hapus jadwal lama
mysqli_query($koneksi, "DELETE FROM jadwal_tanding WHERE kelas = '$kelas_str'");
mysqli_query($koneksi, "DELETE FROM jadwal_tanding_bye WHERE kelas = '$kelas_str'");

$tanggal = date('Y-m-d');
$gelanggang = 'A';
$resultPartai = mysqli_query($koneksi, "SELECT MAX(partai) AS max_partai FROM jadwal_tanding");
$dataPartai = mysqli_fetch_assoc($resultPartai);
$partai = ($dataPartai['max_partai'] !== null) ? $dataPartai['max_partai'] + 1 : 1;

// Pairing antar kontingen berbeda dulu
$paired = [];
$used = [];

for ($i = 0; $i < count($peserta_array); $i++) {
    if (in_array($i, $used)) continue;

    $p1 = $peserta_array[$i];
    $found = false;

    for ($j = $i + 1; $j < count($peserta_array); $j++) {
        if (in_array($j, $used)) continue;
        $p2 = $peserta_array[$j];

        if ($p1['kontingen'] !== $p2['kontingen']) {
            $paired[] = [$p1, $p2];
            $used[] = $i;
            $used[] = $j;
            $found = true;
            break;
        }
    }

    if (!$found) {
        // Kalau tidak ada kontingen lain, pasangkan dengan siapa saja
        for ($j = $i + 1; $j < count($peserta_array); $j++) {
            if (in_array($j, $used)) continue;
            $p2 = $peserta_array[$j];

            $paired[] = [$p1, $p2];
            $used[] = $i;
            $used[] = $j;
            break;
        }
    }
}

// Simpan ke jadwal tanding (normal)
foreach ($paired as $pasangan) {
    $merah = $pasangan[0];
    $biru = $pasangan[1];

    mysqli_query($koneksi, "INSERT INTO jadwal_tanding (
        tgl, kelas, gelanggang, partai,
        nm_merah, kontingen_merah,
        nm_biru, kontingen_biru,
        status, pemenang, babak, medali, aktif, round1
    ) VALUES (
        '$tanggal', '$kelas_str', '$gelanggang', '$partai',
        '{$merah['nm_lengkap']}', '{$merah['kontingen']}',
        '{$biru['nm_lengkap']}', '{$biru['kontingen']}',
        '-', '-', 'SEMIFINAL', 0, 1, 0
    )");

    $partai++;
}

// Tangani peserta BYE jika ganjil → masuk ke jadwal_tanding_bye
if (count($used) < count($peserta_array)) {
    for ($i = 0; $i < count($peserta_array); $i++) {
        if (!in_array($i, $used)) {
            $bye = $peserta_array[$i];

            // Ambil partai terakhir dari jadwal_tanding_bye
            $resultBye = mysqli_query($koneksi, "SELECT MAX(partai) AS max_partai FROM jadwal_tanding_bye");
            $dataBye = mysqli_fetch_assoc($resultBye);
            $partai_bye = ($dataBye['max_partai'] !== null) ? $dataBye['max_partai'] + 1 : 1;

            mysqli_query($koneksi, "INSERT INTO jadwal_tanding_bye (
                tgl, kelas, gelanggang, partai,
                nm_merah, kontingen_merah,
                nm_biru, kontingen_biru,
                status, pemenang, babak, medali, aktif, round1
            ) VALUES (
                '$tanggal', '$kelas_str', '$gelanggang', '$partai_bye',
                '{$bye['nm_lengkap']}', '{$bye['kontingen']}',
                '-', '-', 'BYE', '-', 'FINAL', 0, 1, 0
            )");
        }
    }
}

echo "<script>alert('Data peserta berhasil diundi dan jadwal tanding dibuat.');document.location='admin_undian_tanding.php';</script>";
?>
