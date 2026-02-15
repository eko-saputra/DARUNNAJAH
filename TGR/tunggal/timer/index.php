<?php
include "../../../backend/includes/connection.php";

$id_partai = mysqli_real_escape_string($koneksi, $_GET["id_partai"]);
$sqljadwal = "SELECT * FROM jadwal_tgr WHERE id_partai='$id_partai'";
$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
$jadwal = mysqli_fetch_array($jadwal_tanding);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Operator Pertandingan TGR</title>
    <link rel="shortcut icon" href="../../../assets/img/LogoIPSI.png" />
    <link rel="stylesheet" href="../../../assets/bootstrap/dist/css/bootstrap.min.css" />
    <style>
        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            min-height: 100vh;
        }

        .card-custom {
            background: #0f3460;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .timer-display {
            font-family: 'Courier New', monospace;
            font-size: 5rem;
            font-weight: 800;
            letter-spacing: 5px;
            text-shadow: 0 0 20px rgba(0, 200, 255, 0.7);
            color: #00e5ff;
            padding: 20px;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 15px;
            border: 3px solid rgba(0, 229, 255, 0.2);
        }

        .btn-round {
            border-radius: 50px;
            font-weight: 700;
            padding: 12px 30px;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-round:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 15px rgba(0, 0, 0, 0.3);
        }

        .btn-start {
            background: linear-gradient(135deg, #00b09b, #96c93d);
        }

        .btn-pause {
            background: linear-gradient(135deg, #f7971e, #ffd200);
        }

        .btn-resume {
            background: linear-gradient(135deg, #00c6ff, #0072ff);
        }

        .btn-stop {
            background: linear-gradient(135deg, #ff416c, #ff4b2b);
        }

        .player-card {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 15px;
            padding: 25px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .player-card:hover {
            background: rgba(255, 255, 255, 0.12);
            transform: translateY(-5px);
        }

        .player-card.biru {
            border-color: #007bff;
        }

        .player-card.merah {
            border-color: #dc3545;
        }

        .player-card.selected {
            border-width: 3px;
            box-shadow: 0 0 25px rgba(0, 123, 255, 0.4);
        }

        .player-card.merah.selected {
            box-shadow: 0 0 25px rgba(220, 53, 69, 0.4);
        }

        .player-name {
            font-size: 1.8rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .player-kontingen {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .info-badge {
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 1.1rem;
        }

        .babak-display {
            background: linear-gradient(135deg, #8e2de2, #4a00e0);
            border-radius: 15px;
            padding: 15px 30px;
            font-size: 1.4rem;
            font-weight: 700;
        }

        .fullscreen-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 10px;
            transition: all 0.3s ease;
        }

        .fullscreen-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.1);
        }

        .time-input {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(0, 229, 255, 0.3);
            color: white;
            border-radius: 10px;
            padding: 10px 15px;
            font-size: 1.2rem;
        }

        .time-input:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #00e5ff;
            box-shadow: 0 0 15px rgba(0, 229, 255, 0.5);
            color: white;
        }

        .btn-pilih {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 10px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-pilih:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .btn-pilih:disabled {
            background: linear-gradient(135deg, #28a745, #20c997);
            transform: none;
            box-shadow: 0 0 15px rgba(40, 167, 69, 0.5);
        }

        .header-info {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 15px;
            padding: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center py-5">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="card-custom p-4 p-md-5">

                    <!-- Header Section -->
                    <div class="row align-items-center mb-5">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center gap-3">
                                <button class="btn btn-secondary bg-gradient tukar-partai btn-round" onclick="tukarpartai()">
                                    ← Tukar Partai
                                </button>
                                <a href="../monitor/view_tunggal.html" target="_blank" class="btn btn-primary bg-gradient btn-round">
                                    🖥️ Layar Monitor
                                </a>
                                <div class="d-flex align-items-center gap-2">
                                    <button id="openfull" onclick="openFullscreen();" class="fullscreen-btn">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="#fff">
                                            <path d="M14,5H19V10H17V7H14V5M17,14H19V19H14V17H17V14M10,17V19H5V14H7V17H10Z" />
                                        </svg>
                                    </button>
                                    <button id="exitfull" onclick="closeFullscreen();" class="fullscreen-btn d-none">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="#fff">
                                            <path d="M14,14H19V16H16V19H14V14M5,14H10V19H8V16H5V14M8,5H10V10H5V8H8V5M19,8V10H14V5H16V8H19Z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <div class="header-info">
                                <h5 class="mb-0 text-light">
                                    <span class="text-info">Golongan :</span>
                                    <?= htmlspecialchars($jadwal['kategori']) . "/" . htmlspecialchars($jadwal['golongan']) . "-" . htmlspecialchars($jadwal['id_partai']); ?>
                                </h5>
                            </div>
                        </div>
                    </div>

                    <!-- Timer Section -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <div class="text-center mb-4">
                                <div class="row justify-content-center">
                                    <div class="col-md-6 col-lg-4">
                                        <label class="form-label text-light text-uppercase mb-2">Set Waktu Pertandingan</label>
                                        <input type="time" id="input-time" class="form-control time-input text-center" onchange="setRemaining()">
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <div id="waktu" class="timer-display d-inline-block">02:00</div>
                            </div>
                        </div>
                    </div>

                    <!-- Timer Controls -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <div class="d-flex justify-content-center flex-wrap gap-3">
                                <button id="btn-start" class="btn btn-start btn-round text-white" onclick="startTimer()">
                                    <i class="fas fa-play me-2"></i>Start
                                </button>
                                <button id="btn-pause" class="btn btn-pause btn-round text-dark" onclick="pauseTimer()">
                                    <i class="fas fa-pause me-2"></i>Pause
                                </button>
                                <button id="btn-resume" class="btn btn-resume btn-round text-white" onclick="resumeTimer()">
                                    <i class="fas fa-play me-2"></i>Resume
                                </button>
                                <button id="btn-stop" class="btn btn-stop btn-round text-white" onclick="stopTimer()">
                                    <i class="fas fa-stop me-2"></i>Stop
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Players and Round Info -->
                    <div class="row g-4">
                        <!-- Player Biru -->
                        <div class="col-md-4">
                            <div class="player-card biru text-center h-100">
                                <div class="mb-3">
                                    <span class="badge bg-primary px-3 py-2 fs-6">BIRU</span>
                                </div>
                                <h3 class="player-name text-primary mb-2">
                                    <?= htmlspecialchars($jadwal['nm_biru'] ?? '-') ?>
                                </h3>
                                <p class="player-kontingen text-light mb-4">
                                    <?= htmlspecialchars($jadwal['kontingen_biru'] ?? '-') ?>
                                </p>
                                <button class="btn-pilih pilih-button" data-sudut="BIRU">Pilih</button>
                            </div>
                        </div>

                        <!-- Round Info -->
                        <div class="col-md-4 d-flex align-items-center justify-content-center">
                            <div class="text-center">
                                <div class="babak-display mb-3 text-white">
                                    <?= htmlspecialchars($jadwal['babak'] ?? '-') ?>
                                </div>
                                <div class="info-badge text-light mb-3">
                                    Babak Pertandingan
                                </div>
                                <div class="text-light">
                                    <small>ID Partai: <?= htmlspecialchars($jadwal['id_partai']) ?></small>
                                </div>
                            </div>
                        </div>

                        <!-- Player Merah -->
                        <div class="col-md-4">
                            <div class="player-card merah text-center h-100">
                                <div class="mb-3">
                                    <span class="badge bg-danger px-3 py-2 fs-6">MERAH</span>
                                </div>
                                <h3 class="player-name text-danger mb-2">
                                    <?= htmlspecialchars($jadwal['nm_merah'] ?? '-') ?>
                                </h3>
                                <p class="player-kontingen text-light mb-4">
                                    <?= htmlspecialchars($jadwal['kontingen_merah'] ?? '-') ?>
                                </p>
                                <button class="btn-pilih pilih-button" data-sudut="MERAH">Pilih</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="../../../assets/jquery/jquery.min.js"></script>
    <script>
        // Semua kode JavaScript asli tetap dipertahankan tanpa perubahan
        const waktuElem = $('#waktu');
        const btnStart = $('#btn-start');
        const btnPause = $('#btn-pause');
        const btnResume = $('#btn-resume');
        const btnStop = $('#btn-stop');
        const pilihButtons = $('.pilih-button');
        const is_login = localStorage.getItem('is_login');
        const operator = localStorage.getItem('operator');
        const nama_operator = localStorage.getItem('nama_operator');
        localStorage.setItem('waktu', '03:00');
        localStorage.setItem('timer', 180);

        let remaining = localStorage.getItem('timer');

        const staticPartaiData = {
            id_partai: "<?= htmlspecialchars($jadwal['id_partai']) ?>",
            partai: "<?= htmlspecialchars($jadwal['partai'] ?? '') ?>",
            kategori: "<?= htmlspecialchars($jadwal['kategori'] ?? '') ?>",
            golongan: "<?= htmlspecialchars($jadwal['golongan'] ?? '') ?>",
            babak: "<?= htmlspecialchars($jadwal['babak'] ?? '') ?>",
            nm_biru: "<?= htmlspecialchars($jadwal['nm_biru'] ?? '') ?>",
            kontingen_biru: "<?= htmlspecialchars($jadwal['kontingen_biru'] ?? '') ?>",
            nm_merah: "<?= htmlspecialchars($jadwal['nm_merah'] ?? '') ?>",
            kontingen_merah: "<?= htmlspecialchars($jadwal['kontingen_merah'] ?? '') ?>"
        };

        document.addEventListener('DOMContentLoaded', () => {
            const savedTime = localStorage.getItem('waktu');
            if (savedTime) {
                document.getElementById('input-time').value = savedTime;
                const [minutes, seconds] = savedTime.split(':').map(Number);
                remaining = (minutes * 60) + seconds;
            } else {
                localStorage.setItem('waktu', '02:00');
                document.getElementById('input-time').value = '02:00';
            }
            updateDisplay();
            updateButtons('stopped');

            const selectedPartaiData = JSON.parse(localStorage.getItem('currentPartai'));
            if (selectedPartaiData && selectedPartaiData.id_partai === staticPartaiData.id_partai) {
                const selectedSudutOnLoad = selectedPartaiData.sudut;
                pilihButtons.each(function() {
                    const $btn = $(this);
                    const sudut = $btn.data('sudut');
                    if (sudut === selectedSudutOnLoad) {
                        $btn.text('✔️ Dipilih');
                        $btn.prop('disabled', true);
                        $btn.closest('.player-card').addClass('selected');
                    } else {
                        $btn.text('Pilih');
                        $btn.prop('disabled', false);
                        $btn.closest('.player-card').removeClass('selected');
                    }
                });
            } else {
                pilihButtons.text('Pilih').prop('disabled', false);
                $('.player-card').removeClass('selected');
                btnStart.prop('disabled', true);
            }
        });

        function setRemaining() {
            const input = document.getElementById('input-time');
            const timeValue = input.value;

            if (timeValue) {
                const [minutes, seconds] = timeValue.split(':').map(Number);
                remaining = (minutes * 60) + seconds;
                console.log('Remaining time set to:', remaining, 'seconds');
                localStorage.setItem('timer', remaining);
                localStorage.setItem('waktu', timeValue);
                updateDisplay();
            } else {
                alert('Pilih waktu terlebih dahulu!');
            }
        }

        const ws = new WebSocket('ws://localhost:3000');

        ws.onopen = () => {
            console.log("Server terhubung.");
            const selectedPartaiData = JSON.parse(localStorage.getItem('currentPartai'));
            if (selectedPartaiData && selectedPartaiData.id_partai === staticPartaiData.id_partai) {
                refreshpartai();
            }
        };

        ws.onmessage = (event) => {
            const data = JSON.parse(event.data);
            if (typeof data.remaining === 'number') {
                remaining = data.remaining;
            }
            if (data.type === 'tick') {
                remaining = data.remaining;
                updateDisplay();
                updateButtons('tick');
            } else if (data.type === 'stopped') {
                updateButtons('stopped');
            } else if (data.type === 'paused') {
                updateButtons('paused');
            } else if (data.type === 'resumed') {
                updateButtons('resumed');
            } else if (data.type === 'ended') {
                updateButtons('ended');
            } else if (data.type === 'partai_data_tunggal') {
                const p = data.data;
                console.log("Data partai diterima dari WebSocket:", p);
                localStorage.setItem('partai', p.partai);
                localStorage.setItem('currentPartai', JSON.stringify(p));
            }
        };

        ws.onclose = function() {
            console.log('WebSocket disconnected');
        };

        function updateDisplay() {
            const m = Math.floor(remaining / 60);
            const s = remaining % 60;
            waktuElem.text(`${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`);
        }

        function updateButtons(state) {
            btnStart.hide();
            btnPause.hide();
            btnResume.hide();
            btnStop.hide();

            if (state === 'stopped' || state === 'ended') {
                btnStart.show();
                const storedPartai = JSON.parse(localStorage.getItem('currentPartai'));
                if (storedPartai && storedPartai.id_partai === staticPartaiData.id_partai) {
                    btnStart.prop('disabled', false);
                } else {
                    btnStart.prop('disabled', true);
                }
                $('.tukar-partai').prop('disabled', false);
            } else if (state === 'started' || state === 'resumed' || state === 'tick') {
                btnPause.show();
                btnStop.show();
                $('.tukar-partai').prop('disabled', true);
            } else if (state === 'paused') {
                btnResume.show();
                btnStop.show();
                $('.tukar-partai').prop('disabled', true);
            }
        }

        pilihButtons.on('click', function() {
            const $btn = $(this);
            const selectedSudut = $btn.data('sudut');

            let namaPesilat = '';
            let kontingenSudut = '';

            if (selectedSudut === 'BIRU') {
                namaPesilat = staticPartaiData.nm_biru;
                kontingenSudut = staticPartaiData.kontingen_biru;
            } else if (selectedSudut === 'MERAH') {
                namaPesilat = staticPartaiData.nm_merah;
                kontingenSudut = staticPartaiData.kontingen_merah;
            }

            const partaiToSave = {
                id_partai: staticPartaiData.id_partai,
                kategori: staticPartaiData.kategori,
                kelas: staticPartaiData.golongan,
                sudut: selectedSudut,
                nama: namaPesilat,
                kontingen: kontingenSudut
            };
            localStorage.setItem('currentPartai', JSON.stringify(partaiToSave));

            pilihButtons.each(function() {
                $(this).text('Pilih').prop('disabled', false);
                $(this).closest('.player-card').removeClass('selected');
            });

            $btn.text('✔️ Dipilih').prop('disabled', true);
            $btn.closest('.player-card').addClass('selected');

            btnStart.prop('disabled', false);
            refreshpartai();
        });

        function refreshpartai() {
            if (ws.readyState === WebSocket.OPEN) {
                const selectedPartaiData = JSON.parse(localStorage.getItem('currentPartai'));

                if (selectedPartaiData && selectedPartaiData.id_partai === staticPartaiData.id_partai) {
                    const partaiDataToSend = {
                        type: 'set_partai_tunggal',
                        partai: staticPartaiData.partai,
                        kategori: staticPartaiData.kategori,
                        babak: staticPartaiData.babak,
                        kelas: staticPartaiData.golongan,
                        peserta: {
                            nama: selectedPartaiData.nama,
                            kontingen: selectedPartaiData.kontingen,
                            sudut: selectedPartaiData.sudut
                        }
                    };
                    ws.send(JSON.stringify(partaiDataToSend));
                    console.log('Partai data sent to monitor:', partaiDataToSend);
                } else {
                    console.log("No matching selected partai data in localStorage to refresh, or current partai is different.");
                    ws.send(JSON.stringify({
                        type: 'clear_partai_data'
                    }));
                }
            } else {
                console.warn("WebSocket is not open. Cannot send refreshpartai data.");
            }
        }

        function tukarpartai() {
            console.log("Tukar partai");
            localStorage.removeItem('currentPartai');
            localStorage.removeItem('waktu');
            localStorage.removeItem('timer');
            localStorage.setItem('is_login', is_login);
            localStorage.setItem('operator', operator);
            localStorage.setItem('nama_operator', nama_operator);
            if (ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({
                    type: 'tukar_partai_tunggal'
                }));
            } else {
                console.warn("WebSocket not open for tukar_partai_tunggal.");
            }
            document.location.href = "http://localhost/DARUNNAJAH/TGR/tunggal/operator/daftar.php?status=";
        }

        function startTimer() {
            $('.tukar-partai').prop('disabled', true);
            const currentSelectedPartai = JSON.parse(localStorage.getItem('currentPartai'));
            if (!currentSelectedPartai) {
                alert('Mohon pilih peserta terlebih dahulu!');
                return;
            }
            if (ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({
                    type: 'start',
                    remaining: localStorage.getItem('timer'),
                    partai: currentSelectedPartai.id_partai,
                    sudut: currentSelectedPartai.sudut
                }));
            } else {
                console.warn("WebSocket not open for start timer.");
            }
        }

        function pauseTimer() {
            const currentSelectedPartai = JSON.parse(localStorage.getItem('currentPartai'));
            if (!currentSelectedPartai) return;
            if (ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({
                    type: 'pause',
                    partai: currentSelectedPartai.id_partai
                }));
            } else {
                console.warn("WebSocket not open for pause timer.");
            }
        }

        function resumeTimer() {
            const currentSelectedPartai = JSON.parse(localStorage.getItem('currentPartai'));
            if (!currentSelectedPartai) return;
            if (ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({
                    type: 'resume',
                    partai: currentSelectedPartai.id_partai
                }));
            } else {
                console.warn("WebSocket not open for resume timer.");
            }
        }

        function stopTimer() {
            $('.tukar-partai').prop('disabled', false);
            const currentSelectedPartai = JSON.parse(localStorage.getItem('currentPartai'));
            if (!currentSelectedPartai) return;
            if (ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({
                    type: 'stop',
                    partai: currentSelectedPartai.id_partai
                }));
            } else {
                console.warn("WebSocket not open for stop timer.");
            }
        }

        function openFullscreen() {
            const elem = document.documentElement;
            if (elem.requestFullscreen) elem.requestFullscreen();
            else if (elem.webkitRequestFullscreen) elem.webkitRequestFullscreen();
            else if (elem.msRequestFullscreen) elem.msRequestFullscreen();
            $('#openfull').hide();
            $('#exitfull').show();
        }

        function closeFullscreen() {
            if (document.exitFullscreen) document.exitFullscreen();
            else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
            else if (document.msExitFullscreen) document.msExitFullscreen();
            $('#openfull').show();
            $('#exitfull').hide();
        }
    </script>
</body>

</html>