<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Operator Timer & Babak</title>
  <link rel="shortcut icon" href="../../assets/img/LogoIPSI.png" />
  <link href="../../assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: #0a0e17;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', system-ui, sans-serif;
    }

    .timer-card {
      background: linear-gradient(145deg, #1e2532 0%, #141b26 100%);
      border-radius: 40px;
      box-shadow: 0 25px 50px -8px rgba(0, 0, 0, 0.6), inset 0 2px 4px rgba(255, 255, 255, 0.05);
      padding: 2.5rem;
      max-width: 900px;
      width: 100%;
      border: 1px solid rgba(0, 180, 255, 0.15);
    }

    .timer-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }

    .timer-header h3 {
      color: #fff;
      font-weight: 600;
      letter-spacing: 1px;
      margin: 0;
      text-transform: uppercase;
      background: linear-gradient(135deg, #7dd3fc, #38bdf8);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .fullscreen-btn {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 16px;
      padding: 10px 16px;
      transition: all 0.3s ease;
      color: #94a3b8;
      cursor: pointer;
    }

    .fullscreen-btn:hover {
      background: rgba(255, 255, 255, 0.1);
      border-color: #38bdf8;
      color: #38bdf8;
      transform: scale(1.05);
    }

    .control-panel {
      background: rgba(0, 0, 0, 0.3);
      border-radius: 28px;
      padding: 1.75rem;
      border: 1px solid rgba(255, 255, 255, 0.03);
      backdrop-filter: blur(5px);
      margin-bottom: 2rem;
    }

    .form-label {
      color: #94a3b8;
      font-size: 0.85rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      margin-bottom: 0.75rem;
    }

    .form-select-custom,
    .time-input-custom {
      background: rgba(0, 0, 0, 0.4);
      border: 2px solid rgba(56, 189, 248, 0.2);
      color: #e2e8f0;
      border-radius: 20px;
      padding: 14px 20px;
      font-size: 1.1rem;
      font-weight: 500;
      width: 100%;
      transition: all 0.3s ease;
    }

    .form-select-custom:focus,
    .time-input-custom:focus {
      border-color: #38bdf8;
      box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.15);
      outline: none;
      background: rgba(0, 0, 0, 0.6);
    }

    .time-input-custom {
      font-family: 'Courier New', monospace;
      font-size: 1.3rem;
      letter-spacing: 2px;
      text-align: center;
    }

    .timer-display {
      font-family: 'Courier New', monospace;
      font-size: 7rem;
      font-weight: 800;
      text-align: center;
      color: #38bdf8;
      text-shadow: 0 0 40px rgba(56, 189, 248, 0.5);
      background: rgba(0, 0, 0, 0.3);
      border-radius: 50px;
      padding: 2rem 1rem;
      margin: 1.5rem 0 2rem;
      border: 2px solid rgba(56, 189, 248, 0.2);
      letter-spacing: 8px;
    }

    .timer-display.warning {
      color: #fbbf24;
      text-shadow: 0 0 40px rgba(251, 191, 36, 0.5);
      border-color: rgba(251, 191, 36, 0.3);
    }

    .timer-display.danger {
      color: #f87171;
      text-shadow: 0 0 40px rgba(248, 113, 113, 0.5);
      border-color: rgba(248, 113, 113, 0.3);
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

    .btn-timer {
      border-radius: 40px;
      font-weight: 700;
      padding: 16px 36px;
      font-size: 1.2rem;
      border: none;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      min-width: 150px;
      letter-spacing: 1px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    }

    .btn-timer:hover:not(:disabled) {
      transform: translateY(-3px) scale(1.02);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
    }

    .btn-start {
      background: linear-gradient(135deg, #059669, #10b981);
      color: white;
    }

    .btn-pause {
      background: linear-gradient(135deg, #d97706, #f59e0b);
      color: white;
    }

    .btn-resume {
      background: linear-gradient(135deg, #2563eb, #3b82f6);
      color: white;
    }

    .btn-stop {
      background: linear-gradient(135deg, #b91c1c, #dc2626);
      color: white;
    }

    .babak-container {
      display: flex;
      justify-content: center;
      gap: 15px;
      flex-wrap: wrap;
      margin: 25px 0 20px;
    }

    .babak-btn {
      background: rgba(255, 255, 255, 0.03);
      border: 2px solid rgba(56, 189, 248, 0.2);
      color: #94a3b8;
      border-radius: 60px;
      padding: 14px 36px;
      font-size: 1.2rem;
      font-weight: 700;
      transition: all 0.3s ease;
      min-width: 130px;
      cursor: pointer;
    }

    .babak-btn:hover:not(:disabled) {
      border-color: #38bdf8;
      color: #38bdf8;
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(56, 189, 248, 0.2);
    }

    .babak-btn.active {
      background: linear-gradient(135deg, #f59e0b, #fbbf24);
      border-color: #fbbf24;
      color: #000;
      font-weight: 800;
      box-shadow: 0 0 30px rgba(251, 191, 36, 0.4);
    }

    .babak-btn.completed {
      border-color: #10b981;
      color: #10b981;
      position: relative;
      padding-right: 50px;
    }

    .babak-btn.completed::after {
      content: "✓";
      position: absolute;
      right: 20px;
      font-size: 1.3rem;
      font-weight: bold;
    }

    .babak-btn:disabled {
      opacity: 0.25;
      cursor: not-allowed;
      border-color: rgba(255, 255, 255, 0.1);
    }

    .switch {
      position: relative;
      display: inline-block;
      width: 60px;
      height: 30px;
    }

    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(0, 0, 0, 0.4);
      border: 2px solid rgba(56, 189, 248, 0.2);
      border-radius: 30px;
      transition: 0.3s;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 22px;
      width: 22px;
      left: 4px;
      bottom: 2px;
      background-color: #94a3b8;
      border-radius: 50%;
      transition: 0.3s;
    }

    input:checked+.slider {
      background-color: #38bdf8;
      border-color: #38bdf8;
    }

    input:checked+.slider:before {
      transform: translateX(28px);
      background-color: white;
    }

    .status-badge {
      padding: 8px 24px;
      border-radius: 40px;
      font-weight: 600;
      font-size: 0.95rem;
      background: rgba(0, 0, 0, 0.3);
    }

    .status-active {
      border: 1px solid #10b981;
      color: #10b981;
    }

    .status-paused {
      border: 1px solid #f59e0b;
      color: #f59e0b;
    }

    .status-stopped {
      border: 1px solid #ef4444;
      color: #ef4444;
    }

    .preset-buttons {
      display: flex;
      gap: 8px;
      justify-content: center;
      margin-top: 12px;
    }

    .preset-btn {
      background: rgba(56, 189, 248, 0.1);
      border: 1px solid rgba(56, 189, 248, 0.2);
      color: #94a3b8;
      border-radius: 30px;
      padding: 6px 16px;
      font-size: 0.9rem;
      transition: all 0.2s ease;
      cursor: pointer;
    }

    .preset-btn:hover {
      background: rgba(56, 189, 248, 0.2);
      border-color: #38bdf8;
      color: #38bdf8;
    }

    @media (max-width: 768px) {
      .timer-card {
        padding: 1.5rem;
      }

      .timer-display {
        font-size: 4rem;
      }

      .btn-timer {
        padding: 12px 24px;
        font-size: 1rem;
        min-width: 120px;
      }

      .babak-btn {
        padding: 10px 24px;
        font-size: 1rem;
        min-width: 100px;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="timer-card">

          <!-- Header -->
          <div class="timer-header">
            <h3>⏱️ TIMER PERTANDINGAN</h3>
            <div class="d-flex gap-2">
              <button id="openfull" onclick="openFullscreen();" class="fullscreen-btn" title="Fullscreen">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3" />
                </svg>
              </button>
              <button id="exitfull" onclick="closeFullscreen();" class="fullscreen-btn" style="display: none;" title="Exit Fullscreen">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3" />
                </svg>
              </button>
            </div>
          </div>

          <!-- Control Panel -->
          <div class="control-panel">
            <div class="row g-4">
              <div class="col-md-6">
                <div class="form-label">📋 JUMLAH BABAK</div>
                <select class="form-select-custom" id="jumlah-babak">
                  <option value="1">1 Babak</option>
                  <option value="2">2 Babak</option>
                  <option value="3" selected>3 Babak</option>
                  <option value="4">4 Babak</option>
                  <option value="5">5 Babak</option>
                </select>
              </div>
              <div class="col-md-6">
                <div class="form-label">⏱️ DURASI PER BABAK</div>
                <input type="text"
                  class="time-input-custom"
                  id="manual-time"
                  placeholder="2:00"
                  value="2:00"
                  oninput="validateManualTime(this)"
                  onchange="updateTimerFromInput(this)"
                  onblur="formatAndUpdateTime(this)"
                  onkeyup="handleTimeKeyUp(event)">
                <div class="preset-buttons">
                  <button class="preset-btn" onclick="setTimePreset('1:00')">1:00</button>
                  <button class="preset-btn" onclick="setTimePreset('1:30')">1:30</button>
                  <button class="preset-btn" onclick="setTimePreset('2:00')">2:00</button>
                  <button class="preset-btn" onclick="setTimePreset('3:00')">3:00</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Timer Display -->
          <div id="timer" class="timer-display">02:00</div>

          <!-- Timer Controls -->
          <div class="d-flex justify-content-center flex-wrap gap-3 mb-4">
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

          <!-- Babak Selection -->
          <div class="text-center">
            <div class="form-label mb-3">🎯 PILIH BABAK</div>
            <div id="babak-container" class="babak-container"></div>

            <!-- Footer -->
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top border-secondary">
              <div class="d-flex align-items-center gap-3">
                <label class="switch">
                  <input type="checkbox" id="aktifkan-semua">
                  <span class="slider"></span>
                </label>
                <span class="text-light">Aktifkan Semua</span>
              </div>
              <div id="status-indicator" class="status-badge status-stopped">
                <i class="fas fa-stop-circle me-2"></i>Stopped
              </div>
            </div>

            <div class="mt-4 text-secondary small">
              <kbd>Space</kbd> Start/Pause/Resume ·
              <kbd>S</kbd> Stop ·
              <kbd>R</kbd> Reset Waktu ·
              <kbd>F</kbd> Fullscreen
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <script src="../../assets/jquery/jquery-3.6.0.min.js"></script>
  <script src="../../assets/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    const timerDisplay = $('#timer');
    const btnStart = $('#btn-start');
    const btnPause = $('#btn-pause');
    const btnResume = $('#btn-resume');
    const btnStop = $('#btn-stop');
    const manualTime = $('#manual-time');
    const jumlahBabak = $('#jumlah-babak');
    const aktifkanSemua = $('#aktifkan-semua');
    const babakContainer = $('#babak-container');
    const statusIndicator = $('#status-indicator');

    const hostname = window.location.hostname;
    const ws = new WebSocket('ws://' + hostname + ':3000');

    let remaining = 120;
    let currentRound = null;
    let maxBabak = 3;
    let timerState = 'stopped';
    let lastValidTime = '2:00';
    let initialTime = 120; // Menyimpan waktu awal dari input

    // WebSocket
    ws.onopen = () => {
      console.log("Timer Connected");
      updateStatus('connected');
      loadSavedData();
    };

    ws.onmessage = (event) => {
      const data = JSON.parse(event.data);

      if (typeof data.remaining === 'number') {
        remaining = data.remaining;
        updateDisplay();
      }

      if (data.type === 'tick') {
        timerState = 'active';
        updateButtons('active');
        updateStatus('active');
      } else if (data.type === 'stopped') {
        timerState = 'stopped';
        updateButtons('stopped');
        updateStatus('stopped');

        // KEMBALIKAN KE WAKTU AWAL DARI INPUT
        resetToInitialTime();

      } else if (data.type === 'paused') {
        timerState = 'paused';
        updateButtons('paused');
        updateStatus('paused');
      } else if (data.type === 'resumed') {
        timerState = 'active';
        updateButtons('active');
        updateStatus('active');
      } else if (data.type === 'ended') {
        timerState = 'stopped';
        updateButtons('stopped');
        updateStatus('stopped');
        playNotification();

        // KEMBALIKAN KE WAKTU AWAL DARI INPUT
        resetToInitialTime();

        handleRoundEnd();
      }
    };

    ws.onclose = () => {
      console.log('WebSocket Disconnected');
      updateStatus('disconnected');
    };

    // Initialize
    $(document).ready(() => {
      loadSavedData();
      setupEventListeners();
      renderBabakButtons(maxBabak);
    });

    function loadSavedData() {
      const savedWaktu = localStorage.getItem('waktu') || '2:00';
      manualTime.val(savedWaktu);
      lastValidTime = savedWaktu;
      initialTime = parseTimeToSeconds(savedWaktu);
      remaining = initialTime;
      updateDisplay();

      maxBabak = parseInt(localStorage.getItem('jumlahBabak')) || 3;
      jumlahBabak.val(maxBabak);
    }

    function setupEventListeners() {
      // Event listeners untuk manual time
      manualTime.on('input', function() {
        validateManualTime(this);
      });

      manualTime.on('change', function() {
        console.log('Change event triggered');
        updateTimerFromInput(this);
      });

      manualTime.on('blur', function() {
        console.log('Blur event triggered');
        formatAndUpdateTime(this);
      });

      jumlahBabak.on('change', function() {
        maxBabak = parseInt(jumlahBabak.val());
        localStorage.setItem('jumlahBabak', maxBabak);
        renderBabakButtons(maxBabak);
        currentRound = null;
        btnStart.prop('disabled', true);
        aktifkanSemua.prop('checked', false);
        toggleBabakMode();

        if (ws.readyState === WebSocket.OPEN) {
          ws.send(JSON.stringify({
            type: 'set_jumlah_babak',
            jumlah: maxBabak
          }));
        }
      });

      aktifkanSemua.on('change', toggleBabakMode);

      btnStart.on('click', startTimer);
      btnPause.on('click', pauseTimer);
      btnResume.on('click', resumeTimer);
      btnStop.on('click', stopTimer);
    }

    // Fungsi untuk mereset ke waktu awal dari input
    function resetToInitialTime() {
      const timeValue = manualTime.val();
      initialTime = parseTimeToSeconds(timeValue);
      remaining = initialTime;
      localStorage.setItem('timer', remaining);
      updateDisplay();
      console.log('Reset to initial time:', timeValue, '(', remaining, 'seconds)');
    }

    // Time Functions
    function parseTimeToSeconds(timeString) {
      if (!timeString) return 120;
      timeString = timeString.trim();

      // Format MM:SS atau M:SS
      if (timeString.match(/^\d{1,2}:\d{2}$/)) {
        const parts = timeString.split(':');
        const minutes = parseInt(parts[0]) || 0;
        const seconds = parseInt(parts[1]) || 0;

        // Validasi detik tidak boleh lebih dari 59
        if (seconds > 59) {
          return (minutes * 60) + 59;
        }

        return (minutes * 60) + seconds;
      }

      // Format hanya angka (dianggap menit)
      if (timeString.match(/^\d+$/)) {
        return parseInt(timeString) * 60;
      }

      return 120;
    }

    function secondsToDisplayTime(seconds) {
      const m = Math.floor(seconds / 60);
      const s = seconds % 60;
      return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
    }

    function validateManualTime(input) {
      let value = input.value.replace(/[^0-9:]/g, '');

      // Auto-format while typing
      if (value.length === 2 && !value.includes(':')) {
        value = value + ':';
      }

      // Prevent multiple colons
      const colonCount = (value.match(/:/g) || []).length;
      if (colonCount > 1) {
        value = value.replace(/:/g, '');
        if (value.length > 2) {
          value = value.substring(0, 2) + ':' + value.substring(2, 4);
        }
      }

      // Limit length
      if (value.length > 5) {
        value = value.substring(0, 5);
      }

      input.value = value;
    }

    function handleTimeKeyUp(event) {
      if (event.key === 'Enter') {
        formatAndUpdateTime(event.target);
      }
    }

    function formatAndUpdateTime(input) {
      // Format waktu ketika kehilangan fokus atau enter
      const timeString = input.value.trim();

      if (!timeString) {
        input.value = lastValidTime;
        updateTimerFromInput(input);
        return;
      }

      // Jika hanya angka (contoh: "2" -> "2:00")
      if (timeString.match(/^\d+$/)) {
        const minutes = parseInt(timeString);
        const formattedTime = `${minutes}:00`;
        input.value = formattedTime;
        updateTimerFromInput(input);
        return;
      }

      // Jika format tidak lengkap (contoh: "2:" -> "2:00")
      if (timeString.match(/^\d+:$/)) {
        const minutes = timeString.replace(':', '');
        const formattedTime = `${minutes}:00`;
        input.value = formattedTime;
        updateTimerFromInput(input);
        return;
      }

      // Jika format M:SS tetapi detik > 59
      if (timeString.match(/^\d+:\d{2}$/)) {
        const parts = timeString.split(':');
        const seconds = parseInt(parts[1]);
        if (seconds > 59) {
          const minutes = parseInt(parts[0]);
          const formattedTime = `${minutes}:59`;
          input.value = formattedTime;
          updateTimerFromInput(input);
          return;
        }
      }

      updateTimerFromInput(input);
    }

    function updateTimerFromInput(input) {
      const timeString = input.value.trim();
      console.log('Updating timer from input:', timeString);

      if (!timeString) {
        input.value = lastValidTime;
        return;
      }

      const totalSeconds = parseTimeToSeconds(timeString);
      console.log('Total seconds:', totalSeconds);

      if (isNaN(totalSeconds) || totalSeconds <= 0) {
        input.value = lastValidTime;
        return;
      }

      if (totalSeconds > 599) { // Max 9:59
        input.value = '9:59';
        remaining = 599;
        initialTime = 599;
        localStorage.setItem('timer', 599);
        localStorage.setItem('waktu', '9:59');
        updateDisplay();
        return;
      }

      const minutes = Math.floor(totalSeconds / 60);
      const seconds = totalSeconds % 60;
      const formattedTime = `${minutes}:${seconds.toString().padStart(2, '0')}`;

      console.log('Formatted time:', formattedTime);

      input.value = formattedTime;
      lastValidTime = formattedTime;
      remaining = totalSeconds;
      initialTime = totalSeconds; // Update initial time
      localStorage.setItem('timer', remaining);
      localStorage.setItem('waktu', formattedTime);
      updateDisplay();

      // Kirim update ke WebSocket jika diperlukan
      if (ws.readyState === WebSocket.OPEN && currentRound && timerState === 'stopped') {
        ws.send(JSON.stringify({
          type: 'update_time',
          remaining: remaining,
          round: currentRound
        }));
      }
    }

    function setTimePreset(timeString) {
      console.log('Setting time preset:', timeString);
      manualTime.val(timeString);
      updateTimerFromInput(manualTime[0]);
    }

    function updateDisplay() {
      const displayTime = secondsToDisplayTime(remaining);
      console.log('Updating display to:', displayTime);
      timerDisplay.text(displayTime);

      // Update color based on time
      timerDisplay.removeClass('warning danger');
      if (remaining <= 10) {
        timerDisplay.addClass('danger');
      } else if (remaining <= 30) {
        timerDisplay.addClass('warning');
      }
    }

    function updateStatus(status) {
      statusIndicator.removeClass('status-active status-paused status-stopped');
      if (status === 'active') {
        statusIndicator.addClass('status-active').html('<i class="fas fa-play-circle me-2"></i>Active');
      } else if (status === 'paused') {
        statusIndicator.addClass('status-paused').html('<i class="fas fa-pause-circle me-2"></i>Paused');
      } else if (status === 'stopped') {
        statusIndicator.addClass('status-stopped').html('<i class="fas fa-stop-circle me-2"></i>Stopped');
      } else if (status === 'connected') {
        statusIndicator.addClass('status-stopped').html('<i class="fas fa-circle me-2" style="color: #10b981;"></i>Connected');
      } else {
        statusIndicator.addClass('status-stopped').html('<i class="fas fa-circle me-2" style="color: #ef4444;"></i>Disconnected');
      }
    }

    function updateButtons(state) {
      btnStart.hide();
      btnPause.hide();
      btnResume.hide();
      btnStop.hide();

      if (state === 'stopped') {
        btnStart.show();
      } else if (state === 'active') {
        btnPause.show();
        btnStop.show();
      } else if (state === 'paused') {
        btnResume.show();
        btnStop.show();
      }
    }

    function renderBabakButtons(total) {
      babakContainer.empty();
      for (let i = 1; i <= total; i++) {
        const btn = $(`<button class="babak-btn" id="babak${i}" disabled>Babak ${i}</button>`);
        btn.on('click', () => setRound(i));
        babakContainer.append(btn);
      }

      const savedRound = parseInt(localStorage.getItem('babak'));
      if (!isNaN(savedRound) && savedRound <= total) {
        highlightActiveRound(savedRound);
        currentRound = savedRound;
        btnStart.prop('disabled', false);
      } else {
        setRound(1);
      }

      toggleBabakMode();
    }

    function toggleBabakMode() {
      if (aktifkanSemua.is(':checked')) {
        $('.babak-btn').prop('disabled', false);
      } else {
        $('.babak-btn').prop('disabled', true);
        if (currentRound) {
          $(`#babak${currentRound}`).prop('disabled', false);
        }
      }
    }

    function highlightActiveRound(round) {
      $('.babak-btn').removeClass('active completed');
      for (let i = 1; i < round; i++) {
        $(`#babak${i}`).addClass('completed');
      }
      $(`#babak${round}`).addClass('active');
    }

    function setRound(round) {
      currentRound = round;
      highlightActiveRound(round);
      btnStart.prop('disabled', false);
      localStorage.setItem('babak', round);
      toggleBabakMode();

      if (ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify({
          type: 'set_round',
          round
        }));
      }
    }

    function handleRoundEnd() {
      if (currentRound < maxBabak) {
        $(`#babak${currentRound}`).removeClass('active').addClass('completed');
        currentRound++;
        localStorage.setItem('babak', currentRound);
        $(`#babak${currentRound}`).addClass('active');
        if (!aktifkanSemua.is(':checked')) {
          $('.babak-btn').prop('disabled', true);
          $(`#babak${currentRound}`).prop('disabled', false);
        }

        if (ws.readyState === WebSocket.OPEN) {
          ws.send(JSON.stringify({
            type: 'set_round',
            round: currentRound
          }));
        }
        btnStart.prop('disabled', false);
      } else {
        $(`#babak${maxBabak}`).removeClass('active').addClass('completed');
        btnStart.hide().off('click');
        btnStart
          .removeClass('btn-start btn-success')
          .addClass('btn-secondary')
          .html('<i class="fas fa-redo me-2"></i>Reset')
          .show()
          .prop('disabled', false)
          .on('click', resetBabak);
        btnStop.hide();
      }
    }

    function resetBabak() {
      localStorage.removeItem('babak');
      currentRound = null;
      renderBabakButtons(maxBabak);
      btnStart
        .removeClass('btn-secondary')
        .addClass('btn-start btn-success')
        .html('<i class="fas fa-play me-2"></i>Start')
        .prop('disabled', true)
        .on('click', startTimer);
      setRound(1);

      // Reset waktu ke input
      resetToInitialTime();
    }

    function startTimer() {
      if (!currentRound) {
        alert('Pilih babak terlebih dahulu!');
        return;
      }
      if (ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify({
          type: 'start',
          remaining,
          round: currentRound
        }));
      }
    }

    function pauseTimer() {
      if (ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify({
          type: 'pause',
          round: currentRound
        }));
      }
    }

    function resumeTimer() {
      if (ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify({
          type: 'resume',
          round: currentRound
        }));
      }
    }

    function stopTimer() {
      if (ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify({
          type: 'stop',
          round: currentRound
        }));
      }
    }

    function playNotification() {
      try {
        const audio = new AudioContext();
        const oscillator = audio.createOscillator();
        const gain = audio.createGain();
        oscillator.connect(gain);
        gain.connect(audio.destination);
        oscillator.frequency.value = 800;
        gain.gain.setValueAtTime(0.3, audio.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.01, audio.currentTime + 0.5);
        oscillator.start();
        oscillator.stop(audio.currentTime + 0.5);
      } catch (e) {}
    }

    // Fullscreen
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

    // Keyboard Shortcuts
    document.addEventListener('keydown', function(e) {
      if (e.target.matches('input, select')) return;

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
        resetToInitialTime();
      }
      if (e.code === 'KeyF') {
        e.preventDefault();
        document.fullscreenElement ? closeFullscreen() : openFullscreen();
      }
    });

    document.addEventListener('selectstart', (e) => e.preventDefault());
  </script>
</body>

</html>