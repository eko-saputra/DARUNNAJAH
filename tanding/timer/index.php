<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Operator Pertandingan - Timer & Babak</title>
  <link rel="shortcut icon" href="../../assets/img/LogoIPSI.png">
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" /> -->

  <link href="../../assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body class="bg-dark text-white d-flex align-items-center justify-content-center">
  <div class="bg-dark bg-gradient text-white p-5 m-5 rounded-5">
    <div class="d-flex justify-content-between align-items-start mb-4">
      <h3 class="text-uppercase text-light mt-2">Operator TIMER</h3>
      <div>
        <button id="openfull" onclick="openFullscreen();" class="btn btn-outline-secondary me-2" title="Fullscreen">
          <svg width="24" height="24" viewBox="0 0 24 24">
            <path fill="#ffffff"
              d="M5,5H10V7H7V10H5V5M14,5H19V10H17V7H14V5M17,14H19V19H14V17H17V14M10,17V19H5V14H7V17H10Z" />
          </svg>
        </button>
        <button id="exitfull" onclick="closeFullscreen();" class="btn btn-outline-secondary d-none" title="Exit Fullscreen">
          <svg width="24" height="24" viewBox="0 0 24 24">
            <path fill="#ffffff"
              d="M14,14H19V16H16V19H14V14M5,14H10V19H8V16H5V14M8,5H10V10H5V8H8V5M19,8V10H14V5H16V8H19Z" />
          </svg>
        </button>
      </div>
    </div>

    <div class="card-body">
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <label for="jumlah-babak" class="form-label text-uppercase">Jumlah Babak</label>
          <select class="form-select form-select-lg bg-secondary text-white border-0" id="jumlah-babak">
            <option value="2">2</option>
            <option value="3" selected>3</option>
          </select>
        </div>
        <div class="col-md-6">
          <label for="input-time" class="form-label text-uppercase">Waktu</label>
          <style>
            input[type="time"]::-webkit-datetime-edit-ampm-field {
              display: none;
            }
          </style>

          <input type="time" class="form-control form-control-lg bg-secondary text-white border-0" id="input-time">
        </div>
      </div>

      <div class="text-center my-5">
        <div id="waktu" class="display-3 fw-bold text-info">02:00</div>
      </div>

      <div class="d-flex justify-content-center flex-wrap gap-3 mb-4">
        <button id="btn-start" class="btn btn-success btn-lg px-4 rounded-pill" disabled>
          <i class="fas fa-play me-2"></i>Start
        </button>
        <button id="btn-pause" class="btn btn-warning btn-lg px-4 rounded-pill d-none">
          <i class="fas fa-pause me-2"></i>Pause
        </button>
        <button id="btn-resume" class="btn btn-info btn-lg px-4 rounded-pill d-none">
          <i class="fas fa-play me-2"></i>Resume
        </button>
        <button id="btn-stop" class="btn btn-danger btn-lg px-4 rounded-pill d-none">
          <i class="fas fa-stop me-2"></i>Stop
        </button>
      </div>

      <div class="text-center mb-3">
        <h6 class="mb-3 text-uppercase">Pilih Babak</h6>
        <div id="babak-container" class="d-flex justify-content-center"></div>
      </div>

      <div class="d-flex justify-content-center align-items-center mt-5">
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="aktifkan-semua">
        </div>
      </div>
    </div>
  </div>

  <script src="../../assets/jquery/jquery-3.6.0.min.js"></script>
  <script src="../../assets/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const waktuElem = $('#waktu');
    const btnStart = $('#btn-start');
    const btnPause = $('#btn-pause');
    const btnResume = $('#btn-resume');
    const btnStop = $('#btn-stop');
    const inputTime = $('#input-time');
    const jumlahBabak = $('#jumlah-babak');
    const aktifkanSemua = $('#aktifkan-semua');
    const babakContainer = $('#babak-container');

    const ws = new WebSocket('ws://192.168.30.2:3000');

    let remaining = (parseInt(localStorage.getItem('timer')) || 120); // Default 2 minutes
    let currentRound = null;
    console.log(currentRound);
    let maxBabak = parseInt(jumlahBabak.val());
    let lastState = 'stopped';

    $(document).ready(() => {
      const savedWaktu = localStorage.getItem('waktu') || '02:00';
      inputTime.val(savedWaktu);
      setRemaining(false);

      maxBabak = parseInt(localStorage.getItem('jumlahBabak')) || 3;
      jumlahBabak.val(maxBabak);

      renderBabakButtons(maxBabak);
      updateButtons('stopped');

      inputTime.on('change input', () => setRemaining());

      jumlahBabak.on('change', () => {
        maxBabak = parseInt(jumlahBabak.val());
        localStorage.setItem('jumlahBabak', maxBabak);
        renderBabakButtons(maxBabak);
        currentRound = null;
        btnStart.prop('disabled', true);

        // Reset toggle
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
    });

    function renderBabakButtons(total) {
      babakContainer.empty();
      for (let i = 1; i <= total; i++) {
        const btn = $(`<button class="btn bg-success bg-gradient mx-3 px-4 tombol-babak text-dark rounded-pill" id="babak${i}" disabled>Babak ${i}</button>`);
        btn.on('click', () => setRound(i));
        babakContainer.append(btn);
      }
      enableFirstRound();

      const savedRound = parseInt(localStorage.getItem('babak'));
      if (!isNaN(savedRound) && savedRound <= total) {
        highlightActiveRound(savedRound);
        currentRound = savedRound;
        btnStart.prop('disabled', false);
      }

      if (!aktifkanSemua.is(':checked')) {
        $('.tombol-babak').prop('disabled', true);
        $(`#babak${savedRound}`).prop('disabled', false);
      }
    }

    function enableFirstRound() {
      $('#babak1').prop('disabled', false);
    }

    function toggleBabakMode() {
      if (aktifkanSemua.is(':checked')) {
        $('.tombol-babak').prop('disabled', false);
      } else {
        if (currentRound) {
          // Jika ada babak aktif, hanya itu yang enable
          $('.tombol-babak').prop('disabled', true);
          $(`#babak${currentRound}`).prop('disabled', false);
        } else {
          enableFirstRound(); // Kalau belum ada babak dipilih
        }
      }
    }

    function highlightActiveRound(round) {
      $('.tombol-babak')
        .removeClass('btn-warning text-dark')
        .addClass('bg-success text-dark');

      $(`#babak${round}`)
        .removeClass('bg-success')
        .addClass('btn-warning text-dark');

      // Kontrol enable/disable sesuai toggle
      if (!aktifkanSemua.is(':checked')) {
        $('.tombol-babak').prop('disabled', true);
        $(`#babak${round}`).prop('disabled', false);
      }
    }

    function setRemaining(saveToLocal = true) {
      const timeValue = inputTime.val();
      const parts = timeValue.split(':').map(Number);
      if (parts.length === 2) remaining = (parts[0] * 60) + parts[1];
      else if (parts.length === 3) remaining = (parts[0] * 3600) + (parts[1] * 60) + parts[2];
      updateDisplay();
      if (saveToLocal) {
        localStorage.setItem('waktu', timeValue);
        localStorage.setItem('timer', remaining);
      }
    }

    function setRound(round) {
      currentRound = round;
      highlightActiveRound(round); // Gunakan fungsi ini agar toggle & tombol lain ikut update
      btnStart.prop('disabled', false);
      localStorage.setItem('babak', round);

      console.log(round);

      if (ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify({
          type: 'set_round',
          round
        }));
      }
    }

    function updateDisplay() {
      const m = Math.floor(remaining / 60);
      const s = remaining % 60;
      waktuElem.text(`${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`);
    }

    function updateButtons(state) {
      lastState = state;
      btnStart.addClass('d-none');
      btnPause.addClass('d-none');
      btnResume.addClass('d-none');
      btnStop.addClass('d-none');
      switch (state) {
        case 'stopped':
        case 'ended':
          btnStart.removeClass('d-none');
          break;
        case 'started':
        case 'resumed':
        case 'tick':
          btnPause.removeClass('d-none');
          btnStop.removeClass('d-none');
          break;
        case 'paused':
          btnResume.removeClass('d-none');
          btnStop.removeClass('d-none');
          break;
      }
    }

    function startTimer() {
      if (currentRound) ws.send(JSON.stringify({
        type: 'start',
        remaining,
        round: currentRound
      }));
    }

    function pauseTimer() {
      ws.send(JSON.stringify({
        type: 'pause',
        round: currentRound
      }));
    }

    function resumeTimer() {
      ws.send(JSON.stringify({
        type: 'resume',
        round: currentRound
      }));
    }

    function stopTimer() {
      ws.send(JSON.stringify({
        type: 'stop',
        round: currentRound
      }));
    }

    ws.onopen = () => {
      console.log("OPERATOR Timer Connected");
    };

    ws.onmessage = event => {
      const msg = JSON.parse(event.data);
      if (typeof msg.remaining === 'number') {
        remaining = msg.remaining;
        updateDisplay();
      }
      if (['tick', 'started', 'resumed', 'paused', 'stopped', 'ended'].includes(msg.type)) updateButtons(msg.type);

      if (msg.type === 'stopped') {
        remaining = parseInt(localStorage.getItem('timer'), 10) || 120; // konversi ke angka
        var inputTime = $('#input-time');
        inputTime.val(localStorage.getItem('waktu') || '02:00'); // set input time

        updateDisplay(); // panggil fungsi update tampilan timer agar UI sinkron
        console.log('Timer stopped, remaining:', remaining);
      }

      if (msg.type === 'ended') {
        remaining = localStorage.getItem('timer');
        if (parseInt(localStorage.getItem('babak')) === localStorage.getItem('jumlahBabak')) {
          btnStart
            .removeClass('btn-success')
            .addClass('btn-secondary')
            .html('<i class="fas fa-redo me-2"></i>Reset')
            .prop('disabled', false)
            .off('click')
            .on('click', resetBabak);
        }
        updateDisplay();
        console.log(currentRound, maxBabak);

        if (currentRound < maxBabak) {
          // Disable babak sebelumnya
          $(`#babak${currentRound}`).prop('disabled', true);

          // Pindah ke babak berikutnya
          currentRound++;
          localStorage.setItem('babak', currentRound);

          // Enable babak berikutnya & highlight
          $(`#babak${currentRound}`).prop('disabled', false);
          highlightActiveRound(currentRound);

          // Sinkron ke server
          if (ws.readyState === WebSocket.OPEN) {
            ws.send(JSON.stringify({
              type: 'set_round',
              round: currentRound
            }));
          }

          btnStart.prop('disabled', false);
        } else {
          // Semua babak telah selesai
          btnStart
            .removeClass('btn-success')
            .addClass('btn-secondary')
            .html('<i class="fas fa-redo me-2"></i>Reset')
            .removeClass('d-none')
            .prop('disabled', false)
            .off('click') // Hapus event lama
            .on('click', resetBabak);
        }
      }

    };

    function resetBabak() {
      localStorage.removeItem('babak');
      currentRound = null;
      console.log(currentRound);
      renderBabakButtons(maxBabak);
      btnStart
        .removeClass('btn-secondary')
        .addClass('btn-success')
        .html('<i class="fas fa-play me-2"></i>Start')
        .prop('disabled', true)
        .off('click')
        .on('click', startTimer);

      updateDisplay();
      $(`#babak1`)
        .removeClass('bg-success')
        .addClass('btn-warning text-dark');
      btnStart.prop('disabled', false);
      setRound(1);
    }

    function openFullscreen() {
      const elem = document.documentElement;
      if (elem.requestFullscreen) elem.requestFullscreen();
      else if (elem.webkitRequestFullscreen) elem.webkitRequestFullscreen();
      else if (elem.msRequestFullscreen) elem.msRequestFullscreen();
      $('#openfull').addClass('d-none');
      $('#exitfull').removeClass('d-none');
    }

    function closeFullscreen() {
      if (document.exitFullscreen) document.exitFullscreen();
      else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
      else if (document.msExitFullscreen) document.msExitFullscreen();
      $('#openfull').removeClass('d-none');
      $('#exitfull').addClass('d-none');
    }
  </script>

  <script>
    // Nonaktifkan drag teks
    document.addEventListener('selectstart', function(e) {
      e.preventDefault();
    });
  </script>
</body>

</html>