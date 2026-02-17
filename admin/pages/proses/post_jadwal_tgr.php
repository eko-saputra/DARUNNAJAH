<?php
include('../../includes/connection.php');

// AMBIL VARIABEL - TANPA POST TANGGAL
$tanggal = date('Y-m-d'); // ambil tanggal server otomatis
$kategori = mysqli_real_escape_string($koneksi, $_POST["kategori"]);
$golongan = mysqli_real_escape_string($koneksi, $_POST["golongan"]);
$nopartai = mysqli_real_escape_string($koneksi, $_POST["nopartai"]);
$nm_merah = mysqli_real_escape_string($koneksi, $_POST["nm_merah"]);
$kont_merah = mysqli_real_escape_string($koneksi, $_POST["kont_merah"]);
$nm_biru = mysqli_real_escape_string($koneksi, $_POST["nm_biru"]);
$kont_biru = mysqli_real_escape_string($koneksi, $_POST["kont_biru"]);
$babak = mysqli_real_escape_string($koneksi, $_POST["babak"]);

// CEK DATA KOSONG - HAPUS CEK TANGGAL
if (
    $kategori == '' or $golongan == '' or $nopartai == '' or
    $nm_merah == '' or $kont_merah == '' or
    $nm_biru == '' or $kont_biru == '' or $babak == ''
) {
?>
    <script type="text/javascript">
        alert('GAGAL ! Data masih ada yang kosong!');
        document.location = 'jadwal_partai_tgr.php';
    </script>
<?php
    exit;
}

$sql = "INSERT INTO jadwal_tgr(id_partai,tgl, kategori, golongan, partai, 
                nm_merah, kontingen_merah, nm_biru, kontingen_biru, 
                status, pemenang, babak) 
        VALUES('$nopartai',NOW(), '$kategori', '$golongan', '$nopartai',
            '$nm_merah', '$kont_merah', '$nm_biru', '$kont_biru',
            '-', '-', '$babak')";

if (mysqli_query($koneksi, $sql)) {
?>
    <script type="text/javascript">
        document.location = '../../?page=tambah_jadwal_tgr';
    </script>
<?php
} else {
?>
    <script type="text/javascript">
        document.location = '../../?page=tambah_jadwal';
    </script>
<?php
    die('Unable to delete record: ' . mysqli_error($koneksi));
}
?>