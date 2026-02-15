<?php
include "../../../backend/includes/connection.php";

$id_partai = mysqli_real_escape_string($koneksi, $_GET["id_partai"]);

// Ambil partai saat ini
$sqljadwal = "SELECT * FROM jadwal_tgr WHERE id_partai='$id_partai'";
$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
$jadwal = mysqli_fetch_array($jadwal_tanding);

// Ambil partai sebelumnya
$sql_prev = "SELECT id_partai FROM jadwal_tgr WHERE id_partai < '$id_partai' ORDER BY id_partai DESC LIMIT 1";
$result_prev = mysqli_query($koneksi, $sql_prev);
$prev_partai = mysqli_fetch_assoc($result_prev);

// Ambil partai berikutnya
$sql_next = "SELECT id_partai FROM jadwal_tgr WHERE id_partai > '$id_partai' ORDER BY id_partai ASC LIMIT 1";
$result_next = mysqli_query($koneksi, $sql_next);
$next_partai = mysqli_fetch_assoc($result_next);
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
            background: #121212;
            min-height: 100vh;
            color: #e0e0e0;
        }

        .card-custom {
            background: rgba(30, 30, 30, 0.95);
            border-radius: 20px;
            border: 1px solid rgba(0, 150, 255, 0.2);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
        }

        .timer-display {
            font-family: 'Courier New', monospace;
            font-size: 5.5rem;
            font-weight: 900;
            letter-spacing: 8px;
            color: #00e5ff;
            text-shadow: 0 0 20px rgba(0, 229, 255, 0.8),
                0 0 40px rgba(0, 229, 255, 0.4);
            padding: 25px 40px;
            background: rgba(0, 0, 0, 0.6);
            border-radius: 20px;
            border: 3px solid rgba(0, 229, 255, 0.3);
            display: inline-block;
            min-width: 300px;
        }

        .btn-round {
            border-radius: 50px;
            font-weight: 700;
            padding: 14px 35px;
            font-size: 1.1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-round:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
        }

        .btn-round:active {
            transform: translateY(-1px);
        }

        .btn-start {
            background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
            color: white;
        }

        .btn-pause {
            background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
            color: #212529;
        }

        .btn-resume {
            background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);
            color: white;
        }

        .btn-stop {
            background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
            color: white;
        }

        .btn-nav {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 25px;
            font-weight: 600;
            border-radius: 50px;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-nav:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-nav:disabled {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: #adb5bd;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .player-card {
            background: rgba(40, 40, 40, 0.8);
            border-radius: 15px;
            padding: 30px 25px;
            transition: all 0.3s ease;
            border: none;
            /* Removed border */
            height: 100%;
            position: relative;
        }

        .player-card:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            border-radius: 15px 15px 0 0;
        }

        .player-card.biru:before {
            background: linear-gradient(90deg, #007bff, #00b3ff);
        }

        .player-card.merah:before {
            background: linear-gradient(90deg, #dc3545, #ff6b6b);
        }

        .player-card:hover {
            background: rgba(50, 50, 50, 0.9);
            transform: translateY(-5px);
        }

        .player-card.selected {
            box-shadow: 0 0 50px rgba(64, 156, 255, 0.7);
            /* Brighter blue shadow */
            background: linear-gradient(135deg, rgba(30, 60, 114, 0.9), rgba(42, 82, 152, 0.9));
            /* Brighter blue gradient */
        }

        .player-card.merah.selected {
            box-shadow: 0 0 50px rgba(255, 99, 132, 0.7);
            /* Brighter red shadow */
            background: linear-gradient(135deg, rgba(152, 30, 50, 0.9), rgba(203, 45, 74, 0.9));
            /* Brighter red gradient */
        }

        .player-name {
            font-size: 1.9rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 10px;
            line-height: 1.2;
            border: none;
            /* Removed border from name */
        }

        .player-card.biru .player-name {
            color: #4dabff;
            /* Brighter blue for name */
            text-shadow: 0 2px 15px rgba(77, 171, 255, 0.5);
            /* Brighter shadow */
        }

        .player-card.merah .player-name {
            color: #ff6b8b;
            /* Brighter red for name */
            text-shadow: 0 2px 15px rgba(255, 107, 139, 0.5);
            /* Brighter shadow */
        }

        .player-card.biru.selected .player-name {
            color: #80d0ff;
            /* Even brighter blue when selected */
            text-shadow: 0 0 20px rgba(128, 208, 255, 0.8);
            /* Stronger glow when selected */
        }

        .player-card.merah.selected .player-name {
            color: #ff9eb5;
            /* Even brighter red when selected */
            text-shadow: 0 0 20px rgba(255, 158, 181, 0.8);
            /* Stronger glow when selected */
        }

        .player-kontingen {
            font-size: 1.1rem;
            color: #aaa;
            margin-bottom: 25px;
            font-weight: 500;
        }

        .babak-section {
            background: linear-gradient(135deg, rgba(0, 150, 255, 0.1), rgba(138, 43, 226, 0.1));
            border-radius: 15px;
            padding: 25px;
            height: 100%;
            border: 1px solid rgba(0, 150, 255, 0.3);
        }

        .babak-title {
            color: #00e5ff;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .babak-value {
            font-size: 3.5rem;
            /* DIKECILKAN dari 4rem agar muat "SEMIFINAL" */
            font-weight: 900;
            color: #00b3ff;
            text-shadow: 0 0 20px rgba(0, 179, 255, 0.5);
            line-height: 1;
            word-break: break-word;
            min-height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .info-badge {
            background: rgba(0, 150, 255, 0.15);
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            color: #80d0ff;
            border: 1px solid rgba(0, 150, 255, 0.3);
        }

        .fullscreen-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 12px;
            transition: all 0.3s ease;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fullscreen-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: #00e5ff;
            transform: scale(1.1);
        }

        .time-input {
            background: rgba(0, 0, 0, 0.5);
            border: 2px solid rgba(0, 229, 255, 0.4);
            color: #00e5ff;
            border-radius: 12px;
            padding: 12px 20px;
            font-size: 1.3rem;
            font-weight: 600;
            text-align: center;
            font-family: 'Courier New', monospace;
            width: 100%;
        }

        .time-input:focus {
            background: rgba(0, 0, 0, 0.7);
            border-color: #00e5ff;
            box-shadow: 0 0 20px rgba(0, 229, 255, 0.5);
            color: #00e5ff;
            outline: none;
        }

        .time-input-manual {
            display: inline-block;
        }

        .btn-pilih {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 12px 35px;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-pilih:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-pilih:disabled {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            transform: none;
            box-shadow: 0 0 25px rgba(40, 167, 69, 0.6);
            cursor: not-allowed;
        }

        .header-info {
            background: rgba(0, 0, 0, 0.4);
            border-radius: 12px;
            padding: 15px 20px;
            border: 1px solid rgba(0, 150, 255, 0.2);
        }

        .header-info h5 {
            color: #00e5ff;
            font-weight: 700;
            margin: 0;
        }

        .control-section {
            background: rgba(20, 20, 20, 0.7);
            border-radius: 15px;
            padding: 20px;
            border: 1px solid rgba(0, 150, 255, 0.1);
        }

        .section-title {
            color: #00b3ff;
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .partai-info {
            font-size: 1.4rem;
            color: #00e5ff;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .time-label {
            color: #80d0ff;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-buttons {
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: center;
        }

        .time-preset-buttons {
            display: flex;
            gap: 5px;
            margin-top: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .time-preset-btn {
            background: rgba(0, 150, 255, 0.2);
            border: 1px solid rgba(0, 150, 255, 0.3);
            color: #80d0ff;
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .time-preset-btn:hover {
            background: rgba(0, 150, 255, 0.3);
            border-color: #00e5ff;
            transform: translateY(-1px);
        }

        /* Animasi untuk timer yang aktif */
        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.8;
            }

            100% {
                opacity: 1;
            }
        }

        .timer-active {
            animation: pulse 2s infinite;
            border-color: rgba(0, 229, 255, 0.6);
        }

        /* Animasi untuk player card yang dipilih */
        @keyframes selected-glow {
            0% {
                box-shadow: 0 0 30px rgba(64, 156, 255, 0.5);
            }

            50% {
                box-shadow: 0 0 60px rgba(64, 156, 255, 0.8);
            }

            100% {
                box-shadow: 0 0 30px rgba(64, 156, 255, 0.5);
            }
        }

        @keyframes selected-glow-red {
            0% {
                box-shadow: 0 0 30px rgba(255, 99, 132, 0.5);
            }

            50% {
                box-shadow: 0 0 60px rgba(255, 99, 132, 0.8);
            }

            100% {
                box-shadow: 0 0 30px rgba(255, 99, 132, 0.5);
            }
        }

        .player-card.biru.selected {
            animation: selected-glow 2s infinite;
        }

        .player-card.merah.selected {
            animation: selected-glow-red 2s infinite;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .timer-display {
                font-size: 3.5rem;
                padding: 20px;
                letter-spacing: 5px;
                min-width: 250px;
            }

            .btn-round {
                padding: 12px 25px;
                font-size: 1rem;
            }

            .player-name {
                font-size: 1.5rem;
            }

            .babak-value {
                font-size: 2.5rem;
                /* Lebih kecil untuk mobile */
            }

            .nav-buttons {
                flex-direction: column;
                width: 100%;
            }

            .btn-nav {
                width: 100%;
            }

            .time-preset-buttons {
                gap: 3px;
            }

            .time-preset-btn {
                padding: 4px 8px;
                font-size: 0.8rem;
            }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.3);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(0, 150, 255, 0.5);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 150, 255, 0.7);
        }

        /* Selection color */
        ::selection {
            background: rgba(0, 229, 255, 0.3);
            color: white;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="d-flex align-items-center justify-content-center py-2">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="card-custom p-3 p-md-5">

                    <!-- Header dengan kontrol -->
                    <div class="row align-items-center mb-3">
                        <div class="col-md-12">
                            <div class="control-section">
                                <div class="d-flex flex-wrap align-items-center gap-3">
                                    <div class="nav-buttons">
                                        <button class="btn btn-nav" onclick="navigatePartai('prev')" <?= !$prev_partai ? 'disabled' : '' ?>>
                                            <i class="fas fa-arrow-left me-2"></i>Previous
                                        </button>
                                        <button class="btn btn-secondary bg-gradient tukar-partai btn-round" onclick="tukarpartai()">
                                            <i class="fas fa-exchange-alt me-2"></i>Tukar Partai
                                        </button>
                                        <button class="btn btn-nav" onclick="navigatePartai('next')" <?= !$next_partai ? 'disabled' : '' ?>>
                                            <i class="fas fa-arrow-right me-2"></i>Next
                                        </button>
                                    </div>
                                    <a href="../monitor/view_tunggal.php" target="_blank" class="btn btn-primary bg-gradient btn-round">
                                        <i class="fas fa-tv me-2"></i>Layar Monitor
                                    </a>
                                    <div class="d-flex align-items-center gap-2">
                                        <button id="openfull" onclick="openFullscreen();" class="fullscreen-btn" title="Fullscreen">
                                            <i class="fas fa-expand text-light"></i>
                                        </button>
                                        <button id="exitfull" onclick="closeFullscreen();" class="fullscreen-btn" style="display: none;" title="Keluar Fullscreen">
                                            <i class="fas fa-compress text-light"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timer Section -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="text-center mb-2">
                                <div class="time-label">Set Durasi Pertandingan</div>
                                <div class="row justify-content-center">
                                    <div class="col-md-3 col-lg-3">
                                        <input type="text"
                                            id="input-time-manual"
                                            class="form-control time-input time-input-manual text-center"
                                            placeholder="Contoh: 2:00 atau 1:40"
                                            value="2:00"
                                            onblur="validateAndSetTime(this)">
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <div id="waktu" class="timer-display">02:00</div>
                            </div>
                        </div>
                    </div>

                    <!-- Timer Controls -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-center flex-wrap gap-3">
                                <button id="btn-start" class="btn btn-start btn-round" onclick="startTimer()">
                                    <i class="fas fa-play me-2"></i>Start
                                </button>
                                <button id="btn-pause" class="btn btn-pause btn-round" onclick="pauseTimer()">
                                    <i class="fas fa-pause me-2"></i>Pause
                                </button>
                                <button id="btn-resume" class="btn btn-resume btn-round" onclick="resumeTimer()">
                                    <i class="fas fa-play me-2"></i>Resume
                                </button>
                                <button id="btn-stop" class="btn btn-stop btn-round" onclick="stopTimer()">
                                    <i class="fas fa-stop me-2"></i>Stop
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Players and Round Info -->
                    <div class="row g-4">
                        <!-- Player Biru -->
                        <div class="col-md-4">
                            <div class="player-card biru text-center">
                                <div class="mb-4">
                                    <span class="badge bg-primary px-4 py-2 fs-5">BIRU</span>
                                </div>
                                <h3 class="player-name mb-3">
                                    <?= htmlspecialchars($jadwal['nm_biru'] ?? '-') ?>
                                </h3>
                                <p class="player-kontingen mb-4">
                                    <i class="fas fa-flag me-2"></i>
                                    <?= htmlspecialchars($jadwal['kontingen_biru'] ?? '-') ?>
                                </p>
                                <button class="btn-pilih pilih-button" data-sudut="BIRU">
                                    <i class="fas fa-check-circle me-2"></i>Pilih
                                </button>
                            </div>
                        </div>

                        <!-- Round Info -->
                        <div class="col-md-4 d-flex align-items-center text-info">
                            <div class="babak-section w-100 text-center">
                                <div class="babak-title">Babak</div>
                                <div class="babak-value mb-3" id="babak-value">
                                    <?= htmlspecialchars($jadwal['babak'] ?? '-') ?>
                                </div>
                                <div class="info-badge mb-2">
                                    <?= htmlspecialchars($jadwal['kategori']) . " / " . htmlspecialchars($jadwal['golongan']) . " - " . htmlspecialchars($jadwal['id_partai']); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Player Merah -->
                        <div class="col-md-4">
                            <div class="player-card merah text-center">
                                <div class="mb-4">
                                    <span class="badge bg-danger px-4 py-2 fs-5">MERAH</span>
                                </div>
                                <h3 class="player-name mb-3">
                                    <?= htmlspecialchars($jadwal['nm_merah'] ?? '-') ?>
                                </h3>
                                <p class="player-kontingen mb-4">
                                    <i class="fas fa-flag me-2"></i>
                                    <?= htmlspecialchars($jadwal['kontingen_merah'] ?? '-') ?>
                                </p>
                                <button class="btn-pilih pilih-button" data-sudut="MERAH">
                                    <i class="fas fa-check-circle me-2"></i>Pilih
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="../../../assets/jquery/jquery.min.js"></script>
    <script>
        const waktuElem = $('#waktu');
        const btnStart = $('#btn-start');
        const btnPause = $('#btn-pause');
        const btnResume = $('#btn-resume');
        const btnStop = $('#btn-stop');
        const pilihButtons = $('.pilih-button');
        const is_login = localStorage.getItem('is_login');
        const operator = localStorage.getItem('operator');
        const nama_operator = localStorage.getItem('nama_operator');
        localStorage.setItem('waktu', '2:00');
        localStorage.setItem('timer', 120);

        let remaining = parseInt(localStorage.getItem('timer')) || 120;
        let timerActive = false;

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

        // FUNGSI UNTUK MENGATUR UKURAN FONT OTOMATIS
        function adjustBabakFontSize() {
            const babakElement = document.getElementById('babak-value');
            const text = babakElement.textContent.trim();
            const containerWidth = babakElement.clientWidth;

            // Reset ke ukuran default
            babakElement.style.fontSize = '3.5rem';

            // Cek jika teks panjang (misal: "SEMIFINAL" atau "QUARTERFINAL")
            if (text.length > 8) {
                // Untuk teks panjang, kurangi ukuran font
                if (text.length > 12) {
                    babakElement.style.fontSize = '2.2rem';
                } else if (text.length > 10) {
                    babakElement.style.fontSize = '2.5rem';
                } else if (text.length > 8) {
                    babakElement.style.fontSize = '3rem';
                }
            }

            // Cek jika teks masih overflow
            if (babakElement.scrollWidth > containerWidth) {
                // Kurangi ukuran font secara bertahap sampai muat
                let fontSize = 3.5;
                while (babakElement.scrollWidth > containerWidth && fontSize > 1.5) {
                    fontSize -= 0.1;
                    babakElement.style.fontSize = fontSize + 'rem';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const savedTime = localStorage.getItem('waktu');
            if (savedTime) {
                document.getElementById('input-time-manual').value = savedTime;
                remaining = parseTimeToSeconds(savedTime);
            } else {
                localStorage.setItem('waktu', '2:00');
                document.getElementById('input-time-manual').value = '2:00';
                remaining = 120;
            }
            updateDisplay();
            updateButtons('stopped');

            // Atur ukuran font untuk babak
            setTimeout(() => {
                adjustBabakFontSize();
            }, 100);

            // Cek apakah ada pilihan sebelumnya yang tersimpan untuk partai ini
            const savedSelectedPartai = localStorage.getItem(`selectedPartai_${staticPartaiData.id_partai}`);

            if (savedSelectedPartai) {
                // Restore pilihan dari localStorage
                const selectedData = JSON.parse(savedSelectedPartai);
                selectSudut(selectedData.sudut, true);
            } else {
                // Cek di currentPartai untuk kompatibilitas
                const selectedPartaiData = JSON.parse(localStorage.getItem('currentPartai'));
                if (selectedPartaiData && selectedPartaiData.id_partai === staticPartaiData.id_partai) {
                    selectSudut(selectedPartaiData.sudut, true);
                } else {
                    resetPilihan();
                }
            }
        });

        // Fungsi untuk memilih sudut
        function selectSudut(sudut, fromStorage = false) {
            let namaPesilat = '';
            let kontingenSudut = '';

            if (sudut === 'BIRU') {
                namaPesilat = staticPartaiData.nm_biru;
                kontingenSudut = staticPartaiData.kontingen_biru;
            } else if (sudut === 'MERAH') {
                namaPesilat = staticPartaiData.nm_merah;
                kontingenSudut = staticPartaiData.kontingen_merah;
            }

            const partaiToSave = {
                id_partai: staticPartaiData.id_partai,
                kategori: staticPartaiData.kategori,
                kelas: staticPartaiData.golongan,
                sudut: sudut,
                nama: namaPesilat,
                kontingen: kontingenSudut
            };

            // Simpan ke dua tempat untuk kompatibilitas
            localStorage.setItem('currentPartai', JSON.stringify(partaiToSave));
            localStorage.setItem(`selectedPartai_${staticPartaiData.id_partai}`, JSON.stringify(partaiToSave));

            pilihButtons.each(function() {
                const $btn = $(this);
                const btnSudut = $btn.data('sudut');
                if (btnSudut === sudut) {
                    $btn.html('<i class="fas fa-check-circle me-2"></i>Dipilih').prop('disabled', false);
                    $btn.closest('.player-card').addClass('selected');
                } else {
                    $btn.html('Pilih').prop('disabled', false);
                    $btn.closest('.player-card').removeClass('selected');
                }
            });

            btnStart.prop('disabled', false);

            // Refresh partai ke server hanya jika bukan dari storage (untuk menghindari duplikasi)
            if (!fromStorage) {
                refreshpartai();
            }
        }

        // Fungsi untuk reset pilihan
        function resetPilihan() {
            pilihButtons.html('<i class="fas fa-check-circle me-2"></i>Pilih').prop('disabled', false);
            $('.player-card').removeClass('selected');
            btnStart.prop('disabled', true);

            // Hapus data yang tersimpan untuk partai ini
            localStorage.removeItem(`selectedPartai_${staticPartaiData.id_partai}`);
            localStorage.removeItem('currentPartai');
        }

        // Fungsi untuk mengkonversi format waktu ke detik
        function parseTimeToSeconds(timeString) {
            timeString = timeString.trim();

            // Jika format sudah HH:MM:SS
            if (timeString.match(/^\d+:\d{2}:\d{2}$/)) {
                const parts = timeString.split(':');
                const hours = parseInt(parts[0]) || 0;
                const minutes = parseInt(parts[1]) || 0;
                const seconds = parseInt(parts[2]) || 0;
                return (hours * 3600) + (minutes * 60) + seconds;
            }

            // Jika format MM:SS
            if (timeString.match(/^\d+:\d{2}$/)) {
                const parts = timeString.split(':');
                const minutes = parseInt(parts[0]) || 0;
                const seconds = parseInt(parts[1]) || 0;
                return (minutes * 60) + seconds;
            }

            // Jika hanya angka (detik)
            if (timeString.match(/^\d+$/)) {
                return parseInt(timeString);
            }

            // Jika format fleksibel seperti "1:40" atau "2:00"
            if (timeString.includes(':')) {
                const parts = timeString.split(':');
                if (parts.length === 2) {
                    const minutes = parseInt(parts[0]) || 0;
                    let seconds = parseInt(parts[1]) || 0;

                    // Handle jika detik lebih dari 59
                    if (seconds >= 60) {
                        const extraMinutes = Math.floor(seconds / 60);
                        minutes += extraMinutes;
                        seconds = seconds % 60;
                    }

                    return (minutes * 60) + seconds;
                }
            }

            // Default fallback
            return 120; // 2 menit
        }

        // Fungsi untuk mengkonversi detik ke format MM:SS
        function secondsToTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const secs = seconds % 60;

            if (minutes >= 60) {
                const hours = Math.floor(minutes / 60);
                const mins = minutes % 60;
                return `${hours}:${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            }

            return `${minutes}:${secs.toString().padStart(2, '0')}`;
        }

        // Fungsi untuk mengkonversi detik ke format MM:SS untuk display
        function secondsToDisplayTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const secs = seconds % 60;

            if (minutes >= 60) {
                const hours = Math.floor(minutes / 60);
                const mins = minutes % 60;
                return `${hours.toString().padStart(2, '0')}:${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            }

            return `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }

        function setTime(timeString) {
            const input = document.getElementById('input-time-manual');
            input.value = timeString;
            validateAndSetTime(input);
        }

        function validateAndSetTime(input) {
            const timeString = input.value.trim();

            if (!timeString) {
                alert('Masukkan waktu terlebih dahulu!');
                input.value = '2:00';
                setRemainingFromInput('2:00');
                return;
            }

            // Validasi format dasar
            if (!timeString.match(/^[\d:.]+$/)) {
                alert('Format waktu tidak valid. Gunakan format Menit:Detik (contoh: 2:00 atau 1:40)');
                input.value = '2:00';
                setRemainingFromInput('2:00');
                return;
            }

            const totalSeconds = parseTimeToSeconds(timeString);

            if (isNaN(totalSeconds) || totalSeconds <= 0) {
                alert('Waktu harus lebih dari 0 detik!');
                input.value = '2:00';
                setRemainingFromInput('2:00');
                return;
            }

            // Format ulang input untuk konsistensi
            const formattedTime = secondsToTime(totalSeconds);
            input.value = formattedTime;

            setRemainingFromInput(formattedTime);
        }

        function setRemainingFromInput(timeString) {
            const totalSeconds = parseTimeToSeconds(timeString);

            if (totalSeconds > 0) {
                remaining = totalSeconds;
                console.log('Remaining time set to:', remaining, 'seconds');
                localStorage.setItem('timer', remaining);
                localStorage.setItem('waktu', timeString);
                updateDisplay();

                // Update monitor via WebSocket
                const currentSelectedPartai = JSON.parse(localStorage.getItem('currentPartai'));
                if (currentSelectedPartai && ws.readyState === WebSocket.OPEN) {
                    ws.send(JSON.stringify({
                        type: 'update_time',
                        remaining: remaining,
                        partai: currentSelectedPartai.id_partai
                    }));
                }
            }
        }

        function navigatePartai(direction) {
            let targetId = '';

            if (direction === 'prev' && <?= $prev_partai ? 'true' : 'false' ?>) {
                targetId = '<?= $prev_partai ? htmlspecialchars($prev_partai['id_partai']) : '' ?>';
            } else if (direction === 'next' && <?= $next_partai ? 'true' : 'false' ?>) {
                targetId = '<?= $next_partai ? htmlspecialchars($next_partai['id_partai']) : '' ?>';
            }

            if (targetId) {
                // Navigate to new partai
                window.location.href = `?id_partai=${targetId}`;
            }
        }

        const ws = new WebSocket('ws://localhost:3000');

        ws.onopen = () => {
            console.log("Server terhubung.");
            const savedSelectedPartai = localStorage.getItem(`selectedPartai_${staticPartaiData.id_partai}`);
            if (savedSelectedPartai) {
                refreshpartai();
            }
        };

        ws.onmessage = (event) => {
            const data = JSON.parse(event.data);

            if (typeof data.remaining === 'number') {
                remaining = data.remaining;
                updateDisplay();
            }

            if (data.type === 'tick') {
                remaining = data.remaining;
                updateDisplay();
                updateButtons('tick');
                timerActive = true;
                waktuElem.addClass('timer-active');
            } else if (data.type === 'stopped') {
                updateButtons('stopped');
                timerActive = false;
                waktuElem.removeClass('timer-active');
            } else if (data.type === 'paused') {
                updateButtons('paused');
                timerActive = false;
                waktuElem.removeClass('timer-active');
            } else if (data.type === 'resumed') {
                updateButtons('resumed');
                timerActive = true;
                waktuElem.addClass('timer-active');
            } else if (data.type === 'ended') {
                updateButtons('ended');
                timerActive = false;
                waktuElem.removeClass('timer-active');

                // Play sound notification
                playNotificationSound();
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

        function playNotificationSound() {
            // Create notification sound
            const audioContext = new(window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);

            oscillator.frequency.value = 800;
            oscillator.type = 'sine';

            gainNode.gain.setValueAtTime(0, audioContext.currentTime);
            gainNode.gain.linearRampToValueAtTime(0.5, audioContext.currentTime + 0.1);
            gainNode.gain.exponentialRampToValueAtTime(0.001, audioContext.currentTime + 0.5);

            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.5);
        }

        function updateDisplay() {
            waktuElem.text(secondsToDisplayTime(remaining));

            // Update color based on remaining time
            if (remaining <= 30) {
                waktuElem.css('color', '#ff6b6b');
                waktuElem.css('text-shadow', '0 0 20px rgba(255, 107, 107, 0.8), 0 0 40px rgba(255, 107, 107, 0.4)');
            } else if (remaining <= 60) {
                waktuElem.css('color', '#ffd166');
                waktuElem.css('text-shadow', '0 0 20px rgba(255, 209, 102, 0.8), 0 0 40px rgba(255, 209, 102, 0.4)');
            } else {
                waktuElem.css('color', '#00e5ff');
                waktuElem.css('text-shadow', '0 0 20px rgba(0, 229, 255, 0.8), 0 0 40px rgba(0, 229, 255, 0.4)');
            }
        }

        function updateButtons(state) {
            btnStart.hide();
            btnPause.hide();
            btnResume.hide();
            btnStop.hide();

            if (state === 'stopped' || state === 'ended') {
                btnStart.show();
                const savedSelectedPartai = localStorage.getItem(`selectedPartai_${staticPartaiData.id_partai}`);
                if (savedSelectedPartai) {
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
            selectSudut(selectedSudut);
        });

        function refreshpartai() {
            if (ws.readyState === WebSocket.OPEN) {
                const savedSelectedPartai = localStorage.getItem(`selectedPartai_${staticPartaiData.id_partai}`);

                if (savedSelectedPartai) {
                    const selectedData = JSON.parse(savedSelectedPartai);
                    const partaiDataToSend = {
                        type: 'set_partai_tunggal',
                        partai: staticPartaiData.partai,
                        kategori: staticPartaiData.kategori,
                        babak: staticPartaiData.babak,
                        kelas: staticPartaiData.golongan,
                        peserta: {
                            nama: selectedData.nama,
                            kontingen: selectedData.kontingen,
                            sudut: selectedData.sudut
                        }
                    };
                    ws.send(JSON.stringify(partaiDataToSend));
                    console.log('Partai data sent to monitor:', partaiDataToSend);
                } else {
                    console.log("No selected partai data found in localStorage.");
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
            // Hapus semua data yang terkait dengan partai ini
            localStorage.removeItem('currentPartai');
            localStorage.removeItem(`selectedPartai_${staticPartaiData.id_partai}`);
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
            const savedSelectedPartai = localStorage.getItem(`selectedPartai_${staticPartaiData.id_partai}`);
            if (!savedSelectedPartai) {
                alert('Mohon pilih peserta terlebih dahulu!');
                return;
            }

            const selectedData = JSON.parse(savedSelectedPartai);
            if (ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({
                    type: 'start',
                    remaining: remaining,
                    partai: selectedData.id_partai,
                    sudut: selectedData.sudut
                }));
            } else {
                console.warn("WebSocket not open for start timer.");
            }
        }

        function pauseTimer() {
            const savedSelectedPartai = localStorage.getItem(`selectedPartai_${staticPartaiData.id_partai}`);
            if (!savedSelectedPartai) return;

            const selectedData = JSON.parse(savedSelectedPartai);
            if (ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({
                    type: 'pause',
                    partai: selectedData.id_partai
                }));
            } else {
                console.warn("WebSocket not open for pause timer.");
            }
        }

        function resumeTimer() {
            const savedSelectedPartai = localStorage.getItem(`selectedPartai_${staticPartaiData.id_partai}`);
            if (!savedSelectedPartai) return;

            const selectedData = JSON.parse(savedSelectedPartai);
            if (ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({
                    type: 'resume',
                    partai: selectedData.id_partai
                }));
            } else {
                console.warn("WebSocket not open for resume timer.");
            }
        }

        function stopTimer() {
            $('.tukar-partai').prop('disabled', false);
            const savedSelectedPartai = localStorage.getItem(`selectedPartai_${staticPartaiData.id_partai}`);
            if (!savedSelectedPartai) return;

            const selectedData = JSON.parse(savedSelectedPartai);
            if (ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({
                    type: 'stop',
                    partai: selectedData.id_partai
                }));
            } else {
                console.warn("WebSocket not open for stop timer.");
            }
        }

        var elem = document.documentElement;

        function openFullscreen() {
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.mozRequestFullScreen) {
                elem.mozRequestFullScreen();
            } else if (elem.webkitRequestFullscreen) {
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) {
                elem.msRequestFullscreen();
            }
            document.getElementById("openfull").style.display = "none";
            document.getElementById("exitfull").style.display = "block";
        }

        function closeFullscreen() {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
            document.getElementById("openfull").style.display = "block";
            document.getElementById("exitfull").style.display = "none";
        }
    </script>
</body>

</html>