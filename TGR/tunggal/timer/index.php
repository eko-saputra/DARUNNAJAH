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
        }

        /* Loading animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(0, 229, 255, 0.3);
            border-radius: 50%;
            border-top-color: #00e5ff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
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
                        <button id="btn-start" class="btn-timer btn-start" onclick="startTimer()">
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
                            <kbd>F</kbd> (Fullscreen)
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
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

        let remaining = 120; // Default 2 minutes
        let timerActive = false;
        let timerState = 'stopped';
        let lastValidTime = '2:00';
        let updateTimeout;
        let reconnectAttempts = 0;
        const maxReconnectAttempts = 5;

        const hostname = window.location.hostname;
        let ws;

        function connectWebSocket() {
            ws = new WebSocket('ws://' + hostname + ':3000');

            // WebSocket connection
            ws.onopen = () => {
                console.log("Timer terhubung ke server");
                updateConnectionStatus(true);
                reconnectAttempts = 0;

                // Load saved time
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

                // Handle timer updates
                if (typeof data.remaining === 'number') {
                    remaining = data.remaining;
                    updateDisplay();
                }

                // Handle specific timer states
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

                    // Reset to initial time from input
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

                    // Reset to initial time from input
                    const inputTime = document.getElementById('input-time').value;
                    remaining = parseTimeToSeconds(inputTime);
                    localStorage.setItem('timer', remaining);
                    updateDisplay();
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
        document.addEventListener('DOMContentLoaded', function() {
            const savedWaktu = localStorage.getItem('waktu');
            if (savedWaktu) {
                document.getElementById('input-time').value = savedWaktu;
                remaining = parseTimeToSeconds(savedWaktu);
                lastValidTime = savedWaktu;
            }
            updateDisplay();
        });

        // Time Functions
        function parseTimeToSeconds(timeString) {
            if (!timeString) return 120;

            timeString = timeString.trim();

            // Format MM:SS
            if (timeString.match(/^\d+:\d{2}$/)) {
                const parts = timeString.split(':');
                const minutes = parseInt(parts[0]) || 0;
                const seconds = parseInt(parts[1]) || 0;
                return (minutes * 60) + seconds;
            }

            // Format angka saja (detik)
            if (timeString.match(/^\d+$/)) {
                return parseInt(timeString);
            }

            // Format fleksibel
            if (timeString.includes(':')) {
                const parts = timeString.split(':');
                if (parts.length === 2) {
                    const minutes = parseInt(parts[0]) || 0;
                    let seconds = parseInt(parts[1]) || 0;

                    // Handle jika detik > 59
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

            if (minutes >= 60) {
                const hours = Math.floor(minutes / 60);
                const mins = minutes % 60;
                return `${hours.toString().padStart(2, '0')}:${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            }

            return `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }

        function updateDisplay() {
            timerDisplay.textContent = secondsToDisplayTime(remaining);

            // Update color based on time
            timerDisplay.classList.remove('warning', 'danger');
            if (remaining <= 10) {
                timerDisplay.classList.add('danger');
            } else if (remaining <= 30) {
                timerDisplay.classList.add('warning');
            }
        }

        function validateAndUpdateTime(input) {
            // Clear previous timeout
            if (updateTimeout) {
                clearTimeout(updateTimeout);
            }

            // Set timeout to validate after user stops typing
            updateTimeout = setTimeout(() => {
                const timeString = input.value.trim();

                // Allow empty while typing
                if (!timeString) return;

                // Auto-format while typing
                let formatted = timeString.replace(/[^0-9:]/g, '');

                // Add colon if needed
                if (formatted.length === 2 && !formatted.includes(':') && timeString.length === 2) {
                    formatted = formatted + ':';
                }

                // Limit to reasonable length
                if (formatted.length > 5) {
                    formatted = formatted.substring(0, 5);
                }

                input.value = formatted;

                // Try to parse
                const totalSeconds = parseTimeToSeconds(formatted);

                if (!isNaN(totalSeconds) && totalSeconds > 0 && totalSeconds <= 599) {
                    // Valid time
                    remaining = totalSeconds;
                    lastValidTime = formatted;
                    localStorage.setItem('timer', remaining);
                    localStorage.setItem('waktu', formatted);
                    updateDisplay();

                    // Add visual feedback
                    input.style.borderColor = '#00e5ff';
                } else if (totalSeconds > 599) {
                    // Time too large
                    input.style.borderColor = '#ff6b6b';
                } else {
                    // Invalid, but don't change remaining yet
                    input.style.borderColor = '#ff6b6b';
                }
            }, 500); // Wait 500ms after user stops typing
        }

        function handleTimeInputKeypress(event) {
            // Allow only numbers and colon
            const char = String.fromCharCode(event.which);
            if (!char.match(/[0-9:]/) && event.which !== 8) { // 8 is backspace
                event.preventDefault();
            }

            // Auto-submit on Enter
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

            if (totalSeconds > 599) { // Max 9:59
                input.value = '9:59';
                remaining = 599;
                localStorage.setItem('timer', 599);
                localStorage.setItem('waktu', '9:59');
                updateDisplay();
                input.style.borderColor = '#00e5ff';
                return;
            }

            // Format waktu dengan benar
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

            // Visual feedback
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
                timerStatus.classList.add('status-active');
                timerStatus.innerHTML = '<i class="fas fa-play-circle me-2"></i>Active';
            } else if (status === 'paused') {
                timerStatus.classList.add('status-paused');
                timerStatus.innerHTML = '<i class="fas fa-pause-circle me-2"></i>Paused';
            } else if (status === 'stopped') {
                timerStatus.classList.add('status-stopped');
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

        // Timer control functions
        function startTimer() {
            if (ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({
                    type: 'start',
                    remaining: remaining
                }));
            } else {
                alert('Koneksi server terputus. Menunggu koneksi kembali...');
            }
        }

        function pauseTimer() {
            if (ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({
                    type: 'pause'
                }));
            }
        }

        function resumeTimer() {
            if (ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({
                    type: 'resume'
                }));
            }
        }

        function stopTimer() {
            if (ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({
                    type: 'stop'
                }));
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
            openfullBtn.style.display = "none";
            exitfullBtn.style.display = "block";
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
            openfullBtn.style.display = "block";
            exitfullBtn.style.display = "none";
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
                openfullBtn.style.display = "none";
                exitfullBtn.style.display = "block";
            } else {
                openfullBtn.style.display = "block";
                exitfullBtn.style.display = "none";
            }
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ignore if typing in input
            if (e.target.matches('input, textarea')) {
                return;
            }

            // Space bar untuk start/pause/resume
            if (e.code === 'Space') {
                e.preventDefault();

                if (timerState === 'stopped') {
                    startTimer();
                } else if (timerState === 'active') {
                    pauseTimer();
                } else if (timerState === 'paused') {
                    resumeTimer();
                }
            }

            // S key untuk stop
            if (e.code === 'KeyS') {
                e.preventDefault();
                stopTimer();
            }

            // R key untuk reset time dari input
            if (e.code === 'KeyR') {
                e.preventDefault();
                const input = document.getElementById('input-time');
                validateAndSetTime(input);
            }

            // F key untuk fullscreen
            if (e.code === 'KeyF') {
                e.preventDefault();
                if (document.fullscreenElement ||
                    document.webkitFullscreenElement ||
                    document.mozFullScreenElement ||
                    document.msFullscreenElement) {
                    closeFullscreen();
                } else {
                    openFullscreen();
                }
            }
        });
    </script>
</body>

</html>