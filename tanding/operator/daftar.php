<?php
include "../../backend/includes/connection.php";
$status_filter = isset($_GET['status']) ? ($_GET['status'] == '' ? '-' : $_GET['status']) : '';

//mencari TOTAL partai
$sqltotalpartai = mysqli_query($koneksi, "SELECT COUNT(*) FROM jadwal_tanding");
$totalpartai = mysqli_fetch_array($sqltotalpartai);

//mencari TOTAL partai SELESAI
$sqlpartaiselesai = mysqli_query($koneksi, "SELECT COUNT(*) FROM jadwal_tanding WHERE status='selesai'");
$partaiselesai = mysqli_fetch_array($sqlpartaiselesai);

//Mencari data jadwal pertandingan berdasarkan status
if ($status_filter === 'proses') {
    $sqljadwal = "SELECT * FROM jadwal_tanding WHERE status='proses' ORDER BY id_partai ASC";
} elseif ($status_filter === 'selesai') {
    $sqljadwal = "SELECT * FROM jadwal_tanding WHERE status='selesai' ORDER BY id_partai ASC";
} else {
    $sqljadwal = "SELECT * FROM jadwal_tanding WHERE status='-' ORDER BY id_partai ASC";
}
$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <link rel="shortcut icon" href="../../assets/img/LogoIPSI.png">
    <title>Daftar Pertandingan</title>
    <link rel="stylesheet" href="../../assets/bootstrap/dist/css/bootstrap.min.css">
    <script src="../../assets/jquery/jquery.min.js"></script>
    <script src="../../assets/bootstrap/dist/js/bootstrap.min.js"></script>
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

