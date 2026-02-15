<?php
session_start();
include('includes/connection.php');

// Ambil data dari form
$nm_lengkap       = mysqli_real_escape_string($koneksi, $_POST['nm_lengkap']);
$jenis_kelamin    = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
$tpt_lahir        = mysqli_real_escape_string($koneksi, $_POST['tpt_lahir']);
$tgl_lahir        = mysqli_real_escape_string($koneksi, $_POST['tgl_lahir']);
$tb               = mysqli_real_escape_string($koneksi, $_POST['tb']);
$bb               = mysqli_real_escape_string($koneksi, $_POST['bb']);
$kelas            = mysqli_real_escape_string($koneksi, $_POST['kelas']);
$asal_sekolah     = mysqli_real_escape_string($koneksi, $_POST['asal_sekolah']);
$golongan         = mysqli_real_escape_string($koneksi, $_POST['golongan']);
$kelas_tanding_FK = mysqli_real_escape_string($koneksi, $_POST['kelas_tanding']);
$kontingen        = mysqli_real_escape_string($koneksi, $_POST['kontingen']);

// Nilai tetap
$kategori_tanding = "Tanding";
$status = "PAID";

// Query Insert
$sql = "INSERT INTO peserta 
        (nm_lengkap, jenis_kelamin, tpt_lahir, tgl_lahir, tb, bb, kelas, asal_sekolah, kategori_tanding, golongan, kelas_tanding_FK, kontingen, status) 
        VALUES 
        ('$nm_lengkap', '$jenis_kelamin', '$tpt_lahir', '$tgl_lahir', '$tb', '$bb', '$kelas', '$asal_sekolah', '$kategori_tanding', '$golongan', '$kelas_tanding_FK', '$kontingen', '$status')";

if (mysqli_query($koneksi, $sql)) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Berhasil</title>
        <style>
            .alert-slide {
                position: fixed;
                top: -80px;
                left: 50%;
                transform: translateX(-50%);
                background-color: #4CAF50;
                color: white;
                padding: 15px 20px;
                border-radius: 5px;
                display: flex;
                align-items: center;
                gap: 10px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.2);
                transition: top 0.5s ease-in-out;
                z-index: 9999;
            }
            .alert-slide.show {
                top: 20px;
            }
            .alert-slide button {
                background: none;
                border: none;
                color: white;
                font-size: 20px;
                cursor: pointer;
            }
            .alert-slide button:hover {
                color: #bfbbbbff;
            }
        </style>
    </head>
    <body>

    <div id="successAlert" class="alert-slide">
        <span>✅ Data Peserta Berhasil Ditambahkan</span>
        <button onclick="closeAlert()">×</button>
    </div>

    <script>
        // Munculkan alert dengan animasi
        setTimeout(() => {
            document.getElementById('successAlert').classList.add('show');
        }, 100);

        // Hilang otomatis setelah 5 detik
        setTimeout(() => {
            closeAlert();
        }, 1000);

        function closeAlert() {
            document.getElementById('successAlert').classList.remove('show');
            setTimeout(() => {
                window.location.href = "tambah_peserta.php?status=success";
            }, 500); // tunggu animasi selesai
        }
    </script>

    </body>
    </html>
    <?php
} else {
    echo "Error: " . mysqli_error($koneksi);
}
?>
