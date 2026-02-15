
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
        #openfull,
        #exitfull {
            background: 0 0;
            border: none;
            cursor: pointer;
            padding: 0;
            margin: 0;
            text-align: center;
            width: 30px;
            height: 55px;
            line-height: 55px;
            float: left;
        }

        #openfull:active,
        #exitfull:active,
        #openfull:focus,
        #exitfull:focus {
            outline: 0;
        }

        #openfull svg,
        #exitfull svg {
            vertical-align: middle;
        }

        #exitfull {
            display: none;
        }

        .button-active {
            background-color: #198754 !important;
            color: white !important;
            border-color: #198754 !important;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <b class="text-uppercase"><b class="nama"></b> Jadwal Pertandingan</b>
            <div class="d-flex gap-2 mt-1">
                <a href="daftar.php?status=" class="btn btn-light border border-1 btn-sm reload-btn">
                    Refresh
                </a>
                <button class="btn btn-danger bg-gradient btn-sm keluar small" onclick="keluar();">
                    Keluar
                </button>
            </div>
        </div>
        <ul class="nav nav-tabs mb-3" id="tabMenu">
            <li class="nav-item <?php echo $status_filter === '-' ? 'bg-info' : ''; ?>">
                <a class="nav-link text-dark" href="?status=">BELUM MAIN</a>
            </li>
            <li class="nav-item <?php echo $status_filter === 'proses' ? 'bg-info' : ''; ?>">
                <a class="nav-link text-dark" href="?status=proses">PROSES</a>
            </li>
            <li class="nav-item <?php echo $status_filter === 'selesai' ? 'bg-info' : ''; ?>">
                <a class="nav-link text-dark" href="?status=selesai">SELESAI</a>
            </li>
        </ul>

        <div class="row text-center mb-3">
            <div class="col-md-4 mb-2">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">TOTAL</h5>
                        <p class="card-text fw-bold"><?php echo $totalpartai[0]; ?> Partai</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">SELESAI</h5>
                        <p class="card-text fw-bold"><?php echo $partaiselesai[0]; ?> Partai</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">SISA</h5>
                        <p class="card-text fw-bold"><?php echo $totalpartai[0] - $partaiselesai[0]; ?> Partai</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="jadwaltanding" class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                <tr class="text-center">
                        <th>PARTAI</th>
                        <th>KATEGORI</th>
                        <th>GOLONGAN</th>
                        <th class="bg-danger bg-gradient text-white">SUDUT MERAH</th>
                        <th class="bg-primary bg-gradient text-white">SUDUT BIRU</th>
                        <th>SKOR</th>
                        <th>PEMENANG</th>
                        <th>ACTION</th>
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
            window.location.href = "http://192.168.100.133/skordigital/TGR/regu/operator";
        }

        $(document).ready(function () {
            function loadJadwal() {
                $("#jadwal-body").load("jadwal-tbody.php?status=<?php echo $_GET['status'] ?? ''; ?>");
            }

            const elem = document.documentElement;

            window.openFullscreen = function () {
                if (elem.requestFullscreen) elem.requestFullscreen();
                else if (elem.webkitRequestFullscreen) elem.webkitRequestFullscreen();
                $("#openfull").hide();
                $("#exitfull").show();
            };

            window.closeFullscreen = function () {
                if (document.exitFullscreen) document.exitFullscreen();
                else if (document.webkitExitFullscreen)
                    document.webkitExitFullscreen();
                $("#openfull").show();
                $("#exitfull").hide();
            };

            $('.nama').text(localStorage.getItem('nama_operator'));
            if (localStorage.getItem('is_login') < 1) {
                localStorage.clear();
                window.location.href = "http://192.168.100.133/skordigital/TGR/REGU/operator";
            }
        });

        const ws = new WebSocket('ws://192.168.100.133:3000');

        ws.onopen = () => {
            console.log("Server terhubung.");
        };

        ws.onmessage = (event) => { }

        ws.onerror = (error) => {
            console.error("Server error:", error);
        };

        ws.onclose = () => {
            alert("Koneksi server terputus.");
        };
    </script>
</body>

</html>