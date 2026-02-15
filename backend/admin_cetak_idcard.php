<?php
// Koneksi database
$koneksi = mysqli_connect("localhost", "root", "", "skordigital");

// Ambil data peserta
$query = mysqli_query($koneksi, "SELECT nm_lengkap, kelas FROM peserta ORDER BY ID_peserta ASC");

$data = [];
while ($row = mysqli_fetch_assoc($query)) {
    $data[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>6 ID Card Portrait - A4 (Full Color)</title>
<style>
    @page {
        size: A4;
        margin: 0;
    }
    body {
        margin: 0;
        padding: 0;
        background: #eee;
    }
    .sheet {
        width: 210mm;
        height: 297mm;
        display: grid;
        grid-template-columns: repeat(2, 105mm);
        grid-template-rows: repeat(3, 99mm);
        page-break-after: always;
    }
    .id-card {
        width: 100mm;
        height: 94mm;
        margin: auto;
        background: linear-gradient(180deg, #007BFF 0%, #0056b3 100%);
        border-radius: 10px;
        color: white;
        box-sizing: border-box;
        padding: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        font-family: Arial, sans-serif;
        text-align: center;
        position: relative;
    }
    .logo {
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 50%;
        margin-bottom: 5px;
    }
    .photo {
        width: 65px;
        height: 85px;
        background: white;
        border-radius: 5px;
        border: 2px solid #fff;
        margin: 5px 0;
    }
    h2 {
        font-size: 14px;
        margin: 3px 0;
        font-weight: bold;
    }
    p {
        font-size: 12px;
        margin: 2px 0;
    }
    .qr {
        width: 35px;
        height: 35px;
        background: white;
        border-radius: 3px;
        margin-top: 5px;
    }
</style>
</head>
<body>
<?php
$counter = 0;
foreach ($data as $peserta) {
    if ($counter % 6 == 0) {
        if ($counter > 0) {
            echo "</div>";
        }
        echo "<div class='sheet'>";
    }
    echo "
        <div class='id-card'>
            <div class='logo'></div>
            <div class='photo'></div>
            <h2>{$peserta['nm_lengkap']}</h2>
            <p>Kelas: {$peserta['kelas']}</p>
            <div class='qr'></div>
        </div>
    ";
    $counter++;
}
if ($counter > 0) {
    echo "</div>";
}
?>
</body>
</html>
