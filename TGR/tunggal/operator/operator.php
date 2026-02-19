<?php
include "../../../backend/includes/connection.php";

$id_partai = mysqli_real_escape_string($koneksi, $_GET["id_partai"] ?? '');

// Jika tidak ada id_partai, ambil partai pertama
if (empty($id_partai)) {
    $sql_first = "SELECT id_partai FROM jadwal_tgr ORDER BY id_partai ASC LIMIT 1";
    $result_first = mysqli_query($koneksi, $sql_first);
    $first_partai = mysqli_fetch_assoc($result_first);
    if ($first_partai) {
        header("Location: ?id_partai=" . $first_partai['id_partai']);
        exit;
    }
}

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

// Ambil semua partai untuk dropdown
$sql_all = "SELECT id_partai, partai, kategori, golongan, babak FROM jadwal_tgr ORDER BY id_partai ASC";
$result_all = mysqli_query($koneksi, $sql_all);
$all_partai = mysqli_fetch_all($result_all, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Operator Pertandingan TGR - Panel Kontrol</title>
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

        /* Timer Display Section */
        .timer-display-section {
            background: rgba(20, 20, 20, 0.8);
            border-radius: 15px;
            padding: 20px;
            border: 1px solid rgba(0, 150, 255, 0.3);
            margin-bottom: 20px;
        }

        .timer-box {
            background: rgba(0, 0, 0, 0.5);
            border-radius: 15px;
            padding: 15px;
            text-align: center;
            border: 2px solid rgba(0, 229, 255, 0.3);
        }

        .operator-timer {
            font-family: 'Courier New', monospace;
            font-size: 4rem;
            font-weight: 900;
            color: #00e5ff;
            text-shadow: 0 0 20px rgba(0, 229, 255, 0.8);
            letter-spacing: 5px;
            line-height: 1.2;
        }

        .operator-timer.warning {
            color: #ffd166;
            text-shadow: 0 0 20px rgba(255, 209, 102, 0.8);
        }

        .operator-timer.danger {
            color: #ff6b6b;
            text-shadow: 0 0 20px rgba(255, 107, 107, 0.8);
            animation: pulse 1s infinite;
        }

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

        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1rem;
        }

        .status-active {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
            border: 1px solid #28a745;
        }

        .status-paused {
            background: rgba(255, 193, 7, 0.2);
            color: #ffc107;
            border: 1px solid #ffc107;
        }

        .status-stopped {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
            border: 1px solid #dc3545;
        }

        .player-card {
            background: rgba(40, 40, 40, 0.8);
            border-radius: 15px;
            padding: 30px 25px;
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
            cursor: pointer;
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

        .player-card.selected {
            box-shadow: 0 0 50px rgba(64, 156, 255, 0.7);
            background: linear-gradient(135deg, rgba(30, 60, 114, 0.9), rgba(42, 82, 152, 0.9));
        }

        .player-card.merah.selected {
            box-shadow: 0 0 50px rgba(255, 99, 132, 0.7);
            background: linear-gradient(135deg, rgba(152, 30, 50, 0.9), rgba(203, 45, 74, 0.9));
        }

        .player-name {
            font-size: 1.9rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 10px;
        }

        .player-card.biru .player-name {
            color: #4dabff;
            text-shadow: 0 2px 15px rgba(77, 171, 255, 0.5);
        }

        .player-card.merah .player-name {
            color: #ff6b8b;
            text-shadow: 0 2px 15px rgba(255, 107, 139, 0.5);
        }

        .player-kontingen {
            font-size: 1.1rem;
            color: #aaa;
            margin-bottom: 25px;
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
            font-weight: 900;
            color: #00b3ff;
            text-shadow: 0 0 20px rgba(0, 179, 255, 0.5);
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
            cursor: not-allowed;
            opacity: 0.6;
        }

        .btn-pilih {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 12px 35px;
            font-weight: 700;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-pilih:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-pilih:disabled {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            cursor: not-allowed;
        }

        .btn-pilih.refresh {
            background: linear-gradient(135deg, #ffc107, #ffca2c);
            color: #212529;
        }

        .btn-pilih.refresh:hover {
            background: linear-gradient(135deg, #ffca2c, #ffd54f);
        }

        .btn-timer {
            background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
            color: white;
            padding: 12px 25px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 50px;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-timer:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 176, 155, 0.4);
        }

        .fullscreen-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 10px 15px;
            transition: all 0.3s ease;
            color: white;
        }

        .fullscreen-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: #00e5ff;
            transform: scale(1.05);
        }

        .partai-selector {
            background: rgba(20, 20, 20, 0.7);
            border-radius: 15px;
            padding: 20px;
            border: 1px solid rgba(0, 150, 255, 0.2);
        }

        .partai-dropdown {
            background: rgba(0, 0, 0, 0.5);
            border: 2px solid rgba(0, 229, 255, 0.4);
            color: #00e5ff;
            border-radius: 12px;
            padding: 12px 20px;
            font-size: 1.1rem;
            width: 100%;
            cursor: pointer;
        }

        .partai-dropdown option {
            background: #1a1a1a;
            color: #e0e0e0;
        }

        .partai-dropdown:focus {
            border-color: #00e5ff;
            box-shadow: 0 0 20px rgba(0, 229, 255, 0.5);
            outline: none;
        }

        .timer-status {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
        }

        .connection-status {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(0, 0, 0, 0.3);
            padding: 8px 15px;
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .connection-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #666;
            transition: all 0.3s ease;
        }

        .connection-indicator.connected {
            background-color: #28a745;
            box-shadow: 0 0 15px #28a745;
            animation: pulse-green 2s infinite;
        }

        .connection-indicator.disconnected {
            background-color: #dc3545;
            box-shadow: 0 0 15px #dc3545;
        }

        @keyframes pulse-green {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.6;
            }

            100% {
                opacity: 1;
            }
        }

        .connection-text {
            color: #aaa;
            font-size: 0.9rem;
        }

        .connection-text.connected {
            color: #28a745;
            font-weight: 600;
        }

        .connection-text.disconnected {
            color: #dc3545;
            font-weight: 600;
        }

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
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="card-custom p-4">

                    <!-- Header dengan Fullscreen Button dan Connection Status -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <h2 class="text-light mb-0">
                                        <i class="fas fa-users-cog me-3 text-primary"></i>
                                        OPERATOR TGR
                                    </h2>
                                    <div class="connection-status" id="connectionStatus">
                                        <span id="connectionIndicator" class="connection-indicator disconnected"></span>
                                        <span id="connectionText" class="connection-text disconnected">Server: Disconnected</span>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="../monitor/view_tunggal.php" target="_blank" class="btn btn-timer">
                                        <i class="fas fa-tv me-2"></i>Layar Monitor
                                    </a>
                                    <a href="../timer/index.php" target="_blank" class="btn btn-timer">
                                        <i class="fas fa-clock me-2"></i>Timer Control
                                    </a>
                                    <button id="openfull" onclick="openFullscreen();" class="fullscreen-btn" title="Fullscreen">
                                        <i class="fas fa-expand"></i>
                                    </button>
                                    <button id="exitfull" onclick="closeFullscreen();" class="fullscreen-btn" style="display: none;" title="Keluar Fullscreen">
                                        <i class="fas fa-compress"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timer Display Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="timer-display-section">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="text-light mb-0">
                                        <i class="fas fa-hourglass-half me-2 text-primary"></i>
                                        Waktu Pertandingan
                                    </h5>
                                    <span class="text-info">
                                        <i class="fas fa-sync-alt me-1"></i>
                                        Live Update
                                    </span>
                                </div>
                                <div class="timer-box">
                                    <div id="operatorTimer" class="operator-timer">02:00</div>
                                    <div class="d-flex justify-content-center gap-4 mt-2">
                                        <span id="operatorStatus" class="status-badge status-stopped">
                                            <i class="fas fa-stop-circle me-1"></i>Stopped
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Partai Selector -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="partai-selector">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <label class="text-info mb-2 fw-bold">
                                            <i class="fas fa-list me-2"></i>Pilih Partai:
                                        </label>
                                        <select class="partai-dropdown" id="partaiSelect" onchange="changePartai(this.value)">
                                            <?php foreach ($all_partai as $p): ?>
                                                <option value="<?= $p['id_partai'] ?>" <?= $p['id_partai'] == $id_partai ? 'selected' : '' ?>>
                                                    Partai <?= $p['partai'] ?> - <?= $p['kategori'] ?> / <?= $p['golongan'] ?> (<?= $p['babak'] ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="timer-status mt-3 mt-md-0">
                                            <div class="text-light mb-2">Status Timer:</div>
                                            <div id="timerStatusDisplay" class="status-stopped" style="display: inline-block; padding: 5px 15px;">
                                                <i class="fas fa-stop-circle me-2"></i>Stopped
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-center gap-3">
                                <button class="btn btn-nav" onclick="navigatePartai('prev')" <?= !$prev_partai ? 'disabled' : '' ?>>
                                    <i class="fas fa-arrow-left me-2"></i>Previous Partai
                                </button>
                                <button class="btn btn-secondary bg-gradient" onclick="tukarpartai()">
                                    <i class="fas fa-exchange-alt me-2"></i>Tukar Partai
                                </button>
                                <button class="btn btn-nav" onclick="navigatePartai('next')" <?= !$next_partai ? 'disabled' : '' ?>>
                                    <i class="fas fa-arrow-right me-2"></i>Next Partai
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Players and Round Info -->
                    <div class="row g-4">
                        <!-- Player Biru -->
                        <div class="col-md-4">
                            <div class="player-card biru text-center" id="playerBiru" onclick="selectSudut('BIRU')">
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
                                <button class="btn-pilih pilih-button" data-sudut="BIRU" onclick="event.stopPropagation(); selectSudut('BIRU')">
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
                                    <?= htmlspecialchars($jadwal['kategori']) . " / " . htmlspecialchars($jadwal['golongan']) . " - Partai " . htmlspecialchars($jadwal['partai']); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Player Merah -->
                        <div class="col-md-4">
                            <div class="player-card merah text-center" id="playerMerah" onclick="selectSudut('MERAH')">
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
                                <button class="btn-pilih pilih-button" data-sudut="MERAH" onclick="event.stopPropagation(); selectSudut('MERAH')">
                                    <i class="fas fa-check-circle me-2"></i>Pilih
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Refresh Button -->
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <button class="btn btn-info bg-gradient px-5 py-2 rounded-pill" onclick="refreshSelection()">
                                <i class="fas fa-sync-alt me-2"></i>Refresh Pilihan
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="../../../assets/jquery/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            if (localStorage.getItem('is_login') < 1) {
                localStorage.clear();
                window.location.href = "http://" + hostname + "/DARUNNAJAH/TGR/tunggal/operator";
            }
        });

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

        const pilihButtons = $('.pilih-button');
        const hostname = window.location.hostname;
        let ws;
        let reconnectAttempts = 0;
        const maxReconnectAttempts = 5;

        function connectWebSocket() {
            ws = new WebSocket('ws://' + hostname + ':3000');

            // WebSocket connection
            ws.onopen = () => {
                console.log("Server terhubung.");
                updateConnectionStatus(true);
                reconnectAttempts = 0;

                // Request timer status for this partai
                ws.send(JSON.stringify({
                    type: 'request_status',
                    id_partai: staticPartaiData.id_partai
                }));

                // Kirim data partai saat ini
                const savedSelectedPartai = localStorage.getItem(`selectedPartai_${staticPartaiData.id_partai}`);
                if (savedSelectedPartai) {
                    const selectedData = JSON.parse(savedSelectedPartai);
                    sendPartaiToServer(selectedData);
                }
            };

            ws.onmessage = (event) => {
                const data = JSON.parse(event.data);

                // Update timer display
                if (typeof data.remaining === 'number') {
                    updateOperatorTimer(data.remaining);
                }

                // Update timer status display
                if (data.type === 'timer_status') {
                    updateTimerStatusDisplay(data.status);
                }

                // Handle timer states
                if (data.type === 'tick') {
                    updateOperatorTimer(data.remaining);
                    updateTimerStatusDisplay('active');
                } else if (data.type === 'stopped') {
                    updateTimerStatusDisplay('stopped');
                    // Reset timer to initial value
                    const savedWaktu = localStorage.getItem('waktu') || '2:00';
                    const totalSeconds = parseTimeToSeconds(savedWaktu);
                    updateOperatorTimer(totalSeconds);
                } else if (data.type === 'paused') {
                    updateTimerStatusDisplay('paused');
                } else if (data.type === 'resumed') {
                    updateTimerStatusDisplay('active');
                } else if (data.type === 'ended') {
                    updateTimerStatusDisplay('stopped');
                    // Reset timer to initial value
                    const savedWaktu = localStorage.getItem('waktu') || '2:00';
                    const totalSeconds = parseTimeToSeconds(savedWaktu);
                    updateOperatorTimer(totalSeconds);
                }

                // Handle partai data updates
                if (data.type === 'partai_data_tunggal') {
                    const p = data.data;
                    console.log("Data partai diterima:", p);
                    localStorage.setItem('partai', p.partai);
                    localStorage.setItem('currentPartai', JSON.stringify(p));
                }
            };

            ws.onclose = function() {
                console.log('WebSocket disconnected');
                updateConnectionStatus(false);

                // Attempt to reconnect
                if (reconnectAttempts < maxReconnectAttempts) {
                    reconnectAttempts++;
                    console.log(`Attempting to reconnect... (${reconnectAttempts}/${maxReconnectAttempts})`);
                    setTimeout(connectWebSocket, 3000);
                }
            };

            ws.onerror = function(error) {
                console.log('WebSocket error:', error);
                updateConnectionStatus(false);
            };
        }

        // Initialize WebSocket connection
        connectWebSocket();

        // Initialize
        $(document).ready(function() {
            // Check for saved selection
            const savedSelectedPartai = localStorage.getItem(`selectedPartai_${staticPartaiData.id_partai}`);

            if (savedSelectedPartai) {
                const selectedData = JSON.parse(savedSelectedPartai);
                selectSudut(selectedData.sudut, true);
            } else {
                selectSudut('BIRU', false);
            }

            // Load saved timer
            const savedWaktu = localStorage.getItem('waktu');
            if (savedWaktu) {
                const totalSeconds = parseTimeToSeconds(savedWaktu);
                updateOperatorTimer(totalSeconds);
            }

            // Adjust babak font size
            setTimeout(adjustBabakFontSize, 100);
        });

        // Fungsi untuk update status koneksi
        function updateConnectionStatus(connected) {
            const indicator = document.getElementById('connectionIndicator');
            const text = document.getElementById('connectionText');

            if (connected) {
                indicator.className = 'connection-indicator connected';
                text.className = 'connection-text connected';
                text.innerHTML = 'Server: Connected';
            } else {
                indicator.className = 'connection-indicator disconnected';
                text.className = 'connection-text disconnected';
                text.innerHTML = 'Server: Disconnected';
            }
        }

        // Fungsi untuk refresh pilihan
        function refreshSelection() {
            const currentSelection = localStorage.getItem(`selectedPartai_${staticPartaiData.id_partai}`);
            if (currentSelection) {
                const selectedData = JSON.parse(currentSelection);
                // Kirim ulang data ke server
                if (ws.readyState === WebSocket.OPEN) {
                    sendPartaiToServer(selectedData);
                } else {
                    alert('Koneksi server terputus. Menunggu koneksi kembali...');
                }
            } else {
                // Jika belum ada pilihan, pilih BIRU
                selectSudut('BIRU', false);
            }
        }

        // Timer Functions
        function parseTimeToSeconds(timeString) {
            if (!timeString) return 120;

            timeString = timeString.trim();

            if (timeString.match(/^\d+:\d{2}$/)) {
                const parts = timeString.split(':');
                const minutes = parseInt(parts[0]) || 0;
                const seconds = parseInt(parts[1]) || 0;
                return (minutes * 60) + seconds;
            }

            if (timeString.match(/^\d+$/)) {
                return parseInt(timeString);
            }

            if (timeString.includes(':')) {
                const parts = timeString.split(':');
                if (parts.length === 2) {
                    const minutes = parseInt(parts[0]) || 0;
                    let seconds = parseInt(parts[1]) || 0;
                    return (minutes * 60) + seconds;
                }
            }

            return 120;
        }

        function secondsToDisplayTime(seconds) {
            if (seconds < 0) seconds = 0;
            const minutes = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }

        function updateOperatorTimer(seconds) {
            const timerElement = document.getElementById('operatorTimer');
            timerElement.textContent = secondsToDisplayTime(seconds);

            // Update color based on time
            timerElement.classList.remove('warning', 'danger');
            if (seconds <= 10) {
                timerElement.classList.add('danger');
            } else if (seconds <= 30) {
                timerElement.classList.add('warning');
            }
        }

        function updateTimerStatusDisplay(status) {
            // Update both status displays
            const statusDisplay1 = document.getElementById('timerStatusDisplay');
            const statusDisplay2 = document.getElementById('operatorStatus');

            // Remove all status classes
            statusDisplay1.className = '';
            statusDisplay2.className = 'status-badge';

            if (status === 'active') {
                statusDisplay1.classList.add('status-active');
                statusDisplay1.innerHTML = '<i class="fas fa-play-circle me-2"></i>Active';

                statusDisplay2.classList.add('status-active');
                statusDisplay2.innerHTML = '<i class="fas fa-play-circle me-1"></i>Active';
            } else if (status === 'paused') {
                statusDisplay1.classList.add('status-paused');
                statusDisplay1.innerHTML = '<i class="fas fa-pause-circle me-2"></i>Paused';

                statusDisplay2.classList.add('status-paused');
                statusDisplay2.innerHTML = '<i class="fas fa-pause-circle me-1"></i>Paused';
            } else {
                statusDisplay1.classList.add('status-stopped');
                statusDisplay1.innerHTML = '<i class="fas fa-stop-circle me-2"></i>Stopped';

                statusDisplay2.classList.add('status-stopped');
                statusDisplay2.innerHTML = '<i class="fas fa-stop-circle me-1"></i>Stopped';
            }
        }

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
                partai: staticPartaiData.partai,
                kategori: staticPartaiData.kategori,
                kelas: staticPartaiData.golongan,
                babak: staticPartaiData.babak,
                sudut: sudut,
                nama: namaPesilat,
                kontingen: kontingenSudut
            };

            // Save selection
            localStorage.setItem('currentPartai', JSON.stringify(partaiToSave));
            localStorage.setItem(`selectedPartai_${staticPartaiData.id_partai}`, JSON.stringify(partaiToSave));

            // Update UI
            pilihButtons.each(function() {
                const $btn = $(this);
                const btnSudut = $btn.data('sudut');
                if (btnSudut === sudut) {
                    $btn.html('<i class="fas fa-check-circle me-2"></i>Dipilih').prop('disabled', true);
                    $btn.closest('.player-card').addClass('selected');
                } else {
                    $btn.html('Pilih').prop('disabled', false);
                    $btn.closest('.player-card').removeClass('selected');
                }
            });

            // Send to server
            if (!fromStorage) {
                sendPartaiToServer(partaiToSave);
            }
        }

        function sendPartaiToServer(partaiData) {
            if (ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({
                    type: 'set_partai_tunggal',
                    partai: partaiData.partai,
                    kategori: partaiData.kategori,
                    babak: partaiData.babak,
                    kelas: partaiData.kelas,
                    peserta: {
                        nama: partaiData.nama,
                        kontingen: partaiData.kontingen,
                        sudut: partaiData.sudut
                    }
                }));
                console.log('Partai data sent:', partaiData);
            } else {
                console.warn("WebSocket not open. Data will be sent when connection is restored.");
            }
        }

        function resetPilihan() {
            pilihButtons.html('<i class="fas fa-check-circle me-2"></i>Pilih').prop('disabled', false);
            $('.player-card').removeClass('selected');

            localStorage.removeItem(`selectedPartai_${staticPartaiData.id_partai}`);
            localStorage.removeItem('currentPartai');

            // Clear from server
            if (ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({
                    type: 'clear_partai_data'
                }));
            }
        }

        pilihButtons.on('click', function(e) {
            e.stopPropagation();
            const $btn = $(this);
            const selectedSudut = $btn.data('sudut');
            selectSudut(selectedSudut);
        });

        function navigatePartai(direction) {
            let targetId = '';

            if (direction === 'prev' && <?= $prev_partai ? 'true' : 'false' ?>) {
                targetId = '<?= $prev_partai ? htmlspecialchars($prev_partai['id_partai']) : '' ?>';
            } else if (direction === 'next' && <?= $next_partai ? 'true' : 'false' ?>) {
                targetId = '<?= $next_partai ? htmlspecialchars($next_partai['id_partai']) : '' ?>';
            }

            if (targetId) {
                window.location.href = `?id_partai=${targetId}`;
            }
        }

        function changePartai(id_partai) {
            window.location.href = `?id_partai=${id_partai}`;
        }

        function tukarpartai() {
            // Clear all selections
            localStorage.removeItem('currentPartai');
            localStorage.removeItem(`selectedPartai_${staticPartaiData.id_partai}`);

            // Reset timer in localStorage
            localStorage.removeItem('timer');
            localStorage.removeItem('waktu');

            // Notify server
            if (ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({
                    type: 'tukar_partai_tunggal'
                }));
            }

            // Redirect to daftar
            window.location.href = "daftar.php?status=";
        }

        function adjustBabakFontSize() {
            const babakElement = document.getElementById('babak-value');
            const text = babakElement.textContent.trim();

            babakElement.style.fontSize = '3.5rem';

            if (text.length > 8) {
                if (text.length > 12) {
                    babakElement.style.fontSize = '2.2rem';
                } else if (text.length > 10) {
                    babakElement.style.fontSize = '2.5rem';
                } else if (text.length > 8) {
                    babakElement.style.fontSize = '3rem';
                }
            }
        }

        // Fullscreen functions
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

        // Listen for fullscreen change
        document.addEventListener('fullscreenchange', handleFullscreenChange);
        document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
        document.addEventListener('mozfullscreenchange', handleFullscreenChange);
        document.addEventListener('MSFullscreenChange', handleFullscreenChange);

        function handleFullscreenChange() {
            if (document.fullscreenElement ||
                document.webkitFullscreenElement ||
                document.mozFullScreenElement ||
                document.msFullscreenElement) {
                document.getElementById("openfull").style.display = "none";
                document.getElementById("exitfull").style.display = "block";
            } else {
                document.getElementById("openfull").style.display = "block";
                document.getElementById("exitfull").style.display = "none";
            }
        }
    </script>
</body>

</html>