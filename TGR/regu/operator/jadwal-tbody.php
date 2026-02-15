<?php
include "../../../backend/includes/connection.php";
$status_filter = isset($_GET['status']) ? ($_GET['status'] == '' ? '-' : $_GET['status']) : '';
//mencari TOTAL partai
$sqljadwal = "SELECT * FROM jadwal_tgr WHERE kategori='REGU' AND status='$status_filter' ORDER BY id_partai ASC";
$jadwal_tgr = mysqli_query($koneksi, $sqljadwal);

while ($jadwal = mysqli_fetch_array($jadwal_tgr)) {
?>
    <tr>
        <td rowspan="2" class="text-center align-middle"><?php echo $jadwal['partai']; ?></td>
        <td rowspan="2" class="text-center align-middle"><?php echo $jadwal['kategori']; ?></td>
        <td rowspan="2" class="text-center align-middle"><?php echo $jadwal['golongan']; ?></td>
        <td class="bg-danger bg-gradient text-white"><?php echo $jadwal['nm_merah']; ?></td>
        <td class="bg-primary bg-gradient text-white"><?php echo $jadwal['nm_biru']; ?></td>
        <td rowspan="2" class="text-center align-middle"><?php echo ucfirst($jadwal['status']); ?></td>
        <td rowspan="2" class="text-center align-middle">
            <?php
            $pemenang = strtolower($jadwal['pemenang']);
            if ($pemenang === 'biru') {
                echo '<span class="badge bg-primary">Biru</span>';
            } elseif ($pemenang === 'merah') {
                echo '<span class="badge bg-danger">Merah</span>';
            } else {
                echo '<span class="badge bg-secondary">-</span>'; // fallback jika belum ada pemenang
            }
            ?>
        </td>
        <td rowspan="2" class="text-center align-middle">
            <?php
            if ($jadwal['status'] == 'selesai') {
            ?>
                Pertandingan Selesai
            <?php
            } else {
            ?>
                <a href="operator.php?id_partai=<?php echo $jadwal['id_partai']; ?>" class="btn btn-success bg-gradient btn-sm">Masuk</a>
            <?php
            }
            ?>
        </td>
    </tr>
    <tr>
        <td class="bg-light bg-gradient text-dark"><?php echo $jadwal['kontingen_merah']; ?></td>
        <td class="bg-light bg-gradient text-dark"><?php echo $jadwal['kontingen_biru']; ?></td>
    </tr>
<?php
}
?>