<body class="bg-dark">
    <div class="container mt-5 bg-light p-5 rounded-5">
        <b class="text-uppercase"><b class="nama"></b> Jadwal Pertandingan</b>
        <hr>
        <div class="ms-auto col-4 text-end">
            <a href="daftar.php?status=" class="btn btn-light border border-1 btn-sm reload-btn">
                Refresh
            </a>
            <button class="btn btn-danger bg-gradient btn-sm keluar small" onclick="keluar()">
                Keluar
            </button>
        </div>

        <label class="form-label mb-0">Tampilkan Bagan :</label>
        <!-- <div class="d-flex justify-content-between align-items-center mb-3"> -->
        <div class="d-flex">
            <select id="golongan" class="form-control my-3 me-2">
                <option value="">Golongan</option>
                <option value="Usia Dini 2A">Usia Dini 2A</option>
                <option value="Usia Dini 2B">Usia Dini 2B</option>
                <option value="Pra Remaja">Pra Remaja</option>
                <option value="Remaja">Remaja</option>
            </select>
            <select id="kategori" class="form-control my-3 me-2">
                <option value="">Kategori</option>
                <option value="Putra">Putra</option>
                <option value="Putri">Putri</option>
            </select>
            <select id="kelas" class="form-control my-3 me-2">
                <option value="">Kelas</option>
                <option value="1">UNDER</option>
                <option value="2">KELAS A</option>
                <option value="3">KELAS B</option>
                <option value="4">KELAS C</option>
                <option value="5">KELAS D</option>
                <option value="6">KELAS E</option>
                <option value="7">KELAS F</option>
                <option value="8">KELAS G</option>
                <option value="9">KELAS H</option>
                <option value="10">KELAS I</option>
                <option value="11">KELAS J</option>
                <option value="12">KELAS K</option>
                <option value="13">KELAS L</option>
                <option value="14">KELAS M</option>
                <option value="15">KELAS N</option>
                <option value="16">KELAS O</option>
                <option value="17">KELAS P</option>
                <option value="18">KELAS Q</option>
                <option value="19">KELAS R</option>
            </select>
            <button id="bagan" class="btn btn-secondary my-3">Tampil</button>
        </div>

        <!-- <div>
                <button aria-label="Open Fullscreen" id="openfull" onclick="openFullscreen();" class="btn p-0">
                    <svg width="24" height="24" viewBox="0 0 24 24">
                        <path fill="#2d2d2d"
                            d="M5,5H10V7H7V10H5V5M14,5H19V10H17V7H14V5M17,14H19V19H14V17H17V14M10,17V19H5V14H7V17H10Z" />
                    </svg>
                </button>
                <button aria-label="Exit Fullscreen" id="exitfull" onclick="closeFullscreen();" class="btn p-0">
                    <svg width="24" height="24" viewBox="0 0 24 24">
                        <path fill="#2d2d2d"
                            d="M14,14H19V16H16V19H14V14M5,14H10V19H8V16H5V14M8,5H10V10H5V8H8V5M19,8V10H14V5H16V8H19Z" />
                    </svg>
                </button>
            </div> -->
        <!-- </div> -->
        <ul class="nav nav-tabs mb-3 bg-light" id="tabMenu">
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
            <h6 class="fw-bold">BABAK SEMIFINAL</h6>
            <table class="table table-bordered" id="jadwalTable">
                <thead class="table-light">
                    <tr class="text-center">
                        <th>PARTAI</th>
                        <th>BABAK</th>
                        <th>KELOMPOK</th>
                        <th class="bg-primary bg-gradient text-white">SUDUT BIRU</th>
                        <th class="bg-danger bg-gradient text-white">SUDUT MERAH</th>
                        <th>STATUS</th>
                        <th>PEMENANG</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody id="jadwal-body">

                </tbody>
            </table>

            <h6 class="fw-bold">BABAK FINAL</h6>
            <table class="table table-bordered" id="jadwalTableFinal">
                <thead class="table-light">
                    <tr class="text-center">
                        <th>PARTAI</th>
                        <th>BABAK</th>
                        <th>KELOMPOK</th>
                        <th class="bg-primary bg-gradient text-white">SUDUT BIRU</th>
                        <th class="bg-danger bg-gradient text-white">SUDUT MERAH</th>
                        <th>STATUS</th>
                        <th>PEMENANG</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody id="jadwal-bodyFinal">

                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            // Baca dari localStorage
            const savedGolongan = localStorage.getItem('golongan');
            const savedKategori = localStorage.getItem('kategori');
            const savedKelas = localStorage.getItem('kelas');

            if (savedGolongan) $('#golongan').val(savedGolongan);
            if (savedKategori) $('#kategori').val(savedKategori);
            const kls = localStorage.getItem('kls');
            if (kls) $('#kelas').val(kls); // ✅ pakai value, bukan text

            // Load data awal
            loadJadwal();
            loadJadwalFinal();

            // Koneksi WebSocket
            const ws = new WebSocket('ws://localhost:3000');
            ws.onopen = () => console.log("Server WebSocket terhubung.");
            ws.onerror = err => console.error("WebSocket error:", err);
            ws.onclose = () => alert("Koneksi WebSocket terputus.");

            // Fungsi render jadwal semifinal
            function renderJadwal(data) {
                let tbody = '';
                data.forEach(jadwal => {
                    tbody += `<tr>
                <td rowspan="2" class="text-center align-middle">${jadwal.partai}</td>
                <td rowspan="2" class="text-center align-middle">${jadwal.babak}</td>
                <td rowspan="2" class="align-middle">${jadwal.kelas}</td>
                <td class="bg-primary bg-gradient text-white text-uppercase">${jadwal.nm_biru}</td>
                <td class="bg-danger bg-gradient text-white text-uppercase">${jadwal.nm_merah}</td>
                <td rowspan="2" class="text-center align-middle">${jadwal.status.charAt(0).toUpperCase() + jadwal.status.slice(1)}</td>
                <td rowspan="2" class="text-center align-middle">
                    ${jadwal.pemenang.toLowerCase() === 'biru' ? '<span class="badge bg-primary p-2">Biru</span>' :
                      jadwal.pemenang.toLowerCase() === 'merah' ? '<span class="badge bg-danger p-2">Merah</span>' :
                      '<span class="badge bg-secondary p-2">-</span>'}
                </td>
                <td rowspan="2" class="text-center align-middle">
                    ${jadwal.status === 'selesai' ? 'Pertandingan Selesai' :
                      `<a href="operator.php?id_partai=${jadwal.id_partai}" class="btn btn-success bg-gradient btn-sm">Masuk</a>`}
                </td>
            </tr>
            <tr>
                <td class="bg-light bg-gradient text-dark text-uppercase">${jadwal.kontingen_biru}</td>
                <td class="bg-light bg-gradient text-dark text-uppercase">${jadwal.kontingen_merah}</td>
            </tr>`;
                });
                $('#jadwalTable tbody').html(tbody);
            }

            // Fungsi render jadwal final
            function renderJadwalFinal(data) {
                let tbody = '';
                data.forEach(jadwal => {
                    tbody += `<tr>
                <td rowspan="2" class="text-center align-middle">${jadwal.partai}</td>
                <td rowspan="2" class="text-center align-middle">${jadwal.babak}</td>
                <td rowspan="2" class="align-middle">${jadwal.kelas}</td>
                <td class="bg-primary bg-gradient text-white text-uppercase">${jadwal.nm_biru}</td>
                <td class="bg-danger bg-gradient text-white text-uppercase">${jadwal.nm_merah}</td>
                <td rowspan="2" class="text-center align-middle">${jadwal.status.charAt(0).toUpperCase() + jadwal.status.slice(1)}</td>
                <td rowspan="2" class="text-center align-middle">
                    ${jadwal.pemenang.toLowerCase() === 'biru' ? '<span class="badge bg-primary p-2">Biru</span>' :
                      jadwal.pemenang.toLowerCase() === 'merah' ? '<span class="badge bg-danger p-2">Merah</span>' :
                      '<span class="badge bg-secondary p-2">-</span>'}
                </td>
                <td rowspan="2" class="text-center align-middle">
                    ${jadwal.status === 'selesai' ? 'Pertandingan Selesai' :
                      `<a href="operator.php?id_partai=${jadwal.id_partai}" class="btn btn-success bg-gradient btn-sm">Masuk</a>`}
                </td>
            </tr>
            <tr>
                <td class="bg-light bg-gradient text-dark text-uppercase">${jadwal.kontingen_biru}</td>
                <td class="bg-light bg-gradient text-dark text-uppercase">${jadwal.kontingen_merah}</td>
            </tr>`;
                });
                $('#jadwal-bodyFinal').html(tbody);
            }

            // Load jadwal via AJAX
            function loadJadwal() {
                const golongan = $('#golongan').val();
                const kategori = $('#kategori').val();
                const kelasText = $('#kelas option:selected').text(); // ✅ kirim text untuk tampil
                console.log(golongan + " " + kategori + " " + kelasText);
                // console.log(kelasText);
                $.ajax({
                    url: 'get_jadwal.php',
                    method: 'GET',
                    data: {
                        status: (<?php echo json_encode($status_filter); ?> === '' ? '-' : <?php echo json_encode($status_filter); ?>),
                        kelas: golongan + " " + kategori + " " + kelasText,
                    },
                    dataType: 'json',
                    success: function(data) {
                        renderJadwal(data);
                    },
                    error: function(xhr, status, err) {
                        console.error('Gagal load jadwal:', err);
                    }
                });
            }

            function loadJadwalFinal() {
                const golongan = $('#golongan').val();
                const kategori = $('#kategori').val();
                const kelasText = $('#kelas option:selected').text();
                console.log(golongan + " " + kategori + " " + kelasText);
                $.ajax({
                    url: 'get_jadwalFinal.php',
                    method: 'GET',
                    data: {
                        status: (<?php echo json_encode($status_filter); ?> === '' ? '-' : <?php echo json_encode($status_filter); ?>),
                        kelas: golongan + " " + kategori + " " + kelasText,
                    },
                    dataType: 'json',
                    success: function(data) {
                        renderJadwalFinal(data);
                    },
                    error: function(xhr, status, err) {
                        console.error('Gagal load jadwal:', err);
                    }
                });
            }

            // Event pilih kelas
            $('#bagan').on('click', function(event) {
                event.preventDefault();

                const golongan = $('#golongan').val();
                const kategori = $('#kategori').val();
                const kelas = $('#kelas').val(); // ✅ simpan value, bukan text

                // if (!golongan || !kategori || !kelas) {
                //     alert('Silakan pilih semua terlebih dahulu');
                //     return;
                // }

                // Simpan ke localStorage
                localStorage.setItem('golongan', golongan);
                localStorage.setItem('kategori', kategori);
                localStorage.setItem('kelas', kelas);
                localStorage.setItem('kls', kelas);

                // Kirim WebSocket dan load data
                ws.send(JSON.stringify({
                    type: 'selectKelas',
                    golongan,
                    kategori,
                    kelas,
                }));
                loadJadwal();
                loadJadwalFinal();
            });
        });
    </script>
</body>

</html>