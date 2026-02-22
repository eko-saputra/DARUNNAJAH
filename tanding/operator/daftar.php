<?php
include "../../backend/includes/connection.php";
$status_filter = isset($_GET['status']) ? ($_GET['status'] == '' ? '-' : $_GET['status']) : '';

// mencari TOTAL partai
$sqltotalpartai = mysqli_query($koneksi, "SELECT COUNT(*) FROM jadwal_tanding");
$totalpartai = mysqli_fetch_array($sqltotalpartai);

// mencari TOTAL partai SELESAI
$sqlpartaiselesai = mysqli_query($koneksi, "SELECT COUNT(*) FROM jadwal_tanding WHERE status='selesai'");
$partaiselesai = mysqli_fetch_array($sqlpartaiselesai);

// Mencari data jadwal pertandingan berdasarkan status
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
<html lang="id" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <link rel="shortcut icon" href="../../assets/img/LogoIPSI.png">
    <title>Daftar Pertandingan Laga</title>
    <link rel="stylesheet" href="../../assets/bootstrap/dist/css/bootstrap.min.css">
    <script src="../../assets/jquery/jquery.min.js"></script>
    <script src="../../assets/bootstrap/dist/js/bootstrap.min.js"></script>
    <style>
        body {
            background-color: #121212;
        }

        .container-main {
            background: linear-gradient(145deg, #1e2532 0%, #141b26 100%);
            box-shadow: 0 25px 50px -8px rgba(0, 0, 0, 0.6), inset 0 2px 4px rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 180, 255, 0.15);
        }

        .nav-link {
            color: #ccc;
        }

        .nav-tabs .nav-link.active {
            background-color: #0d6efd !important;
            color: white !important;
            border: none;
        }

        .card {
            background: rgba(0, 0, 0, 0.3);
            border: 2px solid rgba(56, 189, 248, 0.2);
        }

        /* Styling Scrollbar untuk Dark Mode */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1e1e1e;
        }

        ::-webkit-scrollbar-thumb {
            background: #444;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>

<body>
    <div class="container mt-5 mb-5 container-main p-4 p-md-5 rounded-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-uppercase mb-0 fw-bold text-primary">
                <span class="nama"></span> Jadwal Pertandingan Laga
            </h4>
            <div class="d-flex gap-2">
                <button onclick="refreshPage()" class="btn btn-outline-info btn-sm px-3">
                    <i class="bi bi-arrow-clockwise"></i> Refresh
                </button>
                <button class="btn btn-danger btn-sm px-3" onclick="keluar()">
                    Keluar
                </button>
            </div>
        </div>
        <hr class="opacity-10">

        <div class="row g-2 mb-4">
            <div class="col-md-3">
                <label class="small text-secondary">Golongan</label>
                <select id="golongan" class="form-select border-secondary">
                    <option value="">Pilih Golongan</option>
                    <option value="Usia Dini 2A">Usia Dini 2A</option>
                    <option value="Usia Dini 2B">Usia Dini 2B</option>
                    <option value="Pra Remaja">Pra Remaja</option>
                    <option value="Remaja">Remaja</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="small text-secondary">Kategori</label>
                <select id="kategori" class="form-select border-secondary">
                    <option value="">Pilih Kategori</option>
                    <option value="Putra">Putra</option>
                    <option value="Putri">Putri</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="small text-secondary">Kelas</label>
                <select id="kelas" class="form-select border-secondary">
                    <option value="">Pilih Kelas</option>
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
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button id="bagan" class="btn btn-primary w-100 shadow-sm">Tampil</button>
            </div>
        </div>

        <ul class="nav nav-pills mb-4 bg-dark p-1 rounded-3" id="tabMenu">
            <li class="nav-item flex-fill text-center">
                <a class="nav-link <?php echo ($status_filter === '-' || $status_filter === '') ? 'active' : ''; ?>" href="?status=">BELUM MAIN</a>
            </li>
            <li class="nav-item flex-fill text-center">
                <a class="nav-link <?php echo $status_filter === 'proses' ? 'active' : ''; ?>" href="?status=proses">PROSES</a>
            </li>
            <li class="nav-item flex-fill text-center">
                <a class="nav-link <?php echo $status_filter === 'selesai' ? 'active' : ''; ?>" href="?status=selesai">SELESAI</a>
            </li>
        </ul>

        <div class="row text-center mb-4">
            <div class="col-md-4 mb-2">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <small class="text-secondary text-uppercase">Total</small>
                        <h3 class="fw-bold mb-0"><?php echo $totalpartai[0]; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <div class="card h-100 shadow-sm border-0 border-start border-success border-4">
                    <div class="card-body">
                        <small class="text-secondary text-uppercase text-success">Selesai</small>
                        <h3 class="fw-bold mb-0 text-success"><?php echo $partaiselesai[0]; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <div class="card h-100 shadow-sm border-0 border-start border-warning border-4">
                    <div class="card-body">
                        <small class="text-secondary text-uppercase text-warning">Sisa</small>
                        <h3 class="fw-bold mb-0 text-warning"><?php echo $totalpartai[0] - $partaiselesai[0]; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div id="jadwaltanding">
            <div class="d-flex align-items-center mb-2">
                <div class="bg-primary rounded-circle me-2" style="width:10px; height:10px;"></div>
                <h6 class="fw-bold mb-0">BABAK SEMIFINAL</h6>
            </div>
            <div class="table-responsive mb-5">
                <table class="table table-dark table-hover table-bordered border-secondary shadow-sm" id="jadwalTable">
                    <thead class="table-secondary text-dark">
                        <tr class="text-center small">
                            <th>PARTAI</th>
                            <th>BABAK</th>
                            <th>KELOMPOK</th>
                            <th>SUDUT BIRU</th>
                            <th>SUDUT MERAH</th>
                            <th>STATUS</th>
                            <th>PEMENANG</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="jadwal-body" class="align-middle"></tbody>
                </table>
            </div>

            <div class="d-flex align-items-center mb-2">
                <div class="bg-warning rounded-circle me-2" style="width:10px; height:10px;"></div>
                <h6 class="fw-bold mb-0">BABAK FINAL</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-dark table-hover table-bordered border-secondary shadow-sm" id="jadwalTableFinal">
                    <thead class="table-secondary text-dark">
                        <tr class="text-center small">
                            <th>PARTAI</th>
                            <th>BABAK</th>
                            <th>KELOMPOK</th>
                            <th>SUDUT BIRU</th>
                            <th>SUDUT MERAH</th>
                            <th>STATUS</th>
                            <th>PEMENANG</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="jadwal-bodyFinal" class="align-middle"></tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Fungsi Keluar
        function keluar() {
            if (confirm("Apakah Anda yakin ingin keluar dari sistem?")) {
                window.location.href = "index.php"; // Sesuaikan path logout Anda
            }
        }

        // Fungsi Refresh Manual
        function refreshPage() {
            // Menambahkan parameter random agar browser tidak mengambil dari cache
            const url = new URL(window.location.href);
            url.searchParams.set('refresh', Date.now());
            window.location.href = url.href;
        }

        $(document).ready(function() {
            // Restore filters from localStorage
            const savedGolongan = localStorage.getItem('golongan');
            const savedKategori = localStorage.getItem('kategori');
            const savedKelas = localStorage.getItem('kls');

            if (savedGolongan) $('#golongan').val(savedGolongan);
            if (savedKategori) $('#kategori').val(savedKategori);
            if (savedKelas) $('#kelas').val(savedKelas);

            loadJadwal();
            loadJadwalFinal();

            const hostname = window.location.hostname;
            const ws = new WebSocket('ws://' + hostname + ':3000');

            function renderTableRows(data, targetSelector) {
                let tbody = '';
                if (data.length === 0) {
                    tbody = '<tr><td colspan="8" class="text-center py-4 text-secondary">Tidak ada data pertandingan</td></tr>';
                } else {
                    data.forEach(jadwal => {
                        tbody += `
                        <tr>
                            <td rowspan="2" class="text-center fw-bold text-info text-uppercase">${jadwal.partai}</td>
                            <td rowspan="2" class="text-center small text-uppercase">${jadwal.babak}</td>
                            <td rowspan="2" class="small text-uppercase">${jadwal.kelas}</td>
                            <td class="bg-primary bg-opacity-75 text-white fw-bold px-3 text-uppercase">${jadwal.nm_biru}</td>
                            <td class="bg-danger bg-opacity-75 text-white fw-bold px-3 text-uppercase">${jadwal.nm_merah}</td>
                            <td rowspan="2" class="text-center"><span class="badge border border-light text-uppercase">${jadwal.status.toUpperCase()}</span></td>
                            <td rowspan="2" class="text-center">
                                ${jadwal.pemenang.toLowerCase() === 'biru' ? '<span class="badge bg-primary w-100 text-uppercase">Biru</span>' :
                                  jadwal.pemenang.toLowerCase() === 'merah' ? '<span class="badge bg-danger w-100 text-uppercase">Merah</span>' :
                                  '<span class="text-muted text-uppercase">-</span>'}
                            </td>
                            <td rowspan="2" class="text-center text-uppercase">
                                ${jadwal.status === 'selesai' ? '<i class="text-success small">Done</i>' :
                                `<a href="operator.php?id_partai=${jadwal.id_partai}" class="btn btn-success btn-sm px-3 shadow-sm">Mulai</a>`}
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-dark text-secondary small px-3 text-uppercase">${jadwal.kontingen_biru}</td>
                            <td class="bg-dark text-secondary small px-3 text-uppercase">${jadwal.kontingen_merah}</td>
                        </tr>`;
                    });
                }
                $(targetSelector).html(tbody);
            }

            function loadJadwal() {
                const filterKelas = $('#golongan').val() + " " + $('#kategori').val() + " " + $('#kelas option:selected').text();
                $.ajax({
                    url: 'get_jadwal.php',
                    method: 'GET',
                    data: {
                        status: '<?php echo $status_filter; ?>' || '-',
                        kelas: filterKelas
                    },
                    dataType: 'json',
                    success: (data) => renderTableRows(data, '#jadwal-body'),
                    error: (err) => console.error('Error load semifinal:', err)
                });
            }

            function loadJadwalFinal() {
                const filterKelas = $('#golongan').val() + " " + $('#kategori').val() + " " + $('#kelas option:selected').text();
                $.ajax({
                    url: 'get_jadwalFinal.php',
                    method: 'GET',
                    data: {
                        status: '<?php echo $status_filter; ?>' || '-',
                        kelas: filterKelas
                    },
                    dataType: 'json',
                    success: (data) => renderTableRows(data, '#jadwal-bodyFinal'),
                    error: (err) => console.error('Error load final:', err)
                });
            }

            $('#bagan').on('click', function() {
                const g = $('#golongan').val();
                const k = $('#kategori').val();
                const kl = $('#kelas').val();

                localStorage.setItem('golongan', g);
                localStorage.setItem('kategori', k);
                localStorage.setItem('kls', kl);

                if (ws.readyState === WebSocket.OPEN) {
                    ws.send(JSON.stringify({
                        type: 'selectKelas',
                        golongan: g,
                        kategori: k,
                        kelas: kl
                    }));
                }
                loadJadwal();
                loadJadwalFinal();
            });
        });
    </script>
</body>

</html>