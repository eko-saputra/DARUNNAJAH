<?php
// timer.php - Halaman khusus untuk kontrol timer
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Timer Pertandingan TGR</title>
    <link rel="shortcut icon" href="../../../assets/img/LogoIPSI.png" />
    <link rel="stylesheet" href="../../../assets/bootstrap/dist/css/bootstrap.min.css" />
    <style>
        body {
            background: #121212;
            min-height: 100vh;
            color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .timer-container {
            background: rgba(30, 30, 30, 0.95);
            border-radius: 40px;
            border: 1px solid rgba(0, 150, 255, 0.3);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.9);
            padding: 50px 40px;
            max-width: 800px;
            width: 100%;
            position: relative;
        }

        .fullscreen-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 12px;
            transition: all 0.3s ease;
            color: white;
            cursor: pointer;
            z-index: 100;
        }

        .fullscreen-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: #00e5ff;
            transform: scale(1.1);
        }

        .timer-display {
            font-family: 'Courier New', monospace;
            font-size: 9rem;
            font-weight: 900;
            letter-spacing: 15px;
            color: #00e5ff;
            text-shadow: 0 0 40px rgba(0, 229, 255, 0.9),
                0 0 80px rgba(0, 229, 255, 0.5);
            padding: 50px 30px;
            background: rgba(0, 0, 0, 0.8);
            border-radius: 40px;
            border: 5px solid rgba(0, 229, 255, 0.3);
            text-align: center;
            margin-bottom: 40px;
            transition: all 0.3s ease;
        }

        .timer-display.warning {
            color: #ffd166;
            text-shadow: 0 0 40px rgba(255, 209, 102, 0.9);
            border-color: rgba(255, 209, 102, 0.4);
        }

        .timer-display.danger {
            color: #ff6b6b;
            text-shadow: 0 0 40px rgba(255, 107, 107, 0.9);
            border-color: rgba(255, 107, 107, 0.4);
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.9;
                transform: scale(1.02);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .timer-controls {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-bottom: 40px;
        }

        .btn-timer {
            border-radius: 60px;
            font-weight: 800;
            padding: 20px 40px;
            font-size: 1.5rem;
            border: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            flex: 1 1 auto;
            min-width: 160px;
            letter-spacing: 1px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .btn-timer:hover:not(:disabled) {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 20px 30px rgba(0, 0, 0, 0.5);
        }

        .btn-timer:active:not(:disabled) {
            transform: translateY(-2px);
        }

        .btn-timer:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .btn-start {
            background: linear-gradient(135deg, #00b09b, #96c93d);
            color: white;
        }

        .btn-pause {
            background: linear-gradient(135deg, #f7971e, #ffd200);
            color: #212529;
        }

        .btn-resume {
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            color: white;
        }

        .btn-stop {
            background: linear-gradient(135deg, #ff416c, #ff4b2b);
            color: white;
        }

        .time-input-section {
            background: rgba(20, 20, 20, 0.7);
            border-radius: 25px;
            padding: 30px;
            border: 1px solid rgba(0, 150, 255, 0.2);
        }

        .time-label {
            color: #80d0ff;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 3px;
            text-align: center;
        }

        .time-input-wrapper {
            display: flex;
            justify-content: center;
            gap: 10px;
            align-items: center;
        }

        .time-input {
            background: rgba(0, 0, 0, 0.6);
            border: 3px solid rgba(0, 229, 255, 0.5);
            color: #00e5ff;
            border-radius: 20px;
            padding: 15px 30px;
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            font-family: 'Courier New', monospace;
            width: 100%;
            max-width: 250px;
            transition: all 0.3s ease;
        }

        .time-input:focus {
            border-color: #00e5ff;
            box-shadow: 0 0 30px rgba(0, 229, 255, 0.6);
            outline: none;
            background: rgba(0, 0, 0, 0.8);
        }

        .time-input:hover {
            border-color: #00e5ff;
        }

        .preset-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 25px;
            flex-wrap: wrap;
        }

        .preset-btn {
            background: rgba(0, 150, 255, 0.15);
            border: 2px solid rgba(0, 150, 255, 0.3);
            color: #80d0ff;
            border-radius: 40px;
            padding: 12px 30px;
            font-size: 1.3rem;
            font-weight: 600;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .preset-btn:hover {
            background: rgba(0, 150, 255, 0.3);
            border-color: #00e5ff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 229, 255, 0.3);
            color: white;
        }

        .preset-btn:active {
            transform: translateY(0);
        }

        .status-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 25px;
            padding: 10px 20px;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .connection-status {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1rem;
        }

        .connection-indicator {
            width: 12px;
            height: 12px;
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
        }

        .connection-text.connected {
            color: #28a745;
            font-weight: 600;
        }

        .connection-text.disconnected {
            color: #dc3545;
            font-weight: 600;
        }

        .timer-status {
            font-size: 1rem;
            font-weight: 600;
            padding: 5px 15px;
            border-radius: 30px;
            background: rgba(0, 0, 0, 0.3);
        }

        .timer-status.active {
            color: #28a745;
            border: 1px solid #28a745;
        }

        .timer-status.paused {
            color: #ffc107;
            border: 1px solid #ffc107;
        }

        .timer-status.stopped {
            color: #dc3545;
            border: 1px solid #dc3545;
        }

        /* Juri Status Container */
        .juri-status-container {
            margin-top: 25px;
            padding: 20px;
            background: rgba(0, 0, 0, 0.4);
            border-radius: 20px;
            border: 1px solid rgba(0, 150, 255, 0.2);
            text-align: center;
        }

        .juri-count {
            font-size: 3rem;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .juri-count.ready {
            color: #28a745;
            text-shadow: 0 0 10px rgba(40, 167, 69, 0.5);
        }

        .juri-count.waiting {
            color: #ffc107;
        }

        .juri-count.incomplete {
            color: #dc3545;
        }

        .progress {
            height: 10px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
        }

        .progress-bar {
            transition: width 0.3s ease;
        }

        @media (max-width: 768px) {
            .timer-display {
                font-size: 5rem;
                letter-spacing: 8px;
                padding: 30px 20px;
            }

            .btn-timer {
                padding: 15px 25px;
                font-size: 1.2rem;
                min-width: 130px;
            }

            .timer-container {
                padding: 40px 20px 30px;
            }

            .time-input {
                font-size: 2rem;
                padding: 12px 20px;
                max-width: 200px;
            }

            .preset-btn {
                padding: 10px 20px;
                font-size: 1.1rem;
            }

            .fullscreen-btn {
                top: 10px;
                right: 10px;
                padding: 8px;
            }

            .status-container {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }

            .juri-count {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 480px) {
            .timer-display {
                font-size: 3.5rem;
                letter-spacing: 5px;
            }

            .btn-timer {
                padding: 12px 20px;
                font-size: 1rem;
                min-width: 100px;
            }

            .juri-count {
                font-size: 2rem;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Fullscreen Buttons -->
                <center>
                    <button id="openfull" onclick="openFullscreen();" class="fullscreen-btn" title="Fullscreen">
                        <i class="fas fa-expand fa-lg"></i>
                    </button>
                    <button id="exitfull" onclick="closeFullscreen();" class="fullscreen-btn" style="display: none;" title="Keluar Fullscreen">
                        <i class="fas fa-compress fa-lg"></i>
                    </button>
                </center>
                <div class="timer-container">
                    <!-- Timer Display -->
                    <div id="timer" class="timer-display">02:00</div>

                    <!-- Time Input Section -->
                    <div class="time-input-section">
                        <div class="time-label">
                            <i class="fas fa-clock me-2"></i>
                            Set Durasi (Menit:Detik)
                        </div>
                        <div class="time-input-wrapper">
                            <input type="text"
                                id="input-time"
                                class="time-input"
                                placeholder="2:00"
                                value="2:00"
                                oninput="validateAndUpdateTime(this)"
                                onkeypress="handleTimeInputKeypress(event)">
                            <span class="text-secondary ms-2">
                                <i class="fas fa-edit"></i>
                            </span>
                        </div>

                        <div class="preset-buttons">
                            <button class="preset-btn" onclick="setTimePreset('1:00')">
                                <i class="fas fa-clock me-2"></i>1:00
                            </button>
                            <button class="preset-btn" onclick="setTimePreset('1:30')">
                                <i class="fas fa-clock me-2"></i>1:30
                            </button>
                            <button class="preset-btn" onclick="setTimePreset('2:00')">
                                <i class="fas fa-clock me-2"></i>2:00
                            </button>
                            <button class="preset-btn" onclick="setTimePreset('3:00')">
                                <i class="fas fa-clock me-2"></i>3:00
                            </button>
                        </div>
                    </div>

                    <!-- Timer Controls -->
                    <div class="timer-controls mt-4">
                        <button id="btn-start" class="btn-timer btn-start" onclick="startTimer()" disabled>
                            <i class="fas fa-play me-2"></i>Start
                        </button>
                        <button id="btn-pause" class="btn-timer btn-pause" onclick="pauseTimer()" style="display: none;">
                            <i class="fas fa-pause me-2"></i>Pause
                        </button>
                        <button id="btn-resume" class="btn-timer btn-resume" onclick="resumeTimer()" style="display: none;">
                            <i class="fas fa-play me-2"></i>Resume
                        </button>
                        <button id="btn-stop" class="btn-timer btn-stop" onclick="stopTimer()" style="display: none;">
                            <i class="fas fa-stop me-2"></i>Stop
                        </button>
                    </div>

                    <!-- Juri Status Container -->
                    <div id="juri-status-container" class="juri-status-container">
                        <div class="text-center mb-2">
                            <small class="text-secondary">
                                <i class="fas fa-users me-1"></i> STATUS KESIAPAN JURI
                            </small>
                        </div>
                        <div class="text-center">
                            <div id="juri-count" class="juri-count incomplete">0</div>
                            <small class="text-secondary">/4 Juri Ready</small>
                        </div>
                        <div class="progress mt-3">
                            <div id="juri-progress" class="progress-bar bg-danger" role="progressbar"
                                style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="4">
                            </div>
                        </div>
                        <div id="juri-status-text" class="mt-2">
                            <small class="text-secondary">
                                <i class="fas fa-hourglass-half me-1"></i>
                                Menunggu 4 juri siap...
                            </small>
                        </div>
                    </div>

                    <!-- Status Container dengan Connection Status -->
                    <div class="status-container">
                        <div class="connection-status">
                            <span id="connectionIndicator" class="connection-indicator disconnected"></span>
                            <span id="connectionText" class="connection-text disconnected">Server: Disconnected</span>
                        </div>
                        <div id="timerStatus" class="timer-status status-stopped">
                            <i class="fas fa-stop-circle me-2"></i>Stopped
                        </div>
                    </div>

                    <!-- Quick Info -->
                    <div class="text-center mt-4">
                        <small class="text-secondary">
                            <i class="fas fa-keyboard me-2"></i>
                            Shortcut: <kbd>Space</kbd> (Start/Pause/Resume) |
                            <kbd>S</kbd> (Stop) |
                            <kbd>R</kbd> (Reset Time) |
                            <kbd>F</kbd> (Fullscreen)
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // DOM Elements
        const timerDisplay = document.getElementById('timer');
        const btnStart = document.getElementById('btn-start');
        const btnPause = document.getElementById('btn-pause');
        const btnResume = document.getElementById('btn-resume');
        const btnStop = document.getElementById('btn-stop');
        const timerStatus = document.getElementById('timerStatus');
        const connectionIndicator = document.getElementById('connectionIndicator');
        const connectionText = document.getElementById('connectionText');
        const openfullBtn = document.getElementById('openfull');
        const exitfullBtn = document.getElementById('exitfull');

        // Timer Variables
        let remaining = 120;
        let timerActive = false;
        let timerState = 'stopped';
        let lastValidTime = '2:00';
        let updateTimeout;
        let reconnectAttempts = 0;
        const maxReconnectAttempts = 5;

        // Juri Status Variables
        let juriReadyCount = 0;
        let totalJuriRequired = 4;
        let isTimerReady = false;
        let processedJuriIds = [];

        // Storage Keys
        const STORAGE_JURI_COUNT = 'timer_juri_ready_count';
        const STORAGE_TIMER_READY = 'timer_is_ready';
        const STORAGE_PROCESSED_JURIS = 'timer_processed_juris';

        const hostname = window.location.hostname;
        let ws;

        // ========== JURI STATUS FUNCTIONS ==========

        function saveJuriStatusToStorage() {
            localStorage.setItem(STORAGE_JURI_COUNT, juriReadyCount.toString());
            localStorage.setItem(STORAGE_TIMER_READY, isTimerReady.toString());
            localStorage.setItem(STORAGE_PROCESSED_JURIS, JSON.stringify(processedJuriIds));
            console.log(`💾 Status juri disimpan: ${juriReadyCount}/${totalJuriRequired}, ready=${isTimerReady}`);
        }

        function loadJuriStatusFromStorage() {
            const savedCount = localStorage.getItem(STORAGE_JURI_COUNT);
            const savedReady = localStorage.getItem(STORAGE_TIMER_READY);
            const savedProcessed = localStorage.getItem(STORAGE_PROCESSED_JURIS);

            if (savedCount !== null) {
                juriReadyCount = parseInt(savedCount);
                console.log(`📂 Memuat status juri: ${juriReadyCount}/${totalJuriRequired}`);
            }

            if (savedReady !== null) {
                isTimerReady = savedReady === 'true';
                console.log(`📂 Memuat status timer ready: ${isTimerReady}`);
            }

            if (savedProcessed !== null) {
                processedJuriIds = JSON.parse(savedProcessed);
                console.log(`📂 Memuat daftar juri: ${processedJuriIds.join(', ') || 'kosong'}`);
            }

            updateJuriStatusDisplay();

            if (isTimerReady && juriReadyCount >= totalJuriRequired) {
                enableStartButton(true);
                console.log('✅ Timer ready dari localStorage, tombol start diaktifkan');
            } else {
                enableStartButton(false);
            }
        }

        function resetJuriStatus() {
            juriReadyCount = 0;
            isTimerReady = false;
            processedJuriIds = [];
            enableStartButton(false);
            updateJuriStatusDisplay();

            localStorage.removeItem(STORAGE_JURI_COUNT);
            localStorage.removeItem(STORAGE_TIMER_READY);
            localStorage.removeItem(STORAGE_PROCESSED_JURIS);

            console.log('🔄 Status juri direset dan localStorage dibersihkan');
        }

        function updateJuriStatusDisplay() {
            const countElem = document.getElementById('juri-count');
            const progressBar = document.getElementById('juri-progress');
            const statusText = document.getElementById('juri-status-text');

            if (countElem) {
                countElem.textContent = juriReadyCount;
                countElem.classList.remove('ready', 'waiting', 'incomplete');

                if (juriReadyCount === totalJuriRequired) {
                    countElem.classList.add('ready');
                } else if (juriReadyCount >= 2) {
                    countElem.classList.add('waiting');
                } else {
                    countElem.classList.add('incomplete');
                }
            }

            if (progressBar) {
                const percentage = (juriReadyCount / totalJuriRequired) * 100;
                progressBar.style.width = `${percentage}%`;
                progressBar.setAttribute('aria-valuenow', juriReadyCount);

                if (juriReadyCount === totalJuriRequired) {
                    progressBar.className = 'progress-bar bg-success';
                } else if (juriReadyCount >= 2) {
                    progressBar.className = 'progress-bar bg-warning';
                } else {
                    progressBar.className = 'progress-bar bg-danger';
                }
            }

            if (statusText) {
                if (juriReadyCount === totalJuriRequired) {
                    statusText.innerHTML = `
                        <small class="text-success">
                            <i class="fas fa-check-circle me-1"></i>
                            Semua juri sudah siap! Timer dapat dimulai.
                        </small>
                    `;
                } else {
                    const remaining = totalJuriRequired - juriReadyCount;
                    statusText.innerHTML = `
                        <small class="text-secondary">
                            <i class="fas fa-hourglass-half me-1"></i>
                            Menunggu ${remaining} juri lagi untuk siap...
                        </small>
                    `;
                }
            }
        }

        function enableStartButton(enabled) {
            if (btnStart) {
                btnStart.disabled = !enabled;

                if (enabled) {
                    btnStart.style.opacity = '1';
                    btnStart.style.cursor = 'pointer';
                    btnStart.title = 'Mulai timer (semua juri sudah siap)';
                } else {
                    btnStart.style.opacity = '0.5';
                    btnStart.style.cursor = 'not-allowed';
                    btnStart.title = 'Menunggu semua juri siap...';
                }
            }
        }

        function showNotification(message, type = 'info') {
            const oldNotif = document.getElementById('timer-notification');
            if (oldNotif) oldNotif.remove();

            const notif = document.createElement('div');
            notif.id = 'timer-notification';
            notif.style.position = 'fixed';
            notif.style.top = '20px';
            notif.style.right = '20px';
            notif.style.padding = '15px 25px';
            notif.style.borderRadius = '10px';
            notif.style.zIndex = '9999';
            notif.style.animation = 'slideIn 0.3s ease-out';
            notif.style.boxShadow = '0 5px 20px rgba(0,0,0,0.3)';

            const icon = type === 'success' ? 'fa-check-circle' : type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';
            const bgColor = type === 'success' ? 'linear-gradient(135deg, #28a745, #20c997)' :
                type === 'warning' ? 'linear-gradient(135deg, #ffc107, #ff9800)' :
                'linear-gradient(135deg, #17a2b8, #138496)';

            notif.style.background = bgColor;
            notif.style.color = type === 'warning' ? '#212529' : 'white';

            notif.innerHTML = `
                <div class="d-flex align-items-center gap-3">
                    <i class="fas ${icon} fa-2x"></i>
                    <span>${message}</span>
                </div>
            `;

            document.body.appendChild(notif);

            setTimeout(() => {
                if (notif) notif.remove();
            }, 3000);
        }

        // ========== TIMER FUNCTIONS ==========

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
                    if (seconds >= 60) {
                        const extraMinutes = Math.floor(seconds / 60);
                        return ((minutes + extraMinutes) * 60) + (seconds % 60);
                    }
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

        function updateDisplay() {
            timerDisplay.textContent = secondsToDisplayTime(remaining);
            timerDisplay.classList.remove('warning', 'danger');
            if (remaining <= 10) {
                timerDisplay.classList.add('danger');
            } else if (remaining <= 30) {
                timerDisplay.classList.add('warning');
            }
        }

        function validateAndUpdateTime(input) {
            if (updateTimeout) clearTimeout(updateTimeout);

            updateTimeout = setTimeout(() => {
                const timeString = input.value.trim();
                if (!timeString) return;

                let formatted = timeString.replace(/[^0-9:]/g, '');
                if (formatted.length === 2 && !formatted.includes(':') && timeString.length === 2) {
                    formatted = formatted + ':';
                }
                if (formatted.length > 5) formatted = formatted.substring(0, 5);
                input.value = formatted;

                const totalSeconds = parseTimeToSeconds(formatted);
                if (!isNaN(totalSeconds) && totalSeconds > 0 && totalSeconds <= 599) {
                    remaining = totalSeconds;
                    lastValidTime = formatted;
                    localStorage.setItem('timer', remaining);
                    localStorage.setItem('waktu', formatted);
                    updateDisplay();
                    input.style.borderColor = '#00e5ff';
                } else if (totalSeconds > 599) {
                    input.style.borderColor = '#ff6b6b';
                } else {
                    input.style.borderColor = '#ff6b6b';
                }
            }, 500);
        }

        function handleTimeInputKeypress(event) {
            const char = String.fromCharCode(event.which);
            if (!char.match(/[0-9:]/) && event.which !== 8) {
                event.preventDefault();
            }
            if (event.key === 'Enter') {
                event.preventDefault();
                validateAndSetTime(event.target);
            }
        }

        function validateAndSetTime(input) {
            const timeString = input.value.trim();
            if (!timeString) {
                input.value = lastValidTime;
                return;
            }

            const totalSeconds = parseTimeToSeconds(timeString);
            if (isNaN(totalSeconds) || totalSeconds <= 0) {
                input.value = lastValidTime;
                return;
            }

            if (totalSeconds > 599) {
                input.value = '9:59';
                remaining = 599;
                localStorage.setItem('timer', 599);
                localStorage.setItem('waktu', '9:59');
                updateDisplay();
                input.style.borderColor = '#00e5ff';
                return;
            }

            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;
            const formattedTime = `${minutes}:${seconds.toString().padStart(2, '0')}`;

            input.value = formattedTime;
            lastValidTime = formattedTime;
            remaining = totalSeconds;
            localStorage.setItem('timer', remaining);
            localStorage.setItem('waktu', formattedTime);
            updateDisplay();
            input.style.borderColor = '#00e5ff';
        }

        function setTimePreset(timeString) {
            const input = document.getElementById('input-time');
            input.value = timeString;
            validateAndSetTime(input);
            const totalSeconds = parseTimeToSeconds(timeString);
            remaining = totalSeconds;
            updateDisplay();
        }

        function updateButtons(state) {
            btnStart.style.display = 'none';
            btnPause.style.display = 'none';
            btnResume.style.display = 'none';
            btnStop.style.display = 'none';

            if (state === 'stopped') {
                btnStart.style.display = 'block';
            } else if (state === 'active') {
                btnPause.style.display = 'block';
                btnStop.style.display = 'block';
            } else if (state === 'paused') {
                btnResume.style.display = 'block';
                btnStop.style.display = 'block';
            }
        }

        function updateTimerStatus(status) {
            timerStatus.className = 'timer-status';
            if (status === 'active') {
                timerStatus.classList.add('active');
                timerStatus.innerHTML = '<i class="fas fa-play-circle me-2"></i>Active';
            } else if (status === 'paused') {
                timerStatus.classList.add('paused');
                timerStatus.innerHTML = '<i class="fas fa-pause-circle me-2"></i>Paused';
            } else if (status === 'stopped') {
                timerStatus.classList.add('stopped');
                timerStatus.innerHTML = '<i class="fas fa-stop-circle me-2"></i>Stopped';
            }
        }

        function updateConnectionStatus(connected) {
            if (connected) {
                connectionIndicator.className = 'connection-indicator connected';
                connectionText.className = 'connection-text connected';
                connectionText.innerHTML = 'Server: Connected';
            } else {
                connectionIndicator.className = 'connection-indicator disconnected';
                connectionText.className = 'connection-text disconnected';
                connectionText.innerHTML = 'Server: Disconnected';
            }
        }

        // ========== TIMER CONTROL FUNCTIONS ==========

        function startTimer() {
            if (!isTimerReady) {
                const remaining = totalJuriRequired - juriReadyCount;
                showNotification(`⚠️ Belum semua juri siap! Menunggu ${remaining} juri lagi.`, 'warning');
                return;
            }

            if (ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({
                    type: 'start',
                    remaining: remaining
                }));
                showNotification('⏱️ Timer dimulai!', 'success');
            } else {
                showNotification('❌ Koneksi server terputus!', 'error');
            }
        }

        function pauseTimer() {
            if (ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({
                    type: 'pause'
                }));
                showNotification('⏸️ Timer dijeda', 'info');
            }
        }

        function resumeTimer() {
            if (ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({
                    type: 'resume'
                }));
                showNotification('▶️ Timer dilanjutkan', 'success');
            }
        }

        function stopTimer() {
            if (ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({
                    type: 'stop'
                }));
                showNotification('⏹️ Timer dihentikan', 'info');
            }
        }

        function playNotification() {
            try {
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
            } catch (e) {
                console.log('Browser tidak mendukung audio notification');
            }
        }

        // ========== WEBSOCKET FUNCTIONS ==========

        function connectWebSocket() {
            ws = new WebSocket('ws://' + hostname + ':3000');

            ws.onopen = () => {
                console.log("✅ Timer terhubung ke server");
                updateConnectionStatus(true);
                reconnectAttempts = 0;

                const savedTime = localStorage.getItem('timer');
                const savedWaktu = localStorage.getItem('waktu');
                if (savedTime) {
                    remaining = parseInt(savedTime);
                    if (savedWaktu) {
                        document.getElementById('input-time').value = savedWaktu;
                        lastValidTime = savedWaktu;
                    }
                }
                updateDisplay();
                updateTimerStatus('stopped');
            };

            ws.onmessage = (event) => {
                const data = JSON.parse(event.data);

                if (typeof data.remaining === 'number') {
                    remaining = data.remaining;
                    updateDisplay();
                }

                // Handle juri_ready dari server
                if (data.type === 'juri_ready') {
                    const idJuri = data.id_juri;
                    console.log(`✅ Juri ${idJuri} ready`);

                    if (!processedJuriIds.includes(idJuri)) {
                        processedJuriIds.push(idJuri);
                        juriReadyCount++;
                        saveJuriStatusToStorage();
                        updateJuriStatusDisplay();
                        showNotification(`Juri ${idJuri} sudah siap! (${juriReadyCount}/4)`, 'success');

                        if (juriReadyCount >= totalJuriRequired && !isTimerReady) {
                            isTimerReady = true;
                            saveJuriStatusToStorage();
                            enableStartButton(true);
                            updateJuriStatusDisplay();
                            showNotification('🎉 Semua juri sudah siap! Timer dapat dimulai.', 'success');
                        }
                    }
                }

                // Handle partai baru - reset status juri
                if (data.type === 'partai_data_tunggal') {
                    console.log('🔄 Partai baru, reset status juri');
                    resetJuriStatus();
                }

                if (data.type === 'tick') {
                    timerActive = true;
                    timerState = 'active';
                    updateButtons('active');
                    updateTimerStatus('active');
                } else if (data.type === 'stopped') {
                    timerActive = false;
                    timerState = 'stopped';
                    updateButtons('stopped');
                    updateTimerStatus('stopped');
                    const inputTime = document.getElementById('input-time').value;
                    remaining = parseTimeToSeconds(inputTime);
                    localStorage.setItem('timer', remaining);
                    updateDisplay();
                } else if (data.type === 'paused') {
                    timerActive = false;
                    timerState = 'paused';
                    updateButtons('paused');
                    updateTimerStatus('paused');
                } else if (data.type === 'resumed') {
                    timerActive = true;
                    timerState = 'active';
                    updateButtons('active');
                    updateTimerStatus('active');
                } else if (data.type === 'ended') {
                    timerActive = false;
                    timerState = 'stopped';
                    updateButtons('stopped');
                    updateTimerStatus('stopped');
                    playNotification();
                    const inputTime = document.getElementById('input-time').value;
                    remaining = parseTimeToSeconds(inputTime);
                    localStorage.setItem('timer', remaining);
                    updateDisplay();
                    showNotification('⏰ Waktu habis!', 'warning');
                }
            };

            ws.onclose = function() {
                console.log('WebSocket disconnected');
                updateConnectionStatus(false);
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

        // ========== FULLSCREEN FUNCTIONS ==========

        var elem = document.documentElement;

        function openFullscreen() {
            if (elem.requestFullscreen) elem.requestFullscreen();
            else if (elem.mozRequestFullScreen) elem.mozRequestFullScreen();
            else if (elem.webkitRequestFullscreen) elem.webkitRequestFullscreen();
            else if (elem.msRequestFullscreen) elem.msRequestFullscreen();
            openfullBtn.style.display = "none";
            exitfullBtn.style.display = "block";
        }

        function closeFullscreen() {
            if (document.exitFullscreen) document.exitFullscreen();
            else if (document.mozCancelFullScreen) document.mozCancelFullScreen();
            else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
            else if (document.msExitFullscreen) document.msExitFullscreen();
            openfullBtn.style.display = "block";
            exitfullBtn.style.display = "none";
        }

        function handleFullscreenChange() {
            if (document.fullscreenElement || document.webkitFullscreenElement ||
                document.mozFullScreenElement || document.msFullscreenElement) {
                openfullBtn.style.display = "none";
                exitfullBtn.style.display = "block";
            } else {
                openfullBtn.style.display = "block";
                exitfullBtn.style.display = "none";
            }
        }

        document.addEventListener('fullscreenchange', handleFullscreenChange);
        document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
        document.addEventListener('mozfullscreenchange', handleFullscreenChange);
        document.addEventListener('MSFullscreenChange', handleFullscreenChange);

        // ========== KEYBOARD SHORTCUTS ==========

        document.addEventListener('keydown', function(e) {
            if (e.target.matches('input, textarea')) return;

            if (e.code === 'Space') {
                e.preventDefault();
                if (timerState === 'stopped') startTimer();
                else if (timerState === 'active') pauseTimer();
                else if (timerState === 'paused') resumeTimer();
            }

            if (e.code === 'KeyS') {
                e.preventDefault();
                stopTimer();
            }

            if (e.code === 'KeyR') {
                e.preventDefault();
                const input = document.getElementById('input-time');
                validateAndSetTime(input);
            }

            if (e.code === 'KeyF') {
                e.preventDefault();
                if (document.fullscreenElement) closeFullscreen();
                else openFullscreen();
            }
        });

        // ========== INITIALIZATION ==========

        document.addEventListener('DOMContentLoaded', function() {
            const savedWaktu = localStorage.getItem('waktu');
            if (savedWaktu) {
                document.getElementById('input-time').value = savedWaktu;
                remaining = parseTimeToSeconds(savedWaktu);
                lastValidTime = savedWaktu;
            }
            updateDisplay();
            loadJuriStatusFromStorage();
        });

        connectWebSocket();

        // Tambahkan CSS untuk animasi slideIn
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>

</html>