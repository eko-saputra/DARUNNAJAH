<?php
include "../../../backend/includes/connection.php";

//mencari TOTAL partai
$sqltotalpartai = mysqli_query($koneksi, "SELECT COUNT(*) FROM jadwal_tgr");
$totalpartai = mysqli_fetch_array($sqltotalpartai);

//mencari TOTAL partai SELESAI
$sqlpartaiselesai = mysqli_query($koneksi, "SELECT COUNT(*) FROM jadwal_tgr WHERE status='selesai'");
$partaiselesai = mysqli_fetch_array($sqlpartaiselesai);

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <link rel="shortcut icon" href="../../../assets/img/LogoIPSI.png">
    <title>Daftar Pertandingan</title>
    <link rel="stylesheet" href="../../../assets/bootstrap/dist/css/bootstrap.min.css">
    <script src="../../../assets/jquery/jquery.min.js"></script>
    <script src="../../../assets/bootstrap/dist/js/bootstrap.min.js"></script>
    <style>
        :root {
            --bg-primary: #0a0a0f;
            --bg-secondary: #1a1a24;
            --bg-card: #1e1e2d;
            --border-color: #2a2a3a;
            --text-primary: #f0f0f0;
            --text-secondary: #b0b0c0;
            --accent-blue: #3a86ff;
            --accent-red: #ff4d6d;
            --accent-green: #38b000;
            --accent-purple: #8338ec;
        }

        body {
            background: var(--bg-primary);
            /* min-height: 100vh; */
            color: var(--text-primary);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .button-active {
            background-color: var(--accent-green) !important;
            color: white !important;
            border-color: var(--accent-green) !important;
        }

        .container {
            background: var(--bg-secondary);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .nav-tabs {
            border-bottom: 2px solid var(--border-color);
        }

        .nav-link {
            background: var(--bg-card);
            margin-right: 8px;
            color: var(--text-secondary);
            border: 2px solid var(--border-color);
            border-bottom: none;
            border-radius: 8px 8px 0 0;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background: var(--border-color);
            color: var(--text-primary);
            border-color: var(--accent-blue);
        }

        .nav-item.bg-info .nav-link {
            background: var(--accent-blue) !important;
            color: white !important;
            border-color: var(--accent-blue) !important;
        }

        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
        }

        .card-title {
            color: var(--text-secondary);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .card-text {
            color: var(--text-primary);
            font-size: 1.8rem;
            font-weight: 700;
        }

        .table {
            color: var(--text-primary);
            border-color: var(--border-color);
            margin-bottom: 0;
        }

        .table thead th {
            background: var(--bg-card);
            border-bottom: 2px solid var(--border-color);
            font-weight: 600;
            /* padding: 16px 12px; */
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .table tbody td {
            background: var(--bg-card);
            border-color: var(--border-color);
            /* padding: 14px 12px; */
            vertical-align: middle;
        }

        .table tbody tr:hover td {
            background: rgba(58, 134, 255, 0.1);
        }

        .table-bordered {
            border: 1px solid var(--border-color);
            border-radius: 10px;
            overflow: hidden;
        }

        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }

        .bg-danger {
            background: linear-gradient(135deg, var(--accent-red), #c1121f) !important;
        }

        .bg-primary {
            background: linear-gradient(135deg, var(--accent-blue), #1a759f) !important;
        }

        b.text-uppercase {
            color: var(--text-primary);
            font-size: 1.5rem;
            font-weight: 700;
            position: relative;
            padding-left: 15px;
        }

        b.text-uppercase::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 5px;
            height: 70%;
            background: var(--accent-blue);
            border-radius: 3px;
        }

        .d-flex.justify-content-between {
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
                margin-top: 10px;
            }

            .card-text {
                font-size: 1.4rem;
            }

            b.text-uppercase {
                font-size: 1.2rem;
            }

            .table-responsive {
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <div class="container mt-4 mt-md-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <b class="text-uppercase"><b class="nama"></b> Jadwal Pertandingan Seni</b>
            <div class="d-flex gap-2 mt-1">
                <a href="daftar.php?status=" class="btn btn-light border border-1 btn-sm reload-btn">
                    Refresh
                </a>
                <button class="btn btn-danger bg-gradient btn-sm keluar small" onclick="keluar();">
                    Keluar
                </button>
            </div>
        </div>

        <ul class="nav nav-tabs mb-4" id="tabMenu">
            <li class="nav-item <?php echo $status_filter === '-' ? 'bg-info' : ''; ?>">
                <a class="nav-link" href="?status=">BELUM MAIN</a>
            </li>
            <li class="nav-item <?php echo $status_filter === 'proses' ? 'bg-info' : ''; ?>">
                <a class="nav-link" href="?status=proses">PROSES</a>
            </li>
            <li class="nav-item <?php echo $status_filter === 'selesai' ? 'bg-info' : ''; ?>">
                <a class="nav-link" href="?status=selesai">SELESAI</a>
            </li>
        </ul>

        <div class="row text-center mb-4 g-3">
            <div class="col-md-4 mb-2">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h5 class="card-title">TOTAL</h5>
                        <p class="card-text fw-bold"><?php echo $totalpartai[0]; ?> Partai</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h5 class="card-title">SELESAI</h5>
                        <p class="card-text fw-bold"><?php echo $partaiselesai[0]; ?> Partai</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h5 class="card-title">SISA</h5>
                        <p class="card-text fw-bold"><?php echo $totalpartai[0] - $partaiselesai[0]; ?> Partai</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="jadwaltanding" class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th class="text-dark bg-light">PARTAI</th>
                        <th class="text-dark bg-light">KATEGORI</th>
                        <th class="text-dark bg-light">GOLONGAN</th>
                        <th class="bg-primary bg-gradient text-white">SUDUT BIRU</th>
                        <th class="bg-danger bg-gradient text-white">SUDUT MERAH</th>
                        <th class="text-dark bg-light">PEMENANG</th>
                        <th class="text-dark bg-light">ACTION</th>
                    </tr>
                </thead>
                <tbody id="jadwal-body">
                    <?php include "jadwal-tbody.php"; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function keluar() {
            window.location.href = "http://localhost/DARUNNAJAH/TGR/tunggal/operator";
        }

        $(document).ready(function() {
            function loadJadwal() {
                $("#jadwal-body").load("jadwal-tbody.php?status=<?php echo $_GET['status'] ?? ''; ?>");
            }

            const elem = document.documentElement;

            window.openFullscreen = function() {
                if (elem.requestFullscreen) elem.requestFullscreen();
                else if (elem.webkitRequestFullscreen) elem.webkitRequestFullscreen();
                $("#openfull").hide();
                $("#exitfull").show();
            };

            window.closeFullscreen = function() {
                if (document.exitFullscreen) document.exitFullscreen();
                else if (document.webkitExitFullscreen)
                    document.webkitExitFullscreen();
                $("#openfull").show();
                $("#exitfull").hide();
            };

            $('.nama').text(localStorage.getItem('nama_operator'));
            if (localStorage.getItem('is_login') < 1) {
                localStorage.clear();
                window.location.href = "http://localhost/DARUNNAJAH/TGR/tunggal/operator";
            }
        });

        const ws = new WebSocket('ws://localhost:3000');

        ws.onopen = () => {
            console.log("Server terhubung.");
        };

        ws.onmessage = (event) => {}

        ws.onerror = (error) => {
            console.error("Server error:", error);
        };

        ws.onclose = () => {
            alert("Koneksi server terputus.");
        };
    </script>
</body>

</html>